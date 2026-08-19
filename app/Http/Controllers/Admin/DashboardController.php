<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
// use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // fungsi index
    public function index()
    {
        // mengambil jumlah pemilik dari tabel users dengan role 'pemilik'
        $owners = User::role('pemilik')->count();
        $users = User::all()->count();
        $tenants = User::role('pelanggan')->count();

        return view('admin.dashboard', compact('owners', 'users', 'tenants'));
    }
}
