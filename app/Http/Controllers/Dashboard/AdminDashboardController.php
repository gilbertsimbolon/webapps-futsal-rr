<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\BookingQueue;
use App\Models\Branch;
use App\Models\Field;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalBranches = Branch::count();

        $totalFields = Field::count();

        $totalUsers = User::count();

        $weeklyUsers = $this->getWeeklyUsers();

        $roundRobin = $this->getRoundRobinEvaluation();

        return view('dashboard.admin', compact(
            'user',
            'totalBranches',
            'totalFields',
            'totalUsers',
            'weeklyUsers',
            'roundRobin'
        ));
    }

    private function getWeeklyUsers(): array
    {
        $startDate = Carbon::now()
            ->startOfWeek()
            ->subWeeks(7);

        $users = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $result = [];

        for ($i = 0; $i < 8; $i++) {
            $weekStart = $startDate->copy()->addWeeks($i);
            $weekEnd = $weekStart->copy()->endOfWeek();

            $total = $users
                ->filter(function ($user) use ($weekStart, $weekEnd) {
                    $date = Carbon::parse($user->date);

                    return $date->between($weekStart, $weekEnd);
                })
                ->sum('total');

            $result[] = [
                'label' => $weekStart->format('d M'),
                'total' => (int) $total,
            ];
        }

        return $result;
    }

    private function getRoundRobinEvaluation(): array
    {
        $today = Carbon::today();

        $total = BookingQueue::whereDate(
            'booking_date',
            $today
        )->count();

        $active = BookingQueue::whereDate(
            'booking_date',
            $today
        )
            ->where('status', 'active_turn')
            ->count();

        $waiting = BookingQueue::whereDate(
            'booking_date',
            $today
        )
            ->where('status', 'waiting')
            ->count();

        $preempted = BookingQueue::whereDate(
            'booking_date',
            $today
        )
            ->where('status', 'preempted')
            ->count();

        $processed = $active + $preempted;

        $processedPercentage = $total > 0
            ? round(($processed / $total) * 100)
            : 0;

        if ($total === 0) {
            $status = 'Tidak ada antrean';
            $statusType = 'secondary';
        } elseif ($active > 0) {
            $status = 'Round Robin Aktif';
            $statusType = 'success';
        } elseif ($waiting > 0) {
            $status = 'Menunggu Antrean';
            $statusType = 'warning';
        } else {
            $status = 'Selesai';
            $statusType = 'info';
        }

        return [
            'total' => $total,
            'active' => $active,
            'waiting' => $waiting,
            'preempted' => $preempted,
            'processed' => $processed,
            'processed_percentage' => $processedPercentage,
            'status' => $status,
            'status_type' => $statusType,
        ];
    }
}