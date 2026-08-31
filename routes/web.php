<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LupaPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\OwnerDashboardController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\KalenderKetersediaanController;
use App\Http\Controllers\LapanganController;
use App\Http\Controllers\LaporanBookingController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RiwayatBookingController;
use App\Http\Controllers\RoundRobinController;
use App\Http\Controllers\SewaLapanganController;
use Illuminate\Support\Facades\Route;

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

    // Route Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.admin');
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard.owner');

    // Route Manajemen Pengguna
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::put('/pengguna/{user}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/{user}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
    Route::patch('/pengguna/{user}/toggle-status', [PenggunaController::class, 'toggleStatus'])->name('pengguna.toggle-status');

    // Route Cabang
    Route::get('/cabang', [CabangController::class, 'index'])->name('cabang.index');
    Route::post('/cabang', [CabangController::class, 'store'])->name('cabang.store');
    Route::put('/cabang/{branch}', [CabangController::class, 'update'])->name('cabang.update');
    Route::delete('/cabang/{branch}', [CabangController::class, 'destroy'])->name('cabang.destroy');
    Route::patch('/cabang/{branch}/toggle-status', [CabangController::class, 'toggleStatus'])->name('cabang.toggle-status');

    // Route Lapangan
    Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');
    Route::post('/lapangan', [LapanganController::class, 'store'])->name('lapangan.store');
    Route::put('/lapangan/{field}', [LapanganController::class, 'update'])->name('lapangan.update');
    Route::delete('/lapangan/{field}', [LapanganController::class, 'destroy'])->name('lapangan.destroy');
    Route::patch('/lapangan/{field}/toggle-status', [LapanganController::class, 'toggleStatus'])->name('lapangan.toggle-status');

    // Route Jadwal
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::post('/jadwal/generate', [JadwalController::class, 'generate'])->name('jadwal.generate');
    Route::put('/jadwal/{schedule}', [JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{schedule}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');
    Route::patch('/jadwal/{schedule}/toggle-status', [JadwalController::class, 'toggleStatus'])->name('jadwal.toggle-status');

    // Route Booking
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/walk-in', [BookingController::class, 'storeWalkIn'])->name('bookings.store-walkin');
    Route::patch('/bookings/{booking}/pay', [BookingController::class, 'processPayment'])->name('bookings.pay');
    Route::patch('/bookings/{booking}/check-in', [BookingController::class, 'checkIn'])->name('bookings.check-in');
    Route::patch('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::post('/api/bookings/available-slots', [BookingController::class, 'getAvailableSlots'])->name('bookings.available-slots');

    // Route Pembayaran Booking
    Route::get('/booking/bayar/{booking_code}', [BookingController::class, 'showPayment'])->name('booking.payment');
    Route::post('/booking/bayar/{booking}/upload', [BookingController::class, 'uploadPaymentProof'])->name('booking.upload-proof');

    // Route Metode Pembayaran
    Route::get('/metode-pembayaran', [MetodePembayaranController::class, 'index'])->name('metode-pembayaran.index');
    Route::post('/metode-pembayaran', [MetodePembayaranController::class, 'store'])->name('metode-pembayaran.store');
    Route::put('/metode-pembayaran/{paymentMethod}', [MetodePembayaranController::class, 'update'])->name('metode-pembayaran.update');
    Route::delete('/metode-pembayaran/{paymentMethod}', [MetodePembayaranController::class, 'destroy'])->name('metode-pembayaran.destroy');
    Route::patch('/metode-pembayaran/{paymentMethod}/toggle-status', [MetodePembayaranController::class, 'toggleStatus'])->name('metode-pembayaran.toggle-status');

    // Route Round Robin
    Route::get('/round-robin/monitoring', [RoundRobinController::class, 'monitoring'])->name('round-robin.monitoring');
    Route::get('/round-robin/simulasi', [RoundRobinController::class, 'simulation'])->name('round-robin.simulation');
    Route::post('/round-robin/enqueue', [RoundRobinController::class, 'enqueueSimulation'])->name('round-robin.enqueue');
    Route::patch('/round-robin/rotate/{queue}', [RoundRobinController::class, 'forceRotate'])->name('round-robin.rotate');

    // Route Laporan
    Route::get('/laporan/booking', [LaporanBookingController::class, 'index'])->name('laporan.booking');

    // Route Costumer

    // Route Sewa dan Cari Lapangan
    Route::get('/cari-lapangan', [SewaLapanganController::class, 'index'])->name('sewa.index');
    Route::get('/sewa/{field}', [SewaLapanganController::class, 'create'])->name('sewa.create');

    // Route Kalender
    Route::get('/kalender-ketersediaan', [KalenderKetersediaanController::class, 'index'])->name('kalender.index');

    // Route Riwayat Booking
    Route::get('/riwayat-booking', [RiwayatBookingController::class, 'index'])->name('riwayat.index');
    Route::patch('/riwayat-booking/{booking}/cancel', [RiwayatBookingController::class, 'cancel'])->name('riwayat.cancel');
});
