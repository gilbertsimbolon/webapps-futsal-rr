@extends('layouts.app')

@section('title', 'Monitoring Antrean Round Robin | bkngftsl.')

@section('content')
<!-- Header Halaman -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Monitoring Antrean Round Robin</h4>
        <p class="text-muted mb-0 small">Pantau distribusi Time Quantum (q = 15 Menit) dan status pergiliran antrean pemesanan lapangan.</p>
    </div>
    <a href="{{ route('round-robin.simulation') }}" class="btn btn-outline-primary">
        <i class="bx bx-calculator me-1"></i> Buka Simulasi Alur RR
    </a>
</div>

<!-- Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('round-robin.monitoring') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <select name="field_id" class="form-select">
                    <option value="">-- Semua Unit Lapangan --</option>
                    @foreach ($fields as $field)
                        <option value="{{ $field->id }}" {{ request('field_id') == $field->id ? 'selected' : '' }}>
                            {{ $field->field_name }} ({{ $field->branch?->branch_name }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <input type="date" name="date" class="form-control" value="{{ request('date', date('Y-m-d')) }}">
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bx bx-filter-alt me-1"></i> Filter
                </button>
                @if(request()->hasAny(['field_id', 'date']))
                    <a href="{{ route('round-robin.monitoring') }}" class="btn btn-outline-secondary" title="Reset">
                        <i class="bx bx-reset"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Monitoring -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">URUTAN</th>
                    <th>PEMESAN / PROSES</th>
                    <th>LAPANGAN & TANGGAL</th>
                    <th>SLOT WAKTU</th>
                    <th>STATUS GILIRAN</th>
                    <th>SISA TIME QUANTUM</th>
                    <th class="text-center" style="width: 120px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($queues as $q)
                    @php
                        $start = \Carbon\Carbon::parse($q->start_time)->format('H:i');
                        $end = \Carbon\Carbon::parse($q->end_time)->format('H:i');
                    @endphp
                    <tr>
                        <td class="text-center">
                            <span class="badge {{ $q->status === 'active_turn' ? 'bg-primary' : 'bg-label-secondary' }} rounded-circle p-2">
                                #{{ $q->queue_order }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $q->customer_name ?? $q->user?->name ?? 'Tamu' }}</span>
                            <br><small class="text-muted">ID: PROSES-{{ str_pad($q->id, 4, '0', STR_PAD_LEFT) }}</small>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $q->field?->field_name ?? '-' }}</span>
                            <br><small class="text-muted">{{ $q->booking_date->format('d M Y') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-label-info">{{ $start }} - {{ $end }} WITA</span>
                        </td>
                        <td>
                            @if ($q->status === 'active_turn')
                                <span class="badge bg-label-success"><i class="bx bx-play-circle me-1"></i>Active Turn (Sedang Memilih)</span>
                            @elseif ($q->status === 'waiting')
                                <span class="badge bg-label-warning"><i class="bx bx-time me-1"></i>Ready Queue (Menunggu Giliran)</span>
                            @elseif ($q->status === 'completed')
                                <span class="badge bg-label-primary"><i class="bx bx-check-double me-1"></i>Selesai / Lunas</span>
                            @else
                                <span class="badge bg-label-danger"><i class="bx bx-x me-1"></i>Preempted / Expired</span>
                            @endif
                        </td>
                        <td>
                            @if ($q->status === 'active_turn' && $q->quantum_end)
                                <span class="fw-bold text-danger">{{ \Carbon\Carbon::now()->diffInMinutes($q->quantum_end, false) }} Menit Tersisa</span>
                                <br><small class="text-muted">Batas: {{ $q->quantum_end->format('H:i:s') }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($q->status === 'active_turn')
                                <form action="{{ route('round-robin.rotate', $q->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Paksa Rotasi / Preemption">
                                        <i class="bx bx-sync me-1"></i> Rotasi
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="avatar avatar-md bg-label-secondary mb-2 rounded-circle mx-auto d-flex align-items-center justify-content-center">
                                <i class="bx bx-sync fs-3 text-secondary"></i>
                            </div>
                            <h6 class="text-secondary mb-1">Tidak ada antrean aktif pada tanggal yang dipilih.</h6>
                            <p class="text-muted small mb-0">Semua slot jam berjalan normal tanpa terjadi bentrok antrean.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($queues->hasPages())
        <div class="card-footer d-flex justify-content-end pb-0 border-top bg-white">
            {{ $queues->links() }}
        </div>
    @endif
</div>
@endsection
