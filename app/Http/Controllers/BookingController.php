<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingQueue;
use App\Models\Branch;
use App\Models\Field;
use App\Models\PaymentMethod;
use App\Models\Schedule;
use App\Services\JadwalDinamisService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    const TIME_QUANTUM_MINUTES = 15;

    /**
     * Menampilkan daftar transaksi booking.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $this->evaluateForfeitedSlots();

        $userId = $user->id;

        $query = Booking::with([
            'user',
            'branch',
            'field',
            'schedule',
            'paymentMethod',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Filter Hak Akses Pemilik (Isolasi Data Cabang Milik Sendiri)
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('pemilik')) {
            $query->whereHas('branch', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

            $branches = Branch::where('status', 'active')
                ->where('user_id', $user->id)
                ->get();

            $ownerBranchIds = $branches->pluck('id');

            $fields = Field::where('status', 'available')
                ->whereIn('branch_id', $ownerBranchIds)
                ->get();
        } else {
            $branches = Branch::where('status', 'active')->get();
            $fields = Field::where('status', 'available')->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('paymentMethod', function ($pq) use ($search) {
                        $pq->where('name', 'like', "%{$search}%")
                            ->orWhere('account_number', 'like', "%{$search}%");
                    })
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Cabang
        |--------------------------------------------------------------------------
        */

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Tanggal
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $bookings = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Payment Method
        |--------------------------------------------------------------------------
        |
        | Hanya mengambil metode pembayaran milik user/pemilik yang sedang login.
        |
        */

        PaymentMethod::firstOrCreate(
            [
                'user_id' => $userId,
                'type'    => 'cash',
            ],
            [
                'name'   => 'Tunai (Cash)',
                'status' => 'active',
            ]
        );

        $paymentMethods = PaymentMethod::where('user_id', $userId)
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('booking.index', compact(
            'bookings',
            'branches',
            'fields',
            'paymentMethods'
        ));
    }

    /**
     * Endpoint AJAX untuk mengambil slot waktu dinamis adaptif.
     */
    public function getAvailableSlots(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'date'     => 'required|date',
        ]);

        if ($user && $user->hasRole('pemilik')) {
            $fieldExists = Field::where('id', $validated['field_id'])
                ->whereHas('branch', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->exists();

            if (!$fieldExists) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Lapangan tidak ditemukan atau bukan milik Anda.',
                ], 403);
            }
        }

        $slots = JadwalDinamisService::generateSlotsForDate(
            (int) $validated['field_id'],
            $validated['date']
        );

        return response()->json([
            'status' => 'success',
            'data'   => $slots,
        ]);
    }

    /**
     * Input pesanan walk-in kasir.
     *
     * Mendukung:
     * - Full payment
     * - DP 50%
     * - Hold booking
     */
    public function storeWalkIn(Request $request)
    {
        if (!$request->has('payment_method_id') && $request->filled('payment_method') && is_numeric($request->payment_method)) {
            $request->merge(['payment_method_id' => $request->payment_method]);
        }

        $validated = $request->validate([
            'branch_id'        => 'required|exists:branches,id',
            'field_id'         => 'required|exists:fields,id',
            'booking_date'     => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'duration'         => 'required|integer|min:1',
            'customer_name'    => 'required|string|max:255',
            'reservation_type' => 'required|in:full_pay,dp_pay,hold_booking',

            /*
            |--------------------------------------------------------------------------
            | Sekarang menggunakan payment_method_id
            |--------------------------------------------------------------------------
            */

            'payment_method_id' => 'nullable|integer|exists:payment_methods,id',

            'notes' => 'nullable|string',
        ], [
            'payment_method_id.required' => 'Metode pembayaran wajib dipilih untuk pembayaran.',
            'payment_method_id.exists'   => 'Metode pembayaran tidak valid.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi Payment Method
        |--------------------------------------------------------------------------
        |
        | Jika pembayaran langsung dilakukan, payment method wajib dipilih.
        |
        */

        if (
            in_array($validated['reservation_type'], ['full_pay', 'dp_pay'])
            && empty($validated['payment_method_id'])
        ) {
            return back()
                ->with('error', 'Metode pembayaran wajib dipilih untuk pembayaran.')
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan Payment Method milik user yang login
        |--------------------------------------------------------------------------
        */

        $paymentMethod = null;

        if (!empty($validated['payment_method_id'])) {
            $paymentMethod = PaymentMethod::where('id', $validated['payment_method_id'])
                ->where('user_id', Auth::id())
                ->where('status', 'active')
                ->first();

            if (!$paymentMethod) {
                return back()
                    ->with('error', 'Metode pembayaran tidak valid atau bukan milik Anda.')
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung waktu mulai dan selesai
        |--------------------------------------------------------------------------
        */

        $startTime = Carbon::createFromFormat(
            'H:i',
            $validated['start_time']
        );

        $endTime = $startTime->copy()->addHours(
            (int) $validated['duration']
        );

        /*
        |--------------------------------------------------------------------------
        | Cari schedule yang cocok berdasarkan field, hari, dan waktu
        |--------------------------------------------------------------------------
        */

        $bookingDate = Carbon::parse($validated['booking_date']);
        $day = strtolower($bookingDate->format('l'));

        $matchedSchedule = Schedule::where('field_id', $validated['field_id'])
            ->where('status', 'active')
            ->where(function ($query) use ($day) {
                $query->where('day', 'all')
                    ->orWhere('day', $day);
            })
            ->where('start_time', '<=', $startTime->format('H:i:00'))
            ->where('end_time', '>=', $endTime->format('H:i:00'))
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Cek bentrok booking
        |--------------------------------------------------------------------------
        */

        $isConflict = Booking::where('field_id', $validated['field_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->whereIn('status', [
                'confirmed',
                'paid',
                'completed',
            ])
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(
                    'start_time',
                    '<',
                    $endTime->format('H:i:00')
                )->where(
                    'end_time',
                    '>',
                    $startTime->format('H:i:00')
                );
            })
            ->exists();

        if ($isConflict) {
            return back()
                ->with(
                    'error',
                    'Jam tersebut bertabrakan dengan jadwal bermain tim lain!'
                )
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi Kepemilikan Cabang & Lapangan
        |--------------------------------------------------------------------------
        */

        if ($user->hasRole('pemilik')) {
            $branch = Branch::where('id', $validated['branch_id'])
                ->where('user_id', $user->id)
                ->first();

            if (!$branch) {
                return back()->with('error', 'Cabang venue tidak valid atau bukan milik Anda.')->withInput();
            }

            $field = Field::where('id', $validated['field_id'])
                ->where('branch_id', $branch->id)
                ->first();

            if (!$field) {
                return back()->with('error', 'Unit lapangan tidak valid untuk cabang yang dipilih.')->withInput();
            }
        } else {
            $field = Field::findOrFail($validated['field_id']);
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung harga
        |--------------------------------------------------------------------------
        */

        $totalAmount =
            $field->price_per_hour *
            (int) $validated['duration'];

        /*
        |--------------------------------------------------------------------------
        | Default pembayaran
        |--------------------------------------------------------------------------
        */

        $dpAmount = 0;

        $remainingAmount = $totalAmount;

        $status = 'pending';

        $paymentType = 'full';

        $paidAt = null;

        $paymentDeadline = null;

        /*
        |--------------------------------------------------------------------------
        | Full Payment
        |--------------------------------------------------------------------------
        */

        if ($validated['reservation_type'] === 'full_pay') {
            $status = 'paid';

            $paymentType = 'full';

            $dpAmount = $totalAmount;

            $remainingAmount = 0;

            $paidAt = Carbon::now();
        }

        /*
        |--------------------------------------------------------------------------
        | DP 50%
        |--------------------------------------------------------------------------
        */ elseif ($validated['reservation_type'] === 'dp_pay') {
            $status = 'confirmed';

            $paymentType = 'dp';

            $dpAmount = $totalAmount * 0.5;

            $remainingAmount = $totalAmount * 0.5;

            $paidAt = Carbon::now();
        }

        /*
        |--------------------------------------------------------------------------
        | Hold Booking
        |--------------------------------------------------------------------------
        */ else {
            $status = 'pending';

            $paymentType = 'full';

            $paymentDeadline = Carbon::now()
                ->addMinutes(self::TIME_QUANTUM_MINUTES);
        }

        /*
        |--------------------------------------------------------------------------
        | Buat Booking
        |--------------------------------------------------------------------------
        */

        $booking = Booking::create([
            'user_id' => Auth::id() ?? 1,

            'branch_id' => $validated['branch_id'],

            'field_id' => $validated['field_id'],

            'schedule_id' => $matchedSchedule?->id,

            'booking_date' => $validated['booking_date'],

            'start_time' => $startTime->format('H:i:00'),

            'end_time' => $endTime->format('H:i:00'),

            'total_amount' => $totalAmount,

            'dp_amount' => $dpAmount,

            'remaining_amount' => $remainingAmount,

            'payment_type' => $paymentType,

            /*
            |--------------------------------------------------------------------------
            | FK Payment Method
            |--------------------------------------------------------------------------
            */

            'payment_method_id' => $validated['payment_method_id'] ?? null,

            'status' => $status,

            'paid_at' => $paidAt,

            'payment_deadline' => $paymentDeadline,

            'notes' =>
            'Walk-in (' .
                $validated['customer_name'] .
                ')' .
                (
                    !empty($validated['notes'])
                    ? ': ' . $validated['notes']
                    : ''
                ),
        ]);

        return redirect()
            ->route('pemilik.bookings.index')
            ->with(
                'success',
                "Booking Walk-in ({$startTime->format('H:i')} - {$endTime->format('H:i')}) berhasil dicatat!"
            );
    }

    /**
     * Check-in pemain saat tiba di lapangan.
     */
    public function checkIn(Booking $booking)
    {
        $this->authorizeBookingOwner($booking);

        if (!in_array($booking->status, ['paid', 'confirmed'])) {
            return back()
                ->with(
                    'error',
                    'Hanya booking yang telah membayar (DP atau Lunas) yang dapat melakukan check-in.'
                );
        }

        $booking->update([
            'check_in_at' => Carbon::now(),
        ]);

        return redirect()
            ->route('pemilik.bookings.index')
            ->with(
                'success',
                "Pemain untuk booking {$booking->booking_code} berhasil Check-In! Lapangan siap digunakan."
            );
    }

    /**
     * Proses pembayaran booking.
     *
     * Bisa digunakan untuk:
     * - Pelunasan DP
     * - Pembayaran penuh
     */
    public function processPayment(Request $request, Booking $booking)
    {
        $this->authorizeBookingOwner($booking);

        if (!$request->has('payment_method_id') && $request->filled('payment_method') && is_numeric($request->payment_method)) {
            $request->merge(['payment_method_id' => $request->payment_method]);
        }

        $validated = $request->validate([
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
        ], [
            'payment_method_id.required' => 'Metode pembayaran wajib dipilih.',
            'payment_method_id.exists'   => 'Metode pembayaran tidak valid.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan metode pembayaran milik user yang login
        |--------------------------------------------------------------------------
        */

        $paymentMethod = PaymentMethod::where('id', $validated['payment_method_id'])
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (!$paymentMethod) {
            return back()
                ->with(
                    'error',
                    'Metode pembayaran tidak valid atau bukan milik Anda.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Update pembayaran
        |--------------------------------------------------------------------------
        */

        $booking->update([
            'payment_method_id' => $paymentMethod->id,

            'status' => 'paid',

            'remaining_amount' => 0,

            'paid_at' => Carbon::now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Selesaikan queue aktif
        |--------------------------------------------------------------------------
        */

        BookingQueue::where('field_id', $booking->field_id)
            ->whereDate(
                'booking_date',
                $booking->booking_date
            )
            ->where(
                'start_time',
                $booking->start_time
            )
            ->where(
                'status',
                'active_turn'
            )
            ->update([
                'status' => 'completed',
            ]);

        return redirect()
            ->route('pemilik.bookings.index')
            ->with(
                'success',
                "Pembayaran untuk {$booking->booking_code} berhasil diproses dan dinyatakan LUNAS."
            );
    }

    /**
     * Penolakan pembayaran booking.
     */
    public function reject(
        Request $request,
        Booking $booking
    ) {
        $this->authorizeBookingOwner($booking);

        $booking->update([
            'status' => 'cancelled',

            'notes' =>
            'Ditolak: ' .
                (
                    $request->rejection_reason
                    ?? 'Bukti pembayaran tidak sesuai atau waktu transfer habis'
                ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cari queue yang sedang aktif
        |--------------------------------------------------------------------------
        */

        $currentQueue = BookingQueue::where(
            'field_id',
            $booking->field_id
        )
            ->whereDate(
                'booking_date',
                $booking->booking_date
            )
            ->where(
                'start_time',
                $booking->start_time
            )
            ->where(
                'status',
                'active_turn'
            )
            ->first();

        if ($currentQueue) {
            $currentQueue->update([
                'status' => 'preempted',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Ambil antrian berikutnya
            |--------------------------------------------------------------------------
            */

            $nextQueue = BookingQueue::where(
                'field_id',
                $booking->field_id
            )
                ->whereDate(
                    'booking_date',
                    $booking->booking_date
                )
                ->where(
                    'status',
                    'waiting'
                )
                ->orderBy(
                    'queue_order',
                    'asc'
                )
                ->first();

            if ($nextQueue) {
                $now = Carbon::now();

                $nextQueue->update([
                    'status' => 'active_turn',

                    'quantum_start' => $now,

                    'quantum_end' => $now->copy()
                        ->addMinutes(
                            self::TIME_QUANTUM_MINUTES
                        ),
                ]);
            }
        }

        return redirect()
            ->route('pemilik.bookings.index')
            ->with(
                'success',
                "Booking {$booking->booking_code} telah dibatalkan."
            );
    }

    /**
     * Pembaruan status transaksi booking.
     */
    public function updateStatus(
        Request $request,
        Booking $booking
    ) {
        $this->authorizeBookingOwner($booking);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,paid,completed,cancelled',
        ]);

        $booking->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('pemilik.bookings.index')
            ->with(
                'success',
                "Status booking {$booking->booking_code} berhasil diperbarui."
            );
    }

    /**
     * Menghapus data booking.
     */
    public function destroy(Booking $booking)
    {
        $this->authorizeBookingOwner($booking);

        if (
            $booking->payment_proof &&
            Storage::disk('public')->exists(
                $booking->payment_proof
            )
        ) {
            Storage::disk('public')->delete(
                $booking->payment_proof
            );
        }

        $booking->delete();

        return redirect()
            ->route('pemilik.bookings.index')
            ->with(
                'success',
                'Data booking berhasil dihapus.'
            );
    }

    /**
     * Memastikan pemilik hanya dapat mengakses data booking pada cabang miliknya.
     */
    private function authorizeBookingOwner(Booking $booking): void
    {
        $user = Auth::user();
        if ($user && $user->hasRole('pemilik')) {
            $booking->loadMissing('branch');
            if ($booking->branch && (int) $booking->branch->user_id !== (int) $user->id) {
                abort(403, 'Anda tidak memiliki akses ke data booking ini.');
            }
        }
    }

    /**
     * Evaluasi otomatis booking yang hangus.
     *
     * Booking yang sudah membayar tetapi tidak check-in
     * sampai 50% durasi permainan akan dibatalkan.
     */
    private function evaluateForfeitedSlots()
    {
        $user = Auth::user();
        $now = Carbon::now();

        $today = $now->toDateString();

        $query = Booking::whereDate(
            'booking_date',
            $today
        )
            ->whereIn(
                'status',
                ['paid', 'confirmed']
            )
            ->whereNull('check_in_at');

        if ($user && $user->hasRole('pemilik')) {
            $query->whereHas('branch', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $activeBookings = $query->get();

        foreach ($activeBookings as $booking) {
            $start = Carbon::parse(
                $booking->booking_date->format('Y-m-d') .
                    ' ' .
                    $booking->start_time
            );

            $end = Carbon::parse(
                $booking->booking_date->format('Y-m-d') .
                    ' ' .
                    $booking->end_time
            );

            $durationMinutes = $start->diffInMinutes($end);

            $halfTimeThreshold = $start->copy()
                ->addMinutes(
                    $durationMinutes * 0.5
                );

            if ($now->greaterThanOrEqualTo($halfTimeThreshold)) {

                /*
                |--------------------------------------------------------------------------
                | Batalkan booking
                |--------------------------------------------------------------------------
                */

                $booking->update([
                    'status' => 'cancelled',

                    'notes' => (
                        $booking->notes
                        ? $booking->notes . ' | '
                        : ''
                    ) .
                        'Hangus otomatis (Tidak hadir setelah 50% durasi main berjalan)',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Cari antrian berikutnya
                |--------------------------------------------------------------------------
                */

                $nextQueue = BookingQueue::where(
                    'field_id',
                    $booking->field_id
                )
                    ->whereDate(
                        'booking_date',
                        $booking->booking_date
                    )
                    ->where(
                        'status',
                        'waiting'
                    )
                    ->orderBy(
                        'queue_order',
                        'asc'
                    )
                    ->first();

                if ($nextQueue) {
                    $nextQueue->update([
                        'status' => 'active_turn',

                        'start_time' => $now->format('H:i:00'),

                        'end_time' => $booking->end_time,

                        'quantum_start' => $now,

                        'quantum_end' => $now->copy()
                            ->addMinutes(
                                self::TIME_QUANTUM_MINUTES
                            ),
                    ]);
                }
            }
        }
    }
}
