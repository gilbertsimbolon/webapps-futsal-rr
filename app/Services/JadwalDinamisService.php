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
     */
    public static function generateSlotsForDate(
        int $fieldId,
        string $date
    ): array {
        $now = Carbon::now();

        $dateCarbon = Carbon::parse($date);

        $isToday = $dateCarbon->isToday();

        /**
         * Tentukan nama hari.
         *
         * Contoh:
         * 2026-09-01 = Tuesday
         */
        $day = strtolower($dateCarbon->format('l'));

        /**
         * Ambil jadwal aktif lapangan.
         *
         * Schedule dengan day = all
         * berlaku setiap hari.
         *
         * Schedule dengan day tertentu
         * hanya berlaku pada hari tersebut.
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

        /**
         * Jika tidak ada jadwal aktif,
         * berarti tidak ada slot yang bisa ditampilkan.
         */
        if ($masterSchedules->isEmpty()) {
            return [];
        }

        /**
         * Ambil jam buka dari jadwal pertama.
         */
        $venueOpenTime = Carbon::parse(
            $date . ' ' . $masterSchedules->first()->start_time
        );

        /**
         * Ambil jam tutup dari jadwal terakhir.
         */
        $venueCloseTime = Carbon::parse(
            $date . ' ' . $masterSchedules->last()->end_time
        );

        /**
         * Ambil booking aktif pada tanggal tersebut.
         *
         * Booking:
         * - confirmed
         * - paid
         * - completed
         *
         * dianggap mengisi slot.
         *
         * Pending hanya dianggap mengisi slot
         * selama payment_deadline masih aktif.
         */
        $activeBookings = Booking::where('field_id', $fieldId)
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
         * Mulai dari jam buka.
         */
        $cursor = $venueOpenTime->copy();

        /**
         * Loop sampai jam tutup.
         */
        while (
            $cursor->copy()
                ->addHour()
                ->lte($venueCloseTime)
        ) {

            /**
             * Cari booking pertama yang
             * bersinggungan dengan cursor.
             */
            $nextBooking = $activeBookings->first(
                function ($booking) use ($date, $cursor) {

                    $bookingStart = Carbon::parse(
                        $date . ' ' . $booking->start_time
                    );

                    $bookingEnd = Carbon::parse(
                        $date . ' ' . $booking->end_time
                    );

                    /**
                     * Booking dimulai setelah cursor
                     * atau sedang berlangsung pada cursor.
                     */
                    return $bookingStart->gte($cursor)
                        || (
                            $bookingStart->lte($cursor)
                            && $bookingEnd->gt($cursor)
                        );
                }
            );

            /**
             * =====================================================
             * ADA BOOKING
             * =====================================================
             */
            if ($nextBooking) {

                $bookingStart = Carbon::parse(
                    $date . ' ' . $nextBooking->start_time
                );

                $bookingEnd = Carbon::parse(
                    $date . ' ' . $nextBooking->end_time
                );

                /**
                 * Jika cursor masih sebelum booking,
                 * coba buat slot kosong 1 jam.
                 */
                if ($cursor->lt($bookingStart)) {

                    $availableEnd = $cursor->copy()->addHour();

                    /**
                     * Jika tersedia ruang 1 jam penuh
                     * sebelum booking.
                     */
                    if ($availableEnd->lte($bookingStart)) {

                        $slots[] = self::formatSlot(
                            $cursor,
                            $availableEnd,
                            false,
                            $isToday,
                            $now
                        );

                        $cursor = $availableEnd;

                        continue;
                    }

                    /**
                     * Jika sisa waktu kurang dari 1 jam,
                     * langsung lompat ke booking.
                     */
                    $cursor = $bookingStart->copy();
                }

                /**
                 * Tambahkan slot booking sebagai booked.
                 */
                $slots[] = self::formatSlot(
                    $bookingStart,
                    $bookingEnd,
                    true,
                    $isToday,
                    $now
                );

                /**
                 * Cursor dipindahkan ke akhir booking.
                 */
                $cursor = $bookingEnd->copy();

                /**
                 * Hapus booking yang sudah diproses.
                 */
                $activeBookings = $activeBookings->reject(
                    fn ($booking) =>
                        $booking->id === $nextBooking->id
                );

                continue;
            }

            /**
             * =====================================================
             * TIDAK ADA BOOKING
             * =====================================================
             */

            $slotStart = $cursor->copy();

            $slotEnd = $cursor->copy()->addHour();

            /**
             * Jangan melewati jam tutup.
             */
            if ($slotEnd->gt($venueCloseTime)) {
                break;
            }

            /**
             * Tambahkan slot tersedia.
             */
            $slots[] = self::formatSlot(
                $slotStart,
                $slotEnd,
                false,
                $isToday,
                $now
            );

            /**
             * Geser 1 jam.
             */
            $cursor = $slotEnd;
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
        Carbon $now
    ): array {

        /**
         * Tentukan apakah waktu sudah lewat.
         */
        $isPast = false;

        if (
            $isToday &&
            $start->lt($now)
        ) {
            $isPast = true;
        }

        return [
            'start_time' => $start->format('H:i'),

            'end_time' => $end->format('H:i'),

            'time_text' =>
                $start->format('H:i')
                . ' - '
                . $end->format('H:i')
                . ' WITA',

            'is_booked' =>
                $isBooked || $isPast,

            'status' =>
                $isBooked
                    ? 'booked'
                    : (
                        $isPast
                            ? 'expired'
                            : 'available'
                    ),
        ];
    }
}