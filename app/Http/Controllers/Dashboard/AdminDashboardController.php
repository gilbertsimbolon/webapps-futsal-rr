<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
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

        return view('dashboard.admin', compact(
            'user',
            'totalBranches',
            'totalFields',
            'totalUsers',
            'weeklyUsers'
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

                    return $date->between(
                        $weekStart,
                        $weekEnd
                    );
                })
                ->sum('total');

            $result[] = [
                'label' => $weekStart->format('d M'),
                'total' => (int) $total,
            ];
        }

        return $result;
    }
}