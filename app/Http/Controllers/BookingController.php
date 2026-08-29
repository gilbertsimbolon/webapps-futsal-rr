<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingQueue;
use App\Models\Branch;
use App\Models\Field;
use App\Models\PaymentMethod;
use App\Services\JadwalDinamisService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    const TIME_QUANTUM_MINUTES = 15;

    // Halaman daftar semua transaksi booking untuk admin dan owner
    public function index(Request $request)
    {
        $this->evaluateForfeitedSlots();

        $query = Booking::with(['user', 'branch', 'field', 'schedule']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();
        $branches = Branch::where('status', 'active')->get();
        $fields = Field::where('status', 'available')->get();
        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        return view('booking.index', compact('bookings', 'branches', 'fields', 'paymentMethods'));
    }

    // Endpoint AJAX untuk mengambil slot waktu dinamis adaptif
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date'     => 'required|date',
        ]);

        $slots = JadwalDinamisService::generateSlotsForDate((int)$request->field_id, $request->date);

        return response()->json([
            'status' => 'success',
            'data'   => $slots,
        ]);
    }

    // Input pesanan walk in kasir dengan dukungan DP 50 persen atau Lunas Penuh
    public function storeWalkIn(Request $request)
    {
        $validated = $request->validate([
            'branch_id'        => 'required|exists:branches,id',
            'field_id'         => 'required|exists:fields,id',
            'booking_date'     => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'duration'         => 'required|integer|min:1',
            'customer_name'    => 'required|string|max:255',
            'reservation_type' => 'required|in:full_pay,dp_pay,hold_booking',
            'payment_method'   => 'required|string|max:100',
            'notes'            => 'nullable|string',
        ]);

        $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = $startTime->copy()->addHours((int)$validated['duration']);

        $isConflict = Booking::where('field_id', $validated['field_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('status', ['confirmed', 'paid', 'completed'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime->format('H:i:00'))
                    ->where('end_time', '>', $startTime->format('H:i:00'));
            })
            ->exists();

        if ($isConflict) {
            return back()->with('error', 'Jam tersebut bertabrakan dengan jadwal bermain tim lain!')->withInput();
        }

        $field = Field::findOrFail($validated['field_id']);
        $totalAmount = $field->price_per_hour * (int)$validated['duration'];

        $dpAmount = 0;
        $remainingAmount = $totalAmount;
        $status = 'pending';
        $paymentType = 'full';
        $paidAt = null;
        $paymentDeadline = null;

        if ($validated['reservation_type'] === 'full_pay') {
            $status = 'paid';
            $paymentType = 'full';
            $dpAmount = $totalAmount;
            $remainingAmount = 0;
            $paidAt = Carbon::now();
        } elseif ($validated['reservation_type'] === 'dp_pay') {
            $status = 'confirmed';
            $paymentType = 'dp';
            $dpAmount = $totalAmount * 0.5;
            $remainingAmount = $totalAmount * 0.5;
            $paidAt = Carbon::now();
        } else {
            $status = 'pending';
            $paymentDeadline = Carbon::now()->addMinutes(self::TIME_QUANTUM_MINUTES);
        }

        $booking = Booking::create([
            'user_id'          => Auth::id() ?? 1,
            'branch_id'        => $validated['branch_id'],
            'field_id'         => $validated['field_id'],
            'booking_date'     => $validated['booking_date'],
            'start_time'       => $startTime->format('H:i:00'),
            'end_time'         => $endTime->format('H:i:00'),
            'total_amount'     => $totalAmount,
            'dp_amount'        => $dpAmount,
            'remaining_amount' => $remainingAmount,
            'payment_type'     => $paymentType,
            'payment_method'   => $validated['payment_method'],
            'status'           => $status,
            'paid_at'          => $paidAt,
            'payment_deadline' => $paymentDeadline,
            'notes'            => 'Walk-in (' . $validated['customer_name'] . ')' . ($validated['notes'] ? ': ' . $validated['notes'] : ''),
        ]);

        return redirect()->route('bookings.index')->with('success', "Booking Walk-in ({$startTime->format('H:i')} - {$endTime->format('H:i')}) berhasil dicatat!");
    }

    // Check-in pemain saat tiba di lapangan untuk mulai bermain
    public function checkIn(Booking $booking)
    {
        if (!in_array($booking->status, ['paid', 'confirmed'])) {
            return back()->with('error', 'Hanya booking yang telah membayar (DP atau Lunas) yang dapat melakukan check-in.');
        }

        $booking->update([
            'check_in_at' => Carbon::now(),
        ]);

        return redirect()->route('bookings.index')->with('success', "Pemain untuk booking {$booking->booking_code} berhasil Check-In! Lapangan siap digunakan.");
    }

    // Pelunasan transaksi kasir POS (baik pembayaran DP pertama, sisa tagihan 50%, atau lunas penuh)
    public function processPayment(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|max:100',
        ]);

        $booking->update([
            'payment_method'   => $validated['payment_method'],
            'status'           => 'paid',
            'remaining_amount' => 0,
            'paid_at'          => Carbon::now(),
        ]);

        BookingQueue::where('field_id', $booking->field_id)
            ->whereDate('booking_date', $booking->booking_date)
            ->where('start_time', $booking->start_time)
            ->where('status', 'active_turn')
            ->update(['status' => 'completed']);

        return redirect()->route('bookings.index')->with('success', "Pembayaran untuk {$booking->booking_code} berhasil diproses dan dinyatakan LUNAS.");
    }

    // Penolakan pembayaran booking
    public function reject(Request $request, Booking $booking)
    {
        $booking->update([
            'status' => 'cancelled',
            'notes'  => 'Ditolak: ' . ($request->rejection_reason ?? 'Bukti pembayaran tidak sesuai atau waktu transfer habis'),
        ]);

        $currentQueue = BookingQueue::where('field_id', $booking->field_id)
            ->whereDate('booking_date', $booking->booking_date)
            ->where('start_time', $booking->start_time)
            ->where('status', 'active_turn')
            ->first();

        if ($currentQueue) {
            $currentQueue->update(['status' => 'preempted']);

            $nextQueue = BookingQueue::where('field_id', $booking->field_id)
                ->whereDate('booking_date', $booking->booking_date)
                ->where('status', 'waiting')
                ->orderBy('queue_order', 'asc')
                ->first();

            if ($nextQueue) {
                $nextQueue->update([
                    'status'        => 'active_turn',
                    'quantum_start' => Carbon::now(),
                    'quantum_end'   => Carbon::now()->addMinutes(self::TIME_QUANTUM_MINUTES),
                ]);
            }
        }

        return redirect()->route('bookings.index')->with('success', "Booking {$booking->booking_code} telah dibatalkan.");
    }

    // Pembaruan status transaksi booking
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,paid,completed,cancelled',
        ]);

        $booking->update(['status' => $validated['status']]);

        return redirect()->route('bookings.index')->with('success', "Status booking {$booking->booking_code} berhasil diperbarui.");
    }

    // Penghapusan data transaksi booking
    public function destroy(Booking $booking)
    {
        if ($booking->payment_proof && Storage::disk('public')->exists($booking->payment_proof)) {
            Storage::disk('public')->delete($booking->payment_proof);
        }

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Data booking berhasil dihapus.');
    }

    // Helper: Evaluasi otomatis batas kehadiran 50% waktu berjalan untuk booking berstatus bayar
    private function evaluateForfeitedSlots()
    {
        $now = Carbon::now();
        $today = $now->toDateString();

        $activeBookings = Booking::whereDate('booking_date', $today)
            ->whereIn('status', ['paid', 'confirmed'])
            ->whereNull('check_in_at')
            ->get();

        foreach ($activeBookings as $booking) {
            $start = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time);
            $end = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->end_time);

            $durationMinutes = $start->diffInMinutes($end);
            $halfTimeThreshold = $start->copy()->addMinutes($durationMinutes * 0.5);

            if ($now->greaterThanOrEqualTo($halfTimeThreshold)) {
                $booking->update([
                    'status' => 'cancelled',
                    'notes'  => ($booking->notes ? $booking->notes . ' | ' : '') . 'Hangus otomatis (Tidak hadir setelah 50% durasi main berjalan)',
                ]);

                $nextQueue = BookingQueue::where('field_id', $booking->field_id)
                    ->whereDate('booking_date', $booking->booking_date)
                    ->where('status', 'waiting')
                    ->orderBy('queue_order', 'asc')
                    ->first();

                if ($nextQueue) {
                    $nextQueue->update([
                        'status'        => 'active_turn',
                        'start_time'    => $now->format('H:i:00'),
                        'end_time'      => $booking->end_time,
                        'quantum_start' => $now,
                        'quantum_end'   => $now->copy()->addMinutes(15),
                    ]);
                }
            }
        }
    }
}
