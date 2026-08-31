@extends('layouts.app')

@section('title', 'Dashboard Pemilik | bkngftsl.')

@section('content')

<!-- Header Dashboard -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Pemilik</h4>
        <p class="text-muted mb-0">
            Selamat datang, {{ auth()->user()->name }}. Berikut ringkasan bisnis Anda.
        </p>
    </div>

    <div>
        <span class="badge bg-label-primary px-3 py-2">
            <i class="bx bx-user me-1"></i>
            Pemilik
        </span>
    </div>
</div>

<!-- Statistik Utama -->
<div class="row g-4 mb-4">

    <!-- Total Cabang -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-buildings fs-4"></i>
                        </span>
                    </div>

                    <span class="text-muted small">
                        Cabang
                    </span>
                </div>

                <h3 class="mb-1 fw-bold">
                    {{ $totalBranches }}
                </h3>

                <p class="mb-0 text-muted small">
                    Total cabang Anda
                </p>
            </div>
        </div>
    </div>

    <!-- Total Lapangan -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-football fs-4"></i>
                        </span>
                    </div>

                    <span class="text-muted small">
                        Lapangan
                    </span>
                </div>

                <h3 class="mb-1 fw-bold">
                    {{ $totalFields }}
                </h3>

                <p class="mb-0 text-muted small">
                    Total lapangan
                </p>
            </div>
        </div>
    </div>

    <!-- Booking Hari Ini -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-calendar-check fs-4"></i>
                        </span>
                    </div>

                    <span class="text-muted small">
                        Hari Ini
                    </span>
                </div>

                <h3 class="mb-1 fw-bold">
                    {{ $todayBookings }}
                </h3>

                <p class="mb-0 text-muted small">
                    Booking hari ini
                </p>
            </div>
        </div>
    </div>

    <!-- Pendapatan Bulan Ini -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-wallet fs-4"></i>
                        </span>
                    </div>

                    <span class="text-muted small">
                        Bulan Ini
                    </span>
                </div>

                <h5 class="mb-1 fw-bold">
                    Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                </h5>

                <p class="mb-0 text-muted small">
                    Pendapatan bulan ini
                </p>
            </div>
        </div>
    </div>

</div>

