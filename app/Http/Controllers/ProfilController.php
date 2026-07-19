<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    // fungsi index
    public function index()
    {
        return view('profile.index');
    }
}
