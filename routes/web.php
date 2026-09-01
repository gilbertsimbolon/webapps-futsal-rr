<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LupaPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CustomerBookingController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\PemilikDashboardController;
use App\Http\Controllers\FrontendController;
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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/link', function () {
    Artisan::call('storage:link');
    return 'Symlink berhasil dibuat!';
});


// ==========================================
// PUBLIK / GUEST (LANDING & KATALOG LAPANGAN)
// ==========================================

Route::get('/', [FrontendController::class, 'index'])
    ->name('landing');

Route::get('/lapangan', [FrontendController::class, 'fields'])
    ->name('lapangan.index');

Route::get('/lapangan/{field}', [FrontendController::class, 'fieldDetail'])
    ->name('lapangan.detail');

Route::post('/api/lapangan/{field}/slots', [FrontendController::class, 'getFieldSlots'])
    ->name('lapangan.slots');

// Login

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::get('/masuk', [LoginController::class, 'index'])
    ->name('login.index');

Route::post('/login', [LoginController::class, 'store'])
    ->name('login.store');

// Registrasi

Route::get('/register', [RegisterController::class, 'index'])
    ->name('register.index');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

// Lupa Password

Route::get('/lupa-password', [LupaPasswordController::class, 'showHalamanLupaPassword'])
    ->name('lupa-password.index');

Route::post('/lupa-password', [LupaPasswordController::class, 'kirimLinkReset'])
    ->name('lupa-password.kirim');

// Reset Password

Route::get('/reset-password/{token}/{email}', [LupaPasswordController::class, 'showHalamanResetPassword'])
    ->name('reset-password.index');

Route::post('/reset-password', [LupaPasswordController::class, 'resetPassword'])
    ->name('reset-password.reset');


