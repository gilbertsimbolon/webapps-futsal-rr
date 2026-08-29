<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class JadwalDinamisService
{
    // Menghasilkan daftar slot jam yang adaptif mengikuti jam transaksi pada tanggal tertentu
    public static function generateSlotsForDate(int $fieldId, string $date): array
    {
        $now = Carbon::now();
        $isToday = Carbon::parse($date)->isToday();

        // Ambil jam buka dan jam tutup master lapangan
        $masterSchedules = Schedule::where('field_id', $fieldId)
            ->where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->get();

        if ($masterSchedules->isEmpty()) {
            return [];
        }

        $venueOpenTime = Carbon::parse($date . ' ' . $masterSchedules->first()->start_time);
        $venueCloseTime = Carbon::parse($date . ' ' . $masterSchedules->last()->end_time);

        // Ambil semua reservasi aktif pada tanggal tersebut yang sudah valid atau sedang di-hold
        $activeBookings = Booking::where('field_id', $fieldId)
            ->whereDate('booking_date', $date)
            ->where(function ($q) use ($now) {
                $q->whereIn('status', ['confirmed', 'paid', 'completed'])
                    ->orWhere(function ($sub) use ($now) {
                        $sub->where('status', 'pending');
                        if (Schema::hasColumn('bookings', 'payment_deadline')) {
                            $sub->where('payment_deadline', '>', $now);
                        }
                    });
            })
            ->orderBy('start_time', 'asc')
            ->get();

        $slots = [];
        $cursor = $venueOpenTime->copy();

        // Loop timeline dari jam buka sampai jam tutup
        while ($cursor->copy()->addMinutes(30)->lte($venueCloseTime)) {
            // Cek apakah ada booking yang mulai pada atau setelah cursor saat ini
            $nextBooking = $activeBookings->first(function ($booking) use ($date, $cursor) {
                $bStart = Carbon::parse($date . ' ' . $booking->start_time);
                $bEnd = Carbon::parse($date . ' ' . $booking->end_time);
                return $bStart->gte($cursor) || ($bStart->lte($cursor) && $bEnd->gt($cursor));
            });

            if ($nextBooking) {
                $bStart = Carbon::parse($date . ' ' . $nextBooking->start_time);
                $bEnd = Carbon::parse($date . ' ' . $nextBooking->end_time);

                // Jika masih ada jeda waktu kosong minimal satu jam sebelum booking dimulai
                if ($cursor->lt($bStart) && $cursor->copy()->addHour()->lte($bStart)) {
                    $slotEnd = $cursor->copy()->addHour();
                    $slots[] = self::formatSlot($cursor, $slotEnd, false, $isToday, $now);
                    $cursor->addHour();
                    continue;
                }

                // Jika jeda kurang dari satu jam sebelum booking dimulai lompatkan cursor ke jam mulai booking
                if ($cursor->lt($bStart)) {
                    $cursor = $bStart->copy();
                }

                // Masukkan slot booking jam yang terisi
                $slots[] = self::formatSlot($bStart, $bEnd, true, $isToday, $now);

                // Geser kursor waktu persis ke jam selesai booking
                $cursor = $bEnd->copy();

                // Hapus booking dari koleksi pencarian agar tidak terulang
                $activeBookings = $activeBookings->reject(fn($b) => $b->id === $nextBooking->id);
            } else {
                // Bentuk slot satu jam reguler dari posisi cursor saat ini jika tidak ada booking
                $slotStart = $cursor->copy();
                $slotEnd = $cursor->copy()->addHour();

                if ($slotEnd->gt($venueCloseTime)) {
                    break;
                }

                $slots[] = self::formatSlot($slotStart, $slotEnd, false, $isToday, $now);
                $cursor->addHour();
            }
        }

        return $slots;
    }

    // Format output array slot jam
    private static function formatSlot(Carbon $start, Carbon $end, bool $isBooked, bool $isToday, Carbon $now): array
    {
        $isPast = false;
        if ($isToday && $start->lt($now)) {
            $isPast = true;
        }

        return [
            'start_time' => $start->format('H:i'),
            'end_time'   => $end->format('H:i'),
            'time_text'  => $start->format('H:i') . ' - ' . $end->format('H:i') . ' WITA',
            'is_booked'  => $isBooked || $isPast,
            'status'     => $isBooked ? 'booked' : ($isPast ? 'expired' : 'available'),
        ];
    }
}
