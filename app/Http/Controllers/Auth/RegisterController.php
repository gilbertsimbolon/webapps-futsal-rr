<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    // fungsi index
    public function index()
    {
        return view('auth.register');
    }

    
}
