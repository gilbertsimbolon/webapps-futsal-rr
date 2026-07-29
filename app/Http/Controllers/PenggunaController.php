<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    // fungsi index
    public function index()
    {
        $pengguna = User::all();

        return view('pengguna.index', compact('pengguna'));
    }
}