Route::middleware('auth')->group(function () {

    // Logout

    Route::post('/logout', [LogoutController::class, 'logout'])
        ->name('logout');


    // Profil

    Route::prefix('profil')->group(function () {

        Route::get('/', [ProfilController::class, 'index'])
            ->name('profil.index');

        Route::post('/update', [ProfilController::class, 'update'])
            ->name('profil.update');

        Route::post('/password', [ProfilController::class, 'updatePassword'])
            ->name('profil.update.password');
    });


    // ==========================================
    // ADMIN
    // ==========================================

    Route::prefix('admin')
        ->middleware('role:admin')
        ->name('admin.')
        ->group(function () {

            // Dashboard Admin

            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');


            // Data Cabang

            Route::get('/cabang', [CabangController::class, 'index'])
                ->name('cabang.index');

            Route::post('/cabang', [CabangController::class, 'store'])
                ->name('cabang.store');

            Route::put('/cabang/{branch}', [CabangController::class, 'update'])
                ->name('cabang.update');

            Route::delete('/cabang/{branch}', [CabangController::class, 'destroy'])
                ->name('cabang.destroy');

            Route::patch('/cabang/{branch}/toggle-status', [CabangController::class, 'toggleStatus'])
                ->name('cabang.toggle-status');


            // Data Lapangan

            Route::get('/lapangan', [LapanganController::class, 'index'])
                ->name('lapangan.index');

            Route::post('/lapangan', [LapanganController::class, 'store'])
                ->name('lapangan.store');

            Route::put('/lapangan/{field}', [LapanganController::class, 'update'])
                ->name('lapangan.update');

            Route::delete('/lapangan/{field}', [LapanganController::class, 'destroy'])
                ->name('lapangan.destroy');

            Route::patch('/lapangan/{field}/toggle-status', [LapanganController::class, 'toggleStatus'])
                ->name('lapangan.toggle-status');


            // Monitoring Antrean Round Robin

            Route::get('/round-robin/monitoring', [RoundRobinController::class, 'monitoring'])
                ->name('round-robin.monitoring');


            // Simulasi Antrean Round Robin

            Route::get('/round-robin/simulasi', [RoundRobinController::class, 'simulation'])
                ->name('round-robin.simulation');

            Route::post('/round-robin/enqueue', [RoundRobinController::class, 'enqueueSimulation'])
                ->name('round-robin.enqueue');

            Route::patch('/round-robin/rotate/{queue}', [RoundRobinController::class, 'forceRotate'])
                ->name('round-robin.rotate');


            // Manajemen Pengguna

            Route::get('/pengguna', [PenggunaController::class, 'index'])
                ->name('pengguna.index');

            Route::post('/pengguna', [PenggunaController::class, 'store'])
                ->name('pengguna.store');

            Route::put('/pengguna/{user}', [PenggunaController::class, 'update'])
                ->name('pengguna.update');

            Route::delete('/pengguna/{user}', [PenggunaController::class, 'destroy'])
                ->name('pengguna.destroy');

            Route::patch('/pengguna/{user}/toggle-status', [PenggunaController::class, 'toggleStatus'])
                ->name('pengguna.toggle-status');
        });


    // ==========================================
    // PEMILIK
    // ==========================================

    Route::prefix('pemilik')
        ->middleware('role:pemilik')
        ->name('pemilik.')
        ->group(function () {

            // Dashboard Pemilik

            Route::get('/dashboard', [PemilikDashboardController::class, 'index'])
                ->name('dashboard');


            // Data Cabang Pemilik

            Route::get('/cabang', [CabangController::class, 'index'])
                ->name('cabang.index');

            Route::post('/cabang', [CabangController::class, 'store'])
                ->name('cabang.store');

            Route::put('/cabang/{branch}', [CabangController::class, 'update'])
                ->name('cabang.update');

            Route::delete('/cabang/{branch}', [CabangController::class, 'destroy'])
                ->name('cabang.destroy');

            Route::patch('/cabang/{branch}/toggle-status', [CabangController::class, 'toggleStatus'])
                ->name('cabang.toggle-status');


            // Data Lapangan Pemilik

            Route::get('/lapangan', [LapanganController::class, 'index'])
                ->name('lapangan.index');

            Route::post('/lapangan', [LapanganController::class, 'store'])
                ->name('lapangan.store');

            Route::put('/lapangan/{field}', [LapanganController::class, 'update'])
                ->name('lapangan.update');

            Route::delete('/lapangan/{field}', [LapanganController::class, 'destroy'])
                ->name('lapangan.destroy');

            Route::patch('/lapangan/{field}/toggle-status', [LapanganController::class, 'toggleStatus'])
                ->name('lapangan.toggle-status');


            // Jadwal Lapangan Pemilik

            Route::get('/jadwal', [JadwalController::class, 'index'])
                ->name('jadwal.index');

            Route::post('/jadwal', [JadwalController::class, 'store'])
                ->name('jadwal.store');

            Route::post('/jadwal/generate', [JadwalController::class, 'generate'])
                ->name('jadwal.generate');

            Route::put('/jadwal/{schedule}', [JadwalController::class, 'update'])
                ->name('jadwal.update');

            Route::delete('/jadwal/{schedule}', [JadwalController::class, 'destroy'])
                ->name('jadwal.destroy');

            Route::patch('/jadwal/{schedule}/toggle-status', [JadwalController::class, 'toggleStatus'])
                ->name('jadwal.toggle-status');


            // Metode Pembayaran Pemilik

            Route::get('/metode-pembayaran', [MetodePembayaranController::class, 'index'])
                ->name('metode-pembayaran.index');

            Route::post('/metode-pembayaran', [MetodePembayaranController::class, 'store'])
                ->name('metode-pembayaran.store');

            Route::put('/metode-pembayaran/{paymentMethod}', [MetodePembayaranController::class, 'update'])
                ->name('metode-pembayaran.update');

            Route::delete('/metode-pembayaran/{paymentMethod}', [MetodePembayaranController::class, 'destroy'])
                ->name('metode-pembayaran.destroy');

            Route::patch('/metode-pembayaran/{paymentMethod}/toggle-status', [MetodePembayaranController::class, 'toggleStatus'])
                ->name('metode-pembayaran.toggle-status');


            // Booking Pemilik

            Route::get('/bookings', [BookingController::class, 'index'])
                ->name('bookings.index');

            Route::post('/bookings', [BookingController::class, 'store'])
                ->name('bookings.store');

            Route::post('/bookings/walk-in', [BookingController::class, 'storeWalkIn'])
                ->name('bookings.store-walkin');

            Route::patch('/bookings/{booking}/pay', [BookingController::class, 'processPayment'])
                ->name('bookings.pay');

            Route::patch('/bookings/{booking}/check-in', [BookingController::class, 'checkIn'])
                ->name('bookings.check-in');

            Route::patch('/bookings/{booking}/reject', [BookingController::class, 'reject'])
                ->name('bookings.reject');

            Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])
                ->name('bookings.update-status');

            Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])
                ->name('bookings.destroy');

            Route::post('/api/bookings/available-slots', [BookingController::class, 'getAvailableSlots'])
                ->name('bookings.available-slots');


            // Pembayaran Booking

            Route::get('/booking/bayar/{booking_code}', [CustomerBookingController::class, 'showPayment'])
                ->name('booking.payment');

            Route::post('/booking/bayar/{booking}/upload', [CustomerBookingController::class, 'uploadPaymentProof'])
                ->name('booking.upload-proof');


            // Laporan Booking Pemilik

            Route::get('/laporan/booking', [LaporanBookingController::class, 'index'])
                ->name('laporan.booking');
        });


    // ==========================================
    // PELANGGAN (AUTHENTICATED CUSTOMER)
    // ==========================================

    Route::prefix('pelanggan')
        ->middleware('role:pelanggan')
        ->name('pelanggan.')
        ->group(function () {

            // Booking Flow Pelanggan
            Route::get('/booking/{field}', [CustomerBookingController::class, 'create'])
                ->name('booking.create');

            Route::post('/booking', [CustomerBookingController::class, 'store'])
                ->name('booking.store');

            Route::get('/booking/bayar/{booking_code}', [CustomerBookingController::class, 'showPayment'])
                ->name('booking.payment');

            Route::post('/booking/bayar/{booking}/upload', [CustomerBookingController::class, 'uploadPaymentProof'])
                ->name('booking.upload-proof');

            // Riwayat Booking Saya
            Route::get('/riwayat-booking', [RiwayatBookingController::class, 'index'])
                ->name('riwayat.index');

            Route::patch('/riwayat-booking/{booking}/cancel', [RiwayatBookingController::class, 'cancel'])
                ->name('riwayat.cancel');

            // Kalender Ketersediaan
            Route::get('/kalender-ketersediaan', [KalenderKetersediaanController::class, 'index'])
                ->name('kalender.index');

            // Redirects / Aliases
            Route::get('/cari-lapangan', fn() => redirect()->route('lapangan.index'))
                ->name('sewa.index');

            Route::get('/sewa/{field}', fn($field) => redirect()->route('pelanggan.booking.create', $field))
                ->name('sewa.create');
        });
});
