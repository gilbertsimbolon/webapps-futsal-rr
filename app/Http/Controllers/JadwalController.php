<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Menampilkan daftar jadwal milik pemilik yang sedang login.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Pastikan hanya pemilik yang dapat mengakses halaman jadwal
        if (!$user->hasRole('pemilik')) {
            abort(403, 'Anda tidak memiliki akses ke data jadwal.');
        }

        /*
        |--------------------------------------------------------------------------
        | Query Jadwal
        |--------------------------------------------------------------------------
        | Hanya mengambil jadwal dari lapangan yang berada di cabang
        | milik user yang sedang login.
        */
        $query = Schedule::with(['field.branch'])
            ->whereHas('field.branch', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        // Filter berdasarkan lapangan
        if ($request->filled('field_id')) {
            $query->where('field_id', $request->field_id);
        }

        // Filter berdasarkan hari
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        $schedules = $query
            ->orderBy('start_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Daftar Lapangan
        |--------------------------------------------------------------------------
        | Hanya lapangan aktif/nonaktif milik pemilik yang sedang login.
        */
        $fields = Field::with('branch')
            ->whereHas('branch', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('status', '!=', 'inactive')
            ->orderBy('field_name')
            ->get();

        return view('jadwal.index', compact(
            'schedules',
            'fields'
        ));
    }

    /**
     * Menambahkan 1 slot jadwal secara manual.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Hanya pemilik
        if (!$user->hasRole('pemilik')) {
            abort(403, 'Anda tidak memiliki akses untuk menambahkan jadwal.');
        }

        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'day' => 'required|in:all,monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'custom_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan lapangan milik pemilik yang sedang login
        |--------------------------------------------------------------------------
        */
        $field = Field::where('id', $validated['field_id'])
            ->whereHas('branch', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (!$field) {
            return back()
                ->withErrors([
                    'field_id' => 'Lapangan yang dipilih tidak valid atau bukan milik Anda.',
                ])
                ->withInput();
        }

        Schedule::create($validated);

        return redirect()
            ->route('pemilik.jadwal.index')
            ->with(
                'success',
                'Slot jam operasional berhasil ditambahkan.'
            );
    }

    /**
     * Generate slot jadwal otomatis dengan interval 1 jam.
     */
    public function generate(Request $request)
    {
        $user = Auth::user();

        // Hanya pemilik
        if (!$user->hasRole('pemilik')) {
            abort(403, 'Anda tidak memiliki akses untuk membuat jadwal.');
        }

        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'day' => 'required|in:all,monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan lapangan milik pemilik yang sedang login
        |--------------------------------------------------------------------------
        */
        $field = Field::where('id', $validated['field_id'])
            ->whereHas('branch', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (!$field) {
            return back()
                ->withErrors([
                    'field_id' => 'Lapangan yang dipilih tidak valid atau bukan milik Anda.',
                ])
                ->withInput();
        }

        $start = Carbon::createFromFormat(
            'H:i',
            $validated['open_time']
        );

        $end = Carbon::createFromFormat(
            'H:i',
            $validated['close_time']
        );

        $countCreated = 0;

        while ($start->copy()->addHour()->lte($end)) {

            $slotStart = $start->format('H:i:00');
            $slotEnd = $start->copy()->addHour()->format('H:i:00');

            /*
            |--------------------------------------------------------------------------
            | Cek duplikasi slot
            |--------------------------------------------------------------------------
            */
            $exists = Schedule::where('field_id', $validated['field_id'])
                ->where('day', $validated['day'])
                ->where('start_time', $slotStart)
                ->where('end_time', $slotEnd)
                ->exists();

            if (!$exists) {

                Schedule::create([
                    'field_id' => $validated['field_id'],
                    'day' => $validated['day'],
                    'start_time' => $slotStart,
                    'end_time' => $slotEnd,
                    'status' => 'active',
                ]);

                $countCreated++;
            }

            $start->addHour();
        }

        return redirect()
            ->route('pemilik.jadwal.index')
            ->with(
                'success',
                "{$countCreated} slot jam operasional berhasil digenerate."
            );
    }

    /**
     * Memperbarui data jadwal.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $user = Auth::user();

        // Hanya pemilik
        if (!$user->hasRole('pemilik')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah jadwal.');
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan jadwal tersebut milik pemilik yang sedang login
        |--------------------------------------------------------------------------
        */
        $this->authorizeSchedule($schedule, $user);

        $validated = $request->validate([
            'field_id' => 'required|exists:fields,id',
            'day' => 'required|in:all,monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'custom_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Jika field_id diganti, pastikan lapangan baru juga milik user
        |--------------------------------------------------------------------------
        */
        $field = Field::where('id', $validated['field_id'])
            ->whereHas('branch', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->first();

        if (!$field) {
            return back()
                ->withErrors([
                    'field_id' => 'Lapangan yang dipilih tidak valid atau bukan milik Anda.',
                ])
                ->withInput();
        }

        $schedule->update($validated);

        return redirect()
            ->route('pemilik.jadwal.index')
            ->with(
                'success',
                'Slot jam operasional berhasil diperbarui.'
            );
    }

    /**
     * Mengubah status jadwal.
     */
    public function toggleStatus(Schedule $schedule)
    {
        $user = Auth::user();

        // Hanya pemilik
        if (!$user->hasRole('pemilik')) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status jadwal.');
        }

        // Pastikan jadwal milik user
        $this->authorizeSchedule($schedule, $user);

        $newStatus = $schedule->status === 'active'
            ? 'inactive'
            : 'active';

        $schedule->update([
            'status' => $newStatus,
        ]);

        $statusIndo = $newStatus === 'active'
            ? 'aktif'
            : 'nonaktif';

        return redirect()
            ->route('pemilik.jadwal.index')
            ->with(
                'success',
                "Status slot berhasil diubah menjadi {$statusIndo}."
            );
    }

    /**
     * Menghapus jadwal.
     */
    public function destroy(Schedule $schedule)
    {
        $user = Auth::user();

        // Hanya pemilik
        if (!$user->hasRole('pemilik')) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus jadwal.');
        }

        // Pastikan jadwal milik user
        $this->authorizeSchedule($schedule, $user);

        $schedule->delete();

        return redirect()
            ->route('pemilik.jadwal.index')
            ->with(
                'success',
                'Slot jam operasional berhasil dihapus.'
            );
    }

    /**
     * Memastikan jadwal berada pada cabang milik user yang login.
     */
    private function authorizeSchedule(Schedule $schedule, $user)
    {
        $schedule->loadMissing('field.branch');

        if (
            !$schedule->field ||
            !$schedule->field->branch ||
            $schedule->field->branch->user_id !== $user->id
        ) {
            abort(
                403,
                'Anda tidak memiliki akses ke jadwal ini.'
            );
        }
    }
}