@extends('admin.layouts.app')

@section('title', 'Master Data Cabang | bkngftsl.')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-1 mb-0">Master Data Cabang</h4>
            <p class="text-muted mb-0 small">
                Lihat informasi cabang dan lapangan futsal yang terdaftar.
            </p>
        </div>
    </div>

    <!-- GRID CABANG -->
    <div class="row g-4">

        <!-- Card Cabang -->
        <div class="col-md-6 col-lg-4">

            <a href="{{ url('/admin/cabang/1') }}" class="text-decoration-none text-reset">

                <div class="card h-100 shadow-sm border-0 overflow-hidden">

                    <!-- Gambar Cabang -->
                    <div class="position-relative">

                        <img src="{{ asset('img/lapangan.webp') }}" alt="Golden Futsal Center" class="card-img-top"
                            style="height: 190px; object-fit: cover;">

                        <!-- Overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100"
                            style="background: linear-gradient(to bottom, rgba(0,0,0,0.25), transparent 50%);">
                        </div>

                        <!-- Total Lapangan -->
                        <span
                            class="position-absolute top-0 end-0 m-3 badge bg-primary px-3 py-2 d-inline-flex align-items-center gap-1">
                            <i class="bx bx-football"></i>
                            <span>3 Lapangan</span>
                        </span>

                    </div>

                    <!-- Content -->
                    <div class="card-body">

                        <h5 class="card-title fw-bold mb-3">
                            Golden Futsal Center
                        </h5>

                        <div class="d-flex align-items-start mb-2">
                            <i class="bx bx-map text-danger me-2 mt-1"></i>
                            <span class="text-muted small">
                                Jl. Sudirman No. 45, Kebayoran Baru,
                                Jakarta Selatan
                            </span>
                        </div>

                        <div class="d-flex align-items-center mb-2">
                            <i class="bx bx-phone text-success me-2"></i>
                            <span class="text-muted small">
                                0812-3456-7890
                            </span>
                        </div>

                        <div class="d-flex align-items-center">
                            <i class="bx bx-user text-primary me-2"></i>
                            <span class="text-muted small">
                                Budi Santoso
                            </span>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-transparent border-top-0 pt-0 pb-3 px-4">
                        <div class="d-flex justify-content-between align-items-center">

                            <span class="text-muted small">
                                Lihat detail cabang
                            </span>

                            <span class="text-primary">
                                <i class="bx bx-right-arrow-alt fs-4"></i>
                            </span>

                        </div>
                    </div>

                </div>

            </a>

        </div>

    </div>

@endsection
