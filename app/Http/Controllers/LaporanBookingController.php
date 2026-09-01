<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanBookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Pastikan hanya pemilik atau admin yang dapat mengakses
        if (!$user->hasRole('pemilik') && !$user->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki akses ke laporan booking.');
        }

        // 1. Ambil cabang venue milik owner yang login
        if ($user->hasRole('pemilik')) {
            $branches = Branch::where('status', 'active')
                ->where('user_id', $user->id)
                ->get();
            $ownerBranchIds = $branches->pluck('id')->toArray();

            $fields = Field::where('status', 'available')
                ->whereIn('branch_id', $ownerBranchIds)
                ->get();
        } else {
            // Admin
            $branches = Branch::where('status', 'active')->get();
            $ownerBranchIds = $branches->pluck('id')->toArray();
            $fields = Field::where('status', 'available')->get();
        }

        // 2. Filter Periode Cepat (Mingguan, Bulanan, Tahunan, atau Kustom)
        $periode = $request->get('periode', 'bulanan');
        $now = Carbon::now();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->start_date;
            $endDate   = $request->end_date;
            $periode   = 'custom';
        } else {
            switch ($periode) {
                case 'mingguan':
                    $startDate = $now->copy()->startOfWeek()->toDateString();
                    $endDate   = $now->copy()->endOfWeek()->toDateString();
                    break;
                case 'tahunan':
                    $startDate = $now->copy()->startOfYear()->toDateString();
                    $endDate   = $now->copy()->endOfYear()->toDateString();
                    break;
                case 'bulanan':
                default:
                    $startDate = $now->copy()->startOfMonth()->toDateString();
                    $endDate   = $now->copy()->endOfMonth()->toDateString();
                    $periode   = 'bulanan';
                    break;
            }
        }

        // 3. Query Booking yang terisolasi khusus cabang Owner
        $query = Booking::with(['user', 'branch', 'field', 'paymentMethod'])
            ->whereBetween('booking_date', [$startDate, $endDate]);

        if ($user->hasRole('pemilik')) {
            $query->whereIn('branch_id', $ownerBranchIds);
        }

        // Filter Pencarian (Kode Booking, Pemesan, Catatan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Filter Cabang Spesifik
        if ($request->filled('branch_id')) {
            if ($user->hasRole('pemilik')) {
                if (in_array((int) $request->branch_id, $ownerBranchIds)) {
                    $query->where('branch_id', $request->branch_id);
                }
            } else {
                $query->where('branch_id', $request->branch_id);
            }
        }

        // Filter Lapangan Spesifik
        if ($request->filled('field_id')) {
            $query->where('field_id', $request->field_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Urutkan jadwal
        $bookings = $query->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        // 4. Fitur Export CSV / Excel jika diminta
        if ($request->get('export') === 'csv') {
            return $this->exportCsv($bookings, $startDate, $endDate);
        }

        // 5. Rekapitulasi Statistik
        $totalTransaksi = $bookings->count();
        $totalLunas     = $bookings->where('status', 'paid')->count();
        $totalDP        = $bookings->where('status', 'confirmed')->count();
        $totalSelesai   = $bookings->where('status', 'completed')->count();
        $totalPending   = $bookings->where('status', 'pending')->count();
        $totalBatal     = $bookings->where('status', 'cancelled')->count();
        $totalBiaya     = $bookings->whereIn('status', ['paid', 'confirmed', 'completed'])->sum('total_amount');

        // Total Uang Masuk Aktual (100% Lunas/Selesai + 50% DP Masuk)
        $totalPendapatanMasuk = $bookings->sum(function ($b) {
            if (in_array($b->status, ['paid', 'completed'])) {
                return (float) $b->total_amount;
            } elseif ($b->status === 'confirmed') {
                return (float) ($b->dp_amount > 0 ? $b->dp_amount : ($b->total_amount * 0.5));
            }
            return 0;
        });

        return view('laporan.booking', compact(
            'bookings',
            'branches',
            'fields',
            'startDate',
            'endDate',
            'periode',
            'totalTransaksi',
            'totalLunas',
            'totalDP',
            'totalSelesai',
            'totalPending',
            'totalBatal',
            'totalBiaya',
            'totalPendapatanMasuk'
        ));
    }

    /**
     * Export Data Laporan Booking ke CSV (Dapat langsung dibuka di Excel)
     */
    private function exportCsv($bookings, $startDate, $endDate)
    {
        $filename = "laporan-booking-{$startDate}-sd-{$endDate}.csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($bookings) {
            $output = fopen('php://output', 'w');

            // BOM UTF-8 agar karakter simbol & angka terbaca rapi di Microsoft Excel
            fputs($output, "\xEF\xBB\xBF");

            // Header Kolom
            fputcsv($output, [
                'NO',
                'KODE BOOKING',
                'TANGGAL MAIN',
                'SLOT WAKTU',
                'NAMA TIM / PEMESAN',
                'NO TELEPON',
                'UNIT LAPANGAN',
                'CABANG VENUE',
                'TOTAL BIAYA (RP)',
                'DP DIBAYAR (RP)',
                'METODE BAYAR',
                'STATUS BAYAR',
                'CATATAN',
                'TANGGAL TRANSAKSI'
            ]);

            foreach ($bookings as $index => $b) {
                $startTime = $b->start_time ? Carbon::parse($b->start_time)->format('H:i') : '-';
                $endTime   = $b->end_time ? Carbon::parse($b->end_time)->format('H:i') : '-';
                $slot      = "{$startTime} - {$endTime} WITA";

                $statusText = match ($b->status) {
                    'paid'      => 'Lunas 100%',
                    'confirmed' => 'DP 50%',
                    'completed' => 'Selesai Main',
                    'pending'   => 'Pending',
                    'cancelled' => 'Batal',
                    default     => ucfirst($b->status),
                };

                $dpPaid = $b->status === 'confirmed'
                    ? ($b->dp_amount > 0 ? $b->dp_amount : $b->total_amount * 0.5)
                    : ($b->status === 'paid' || $b->status === 'completed' ? $b->total_amount : 0);

                fputcsv($output, [
                    $index + 1,
                    $b->booking_code,
                    $b->booking_date ? Carbon::parse($b->booking_date)->format('d/m/Y') : '-',
                    $slot,
                    $b->user?->name ?? 'Tamu Walk-in',
                    $b->user?->phone ?? '-',
                    $b->field?->field_name ?? '-',
                    $b->branch?->branch_name ?? '-',
                    $b->total_amount,
                    $dpPaid,
                    strtoupper($b->paymentMethod?->name ?? ($b->payment_method ?? '-')),
                    $statusText,
                    $b->notes ?? '-',
                    $b->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
