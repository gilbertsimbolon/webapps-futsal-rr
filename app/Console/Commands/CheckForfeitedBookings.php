<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\BookingQueue;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckForfeitedBookings extends Command
{
    protected $signature = 'bookings:check-forfeited';
    protected $description = 'Mengecek pemesanan lunas yang belum check-in setelah melewati batas toleransi 50% waktu bermain lalu mengalihkan ke antrean Round Robin';

    public function handle()
    {
        $now = Carbon::now();
        $today = $now->toDateString();

        // Ambil semua booking yang lunas pada hari ini dan belum melakukan check-in di lapangan
        $activeBookings = Booking::whereDate('booking_date', $today)
            ->where('status', 'paid')
            ->whereNull('check_in_at')
            ->get();

        foreach ($activeBookings as $booking) {
            $start = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time);
            $end = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->end_time);

            $durationMinutes = $start->diffInMinutes($end);
            $halfTimeThreshold = $start->copy()->addMinutes($durationMinutes * 0.5);

            // Jika waktu sekarang sudah melewati 50% waktu durasi main tapi belum check in
            if ($now->greaterThanOrEqualTo($halfTimeThreshold)) {
                $booking->update([
                    'status' => 'cancelled',
                    'notes'  => ($booking->notes ? $booking->notes . ' | ' : '') . 'Hangus otomatis (Tidak hadir setelah 50% waktu bermain berjalan)',
                ]);

                // Eksekusi rotasi Round Robin: alihkan hak giliran sisa waktu ke antrean tunggu berikutnya
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

                $this->info("Booking {$booking->booking_code} telah dinyatakan HANGUS dan dialihkan.");
            }
        }
    }
}
