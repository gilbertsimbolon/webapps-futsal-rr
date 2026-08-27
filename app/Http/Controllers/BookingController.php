<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    // Halaman daftar semua transaksi booking (Admin / Owner view)
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'branch', 'field', 'schedule']);

        // Filter Pencarian (Kode Booking / Nama Pemesan)
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

        // Filter Cabang
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter Tanggal Main
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();
        $branches = Branch::where('status', 'active')->get();

        return view('booking.index', compact('bookings', 'branches'));
    }

    // Endpoint AJAX untuk mengecek ketersediaan slot lapangan pada tanggal tertentu
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date'     => 'required|date',
        ]);

        $fieldId = $request->field_id;
        $date = $request->date;

        // Ambil semua template slot aktif untuk lapangan ini
        $schedules = Schedule::where('field_id', $fieldId)
            ->where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->get();

        // Ambil id slot yang sudah terisi di tanggal tersebut
        $bookedSlotIds = Booking::where('field_id', $fieldId)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['confirmed', 'paid', 'completed'])
            ->pluck('schedule_id')
            ->toArray();

        $slots = $schedules->map(function ($sch) use ($bookedSlotIds) {
            $startTime = \Carbon\Carbon::parse($sch->start_time)->format('H:i');
            $endTime = \Carbon\Carbon::parse($sch->end_time)->format('H:i');
            $isBooked = in_array($sch->id, $bookedSlotIds);

            return [
                'id'        => $sch->id,
                'time_text' => "{$startTime} - {$endTime}",
                'price'     => $sch->custom_price ?? $sch->field?->price_per_hour ?? 0,
                'is_booked' => $isBooked,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $slots,
        ]);
    }

    // Pembuatan transaksi booking
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id'      => 'required|exists:branches,id',
            'field_id'       => 'required|exists:fields,id',
            'schedule_id'    => 'required|exists:schedules,id',
            'booking_date'   => 'required|date|after_or_equal:today',
            'payment_method' => 'required|string|max:100',
            'notes'          => 'nullable|string',
        ]);

        // Cek double booking
        $isBooked = Booking::where('field_id', $validated['field_id'])
            ->where('schedule_id', $validated['schedule_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('status', ['confirmed', 'paid', 'completed'])
            ->exists();

        if ($isBooked) {
            return back()->with('error', 'Slot jam pada tanggal tersebut sudah dipesan oleh pengguna lain. Silakan pilih slot lain.')->withInput();
        }

        $schedule = Schedule::with('field')->findOrFail($validated['schedule_id']);
        $totalAmount = $schedule->custom_price ?? $schedule->field->price_per_hour;

        $booking = Booking::create([
            'user_id'        => Auth::id() ?? 1,
            'branch_id'      => $validated['branch_id'],
            'field_id'       => $validated['field_id'],
            'schedule_id'    => $validated['schedule_id'],
            'booking_date'   => $validated['booking_date'],
            'total_amount'   => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'status'         => 'pending',
            'notes'          => $validated['notes'],
        ]);

        return redirect()->route('bookings.index')->with('success', "Booking dengan kode {$booking->booking_code} berhasil dibuat.");
    }

    // Action ACC / Setujui Booking (Tandai Lunas)
    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'paid']);

        return redirect()->route('bookings.index')->with('success', "Booking {$booking->booking_code} berhasil disetujui dan ditandai LUNAS.");
    }

    // Update Status Transaksi Booking (Admin / Owner)
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,paid,completed,cancelled',
        ]);

        $booking->update(['status' => $validated['status']]);

        return redirect()->route('bookings.index')->with('success', "Status booking {$booking->booking_code} berhasil diperbarui.");
    }

    // Hapus Data Booking
    public function destroy(Booking $booking)
    {
        if ($booking->payment_proof && Storage::disk('public')->exists($booking->payment_proof)) {
            Storage::disk('public')->delete($booking->payment_proof);
        }

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Data booking berhasil dihapus.');
    }
}
