<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanBookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Ambil cabang venue milik owner yang login
        $branches = Branch::where('status', 'active')
            ->where('user_id', $user->id)
            ->get();
        $ownerBranchIds = $branches->pluck('id')->toArray();

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
        $query = Booking::with(['user', 'branch', 'field'])
            ->whereIn('branch_id', $ownerBranchIds)
            ->whereBetween('booking_date', [$startDate, $endDate]);

        // Filter Cabang Spesifik
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
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
        $totalBatal     = $bookings->where('status', 'cancelled')->count();
        $totalBiaya     = $bookings->whereIn('status', ['paid', 'confirmed', 'completed'])->sum('total_amount');

        return view('laporan.booking', compact(
            'bookings',
            'branches',
            'startDate',
            'endDate',
            'periode',
            'totalTransaksi',
            'totalLunas',
            'totalDP',
            'totalSelesai',
            'totalBatal',
            'totalBiaya'
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
                'UNIT LAPANGAN',
                'CABANG VENUE',
                'TOTAL BIAYA (RP)',
                'METODE BAYAR',
                'STATUS BAYAR',
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

                fputcsv($output, [
                    $index + 1,
                    $b->booking_code,
                    $b->booking_date ? Carbon::parse($b->booking_date)->format('d/m/Y') : '-',
                    $slot,
                    $b->user?->name ?? 'Tamu Walk-in',
                    $b->field?->field_name ?? '-',
                    $b->branch?->branch_name ?? '-',
                    $b->total_amount,
                    strtoupper($b->payment_method ?? '-'),
                    $statusText,
                    $b->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}