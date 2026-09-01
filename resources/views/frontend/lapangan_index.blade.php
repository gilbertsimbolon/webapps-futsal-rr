@extends('layouts.frontend')

@section('title', 'Daftar Lapangan Futsal | bkngftsl.')

@section('content')
<div class="py-4 py-lg-5">
    <div class="container">
        <!-- Breadcrumb & Header -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('landing') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daftar Lapangan</li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <div>
                <h2 class="fw-extrabold text-dark mb-1">Daftar Lapangan Futsal</h2>
                <p class="text-muted mb-0">Temukan arena futsal terbaik di berbagai cabang venue pilihan.</p>
            </div>
            <span class="badge bg-label-primary px-3 py-2 rounded-pill fw-semibold">
                {{ $fields->total() }} Lapangan Tersedia
            </span>
        </div>

        <!-- Filter Box -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3 p-lg-4">
                <form action="{{ route('lapangan.index') }}" method="GET">
                    <div class="row g-2 align-items-end">
                        <!-- Pencarian Nama / Cabang -->
                        <div class="col-12 col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Pencarian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="bx bx-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Nama lapangan / lokasi" value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Cabang Venue -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Cabang Venue</label>
                            <select name="branch_id" class="form-select">
                                <option value="">Semua Cabang</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jenis Lapangan -->
                        <div class="col-12 col-sm-6 col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Jenis Lantai</label>
                            <select name="field_type" class="form-select">
                                <option value="">Semua Jenis</option>
                                @foreach ($fieldTypes as $type)
                                    <option value="{{ $type }}" {{ request('field_type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Urutan -->
                        <div class="col-12 col-sm-6 col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Urutkan</label>
                            <select name="sort" class="form-select">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Tarif Termurah</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Tarif Tertinggi</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A - Z</option>
                            </select>
                        </div>

                        <!-- Tombol Filter -->
                        <div class="col-12 col-sm-6 col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 fw-semibold">
                                <i class="bx bx-filter-alt me-1"></i> Terapkan
                            </button>
                            <a href="{{ route('lapangan.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="bx bx-reset"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Grid Lapangan -->
        <div class="row g-4 mb-4">
            @forelse ($fields as $field)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm field-card bg-white">
                        <div class="position-relative">
                            @if ($field->image)
                                <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->field_name }}" class="field-card-img">
                            @else
                                <div class="field-card-img bg-light d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="bx bx-football fs-1 mb-1 text-primary"></i>
                                    <span class="small fw-semibold">bkngftsl. Arena</span>
                                </div>
                            @endif
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-dark bg-opacity-75 text-uppercase px-2 py-1 rounded-pill" style="font-size: 11px;">
                                    {{ $field->field_type ?? 'Futsal' }}
                                </span>
                            </div>
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 11px;">
                                    <i class="bx bx-check-circle me-1"></i>Tersedia
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <div class="mb-2">
                                <h5 class="fw-bold text-dark mb-1 text-truncate">{{ $field->field_name }}</h5>
                                <small class="text-muted d-flex align-items-center gap-1">
                                    <i class="bx bx-map-pin text-danger"></i> {{ $field->branch?->branch_name ?? 'Venue Futsal' }}
                                </small>
                            </div>

                            <div class="d-flex flex-wrap gap-2 my-3 py-2 border-top border-bottom">
                                <span class="badge bg-light text-muted fw-normal"><i class="bx bx-check me-1 text-success"></i>Indoor</span>
                                <span class="badge bg-light text-muted fw-normal"><i class="bx bx-check me-1 text-success"></i>Lampu LED</span>
                                <span class="badge bg-light text-muted fw-normal"><i class="bx bx-check me-1 text-success"></i>Toilet</span>
                                <span class="badge bg-light text-muted fw-normal"><i class="bx bx-check me-1 text-success"></i>Parkir</span>
                            </div>

                            <div class="mt-auto pt-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 11px;">Tarif Sewa</small>
                                    <span class="fw-bold text-primary fs-5">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                                    <small class="text-muted">/ jam</small>
                                </div>
                            </div>

                            <div class="row g-2 mt-3">
                                <div class="col-6">
                                    <a href="{{ route('lapangan.detail', $field->id) }}" class="btn btn-outline-secondary btn-sm w-100 py-2 fw-semibold rounded-pill">
                                        Lihat Detail
                                    </a>
                                </div>
                                <div class="col-6">
                                    @guest
                                        <button type="button" class="btn btn-primary btn-sm w-100 py-2 fw-semibold rounded-pill shadow-sm" onclick="requireLogin('{{ route('pelanggan.booking.create', $field->id) }}')">
                                            Booking
                                        </button>
                                    @else
                                        <a href="{{ route('pelanggan.booking.create', $field->id) }}" class="btn btn-primary btn-sm w-100 py-2 fw-semibold rounded-pill shadow-sm">
                                            Booking
                                        </a>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <div class="avatar avatar-xl bg-label-secondary mx-auto mb-3 rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bx bx-search-alt fs-1 text-secondary"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Lapangan Tidak Ditemukan</h5>
                    <p class="mb-3">Tidak ada unit lapangan yang sesuai dengan kriteria filter pencarian Anda.</p>
                    <a href="{{ route('lapangan.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                        Reset Filter
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $fields->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

