<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatBookingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');

        $query = Booking::with(['field.branch'])
            ->where('user_id', Auth::id());

        if ($request->filled('status')) {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('customer.riwayat.index', compact('bookings', 'status'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status === 'pending') {
            $booking->update(['status' => 'cancelled']);
            return back()->with('success', 'Pesanan booking berhasil dibatalkan.');
        }

        return back()->withErrors(['msg' => 'Hanya pesanan berstatus pending yang dapat dibatalkan mandiri.']);
    }
}