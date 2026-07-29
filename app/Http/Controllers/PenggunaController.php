<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    // fungsi index
    public function index()
    {
        return view('pengguna.index');
    }
}
