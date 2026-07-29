<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    // fungsi index
    public function index()
    {
        $pelanggan = User::role('pelanggan')->get();

        return view('pelanggan.index', compact('pelanggan'));
    }
}
