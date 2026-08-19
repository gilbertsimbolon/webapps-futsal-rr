@extends('admin.layouts.app')

@section('title', 'Detail Cabang | bkngftsl.')

@section('content')

    <!-- HEADER -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-3 mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Detail Cabang
            </h4>

            <p class="text-muted mb-0 small">
                Informasi lengkap mengenai cabang dan lapangan futsal.
            </p>

        </div>

    </div>


    <!-- INFORMASI CABANG -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body p-3 p-md-4">

            <div class="row g-4 align-items-center">

                <!-- GAMBAR CABANG -->
                <div class="col-12 col-lg-5">

                    <div class="position-relative">

                        <img src="{{ asset('img/lapangan.webp') }}"
                            alt="Golden Futsal Center"
                            class="img-fluid rounded w-100"
                            style="height: 280px; object-fit: cover;">

                        <span
                            class="position-absolute top-0 end-0 m-2 m-md-3 badge bg-primary px-2 px-md-3 py-2 d-inline-flex align-items-center gap-1">

                            <i class="bx bx-football"></i>

                            <span>3 Lapangan</span>

                        </span>

                    </div>

                </div>


                <!-- INFORMASI -->
                <div class="col-12 col-lg-7">

                    <h4 class="fw-bold mb-4">
                        Golden Futsal Center
                    </h4>


                    <!-- ALAMAT -->
                    <div class="d-flex align-items-start mb-3">

                        <div class="me-3 flex-shrink-0">

                            <span class="avatar avatar-sm bg-label-danger rounded">
                                <i class="bx bx-map text-danger"></i>
                            </span>

                        </div>

                        <div class="min-w-0">

                            <small class="text-muted d-block mb-1">
                                Alamat
                            </small>

                            <span class="fw-medium text-break">
                                Jl. Sudirman No. 45, Kebayoran Baru,
                                Jakarta Selatan
                            </span>

                        </div>

                    </div>


                    <!-- TELEPON -->
                    <div class="d-flex align-items-start mb-3">

                        <div class="me-3 flex-shrink-0">

                            <span class="avatar avatar-sm bg-label-success rounded">
                                <i class="bx bx-phone text-success"></i>
                            </span>

                        </div>

                        <div class="min-w-0">

                            <small class="text-muted d-block mb-1">
                                Nomor Telepon
                            </small>

                            <span class="fw-medium text-break">
                                0812-3456-7890
                            </span>

                        </div>

                    </div>


                    <!-- PEMILIK -->
                    <div class="d-flex align-items-start mb-3">

                        <div class="me-3 flex-shrink-0">

                            <span class="avatar avatar-sm bg-label-primary rounded">
                                <i class="bx bx-user text-primary"></i>
                            </span>

                        </div>

                        <div class="min-w-0">

                            <small class="text-muted d-block mb-1">
                                Pemilik / Pengelola
                            </small>

                            <span class="fw-medium text-break">
                                Budi Santoso
                            </span>

                        </div>

                    </div>


                    <!-- STATUS -->
                    <div class="d-flex align-items-start">

                        <div class="me-3 flex-shrink-0">

                            <span class="avatar avatar-sm bg-label-success rounded">
                                <i class="bx bx-check-circle text-success"></i>
                            </span>

                        </div>

                        <div>

                            <small class="text-muted d-block mb-1">
                                Status Cabang
                            </small>

                            <span class="badge bg-success">
                                Aktif
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- HEADER LAPANGAN -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start gap-2 mb-3">

        <div>

            <h5 class="fw-bold mb-1">
                Daftar Lapangan
            </h5>

            <p class="text-muted small mb-0">
                Lapangan yang tersedia pada cabang ini.
            </p>

        </div>

    </div>


    <!-- GRID LAPANGAN -->
    <div class="row g-3 g-md-4">


        <!-- ===================================================== -->
        <!-- LAPANGAN 1 -->
        <!-- ===================================================== -->

        <div class="col-12 col-sm-6 col-xl-4">

            <div class="card h-100 shadow-sm border-0 overflow-hidden">

                <div class="position-relative">

                    <img src="{{ asset('img/lapangan.webp') }}"
                        alt="Lapangan 1"
                        class="card-img-top w-100"
                        style="height: 180px; object-fit: cover;">

                    <span
                        class="position-absolute top-0 end-0 m-2 m-md-3 badge bg-success px-2 px-md-3 py-2">

                        Aktif

                    </span>

                </div>


                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Lapangan 1
                    </h5>


                    <div class="d-flex align-items-center mb-2">

                        <i class="bx bx-football text-primary me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            Rumput Sintetis
                        </span>

                    </div>


                    <div class="d-flex align-items-center mb-2">

                        <i class="bx bx-time text-warning me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            08:00 - 23:00
                        </span>

                    </div>


                    <div class="d-flex align-items-center">

                        <i class="bx bx-money text-success me-2 flex-shrink-0"></i>

                        <span class="fw-semibold text-break">
                            Rp150.000 / jam
                        </span>

                    </div>

                </div>


                <div class="card-footer bg-transparent border-top px-3 px-md-4 py-3">

                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">

                        <span class="text-muted small">
                            Lapangan tersedia
                        </span>

                        <button type="button"
                            class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalLapangan1">

                            Detail

                            <i class="bx bx-right-arrow-alt ms-1"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- LAPANGAN 2 -->
        <!-- ===================================================== -->

        <div class="col-12 col-sm-6 col-xl-4">

            <div class="card h-100 shadow-sm border-0 overflow-hidden">

                <div class="position-relative">

                    <img src="{{ asset('img/lapangan.webp') }}"
                        alt="Lapangan 2"
                        class="card-img-top w-100"
                        style="height: 180px; object-fit: cover;">

                    <span
                        class="position-absolute top-0 end-0 m-2 m-md-3 badge bg-success px-2 px-md-3 py-2">

                        Aktif

                    </span>

                </div>


                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Lapangan 2
                    </h5>


                    <div class="d-flex align-items-center mb-2">

                        <i class="bx bx-football text-primary me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            Vinyl
                        </span>

                    </div>


                    <div class="d-flex align-items-center mb-2">

                        <i class="bx bx-time text-warning me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            08:00 - 23:00
                        </span>

                    </div>


                    <div class="d-flex align-items-center">

                        <i class="bx bx-money text-success me-2 flex-shrink-0"></i>

                        <span class="fw-semibold text-break">
                            Rp175.000 / jam
                        </span>

                    </div>

                </div>


                <div class="card-footer bg-transparent border-top px-3 px-md-4 py-3">

                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">

                        <span class="text-muted small">
                            Lapangan tersedia
                        </span>

                        <button type="button"
                            class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalLapangan2">

                            Detail

                            <i class="bx bx-right-arrow-alt ms-1"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- LAPANGAN 3 -->
        <!-- ===================================================== -->

        <div class="col-12 col-sm-6 col-xl-4">

            <div class="card h-100 shadow-sm border-0 overflow-hidden">

                <div class="position-relative">

                    <img src="{{ asset('img/lapangan.webp') }}"
                        alt="Lapangan 3"
                        class="card-img-top w-100"
                        style="height: 180px; object-fit: cover;">

                    <span
                        class="position-absolute top-0 end-0 m-2 m-md-3 badge bg-secondary px-2 px-md-3 py-2">

                        Tidak Aktif

                    </span>

                </div>


                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Lapangan 3
                    </h5>


                    <div class="d-flex align-items-center mb-2">

                        <i class="bx bx-football text-primary me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            Rumput Sintetis
                        </span>

                    </div>


                    <div class="d-flex align-items-center mb-2">

                        <i class="bx bx-time text-warning me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            08:00 - 23:00
                        </span>

                    </div>


                    <div class="d-flex align-items-center">

                        <i class="bx bx-money text-success me-2 flex-shrink-0"></i>

                        <span class="fw-semibold text-break">
                            Rp150.000 / jam
                        </span>

                    </div>

                </div>


                <div class="card-footer bg-transparent border-top px-3 px-md-4 py-3">

                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">

                        <span class="text-muted small">
                            Sedang tidak tersedia
                        </span>

                        <button type="button"
                            class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modalLapangan3">

                            Detail

                            <i class="bx bx-right-arrow-alt ms-1"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- MODAL LAPANGAN 1 -->
    <!-- ========================================================= -->

    <div class="modal fade"
        id="modalLapangan1"
        tabindex="-1"
        aria-labelledby="modalLapangan1Label"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content border-0 shadow">

                <!-- HEADER -->
                <div class="modal-header">

                    <h5 class="modal-title fw-bold"
                        id="modalLapangan1Label">

                        Detail Lapangan 1

                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                <!-- BODY -->
                <div class="modal-body">

                    <div class="row g-4">

                        <!-- GAMBAR -->
                        <div class="col-12 col-md-5">

                            <img src="{{ asset('img/lapangan.webp') }}"
                                alt="Lapangan 1"
                                class="img-fluid rounded w-100"
                                style="height: 240px; object-fit: cover;">

                        </div>


                        <!-- INFORMASI -->
                        <div class="col-12 col-md-7">

                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">

                                <h4 class="fw-bold mb-0">
                                    Lapangan 1
                                </h4>

                                <span class="badge bg-success">
                                    Aktif
                                </span>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Jenis Lapangan
                                </small>

                                <span class="fw-medium">
                                    Rumput Sintetis
                                </span>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Harga Sewa
                                </small>

                                <span class="fw-bold text-primary">
                                    Rp150.000 / jam
                                </span>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Jam Operasional
                                </small>

                                <span class="fw-medium">
                                    08:00 - 23:00
                                </span>

                            </div>


                            <div>

                                <small class="text-muted d-block">
                                    Deskripsi
                                </small>

                                <span class="text-muted small">
                                    Lapangan futsal dengan rumput sintetis
                                    berkualitas dan cocok untuk pertandingan
                                    maupun latihan.
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- FOOTER -->
                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Tutup

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- MODAL LAPANGAN 2 -->
    <!-- ========================================================= -->

    <div class="modal fade"
        id="modalLapangan2"
        tabindex="-1"
        aria-labelledby="modalLapangan2Label"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <h5 class="modal-title fw-bold"
                        id="modalLapangan2Label">

                        Detail Lapangan 2

                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row g-4">

                        <div class="col-12 col-md-5">

                            <img src="{{ asset('img/lapangan.webp') }}"
                                alt="Lapangan 2"
                                class="img-fluid rounded w-100"
                                style="height: 240px; object-fit: cover;">

                        </div>


                        <div class="col-12 col-md-7">

                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">

                                <h4 class="fw-bold mb-0">
                                    Lapangan 2
                                </h4>

                                <span class="badge bg-success">
                                    Aktif
                                </span>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Jenis Lapangan
                                </small>

                                <span class="fw-medium">
                                    Vinyl
                                </span>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Harga Sewa
                                </small>

                                <span class="fw-bold text-primary">
                                    Rp175.000 / jam
                                </span>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Jam Operasional
                                </small>

                                <span class="fw-medium">
                                    08:00 - 23:00
                                </span>

                            </div>


                            <div>

                                <small class="text-muted d-block">
                                    Deskripsi
                                </small>

                                <span class="text-muted small">
                                    Lapangan dengan permukaan vinyl
                                    yang nyaman digunakan untuk bermain
                                    futsal.
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Tutup

                    </button>

                </div>

            </div>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- MODAL LAPANGAN 3 -->
    <!-- ========================================================= -->

    <div class="modal fade"
        id="modalLapangan3"
        tabindex="-1"
        aria-labelledby="modalLapangan3Label"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content border-0 shadow">

                <div class="modal-header">

                    <h5 class="modal-title fw-bold"
                        id="modalLapangan3Label">

                        Detail Lapangan 3

                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row g-4">

                        <div class="col-12 col-md-5">

                            <img src="{{ asset('img/lapangan.webp') }}"
                                alt="Lapangan 3"
                                class="img-fluid rounded w-100"
                                style="height: 240px; object-fit: cover;">

                        </div>


                        <div class="col-12 col-md-7">

                            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">

                                <h4 class="fw-bold mb-0">
                                    Lapangan 3
                                </h4>

                                <span class="badge bg-secondary">
                                    Tidak Aktif
                                </span>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Jenis Lapangan
                                </small>

                                <span class="fw-medium">
                                    Rumput Sintetis
                                </span>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Harga Sewa
                                </small>

                                <span class="fw-bold text-primary">
                                    Rp150.000 / jam
                                </span>

                            </div>


                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Jam Operasional
                                </small>

                                <span class="fw-medium">
                                    08:00 - 23:00
                                </span>

                            </div>


                            <div>

                                <small class="text-muted d-block">
                                    Deskripsi
                                </small>

                                <span class="text-muted small">
                                    Lapangan sedang tidak tersedia
                                    untuk digunakan.
                                </span>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Tutup

                    </button>

                </div>

            </div>

        </div>

    </div>

@endsection