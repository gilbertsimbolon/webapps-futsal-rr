<?php

namespace App\Http\Controllers;

use App\Models\BookingQueue;
use App\Models\Branch;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoundRobinController extends Controller
{
    const TIME_QUANTUM_MINUTES = 15;

    // Halaman Monitoring Antrean Round Robin Realtime
    public function monitoring(Request $request)
    {
        // Jalankan rotasi otomatis jika ada kuantum waktu aktif yang sudah kedaluwarsa
        $this->autoRotateExpiredQueues();

        $query = BookingQueue::with(['field.branch', 'user']);

        if ($request->filled('field_id')) {
            $query->where('field_id', $request->field_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        } else {
            $query->whereDate('booking_date', Carbon::today());
        }

        $queues = $query->orderBy('status', 'asc')
            ->orderBy('queue_order', 'asc')
            ->paginate(10)
            ->withQueryString();

        $branches = Branch::where('status', 'active')->get();
        $fields = Field::where('status', 'available')->get();

        return view('round_robin.monitoring', compact('queues', 'branches', 'fields'));
    }

    // Halaman Interaktif: Simulasi Alur Round Robin
    public function simulation()
    {
        $fields = Field::where('status', 'available')->get();
        return view('round_robin.simulation', compact('fields'));
    }

    // API Tambah Antrean Simulasi
    public function enqueueSimulation(Request $request)
    {
        $validated = $request->validate([
            'field_id'      => 'required|exists:fields,id',
            'booking_date'  => 'required|date',
            'start_time'    => 'required|date_format:H:i',
            'customer_name' => 'required|string|max:100',
        ]);

        $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = $startTime->copy()->addHour();

        // Cek antrean aktif pada slot tersebut
        $activeTurn = BookingQueue::where('field_id', $validated['field_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->where('start_time', $startTime->format('H:i:00'))
            ->where('status', 'active_turn')
            ->where('quantum_end', '>', Carbon::now())
            ->first();

        $lastOrder = BookingQueue::where('field_id', $validated['field_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->where('start_time', $startTime->format('H:i:00'))
            ->whereIn('status', ['active_turn', 'waiting'])
            ->max('queue_order') ?? 0;

        $newOrder = $lastOrder + 1;

        if (!$activeTurn) {
            $queue = BookingQueue::create([
                'field_id'      => $validated['field_id'],
                'booking_date'  => $validated['booking_date'],
                'start_time'    => $startTime->format('H:i:00'),
                'end_time'      => $endTime->format('H:i:00'),
                'customer_name' => $validated['customer_name'],
                'queue_order'   => 1,
                'status'        => 'active_turn',
                'quantum_start' => Carbon::now(),
                'quantum_end'   => Carbon::now()->addMinutes(self::TIME_QUANTUM_MINUTES),
            ]);
        } else {
            $queue = BookingQueue::create([
                'field_id'      => $validated['field_id'],
                'booking_date'  => $validated['booking_date'],
                'start_time'    => $startTime->format('H:i:00'),
                'end_time'      => $endTime->format('H:i:00'),
                'customer_name' => $validated['customer_name'],
                'queue_order'   => $newOrder,
                'status'        => 'waiting',
            ]);
        }

        return redirect()->back()->with('success', "Proses untuk {$validated['customer_name']} berhasil masuk ke dalam antrean Round Robin.");
    }

    // Paksa Rotasi / Preemption Giliran (Untuk Pengujian & Simulasi)
    public function forceRotate(BookingQueue $queue)
    {
        $queue->update(['status' => 'preempted']);

        // Cari antrean waiting urutan berikutnya
        $nextQueue = BookingQueue::where('field_id', $queue->field_id)
            ->whereDate('booking_date', $queue->booking_date)
            ->where('start_time', $queue->start_time)
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

        return redirect()->back()->with('success', 'Time Quantum telah dirotasi ke antrean berikutnya.');
    }

    // Helper: Rotasi Otomatis Jika Waktu Habis
    private function autoRotateExpiredQueues()
    {
        $expiredQueues = BookingQueue::where('status', 'active_turn')
            ->where('quantum_end', '<=', Carbon::now())
            ->get();

        foreach ($expiredQueues as $queue) {
            $queue->update(['status' => 'preempted']);

            $next = BookingQueue::where('field_id', $queue->field_id)
                ->whereDate('booking_date', $queue->booking_date)
                ->where('start_time', $queue->start_time)
                ->where('status', 'waiting')
                ->orderBy('queue_order', 'asc')
                ->first();

            if ($next) {
                $next->update([
                    'status'        => 'active_turn',
                    'quantum_start' => Carbon::now(),
                    'quantum_end'   => Carbon::now()->addMinutes(self::TIME_QUANTUM_MINUTES),
                ]);
            }
        }
    }
}
