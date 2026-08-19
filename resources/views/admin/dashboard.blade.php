@extends('admin.layouts.app')

@section('title', 'Dashboard | bkngftsl.')

@section('content')
    <div class="col-12 w-full">
        <div class="row">
            <!-- Kartu Total Pemilik Lapangan -->
            <div class="col-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="bx bx-user fs-3"></i>
                                </span>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-icon text-muted" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded fs-4"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.pemilik.index') }}">
                                            <i class="bx bx-show me-2"></i>
                                            Lihat Detail
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <span class="text-muted small fw-medium">
                                TOTAL PEMILIK LAPANGAN
                            </span>

                            <div class="d-flex align-items-end justify-content-between mt-2">
                                <h2 class="mb-0 fw-bold">
                                    {{ $owners }} Orang.
                                </h2>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Kartu Total Pengguna Sistem -->
            <div class="col-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="bx bx-user fs-3"></i>
                                </span>
                            </div>

                        </div>

                        <div>
                            <span class="text-muted small fw-medium">
                                TOTAL PENGGUNA SISTEM
                            </span>

                            <div class="d-flex align-items-end justify-content-between mt-2">
                                <h2 class="mb-0 fw-bold">
                                    {{ $users }} Orang.
                                </h2>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Kartu Total Penyewa Sistem -->
            <div class="col-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="bx bx-user fs-3"></i>
                                </span>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-sm btn-icon text-muted" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded fs-4"></i>
                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{route('admin.pelanggan.index')}}">
                                            <i class="bx bx-show me-2"></i>
                                            Lihat Detail
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <span class="text-muted small fw-medium">
                                TOTAL PELANGGAN
                            </span>

                            <div class="d-flex align-items-end justify-content-between mt-2">
                                <h2 class="mb-0 fw-bold">
                                    {{ $tenants }} Orang.
                                </h2>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 mb-6 profile-report">
                <div class="card h-100">
                    <div class="card-body">
                        <div
                            class="d-flex justify-content-between align-items-center flex-sm-row flex-column gap-10 flex-wrap">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                <div class="card-title mb-6">
                                    <h5 class="text-nowrap mb-1">Profile Report</h5>
                                    <span class="badge bg-label-warning">YEAR 2022</span>
                                </div>
                                <div class="mt-sm-auto">
                                    <span class="text-success text-nowrap fw-medium"><i
                                            class="icon-base bx bx-up-arrow-alt"></i> 68.2%</span>
                                    <h4 class="mb-0">$84,686k</h4>
                                </div>
                            </div>
                            <div id="profileReportChart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
