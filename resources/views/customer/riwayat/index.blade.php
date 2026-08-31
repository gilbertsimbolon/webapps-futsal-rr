@extends('layouts.app')

@section('title', 'Riwayat Booking Saya | bkngftsl.')

@section('content')
<div class="customer-container mobile-content-wrapper">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <span class="badge bg-label-primary mb-1">Aktivitas Saya</span>
            <h4 class="fw-bold mb-0">Riwayat Booking</h4>
        </div>
        <i class="bx bx-history fs-3 text-primary"></i>
    </div>

    <!-- Filter Tab Status -->
    <div class="d-flex gap-2 overflow-x-auto pb-2 mb-3">
        <a href="{{ route('riwayat.index') }}" class="btn btn-sm rounded-pill {{ !$status ? 'btn-primary' : 'btn-outline-secondary' }}">Semua</a>
        <a href="{{ route('riwayat.index', ['status' => 'pending']) }}" class="btn btn-sm rounded-pill {{ $status == 'pending' ? 'btn-primary' : 'btn-outline-secondary' }}">Pending</a>
        <a href="{{ route('riwayat.index', ['status' => 'confirmed']) }}" class="btn btn-sm rounded-pill {{ $status == 'confirmed' ? 'btn-primary' : 'btn-outline-secondary' }}">DP 50%</a>
        <a href="{{ route('riwayat.index', ['status' => 'paid']) }}" class="btn btn-sm rounded-pill {{ $status == 'paid' ? 'btn-primary' : 'btn-outline-secondary' }}">Lunas</a>
        <a href="{{ route('riwayat.index', ['status' => 'completed']) }}" class="btn btn-sm rounded-pill {{ $status == 'completed' ? 'btn-primary' : 'btn-outline-secondary' }}">Selesai</a>
    </div>

    <!-- Daftar Riwayat -->
    <div class="row g-3">
        @forelse ($bookings as $booking)
            @php
                $start = $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : '-';
                $end   = $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : '-';
            @endphp
            <div class="col-12">
                <div class="card border-0 shadow-sm p-3 rounded-3">
                    <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-2">
                        <div>
                            <span class="fw-bold text-primary">{{ $booking->booking_code }}</span>
                            <small class="d-block text-muted">{{ $booking->booking_date ? $booking->booking_date->translatedFormat('d F Y') : '-' }}</small>
                        </div>
                        <div>
                            @if ($booking->status === 'paid')
                                <span class="badge bg-label-success">Lunas</span>
                            @elseif ($booking->status === 'confirmed')
                                <span class="badge bg-label-info">DP Masuk</span>
                            @elseif ($booking->status === 'completed')
                                <span class="badge bg-label-secondary">Selesai</span>
                            @elseif ($booking->status === 'pending')
                                <span class="badge bg-label-warning">Menunggu</span>
                            @else
                                <span class="badge bg-label-danger">Batal</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2">
                        <h6 class="fw-bold text-dark mb-1">{{ $booking->field?->field_name ?? '-' }}</h6>
                        <small class="text-muted d-block"><i class="bx bx-building me-1"></i>{{ $booking->field?->branch?->branch_name ?? '-' }}</small>
                        <small class="text-info fw-semibold"><i class="bx bx-time me-1"></i>{{ $start }} - {{ $end }} WITA</small>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <div>
                            <small class="text-muted d-block" style="font-size: 11px;">Total Bayar</small>
                            <span class="fw-bold text-dark">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                        </div>
                        @if ($booking->status === 'pending')
                            <div class="d-flex gap-2">
                                <a href="{{ route('booking.payment', $booking->booking_code) }}" class="btn btn-sm btn-primary rounded-pill">Bayar</a>
                                <form action="{{ route('riwayat.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Batalkan booking ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">Batal</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bx bx-calendar-x fs-1 mb-2"></i>
                <p class="mb-0">Belum ada riwayat booking.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection