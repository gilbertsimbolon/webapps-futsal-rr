@extends('layouts.app')

@section('title', 'Cari & Sewa Lapangan | bkngftsl.')

@section('content')
<div class="customer-container mobile-content-wrapper">
    <!-- Header Ringkas -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <span class="badge bg-label-primary mb-1">Pilih Lapangan</span>
            <h4 class="fw-bold mb-0">Temukan Lapangan</h4>
        </div>
        <div class="avatar avatar-sm bg-label-primary rounded-circle d-flex align-items-center justify-content-center">
            <i class="bx bx-football fs-4"></i>
        </div>
    </div>

    <!-- Filter Cabang Horizontal -->
    <div class="card border-0 shadow-sm p-3 mb-3 rounded-3">
        <form action="{{ route('sewa.index') }}" method="GET">
            <div class="row g-2">
                <div class="col-8">
                    <select name="branch_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Semua Lokasi Venue</option>
                        @foreach ($branches as $b)
                            <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <select name="field_type" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">Tipe Rumput</option>
                        <option value="vinyl" {{ request('field_type') == 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                        <option value="sintetis" {{ request('field_type') == 'sintetis' ? 'selected' : '' }}>Sintetis</option>
                        <option value="interlock" {{ request('field_type') == 'interlock' ? 'selected' : '' }}>Interlock</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- List Lapangan Mobile Card -->
    <div class="row g-3">
        @forelse ($fields as $field)
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-label-info text-uppercase mb-1" style="font-size: 10px;">{{ $field->field_type ?? 'Futsal' }}</span>
                                <h5 class="fw-bold text-dark mb-0">{{ $field->field_name }}</h5>
                                <small class="text-muted"><i class="bx bx-map-pin me-1"></i>{{ $field->branch?->branch_name }}</small>
                            </div>
                            <span class="badge bg-label-success">Tersedia</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                            <div>
                                <small class="text-muted d-block" style="font-size: 11px;">Tarif / Jam</small>
                                <span class="fw-bold text-primary fs-6">Rp {{ number_format($field->price_per_hour ?? 100000, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('sewa.create', $field->id) }}" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill">
                                Booking Sekarang <i class="bx bx-right-arrow-alt ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="bx bx-layer-x fs-1 mb-2"></i>
                <p class="mb-0">Tidak ada lapangan yang cocok dengan filter yang dipilih.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection