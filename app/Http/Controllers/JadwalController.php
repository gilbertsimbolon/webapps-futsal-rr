<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class JadwalController extends Controller
{
    // fungsi index
    public function index(Request $request)
    {
        $query = Schedule::with(['field.branch']);

        // Filter Berdasarkan Lapangan
        if ($request->filled('field_id')) {
            $query->where('field_id', $request->field_id);
        }

        // Filter Berdasarkan Hari
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        $schedules = $query->orderBy('start_time', 'asc')->paginate(15)->withQueryString();
        $fields = Field::with('branch')->where('status', '!=', 'inactive')->get();

        return view('jadwal.index', compact('schedules', 'fields'));
    }

    // Tambah 1 Slot Manual
    public function store(Request $request)
    {
        $validated = $request->validate([
            'field_id'     => 'required|exists:fields,id',
            'day'          => 'required|in:all,monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
            'custom_price' => 'nullable|numeric|min:0',
            'status'       => 'required|in:active,inactive',
        ]);

        Schedule::create($validated);

        return redirect()->route('jadwal.index')->with('success', 'Slot jam operasional berhasil ditambahkan.');
    }

    // Generate Slot Otomatis (Interval 1 Jam)
    public function generate(Request $request)
    {
        $request->validate([
            'field_id'   => 'required|exists:fields,id',
            'day'        => 'required|in:all,monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'open_time'  => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
        ]);

        $start = Carbon::createFromFormat('H:i', $request->open_time);
        $end = Carbon::createFromFormat('H:i', $request->close_time);
        $countCreated = 0;

        while ($start->copy()->addHour()->lte($end)) {
            $slotStart = $start->format('H:i:00');
            $slotEnd = $start->copy()->addHour()->format('H:i:00');

            // Cek duplikasi slot pada lapangan dan hari yang sama
            $exists = Schedule::where('field_id', $request->field_id)
                ->where('day', $request->day)
                ->where('start_time', $slotStart)
                ->where('end_time', $slotEnd)
                ->exists();

            if (!$exists) {
                Schedule::create([
                    'field_id'   => $request->field_id,
                    'day'        => $request->day,
                    'start_time' => $slotStart,
                    'end_time'   => $slotEnd,
                    'status'     => 'active',
                ]);
                $countCreated++;
            }

            $start->addHour();
        }

        return redirect()->route('jadwal.index')->with('success', "{$countCreated} slot jam operasional berhasil digenerate.");
    }

    // fungsi memperbarui data
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'field_id'     => 'required|exists:fields,id',
            'day'          => 'required|in:all,monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after:start_time',
            'custom_price' => 'nullable|numeric|min:0',
            'status'       => 'required|in:active,inactive',
        ]);

        $schedule->update($validated);

        return redirect()->route('jadwal.index')->with('success', 'Slot jam operasional berhasil diperbarui.');
    }

    // fungsi mengubah status data
    public function toggleStatus(Schedule $schedule)
    {
        $newStatus = ($schedule->status === 'active') ? 'inactive' : 'active';
        $schedule->update(['status' => $newStatus]);

        $statusIndo = ($newStatus === 'active') ? 'aktif' : 'nonaktif';
        return redirect()->route('jadwal.index')->with('success', "Status slot berhasil diubah menjadi {$statusIndo}.");
    }

    // fungsi menghapus data
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('jadwal.index')->with('success', 'Slot jam operasional berhasil dihapus.');
    }
}
