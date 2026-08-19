@extends('admin.layouts.app')

@section('title', 'Data Lapangan | bkngftsl.')

@section('content')

    <!-- HEADER -->
    <div class="mb-4">

        <h4 class="fw-bold mb-1">
            Data Lapangan
        </h4>

        <p class="text-muted small mb-0">
            Lihat informasi lapangan futsal yang terdaftar.
        </p>

    </div>


    <!-- GRID LAPANGAN -->
    <div class="row g-3 g-md-4">


        <!-- LAPANGAN 1 -->
        <div class="col-12 col-sm-6 col-xl-4">

            <div class="card h-100 shadow-sm border-0 overflow-hidden">

                <!-- GAMBAR -->
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


                <!-- CONTENT -->
                <div class="card-body">

                    <h5 class="fw-bold mb-3">
                        Lapangan 1
                    </h5>


                    <!-- CABANG -->
                    <div class="d-flex align-items-center mb-2">

                        <i class="bx bx-buildings text-primary me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            Golden Futsal Center
                        </span>

                    </div>


                    <!-- JENIS -->
                    <div class="d-flex align-items-center mb-2">

                        <i class="bx bx-football text-primary me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            Rumput Sintetis
                        </span>

                    </div>


                    <!-- JAM -->
                    <div class="d-flex align-items-center mb-2">

                        <i class="bx bx-time text-warning me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            08:00 - 23:00
                        </span>

                    </div>


                    <!-- HARGA -->
                    <div class="d-flex align-items-center">

                        <i class="bx bx-money text-success me-2 flex-shrink-0"></i>

                        <span class="fw-semibold text-break">
                            Rp150.000 / jam
                        </span>

                    </div>

                </div>


                <!-- FOOTER -->
                <div class="card-footer bg-transparent border-top px-3 px-md-4 py-3">

                    <div class="d-flex justify-content-between align-items-center gap-2">

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


        <!-- LAPANGAN 2 -->
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

                        <i class="bx bx-buildings text-primary me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            Golden Futsal Center
                        </span>

                    </div>


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

                    <div class="d-flex justify-content-between align-items-center gap-2">

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


        <!-- LAPANGAN 3 -->
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

                        <i class="bx bx-buildings text-primary me-2 flex-shrink-0"></i>

                        <span class="text-muted small">
                            Golden Futsal Center
                        </span>

                    </div>


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

                    <div class="d-flex justify-content-between align-items-center gap-2">

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

                        Detail Lapangan

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

                            <div class="d-flex justify-content-between align-items-start gap-2 mb-4">

                                <h4 class="fw-bold mb-0">
                                    Lapangan 1
                                </h4>

                                <span class="badge bg-success">
                                    Aktif
                                </span>

                            </div>


                            <!-- CABANG -->
                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-buildings text-primary fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Nama Cabang
                                    </small>

                                    <span class="fw-medium">
                                        Golden Futsal Center
                                    </span>

                                </div>

                            </div>


                            <!-- PEMILIK -->
                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-user text-primary fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Nama Pemilik
                                    </small>

                                    <span class="fw-medium">
                                        Budi Santoso
                                    </span>

                                </div>

                            </div>


                            <!-- JENIS -->
                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-football text-primary fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Jenis Lapangan
                                    </small>

                                    <span class="fw-medium">
                                        Rumput Sintetis
                                    </span>

                                </div>

                            </div>


                            <!-- HARGA -->
                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-money text-success fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Harga Sewa
                                    </small>

                                    <span class="fw-bold text-primary">
                                        Rp150.000 / jam
                                    </span>

                                </div>

                            </div>


                            <!-- JAM -->
                            <div class="d-flex align-items-start">

                                <i class="bx bx-time text-warning fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Jam Operasional
                                    </small>

                                    <span class="fw-medium">
                                        08:00 - 23:00
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- DESKRIPSI -->
                    <div class="border-top mt-4 pt-4">

                        <h6 class="fw-bold mb-2">
                            Deskripsi
                        </h6>

                        <p class="text-muted small mb-0">
                            Lapangan futsal dengan rumput sintetis
                            berkualitas yang cocok digunakan untuk
                            pertandingan maupun latihan.
                        </p>

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

                        Detail Lapangan

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

                            <div class="d-flex justify-content-between align-items-start gap-2 mb-4">

                                <h4 class="fw-bold mb-0">
                                    Lapangan 2
                                </h4>

                                <span class="badge bg-success">
                                    Aktif
                                </span>

                            </div>


                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-buildings text-primary fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Nama Cabang
                                    </small>

                                    <span class="fw-medium">
                                        Golden Futsal Center
                                    </span>

                                </div>

                            </div>


                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-user text-primary fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Nama Pemilik
                                    </small>

                                    <span class="fw-medium">
                                        Budi Santoso
                                    </span>

                                </div>

                            </div>


                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-football text-primary fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Jenis Lapangan
                                    </small>

                                    <span class="fw-medium">
                                        Vinyl
                                    </span>

                                </div>

                            </div>


                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-money text-success fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Harga Sewa
                                    </small>

                                    <span class="fw-bold text-primary">
                                        Rp175.000 / jam
                                    </span>

                                </div>

                            </div>


                            <div class="d-flex align-items-start">

                                <i class="bx bx-time text-warning fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Jam Operasional
                                    </small>

                                    <span class="fw-medium">
                                        08:00 - 23:00
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="border-top mt-4 pt-4">

                        <h6 class="fw-bold mb-2">
                            Deskripsi
                        </h6>

                        <p class="text-muted small mb-0">
                            Lapangan dengan permukaan vinyl yang
                            nyaman digunakan untuk bermain futsal.
                        </p>

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

                        Detail Lapangan

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

                            <div class="d-flex justify-content-between align-items-start gap-2 mb-4">

                                <h4 class="fw-bold mb-0">
                                    Lapangan 3
                                </h4>

                                <span class="badge bg-secondary">
                                    Tidak Aktif
                                </span>

                            </div>


                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-buildings text-primary fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Nama Cabang
                                    </small>

                                    <span class="fw-medium">
                                        Golden Futsal Center
                                    </span>

                                </div>

                            </div>


                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-user text-primary fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Nama Pemilik
                                    </small>

                                    <span class="fw-medium">
                                        Budi Santoso
                                    </span>

                                </div>

                            </div>


                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-football text-primary fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Jenis Lapangan
                                    </small>

                                    <span class="fw-medium">
                                        Rumput Sintetis
                                    </span>

                                </div>

                            </div>


                            <div class="d-flex align-items-start mb-3">

                                <i class="bx bx-money text-success fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Harga Sewa
                                    </small>

                                    <span class="fw-bold text-primary">
                                        Rp150.000 / jam
                                    </span>

                                </div>

                            </div>


                            <div class="d-flex align-items-start">

                                <i class="bx bx-time text-warning fs-5 me-3"></i>

                                <div>

                                    <small class="text-muted d-block">
                                        Jam Operasional
                                    </small>

                                    <span class="fw-medium">
                                        08:00 - 23:00
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="border-top mt-4 pt-4">

                        <h6 class="fw-bold mb-2">
                            Deskripsi
                        </h6>

                        <p class="text-muted small mb-0">
                            Lapangan sedang tidak tersedia untuk
                            digunakan.
                        </p>

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