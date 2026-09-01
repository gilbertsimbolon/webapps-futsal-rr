<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Field;
use App\Models\PaymentMethod;
use App\Models\Schedule;
use App\Services\JadwalDinamisService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CustomerBookingController extends Controller
{
    /**
     * Form Checkout / Review Booking Pelanggan (Step 1 - 6)
     */
    public function create(Field $field, Request $request)
    {
        $field->load('branch.user');

        // Pastikan lapangan aktif
        if ($field->status !== 'available') {
            return redirect()->route('lapangan.index')
                ->with('error', 'Maaf, lapangan ini sedang tidak tersedia untuk dibooking.');
        }

        // Ambil metode pembayaran aktif milik pemilik venue ini
        $ownerId = $field->branch->user_id;

        // Pastikan minimal ada opsi tunai default jika pemilik belum setup
        PaymentMethod::firstOrCreate(
            [
                'user_id' => $ownerId,
                'type'    => 'cash',
            ],
            [
                'name'   => 'Tunai di Tempat',
                'status' => 'active',
            ]
        );

        $paymentMethods = PaymentMethod::where('user_id', $ownerId)
            ->where('status', 'active')
            ->get();

        // Parameter pra-pilihan dari detail page jika ada
        $selectedDate = $request->get('date', Carbon::today()->toDateString());
        $selectedTime = $request->get('start_time', '');
        $selectedDuration = (int) $request->get('duration', 1);

        // Ambil slot dinamis untuk tanggal terpilih
        $slots = JadwalDinamisService::generateSlotsForDate($field->id, $selectedDate);

        return view('customer.booking.create', compact(
            'field',
            'paymentMethods',
            'selectedDate',
            'selectedTime',
            'selectedDuration',
            'slots'
        ));
    }

    /**
     * Simpan Booking Baru Pelanggan (Step 7)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id'          => 'required|exists:fields,id',
            'booking_date'      => 'required|date|after_or_equal:today',
            'start_time'        => 'required|date_format:H:i',
            'duration'          => 'required|integer|min:1|max:6',
            'payment_type'      => 'required|in:full,dp',
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'notes'             => 'nullable|string|max:500',
        ], [
            'booking_date.after_or_equal' => 'Tanggal booking tidak boleh tanggal yang sudah lewat.',
            'payment_method_id.required'  => 'Silakan pilih metode pembayaran.',
            'start_time.required'         => 'Silakan pilih jam mulai bermain.',
        ]);

        $field = Field::with('branch')->findOrFail($validated['field_id']);

        // Pastikan metode pembayaran milik pemilik cabang lapangan yang dibooking
        $paymentMethod = PaymentMethod::where('id', $validated['payment_method_id'])
            ->where('user_id', $field->branch->user_id)
            ->where('status', 'active')
            ->first();

        if (!$paymentMethod) {
            return back()
                ->with('error', 'Metode pembayaran tidak valid untuk venue lapangan ini.')
                ->withInput();
        }

        // Hitung jam mulai dan jam selesai
        $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = $startTime->copy()->addHours((int) $validated['duration']);

        // Cek apakah tanggal hari ini dan jam sudah lewat
        $bookingDate = Carbon::parse($validated['booking_date']);
        if ($bookingDate->isToday() && $startTime->lt(Carbon::now())) {
            return back()
                ->with('error', 'Jam mulai yang dipilih sudah lewat dari waktu saat ini.')
                ->withInput();
        }

        // Cek bentrok dengan booking yang sudah ada
        $isConflict = Booking::where('field_id', $field->id)
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('status', ['confirmed', 'paid', 'completed'])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime->format('H:i:00'))
                    ->where('end_time', '>', $startTime->format('H:i:00'));
            })
            ->exists();

        if ($isConflict) {
            return back()
                ->with('error', 'Mohon maaf, jam tersebut baru saja dibooking oleh pelanggan lain. Silakan pilih jam atau slot lain.')
                ->withInput();
        }

        // Cari jadwal operasional lapangan yang cocok
        $day = strtolower($bookingDate->format('l'));
        $matchedSchedule = Schedule::where('field_id', $field->id)
            ->where('status', 'active')
            ->where(function ($query) use ($day) {
                $query->where('day', 'all')
                    ->orWhere('day', $day);
            })
            ->where('start_time', '<=', $startTime->format('H:i:00'))
            ->where('end_time', '>=', $endTime->format('H:i:00'))
            ->first();

        // Hitung nominal harga & pembayaran
        $totalAmount = (float) $field->price_per_hour * (int) $validated['duration'];

        if ($validated['payment_type'] === 'full') {
            $dpAmount = $totalAmount;
            $remainingAmount = 0;
        } else {
            $dpAmount = $totalAmount * 0.5;
            $remainingAmount = $totalAmount * 0.5;
        }

        // Buat record booking
        $booking = Booking::create([
            'user_id'           => Auth::id(),
            'branch_id'         => $field->branch_id,
            'field_id'          => $field->id,
            'schedule_id'       => $matchedSchedule?->id,
            'booking_date'      => $validated['booking_date'],
            'start_time'        => $startTime->format('H:i:00'),
            'end_time'          => $endTime->format('H:i:00'),
            'total_amount'      => $totalAmount,
            'dp_amount'         => $dpAmount,
            'remaining_amount'  => $remainingAmount,
            'payment_type'      => $validated['payment_type'],
            'payment_method_id' => $paymentMethod->id,
            'status'            => 'pending',
            'payment_deadline'  => Carbon::now()->addMinutes(30),
            'notes'             => $validated['notes'] ?? null,
        ]);

        return redirect()->route('pelanggan.booking.payment', $booking->booking_code)
            ->with('success', 'Reservasi berhasil dibuat! Silakan selesaikan pembayaran untuk mengunci jadwal Anda.');
    }

    /**
     * Halaman Konfirmasi & Instruksi Pembayaran Pelanggan
     */
    public function showPayment($booking_code)
    {
        $booking = Booking::with([
            'field.branch',
            'user',
            'paymentMethod'
        ])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        // Keamanan: Hanya pemilik booking atau admin/pemilik cabang terkait yang boleh melihat
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses ke transaksi ini.');
        }

        return view('customer.booking.pembayaran', compact('booking'));
    }

    /**
     * Unggah Bukti Transfer Pembayaran Pelanggan
     */
    public function uploadPaymentProof(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'payment_proof.required' => 'Silakan pilih berkas foto bukti transfer / struk pembayaran.',
            'payment_proof.image'    => 'Berkas harus berupa gambar foto.',
            'payment_proof.max'      => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        // Hapus foto lama jika ada
        if ($booking->payment_proof && Storage::disk('public')->exists($booking->payment_proof)) {
            Storage::disk('public')->delete($booking->payment_proof);
        }

        $path = $request->file('payment_proof')->store('bukti_pembayaran', 'public');

        $booking->update([
            'payment_proof' => $path,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Pengelola venue akan memverifikasi pembayaran Anda.');
    }
}

