<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class JadwalDinamisService
{
    /**
     * Generate slot booking berdasarkan jadwal operasional
     * lapangan dan booking yang sudah ada.
     *
     * Menampilkan semua jadwal operasional dengan 3 status:
     * 1. terisi (menampilkan nama tim yang sedang bermain / mengisi)
     * 2. kosong (tersedia untuk dibooking)
     * 3. kadaluarsa (waktu jam sudah lewat pada hari ini)
     */
    public static function generateSlotsForDate(
        int $fieldId,
        string $date
    ): array {
        $now = Carbon::now();
        $dateCarbon = Carbon::parse($date);
        $isToday = $dateCarbon->isToday();

        /**
         * Tentukan nama hari (contoh: tuesday)
         */
        $day = strtolower($dateCarbon->format('l'));

        /**
         * Ambil jadwal aktif lapangan.
         */
        $masterSchedules = Schedule::where('field_id', $fieldId)
            ->where('status', 'active')
            ->where(function ($query) use ($day) {
                $query
                    ->where('day', 'all')
                    ->orWhere('day', $day);
            })
            ->orderBy('start_time', 'asc')
            ->get();

        if ($masterSchedules->isEmpty()) {
            return [];
        }

        /**
         * Ambil booking aktif pada tanggal tersebut beserta data user / pemesan.
         */
        $activeBookings = Booking::with('user')
            ->where('field_id', $fieldId)
            ->whereDate('booking_date', $date)
            ->where(function ($query) use ($now) {
                $query->whereIn('status', [
                    'confirmed',
                    'paid',
                    'completed',
                ]);

                if (Schema::hasColumn('bookings', 'payment_deadline')) {
                    $query->orWhere(function ($subQuery) use ($now) {
                        $subQuery
                            ->where('status', 'pending')
                            ->where(
                                'payment_deadline',
                                '>',
                                $now
                            );
                    });
                }
            })
            ->orderBy('start_time', 'asc')
            ->get();

        $slots = [];

        /**
         * Tampilkan seluruh jadwal operasional lapangan.
         * Loop setiap jadwal master per jam.
         */
        foreach ($masterSchedules as $schedule) {
            $schedStart = Carbon::parse($date . ' ' . $schedule->start_time);
            $schedEnd = Carbon::parse($date . ' ' . $schedule->end_time);

            $cursor = $schedStart->copy();

            while ($cursor->copy()->addHour()->lte($schedEnd)) {
                $slotStart = $cursor->copy();
                $slotEnd = $cursor->copy()->addHour();

                // Cari booking yang sedang mengisi slot ini
                $matchedBooking = $activeBookings->first(function ($booking) use ($date, $slotStart, $slotEnd) {
                    $bStart = Carbon::parse($date . ' ' . $booking->start_time);
                    $bEnd = Carbon::parse($date . ' ' . $booking->end_time);

                    return $bStart->lt($slotEnd) && $bEnd->gt($slotStart);
                });

                $isBooked = !is_null($matchedBooking);
                $teamName = $matchedBooking ? $matchedBooking->team_name : null;

                $slots[] = self::formatSlot(
                    $slotStart,
                    $slotEnd,
                    $isBooked,
                    $isToday,
                    $now,
                    $teamName
                );

                $cursor->addHour();
            }
        }

        return $slots;
    }

    /**
     * Format data slot untuk frontend.
     */
    private static function formatSlot(
        Carbon $start,
        Carbon $end,
        bool $isBooked,
        bool $isToday,
        Carbon $now,
        ?string $teamName = null
    ): array {
        /**
         * Tentukan apakah waktu sudah lewat.
         */
        $isPast = false;

        if ($isToday && $start->lt($now)) {
            $isPast = true;
        }

        // Tiga status utama: terisi, kosong, kadaluarsa
        if ($isBooked) {
            $status = 'terisi';
            $statusLabel = 'Terisi';
        } elseif ($isPast) {
            $status = 'kadaluarsa';
            $statusLabel = 'Kadaluarsa';
        } else {
            $status = 'kosong';
            $statusLabel = 'Kosong';
        }

        return [
            'start_time' => $start->format('H:i'),
            'end_time'   => $end->format('H:i'),
            'time_text'  => $start->format('H:i') . ' - ' . $end->format('H:i') . ' WITA',
            'is_booked'  => $isBooked || $isPast,
            'status'     => $status, // 'terisi' | 'kosong' | 'kadaluarsa'
            'status_label' => $statusLabel,
            'status_raw' => $isBooked ? 'booked' : ($isPast ? 'expired' : 'available'),
            'team_name'  => $teamName,
        ];
    }
}
