<?php

use App\Http\Controllers\Admin\CabangController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PemilikController;
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


Route::middleware('auth')->group(function () {
    // Route Logout
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

    // Route Profil
    Route::prefix('profil')->group(function () {
        // Halaman Profil
        Route::get('/', [ProfilController::class, 'index'])->name('profil.index');
        // Edit Data Profil
        Route::post('/update', [ProfilController::class, 'update'])->name('profil.update');
        Route::post('/password', [ProfilController::class, 'updatePassword'])->name('profil.update.password');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Route Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route Manajemen Pengguna
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    // Route Update Status Akun
    Route::patch('/pelanggan/{pelanggan}/status', [PelangganController::class, 'updateStatus'])
    ->name('pelanggan.status');
    // Route Hapus Akun Pelanggan
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.delete');
    // Route Update Role Akun
    Route::put('/users/{id}/update-role', [PelangganController::class, 'updateRole'])->name('pelanggan.update-role');

    // Route Manajemen Pemilik
    Route::get('/pemilik', [PemilikController::class, 'index'])->name('pemilik.index');
    // Route Update Status Akun
    Route::patch('/pemilik/{pemilik}/status', [PemilikController::class, 'updateStatus'])->name('pemilik.status');
    // Route Hapus Akun Pemilik
    Route::delete('/pemilik/{id}', [PemilikController::class, 'destroy'])->name('pemilik.delete');

    // Route Cabang
    Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');
    Route::get('/cabang/1', function () {
        return view('admin.cabang.show');
    });
});
