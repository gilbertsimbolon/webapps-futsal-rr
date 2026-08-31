<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemilikDashboardController extends Controller
{
    // Menampilkan dashboard pemilik
    public function index()
    {
        $user = Auth::user();

        // Ambil cabang yang dimiliki oleh pemilik yang sedang login
        $branches = Branch::where('user_id', $user->id)
            ->withCount('fields')
            ->latest()
            ->get();

        // Ambil ID cabang milik pemilik
        $branchIds = $branches->pluck('id');

        // Total cabang milik pemilik
        $totalBranches = $branches->count();

        // Total lapangan dari seluruh cabang milik pemilik
        $totalFields = Field::whereIn('branch_id', $branchIds)->count();

        // Query dasar booking yang hanya berasal dari cabang milik pemilik
        $bookingQuery = Booking::whereHas('field', function ($query) use ($branchIds) {
            $query->whereIn('branch_id', $branchIds);
        });

        // Jumlah booking hari ini
        $todayBookings = (clone $bookingQuery)
            ->whereDate('booking_date', today())
            ->count();

        // Jumlah booking bulan ini
        $monthlyBookings = (clone $bookingQuery)
            ->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->count();

        // Jumlah booking yang masih menunggu konfirmasi
        $pendingBookings = (clone $bookingQuery)
            ->where('status', 'pending')
            ->count();

        // Jumlah booking yang sudah dikonfirmasi
        $confirmedBookings = (clone $bookingQuery)
            ->where('status', 'confirmed')
            ->count();

        // Jumlah booking yang dibatalkan
        $cancelledBookings = (clone $bookingQuery)
            ->where('status', 'cancelled')
            ->count();

        // Total pendapatan bulan ini
        $monthlyRevenue = (clone $bookingQuery)
            ->where('status', 'confirmed')
            ->whereMonth('booking_date', now()->month)
            ->whereYear('booking_date', now()->year)
            ->sum('total_amount');

        // Ambil 10 booking terbaru dari seluruh cabang milik pemilik
        $recentBookings = (clone $bookingQuery)
            ->with([
                'user',
                'field.branch',
            ])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.pemilik', compact(
            'totalBranches',
            'totalFields',
            'todayBookings',
            'monthlyRevenue',
            'recentBookings',
            'branches',
            'pendingBookings',
            'confirmedBookings',
            'cancelledBookings',
            'monthlyBookings'
        ));
    }
}
