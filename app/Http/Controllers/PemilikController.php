<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PemilikController extends Controller
{
    // fungsi index
    public function index()
    {
        $pemilik = User::role('pemilik')->get();

        return view('pemilik.index', compact('pemilik'));
    }
}
