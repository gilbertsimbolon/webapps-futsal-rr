@extends('layouts.app')

@section('title', 'Dashboard Owner | bkngftsl.')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="mb-4">
            <h4 class="fw-bold mb-1">
                Selamat datang, {{ $user->name }} 👋
            </h4>

            <p class="text-muted mb-0">
                Berikut ringkasan bisnis dan aktivitas venue Anda.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-semibold d-block mb-1">
                                    Total Cabang
                                </span>

                                <h3 class="card-title mb-2">
                                    {{ $totalBranches }}
                                </h3>

                                <small class="text-muted">
                                    Cabang terdaftar
                                </small>
                            </div>

                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="bx bx-building-house fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-semibold d-block mb-1">
                                    Total Lapangan
                                </span>

                                <h3 class="card-title mb-2">
                                    {{ $totalFields }}
                                </h3>

                                <small class="text-muted">
                                    Lapangan terdaftar
                                </small>
                            </div>

                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="bx bx-layer fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-semibold d-block mb-1">
                                    Total Booking
                                </span>

                                <h3 class="card-title mb-2">
                                    {{ $totalBookings }}
                                </h3>

                                <small class="text-muted">
                                    Seluruh booking
                                </small>
                            </div>

                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="bx bx-calendar-check fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="fw-semibold d-block mb-1">
                                    Pendapatan
                                </span>

                                <h3 class="card-title mb-2">
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </h3>

                                <small class="text-muted">
                                    Booking dibayar
                                </small>
                            </div>

                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="bx bx-wallet fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4 mt-1">

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1">
                            Booking Minggu Ini
                        </span>

                        <h3 class="card-title mb-0">
                            {{ $weeklyTotalBookings }}
                        </h3>

                        <small class="text-muted">
                            Total booking dari Senin sampai hari ini
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1">
                            Pendapatan Minggu Ini
                        </span>

                        <h3 class="card-title mb-0">
                            Rp {{ number_format($weeklyTotalRevenue, 0, ',', '.') }}
                        </h3>

                        <small class="text-muted">
                            Pendapatan dari booking yang sudah dibayar
                        </small>
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4 mt-1">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-1">Statistik Booking</h5>
                        <small class="text-muted">
                            Aktivitas booking selama minggu berjalan
                        </small>
                    </div>

                    <div class="card-body">
                        <div id="bookingChart"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4 mt-1">

            <div class="col-12">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Booking Terbaru</h5>

                            <small class="text-muted">
                                Aktivitas booking terbaru di venue Anda
                            </small>
                        </div>

                        <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">

                            <thead>
                                <tr>
                                    <th>Booking</th>
                                    <th>Penyewa</th>
                                    <th>Cabang</th>
                                    <th>Lapangan</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse ($recentBookings as $booking)
                                    <tr>

                                        <td>
                                            <span class="fw-semibold">
                                                {{ $booking->booking_code }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $booking->user?->name ?? ($booking->customer_name ?? '-') }}
                                        </td>

                                        <td>
                                            {{ $booking->branch?->branch_name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $booking->field?->field_name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ $booking->booking_date?->format('d M Y') ?? '-' }}
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                                        </td>

                                        <td>
                                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                        </td>

                                        <td>

                                            @php
                                                $statusClass = match ($booking->status) {
                                                    'paid' => 'success',
                                                    'completed' => 'primary',
                                                    'pending' => 'warning',
                                                    'rejected', 'cancelled' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp

                                            <span class="badge bg-label-{{ $statusClass }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="8" class="text-center py-5">

                                            <div class="text-muted">
                                                <i class="bx bx-calendar-x fs-1 d-block mb-2"></i>

                                                Belum ada booking.
                                            </div>

                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>

    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const labels = @json($weeklyLabels);
                const bookingData = @json($weeklyBookingData);
                const revenueData = @json($weeklyRevenueData);

                const options = {
                    series: [{
                            name: 'Booking',
                            type: 'column',
                            data: bookingData
                        },
                        {
                            name: 'Pendapatan',
                            type: 'line',
                            data: revenueData
                        }
                    ],

                    chart: {
                        height: 350,
                        type: 'line',
                        toolbar: {
                            show: false
                        }
                    },

                    stroke: {
                        width: [0, 3],
                        curve: 'smooth'
                    },

                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            columnWidth: '45%'
                        }
                    },

                    dataLabels: {
                        enabled: false
                    },

                    xaxis: {
                        categories: labels
                    },

                    yaxis: [{
                            title: {
                                text: 'Jumlah Booking'
                            }
                        },
                        {
                            opposite: true,
                            title: {
                                text: 'Pendapatan'
                            },

                            labels: {
                                formatter: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID')
                                        .format(value);
                                }
                            }
                        }
                    ],

                    tooltip: {
                        shared: true,
                        intersect: false,

                        y: {
                            formatter: function(value, {
                                seriesIndex
                            }) {
                                if (seriesIndex === 1) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID')
                                        .format(value);
                                }

                                return value + ' booking';
                            }
                        }
                    },

                    legend: {
                        position: 'top'
                    }
                };

                const chart = new ApexCharts(
                    document.querySelector('#bookingChart'),
                    options
                );

                chart.render();
            });
        </script>
    @endpush
@endsection