<!-- Konten Dashboard -->
<div class="row g-4">

    <!-- Booking Terbaru -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold">Booking Terbaru</h5>
                    <p class="text-muted small mb-0">
                        Daftar booking terbaru dari cabang Anda.
                    </p>
                </div>

                <a href="{{ route('pemilik.bookings.index') }}"
                    class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Pelanggan</th>
                            <th>Cabang</th>
                            <th>Lapangan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recentBookings as $booking)
                            <tr>

                                <!-- Pelanggan -->
                                <td>
                                    <div class="d-flex align-items-center">

                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($booking->user->name ?? 'P', 0, 1)) }}
                                            </span>
                                        </div>

                                        <div>
                                            <span class="fw-semibold">
                                                {{ $booking->user->name ?? '-' }}
                                            </span>

                                            @if ($booking->user?->phone)
                                                <small class="text-muted d-block">
                                                    {{ $booking->user->phone }}
                                                </small>
                                            @endif
                                        </div>

                                    </div>
                                </td>

                                <!-- Cabang -->
                                <td>
                                    <span class="small">
                                        {{ $booking->field->branch->branch_name ?? '-' }}
                                    </span>
                                </td>

                                <!-- Lapangan -->
                                <td>
                                    <span class="fw-semibold small">
                                        {{ $booking->field->name ?? '-' }}
                                    </span>
                                </td>

                                <!-- Tanggal -->
                                <td>
                                    <span class="small">
                                        @if ($booking->booking_date)
                                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </td>

                                <!-- Status -->
                                <td>
                                    @if ($booking->status === 'confirmed')
                                        <span class="badge bg-label-success">
                                            Dikonfirmasi
                                        </span>
                                    @elseif ($booking->status === 'pending')
                                        <span class="badge bg-label-warning">
                                            Menunggu
                                        </span>
                                    @elseif ($booking->status === 'cancelled')
                                        <span class="badge bg-label-danger">
                                            Dibatalkan
                                        </span>
                                    @else
                                        <span class="badge bg-label-secondary">
                                            {{ ucfirst($booking->status ?? '-') }}
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center py-5">

                                    <div class="d-flex flex-column align-items-center">

                                        <div class="avatar avatar-md bg-label-secondary rounded-circle d-flex align-items-center justify-content-center mb-2">
                                            <i class="bx bx-calendar-x fs-3"></i>
                                        </div>

                                        <h6 class="mb-1">
                                            Belum ada booking
                                        </h6>

                                        <p class="text-muted small mb-0">
                                            Belum terdapat booking terbaru pada cabang Anda.
                                        </p>

                                    </div>

                                </td>
                            </tr>

                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <!-- Ringkasan Cabang -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">

            <div class="card-header bg-white border-bottom">
                <h5 class="mb-1 fw-bold">
                    Cabang Saya
                </h5>

                <p class="text-muted small mb-0">
                    Ringkasan cabang yang Anda miliki.
                </p>
            </div>

            <div class="card-body">

                @forelse ($branches as $branch)

                    <!-- Item Cabang -->
                    <div class="d-flex align-items-center mb-4">

                        <div class="avatar avatar-sm me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-building-house"></i>
                            </span>
                        </div>

                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-semibold">
                                {{ $branch->branch_name }}
                            </h6>

                            <small class="text-muted d-block">
                                {{ $branch->fields_count }} Lapangan
                            </small>
                        </div>

                        @if ($branch->status === 'active')
                            <span class="badge bg-label-success">
                                Aktif
                            </span>
                        @else
                            <span class="badge bg-label-secondary">
                                Nonaktif
                            </span>
                        @endif

                    </div>

                @empty

                    <!-- Tidak Ada Cabang -->
                    <div class="text-center py-4">

                        <div class="avatar avatar-md bg-label-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2">
                            <i class="bx bx-building-house fs-3"></i>
                        </div>

                        <h6 class="mb-1">
                            Belum ada cabang
                        </h6>

                        <p class="text-muted small mb-3">
                            Anda belum memiliki cabang.
                        </p>

                        <a href="{{ route('pemilik.cabang.index') }}"
                            class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i>
                            Tambah Cabang
                        </a>

                    </div>

                @endforelse

            </div>

            @if ($branches->count() > 0)

                <!-- Footer Cabang -->
                <div class="card-footer bg-white border-top">
                    <a href="{{ route('pemilik.cabang.index') }}"
                        class="btn btn-outline-primary btn-sm w-100">
                        Kelola Cabang
                    </a>
                </div>

            @endif

        </div>
    </div>

</div>

<!-- Statistik Booking -->
<div class="row g-4 mt-1">

    <!-- Booking Menunggu -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-time"></i>
                        </span>
                    </div>

                    <div>
                        <h6 class="mb-1 fw-bold">
                            {{ $pendingBookings }}
                        </h6>

                        <small class="text-muted">
                            Menunggu Konfirmasi
                        </small>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Booking Dikonfirmasi -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-check-circle"></i>
                        </span>
                    </div>

                    <div>
                        <h6 class="mb-1 fw-bold">
                            {{ $confirmedBookings }}
                        </h6>

                        <small class="text-muted">
                            Booking Dikonfirmasi
                        </small>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Booking Dibatalkan -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="bx bx-x-circle"></i>
                        </span>
                    </div>

                    <div>
                        <h6 class="mb-1 fw-bold">
                            {{ $cancelledBookings }}
                        </h6>

                        <small class="text-muted">
                            Booking Dibatalkan
                        </small>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Booking Bulan Ini -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="d-flex align-items-center">

                    <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-bar-chart-alt-2"></i>
                        </span>
                    </div>

                    <div>
                        <h6 class="mb-1 fw-bold">
                            {{ $monthlyBookings }}
                        </h6>

                        <small class="text-muted">
                            Booking Bulan Ini
                        </small>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

@endsection