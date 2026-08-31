@extends('layouts.app')

@section('title', 'Dashboard Admin | bkngftsl.')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Greeting --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">
            Selamat datang, {{ $user->name }} 👋
        </h4>
        <p class="text-muted mb-0">
            Berikut ringkasan aktivitas dan data sistem bkngftsl.
        </p>
    </div>

    {{-- Statistik Utama --}}
    <div class="row g-4 mb-4">

        {{-- Total Lapangan --}}
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">

                        <div>
                            <span class="text-muted small">
                                Total Lapangan
                            </span>

                            <h3 class="mb-1 mt-2 fw-bold">
                                {{ number_format($totalFields) }}
                            </h3>

                            <small class="text-muted">
                                Lapangan terdaftar
                            </small>
                        </div>

                        <div class="avatar avatar-lg">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-layer fs-3"></i>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Total Cabang --}}
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">

                        <div>
                            <span class="text-muted small">
                                Total Cabang
                            </span>

                            <h3 class="mb-1 mt-2 fw-bold">
                                {{ number_format($totalBranches) }}
                            </h3>

                            <small class="text-muted">
                                Cabang terdaftar
                            </small>
                        </div>

                        <div class="avatar avatar-lg">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-building-house fs-3"></i>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Total Pengguna --}}
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">

                        <div>
                            <span class="text-muted small">
                                Total Pengguna
                            </span>

                            <h3 class="mb-1 mt-2 fw-bold">
                                {{ number_format($totalUsers) }}
                            </h3>

                            <small class="text-muted">
                                Pengguna terdaftar
                            </small>
                        </div>

                        <div class="avatar avatar-lg">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-group fs-3"></i>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Statistik Pengguna --}}
    <div class="row g-4">

        <div class="col-12">
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-bottom py-3">
                    <div>
                        <h5 class="mb-1 fw-bold">
                            Statistik Pengguna
                        </h5>

                        <p class="text-muted small mb-0">
                            Jumlah pengguna baru yang terdaftar setiap minggu dalam 8 minggu terakhir.
                        </p>
                    </div>
                </div>

                <div class="card-body">

                    <div style="height: 350px;">
                        <canvas id="weeklyUsersChart"></canvas>
                    </div>

                </div>

            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const weeklyUsers = @json($weeklyUsers);

    const labels = weeklyUsers.map(item => item.label);
    const totals = weeklyUsers.map(item => item.total);

    const canvas = document.getElementById('weeklyUsersChart');

    if (!canvas) {
        return;
    }

    new Chart(canvas, {
        type: 'line',

        data: {
            labels: labels,

            datasets: [
                {
                    label: 'Pengguna Baru',

                    data: totals,

                    borderWidth: 3,

                    tension: 0.4,

                    fill: true,

                    pointRadius: 4,

                    pointHoverRadius: 6
                }
            ]
        },

        options: {
            responsive: true,

            maintainAspectRatio: false,

            interaction: {
                intersect: false,
                mode: 'index'
            },

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.parsed.y + ' pengguna baru';
                        }
                    }
                }
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0
                    },

                    grid: {
                        drawBorder: false
                    }
                },

                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

});
</script>

@endpush