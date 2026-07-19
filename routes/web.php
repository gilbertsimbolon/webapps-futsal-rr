<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LupaPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfilController;
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
Route::get('/lupa-password', [LupaPasswordController::class, 'showHalamanLupaPassword'])->name('lupa-password.index');
Route::post('/lupa-password', [LupaPasswordController::class, 'kirimLinkReset'])->name('lupa-password.kirim');

// Route Reset Password
Route::get('/reset-password/{token}/{email}', [LupaPasswordController::class, 'showHalamanResetPassword'])->name('reset-password.index');
Route::post('/reset-password', [LupaPasswordController::class, 'resetPassword'])->name('reset-password.reset');

// Route Logout
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Route Profil
Route::get('/profil', [ProfilController::class, 'index'])->name('profile.index');

// Route Dashboard
Route::get('/dashboard', function () {
    return view('layouts.app');
})->name('dashboard');