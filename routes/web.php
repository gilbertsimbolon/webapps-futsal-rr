<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route Login
Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// Route Registrasi
Route::get('/register', [RegisterController::class, 'index'])->name('register.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Route Lupa Password
Route::get('/lupa-password', function () {
    return view('auth.lupa_password');
});

// Route Reset Password
Route::get('/reset-password', function() {
    return view('auth.reset_password');
});

// Route Dashboard
Route::get('/dashboard', function () {
    return view('layouts.app');
})->name('dashboard');