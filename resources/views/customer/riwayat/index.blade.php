@extends('layouts.frontend')

@section('title', 'Booking Saya | bkngftsl.')

@section('content')
<div class="py-4 py-lg-5">
    <div class="container">
        <!-- Breadcrumb & Header -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('landing') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Booking Saya</li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="fw-extrabold text-dark mb-1">Riwayat dan Status Booking Saya</h2>
                <p class="text-muted mb-0">Pantau status jadwal futsal dan kelola bukti pembayaran reservasi Anda.</p>
            </div>
            <a href="{{ route('lapangan.index') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bx bx-plus me-1"></i> Booking Lapangan Baru
            </a>
        </div>

        <!-- Filter Tab Status -->
        <div class="d-flex gap-2 overflow-x-auto pb-2 mb-4">
            <a href="{{ route('pelanggan.riwayat.index') }}" class="btn btn-sm rounded-pill px-3 {{ !$status ? 'btn-primary' : 'btn-outline-secondary' }}">
                Semua Status
            </a>
            <a href="{{ route('pelanggan.riwayat.index', ['status' => 'pending']) }}" class="btn btn-sm rounded-pill px-3 {{ $status == 'pending' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Menunggu Pembayaran
            </a>
            <a href="{{ route('pelanggan.riwayat.index', ['status' => 'confirmed']) }}" class="btn btn-sm rounded-pill px-3 {{ $status == 'confirmed' ? 'btn-primary' : 'btn-outline-secondary' }}">
                DP Masuk (50%)
            </a>
            <a href="{{ route('pelanggan.riwayat.index', ['status' => 'paid']) }}" class="btn btn-sm rounded-pill px-3 {{ $status == 'paid' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Lunas (100%)
            </a>
            <a href="{{ route('pelanggan.riwayat.index', ['status' => 'completed']) }}" class="btn btn-sm rounded-pill px-3 {{ $status == 'completed' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Selesai Main
            </a>
            <a href="{{ route('pelanggan.riwayat.index', ['status' => 'cancelled']) }}" class="btn btn-sm rounded-pill px-3 {{ $status == 'cancelled' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Dibatalkan
            </a>
        </div>

        <!-- Daftar Riwayat Booking Cards -->
        <div class="row g-4">
            @forelse ($bookings as $booking)
                @php
                    $start = $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '-';
                    $end   = $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : '-';
                @endphp
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                        <div class="card-header bg-white p-4 pb-2 border-0 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block" style="font-size: 11px;">Kode Reservasi</small>
                                <span class="fw-bold text-primary font-monospace">{{ $booking->booking_code }}</span>
                            </div>
                            <div>
                                @if ($booking->status === 'paid')
                                    <span class="badge bg-success px-3 py-2 rounded-pill text-uppercase">Lunas</span>
                                @elseif ($booking->status === 'confirmed')
                                    <span class="badge bg-info px-3 py-2 rounded-pill text-uppercase">DP Masuk</span>
                                @elseif ($booking->status === 'completed')
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill text-uppercase">Selesai</span>
                                @elseif ($booking->status === 'pending' && !empty($booking->payment_proof))
                                    <span class="badge bg-primary px-3 py-2 rounded-pill text-uppercase">Verifikasi</span>
                                @elseif ($booking->status === 'pending')
                                    <span class="badge bg-warning px-3 py-2 rounded-pill text-uppercase">Menunggu Bayar</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2 rounded-pill text-uppercase">Batal</span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-4 pt-2">
                            <hr class="mt-0 mb-3">

                            <div class="mb-3">
                                <h5 class="fw-bold text-dark mb-1">{{ $booking->field?->field_name ?? 'Lapangan' }}</h5>
                                <small class="text-muted d-flex align-items-center gap-1 mb-1">
                                    <i class="bx bx-map-pin text-danger"></i> {{ $booking->branch?->branch_name ?? '-' }}
                                </small>
                                <div class="d-flex align-items-center gap-2 text-primary fw-semibold small">
                                    <i class="bx bx-calendar"></i>
                                    <span>{{ $booking->booking_date ? $booking->booking_date->translatedFormat('l, d F Y') : '-' }}</span>
                                    &bull;
                                    <i class="bx bx-time"></i>
                                    <span>{{ $start }} - {{ $end }} WITA</span>
                                </div>
                            </div>

                            <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 11px;">Skema Pembayaran</small>
                                    <span class="fw-bold text-dark text-uppercase small">{{ $booking->payment_type === 'dp' ? 'DP 50%' : 'Lunas 100%' }}</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block" style="font-size: 11px;">Total Tagihan</small>
                                    <span class="fw-bold text-primary fs-6">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <a href="{{ route('pelanggan.booking.payment', $booking->booking_code) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                    <i class="bx bx-show me-1"></i> Rincian & Pembayaran
                                </a>

                                @if ($booking->status === 'pending')
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('pelanggan.booking.payment', $booking->booking_code) }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                            <i class="bx bx-credit-card me-1"></i> Bayar
                                        </a>
                                        <form action="{{ route('pelanggan.riwayat.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                Batal
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <div class="avatar avatar-xl bg-label-secondary mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bx bx-calendar-x fs-1 text-secondary"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Belum Ada Riwayat Booking</h5>
                    <p class="mb-3">Anda belum memiliki reservasi lapangan futsal dengan kriteria ini.</p>
                    <a href="{{ route('lapangan.index') }}" class="btn btn-primary rounded-pill px-4">
                        Cari & Booking Lapangan Sekarang
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection