<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $branchIds = Branch::where('user_id', $user->id)
            ->pluck('id');

        $totalBranches = $branchIds->count();

        $totalFields = Field::whereIn('branch_id', $branchIds)
            ->count();

        $totalBookings = Booking::whereIn('branch_id', $branchIds)
            ->count();

        $totalRevenue = Booking::whereIn('branch_id', $branchIds)
            ->whereIn('status', ['paid', 'completed'])
            ->sum('total_amount');

        $startOfWeek = Carbon::now()->startOfWeek();

        $weeklyBookings = Booking::select(
            DB::raw('DATE(booking_date) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->whereIn('branch_id', $branchIds)
            ->whereDate('booking_date', '>=', $startOfWeek)
            ->groupBy(DB::raw('DATE(booking_date)'))
            ->orderBy('date')
            ->get();

        $weeklyRevenue = Booking::select(
            DB::raw('DATE(booking_date) as date'),
            DB::raw('SUM(total_amount) as total')
        )
            ->whereIn('branch_id', $branchIds)
            ->whereIn('status', ['paid', 'completed'])
            ->whereDate('booking_date', '>=', $startOfWeek)
            ->groupBy(DB::raw('DATE(booking_date)'))
            ->orderBy('date')
            ->get();

        $weeklyLabels = [];
        $weeklyBookingData = [];
        $weeklyRevenueData = [];

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);

            $weeklyLabels[] = $date->format('d M');

            $booking = $weeklyBookings->firstWhere(
                'date',
                $date->format('Y-m-d')
            );

            $revenue = $weeklyRevenue->firstWhere(
                'date',
                $date->format('Y-m-d')
            );

            $weeklyBookingData[] = $booking?->total ?? 0;
            $weeklyRevenueData[] = $revenue?->total ?? 0;
        }

        $weeklyTotalBookings = array_sum($weeklyBookingData);
        $weeklyTotalRevenue = array_sum($weeklyRevenueData);

        $recentBookings = Booking::with([
            'user',
            'branch',
            'field',
        ])
            ->whereIn('branch_id', $branchIds)
            ->latest('booking_date')
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('dashboard.owner', [
            'user' => $user,
            'totalBranches' => $totalBranches,
            'totalFields' => $totalFields,
            'totalBookings' => $totalBookings,
            'totalRevenue' => $totalRevenue,
            'weeklyLabels' => $weeklyLabels,
            'weeklyBookingData' => $weeklyBookingData,
            'weeklyRevenueData' => $weeklyRevenueData,
            'weeklyTotalBookings' => $weeklyTotalBookings,
            'weeklyTotalRevenue' => $weeklyTotalRevenue,
            'recentBookings' => $recentBookings,
        ]);
    }
}
