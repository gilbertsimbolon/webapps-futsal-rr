@extends('layouts.app')

@section('title', 'Laporan Booking Transaksi | bkngftsl.')

@section('content')
    <style>
        @media print {

            .layout-menu,
            .layout-navbar,
            .card-filter,
            .btn-action-group,
            footer,
            .content-footer {
                display: none !important;
            }

            .content-wrapper,
            .container-xxl {
                padding: 0 !important;
                margin: 0 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            body {
                background-color: #ffffff !important;
                color: #000000 !important;
            }

            .print-header {
                display: block !important;
            }
        }

        .print-header {
            display: none;
        }
    </style>

    <!-- Header Khusus Cetak Kertas / PDF -->
    <div class="print-header text-center mb-4 pb-3 border-bottom">
        <h3 class="fw-bold mb-1">BKNGFTSL - SISTEM RESERVASI LAPANGAN FUTSAL</h3>
        <h5 class="mb-1">Laporan Riwayat Booking Transaksi</h5>
        <small class="text-muted">
            Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d
            {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
        </small>
    </div>

    <!-- Header Layar & Tombol Aksi -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1">Laporan Booking Transaksi</h4>
            <p class="text-muted mb-0 small">Rekapitulasi jadwal main, rincian biaya sewa, slot waktu, dan status reservasi
                lapangan.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 btn-action-group">
            <!-- Tombol Export Excel / CSV -->
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success shadow-sm">
                <i class="bx bx-spreadsheet me-1"></i> Export Excel (CSV)
            </a>
            <!-- Tombol Cetak / PDF -->
            <button type="button" class="btn btn-secondary shadow-sm" onclick="window.print()">
                <i class="bx bx-printer me-1"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Tombol Cepat Pilihan Periode (Mingguan, Bulanan, Tahunan) -->
    <div class="card border-0 shadow-sm mb-3 card-filter">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="small fw-semibold text-muted text-uppercase me-2"><i
                            class="bx bx-calendar-alt me-1"></i>Pilih Rentang Cepat:</span>
                    <a href="{{ route('laporan.booking', ['periode' => 'mingguan', 'branch_id' => request('branch_id'), 'status' => request('status')]) }}"
                        class="btn btn-sm {{ $periode === 'mingguan' ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }}">
                        Minggu Ini
                    </a>
                    <a href="{{ route('laporan.booking', ['periode' => 'bulanan', 'branch_id' => request('branch_id'), 'status' => request('status')]) }}"
                        class="btn btn-sm {{ $periode === 'bulanan' ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }}">
                        Bulan Ini
                    </a>
                    <a href="{{ route('laporan.booking', ['periode' => 'tahunan', 'branch_id' => request('branch_id'), 'status' => request('status')]) }}"
                        class="btn btn-sm {{ $periode === 'tahunan' ? 'btn-primary shadow-sm' : 'btn-outline-secondary' }}">
                        Tahun Ini
                    </a>
                </div>
                <span class="badge bg-label-info py-2 px-3 fs-7">
                    Aktif: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Form Filter Kustom & Cabang -->
    <div class="card border-0 shadow-sm mb-4 card-filter">
        <div class="card-body p-3">
            <form action="{{ route('laporan.booking') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-semibold mb-1">Cabang Venue</label>
                    <select name="branch_id" class="form-select">
                        <option value="">-- Semua Cabang --</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-semibold mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas 100%</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>DP 50%</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai Main
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end pt-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-filter-alt me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kartu Ringkasan Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-primary border-4 h-100">
                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">Total Booking</small>
                <h3 class="fw-bold text-dark mb-0 mt-1">{{ $totalTransaksi }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-success border-4 h-100">
                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">Lunas 100%</small>
                <h3 class="fw-bold text-success mb-0 mt-1">{{ $totalLunas }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-info border-4 h-100">
                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">DP 50% Masuk</small>
                <h3 class="fw-bold text-info mb-0 mt-1">{{ $totalDP }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-secondary border-4 h-100">
                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">Selesai Main</small>
                <h3 class="fw-bold text-secondary mb-0 mt-1">{{ $totalSelesai }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-danger border-4 h-100">
                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">Batal</small>
                <h3 class="fw-bold text-danger mb-0 mt-1">{{ $totalBatal }}</h3>
            </div>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3 border-start border-dark border-4 h-100">
                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 11px;">Total Biaya
                    Sewa</small>
                <h5 class="fw-bold text-primary mb-0 mt-1">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>

    <!-- Tabel Laporan Booking Lengkap -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">
                Detail Laporan: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} s/d
                {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
            </h6>
            <span class="badge bg-label-primary">{{ $bookings->count() }} Data Ditemukan</span>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">NO</th>
                        <th>TANGGAL & KODE</th>
                        <th>WAKTU SLOT</th>
                        <th>NAMA TIM / PEMESAN</th>
                        <th>LAPANGAN</th>
                        <th>CABANG VENUE</th>
                        <th>BIAYA SEWA</th>
                        <th>STATUS BAYAR</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $index => $booking)
                        @php
                            $startTime = $booking->start_time
                                ? \Carbon\Carbon::parse($booking->start_time)->format('H:i')
                                : '-';
                            $endTime = $booking->end_time
                                ? \Carbon\Carbon::parse($booking->end_time)->format('H:i')
                                : '-';
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span
                                    class="fw-bold text-dark">{{ $booking->booking_date ? $booking->booking_date->translatedFormat('d M Y') : '-' }}</span>
                                <br><small class="text-primary fw-semibold">{{ $booking->booking_code }}</small>
                            </td>
                            <td>
                                <span class="badge bg-label-info fw-bold">{{ $startTime }} - {{ $endTime }}
                                    WITA</span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $booking->user?->name ?? 'Tamu Walk-in' }}</span>
                                @if ($booking->user?->phone)
                                    <br><small class="text-muted"><i
                                            class="bx bx-phone me-1"></i>{{ $booking->user->phone }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-dark">{{ $booking->field?->field_name ?? '-' }}</span>
                                @if ($booking->field?->field_type)
                                    <br><small class="text-muted text-uppercase">{{ $booking->field->field_type }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $booking->branch?->branch_name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">Rp
                                    {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                                @if ($booking->status === 'confirmed')
                                    <br><small class="text-info fw-semibold">DP: Rp
                                        {{ number_format($booking->dp_amount > 0 ? $booking->dp_amount : $booking->total_amount * 0.5, 0, ',', '.') }}</small>
                                @endif
                            </td>
                            <td>
                                @if ($booking->status === 'paid')
                                    <span class="badge bg-label-success"><i class="bx bx-check-double me-1"></i>Lunas
                                        100%</span>
                                @elseif ($booking->status === 'confirmed')
                                    <span class="badge bg-label-info"><i class="bx bx-wallet me-1"></i>DP 50%</span>
                                @elseif ($booking->status === 'completed')
                                    <span class="badge bg-label-secondary"><i class="bx bx-flag me-1"></i>Selesai</span>
                                @elseif ($booking->status === 'pending')
                                    <span class="badge bg-label-warning"><i class="bx bx-time me-1"></i>Pending</span>
                                @else
                                    <span class="badge bg-label-danger"><i class="bx bx-x me-1"></i>Batal</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div
                                    class="avatar avatar-md bg-label-secondary mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-calendar-x fs-3 text-secondary"></i>
                                </div>
                                <p class="mb-0">Tidak ada riwayat booking transaksi pada periode ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
