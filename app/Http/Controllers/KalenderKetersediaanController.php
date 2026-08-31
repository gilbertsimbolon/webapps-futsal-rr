<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KalenderKetersediaanController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->get('date', Carbon::today()->toDateString());
        $branches = Branch::where('status', 'active')->with('fields')->get();

        $bookings = Booking::with(['field.branch', 'user'])
            ->whereDate('booking_date', $selectedDate)
            ->whereIn('status', ['paid', 'confirmed', 'completed', 'pending'])
            ->orderBy('start_time', 'asc')
            ->get();

        return view('customer.kalender.index', compact('branches', 'bookings', 'selectedDate'));
    }
}