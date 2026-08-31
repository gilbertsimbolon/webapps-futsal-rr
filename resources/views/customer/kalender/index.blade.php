@extends('layouts.app')

@section('title', 'Kalender Ketersediaan | bkngftsl.')

@section('content')
<div class="customer-container mobile-content-wrapper">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <span class="badge bg-label-info mb-1">Cek Jadwal Kosong</span>
            <h4 class="fw-bold mb-0">Jadwal & Ketersediaan</h4>
        </div>
        <i class="bx bx-calendar fs-3 text-primary"></i>
    </div>

    <!-- Pilih Tanggal Cepat -->
    <div class="card border-0 shadow-sm p-3 mb-3 rounded-3">
        <form action="{{ route('kalender.index') }}" method="GET" class="d-flex align-items-center gap-2">
            <label class="form-label small fw-semibold mb-0 flex-shrink-0">Pilih Tanggal:</label>
            <input type="date" name="date" class="form-control form-control-sm" value="{{ $selectedDate }}" onchange="this.form.submit()">
        </form>
    </div>

    <!-- Daftar Slot yang Terisi / Booked -->
    <h6 class="fw-bold text-muted mb-2 small text-uppercase">Slot Terisi ({{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }})</h6>

    <div class="row g-2">
        @forelse ($bookings as $b)
            @php
                $start = $b->start_time ? \Carbon\Carbon::parse($b->start_time)->format('H:i') : '-';
                $end   = $b->end_time ? \Carbon\Carbon::parse($b->end_time)->format('H:i') : '-';
            @endphp
            <div class="col-12">
                <div class="card border-0 shadow-sm p-3 rounded-3 border-start border-danger border-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-label-danger fw-bold mb-1">{{ $start }} - {{ $end }} WITA</span>
                            <h6 class="fw-bold text-dark mb-0">{{ $b->field?->field_name }}</h6>
                            <small class="text-muted">{{ $b->field?->branch?->branch_name }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-label-secondary">Terboking</span>
                            <small class="d-block text-muted mt-1" style="font-size: 11px;">{{ $b->user?->name ?? 'Tamu' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4 bg-white rounded-3 shadow-sm text-muted">
                <i class="bx bx-check-circle text-success fs-1 mb-2"></i>
                <p class="mb-0 fw-semibold text-dark">Seluruh Slot Lapangan Tersedia!</p>
                <small class="text-muted">Belum ada tim yang memesan di tanggal ini.</small>
            </div>
        @endforelse
    </div>
</div>
@endsection