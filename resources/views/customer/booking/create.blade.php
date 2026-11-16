@extends('layouts.frontend')

@section('title', 'Konfirmasi Booking Lapangan | bkngftsl.')

@push('styles')
<style>
    /* Date Pill Carousel */
    .date-scroll-container {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding-bottom: 4px;
    }
    .date-scroll-container::-webkit-scrollbar {
        display: none;
    }
    .date-pill-btn {
        min-width: 76px;
        padding: 0.6rem 0.5rem;
        border-radius: 0.85rem;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #1e293b;
        transition: all 0.2s ease;
        text-align: center;
        flex-shrink: 0;
    }
    .date-pill-btn:hover {
        border-color: #696cff;
        background: #f8f9ff;
    }
    .date-pill-btn.active {
        background: #696cff;
        border-color: #696cff;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.25);
    }
    .date-pill-btn.active span {
        color: #ffffff !important;
    }

    /* Slot Checkbox Cards */
    .slot-checkbox-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 0.85rem;
        background: #ffffff;
        padding: 0.85rem;
        transition: all 0.2s ease;
        cursor: pointer;
        user-select: none;
        position: relative;
    }
    .slot-checkbox-card:hover:not(.disabled) {
        border-color: #696cff;
        background: #f8faff;
    }
    .slot-checkbox-card.selected {
        border-color: #696cff;
        background: rgba(105, 108, 255, 0.08);
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.15);
    }
    .slot-checkbox-card.disabled {
        cursor: not-allowed;
    }

    /* Payment Cards */
    .payment-type-card, .payment-method-card {
        transition: all 0.2s ease;
        border: 1.5px solid #e2e8f0 !important;
    }
    .payment-type-card:hover, .payment-method-card:hover {
        border-color: #696cff !important;
    }
    .payment-type-card.active, .payment-method-card.active {
        border-color: #696cff !important;
        background-color: rgba(105, 108, 255, 0.04);
    }

    /* Mobile Sticky Bottom Bar */
    @media (max-width: 991.98px) {
        body {
            padding-bottom: 90px;
        }
        .mobile-booking-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid rgba(67, 89, 113, 0.12);
            padding: 0.75rem 1rem;
            z-index: 1040;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
        }
    }
</style>
@endpush

@section('content')
<div class="py-4 py-lg-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('landing') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('lapangan.index') }}">Lapangan</a></li>
                <li class="breadcrumb-item"><a href="{{ route('lapangan.detail', $field->id) }}">{{ $field->field_name }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Booking</li>
            </ol>
        </nav>

        <div class="mb-4">
            <h2 class="fw-extrabold text-dark mb-1">Konfirmasi dan Pembayaran Booking</h2>
            <p class="text-muted mb-0">Pilih jadwal main dari daftar slot tersedia dan tentukan opsi pembayaran Anda.</p>
        </div>

        <form action="{{ route('pelanggan.booking.store') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" name="field_id" value="{{ $field->id }}">

            <!-- Hidden Inputs untuk Nilai yang dihitung otomatis dari slot yang dicentang -->
            <input type="hidden" name="booking_date" id="inputBookingDate" value="{{ old('booking_date', $selectedDate) }}" required>
            <input type="hidden" name="start_time" id="hiddenStartTime" value="{{ old('start_time', $selectedTime) }}" required>
            <input type="hidden" name="duration" id="hiddenDuration" value="{{ old('duration', $selectedDuration) }}" required>

            <div class="row g-4">
                <!-- Kolom Kiri: Form Jadwal & Metode Pembayaran -->
                <div class="col-12 col-lg-8">
                    <!-- Card: Jadwal Bermain (Tanpa Nomor Header) -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white p-3 p-sm-4 pb-0 border-0">
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="bx bx-calendar-event text-primary me-2"></i>Jadwal Bermain
                            </h5>
                            <small class="text-muted">Pilih tanggal dan centang slot jam bermain yang Anda inginkan.</small>
                        </div>
                        <div class="card-body p-3 p-sm-4">
                            <!-- Ringkasan Singkat Lapangan -->
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-4">
                                <div class="rounded-3 overflow-hidden" style="width: 64px; height: 64px; background: #334155; flex-shrink: 0;">
                                    @if ($field->image)
                                        <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->field_name }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                            <i class="bx bx-football fs-3"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <span class="badge bg-primary text-uppercase" style="font-size: 10px;">{{ $field->field_type ?? 'Futsal' }}</span>
                                    <h6 class="fw-bold text-dark mb-0 mt-1 text-truncate">{{ $field->field_name }}</h6>
                                    <small class="text-muted text-truncate d-block"><i class="bx bx-map-pin me-1"></i>{{ $field->branch?->branch_name }}</small>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <small class="text-muted d-block" style="font-size: 11px;">Tarif / Jam</small>
                                    <span class="fw-bold text-primary">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Pilihan Tanggal Main User-Friendly -->
                            <div class="mb-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                    <label class="form-label small fw-bold text-dark mb-0">
                                        <i class="bx bx-calendar me-1 text-primary"></i>Pilih Tanggal Main
                                    </label>
                                    <div class="d-flex align-items-center gap-2">
                                        <span id="activeDateDisplayBadge" class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill fw-semibold" style="font-size: 11px;">
                                            {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}
                                        </span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 rounded-pill" id="btnOpenDatePicker" title="Pilih Tanggal Lain">
                                            <i class="bx bx-calendar-edit"></i>
                                        </button>
                                        <input type="date" id="manualDatePicker" class="visually-hidden" min="{{ date('Y-m-d') }}" value="{{ $selectedDate }}">
                                    </div>
                                </div>

                                <!-- Date Carousel / Horizontal Pills -->
                                <div class="date-scroll-container py-1" id="datePillsContainer">
                                    @foreach ($datePills as $dp)
                                        <button type="button"
                                            class="btn date-pill-btn {{ $dp['date'] === $selectedDate ? 'active' : '' }}"
                                            data-date="{{ $dp['date'] }}"
                                            data-formatted="{{ $dp['formatted'] }}">
                                            <span class="d-block text-muted small" style="font-size: 10px; font-weight: 600;">{{ $dp['day_name'] }}</span>
                                            <span class="d-block fw-bold fs-5 my-0 lh-1">{{ $dp['day_number'] }}</span>
                                            <span class="d-block text-muted" style="font-size: 10px;">{{ $dp['month_name'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Bar Status & Indikator -->
                            <div class="pt-3 border-top">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                                    <div>
                                        <label class="form-label small fw-bold text-dark mb-0">
                                            <i class="bx bx-time me-1 text-primary"></i>Pilih Slot Jadwal
                                        </label>
                                        <small class="text-muted d-block" style="font-size: 11px;">Centang 1 atau lebih slot berurutan. Durasi bertambah otomatis.</small>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 10px;">
                                            <i class="bx bx-check-circle me-1"></i>Kosong
                                        </span>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1" style="font-size: 10px;">
                                            <i class="bx bxs-user-x me-1"></i>Terisi
                                        </span>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size: 10px;">
                                            <i class="bx bx-time-five me-1"></i>Kadaluarsa
                                        </span>
                                    </div>
                                </div>

                                <!-- Box Ringkasan Pilihan Slot Aktif -->
                                <div class="p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 mb-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bx bx-time-five fs-3 text-primary"></i>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 11px;">Jadwal yang Dipilih</small>
                                            <span class="fw-bold text-primary" id="selectedSlotsOverviewText">Belum ada slot dipilih</span>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary text-white rounded-pill px-3 py-1" id="selectedDurationBadge" style="font-size: 11px;">0 Jam</span>
                                </div>

                                <!-- Grid Checkbox Seluruh Slot Jadwal -->
                                <div id="createSlotsGrid" class="row g-2" style="max-height: 320px; overflow-y: auto; padding-right: 2px;">
                                    @php
                                        // Cek apakah slot awal match dengan $selectedTime
                                        $initialStartTime = old('start_time', $selectedTime);
                                        $initialDuration = (int) old('duration', $selectedDuration);
                                    @endphp
                                    @foreach ($slots as $index => $slot)
                                        @php
                                            $isKosong = in_array($slot['status'], ['kosong', 'available']);
                                            $isTerisi = in_array($slot['status'], ['terisi', 'booked']);
                                            $isKadaluarsa = in_array($slot['status'], ['kadaluarsa', 'expired']);

                                            // Precheck logic jika ada selectedTime & duration
                                            $isPreChecked = false;
                                            if ($isKosong && $initialStartTime) {
                                                $startCarbon = \Carbon\Carbon::createFromFormat('H:i', $initialStartTime);
                                                $slotStartCarbon = \Carbon\Carbon::createFromFormat('H:i', $slot['start_time']);
                                                $diffHours = $startCarbon->diffInHours($slotStartCarbon, false);
                                                if ($diffHours >= 0 && $diffHours < $initialDuration) {
                                                    $isPreChecked = true;
                                                }
                                            }
                                        @endphp
                                        <div class="col-12 col-sm-6 col-md-4">
                                            @if ($isKosong)
                                                <label class="slot-checkbox-card d-block h-100 {{ $isPreChecked ? 'selected' : '' }}" data-time="{{ $slot['start_time'] }}" data-end="{{ $slot['end_time'] }}">
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <input type="checkbox" class="form-check-input slot-check-input mt-0 fs-5 flex-shrink-0"
                                                                value="{{ $slot['start_time'] }}" data-end="{{ $slot['end_time'] }}" {{ $isPreChecked ? 'checked' : '' }}>
                                                            <span class="fw-bold text-dark text-truncate" style="font-size: 13px;">{{ $slot['time_text'] }}</span>
                                                        </div>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0 flex-shrink-0" style="font-size: 9.5px;">Kosong</span>
                                                    </div>
                                                    <small class="text-muted d-block ps-4 ms-1" style="font-size: 11px;">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }} / jam</small>
                                                </label>
                                            @elseif ($isTerisi)
                                                <div class="slot-checkbox-card disabled p-3 rounded-3 border border-danger-subtle bg-light h-100 opacity-75">
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <input type="checkbox" class="form-check-input mt-0 fs-5 flex-shrink-0" disabled>
                                                            <span class="fw-semibold text-dark text-truncate" style="font-size: 13px;">{{ $slot['time_text'] }}</span>
                                                        </div>
                                                        <span class="badge bg-danger text-white rounded-pill px-2 py-0 flex-shrink-0" style="font-size: 9.5px;">Terisi</span>
                                                    </div>
                                                    <div class="ps-4 ms-1 text-truncate">
                                                        <small class="text-danger fw-semibold d-flex align-items-center gap-1 text-truncate" style="font-size: 11px;">
                                                            <i class="bx bxs-user-detail"></i> {{ $slot['team_name'] ?: 'Sedang Mengisi' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="slot-checkbox-card disabled p-3 rounded-3 border bg-light h-100 opacity-50">
                                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                                        <div class="d-flex align-items-center gap-2 min-w-0">
                                                            <input type="checkbox" class="form-check-input mt-0 fs-5 flex-shrink-0" disabled>
                                                            <span class="fw-semibold text-muted text-truncate" style="font-size: 13px;">{{ $slot['time_text'] }}</span>
                                                        </div>
                                                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0 flex-shrink-0" style="font-size: 9.5px;">Kadaluarsa</span>
                                                    </div>
                                                    <div class="ps-4 ms-1">
                                                        <small class="text-muted" style="font-size: 11px;">Waktu telah lewat</small>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Skema Pembayaran (Tanpa Nomor Header) -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white p-3 p-sm-4 pb-0 border-0">
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="bx bx-wallet text-primary me-2"></i>Skema Pembayaran
                            </h5>
                            <small class="text-muted">Pilih pembayaran lunas langsung atau uang muka (DP 50%).</small>
                        </div>
                        <div class="card-body p-3 p-sm-4">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <label class="card border p-3 rounded-3 cursor-pointer h-100 payment-type-card {{ old('payment_type', 'full') === 'full' ? 'active' : '' }}" for="typeFull">
                                        <div class="d-flex align-items-center gap-3">
                                            <input class="form-check-input mt-0" type="radio" name="payment_type" id="typeFull" value="full" {{ old('payment_type', 'full') === 'full' ? 'checked' : '' }}>
                                            <div>
                                                <span class="fw-bold text-dark d-block">Pembayaran Lunas 100%</span>
                                                <small class="text-muted">Bayar total biaya sewa sekaligus tanpa tagihan sisa.</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <div class="col-12 col-sm-6">
                                    <label class="card border p-3 rounded-3 cursor-pointer h-100 payment-type-card {{ old('payment_type') === 'dp' ? 'active' : '' }}" for="typeDP">
                                        <div class="d-flex align-items-center gap-3">
                                            <input class="form-check-input mt-0" type="radio" name="payment_type" id="typeDP" value="dp" {{ old('payment_type') === 'dp' ? 'checked' : '' }}>
                                            <div>
                                                <span class="fw-bold text-dark d-block">Uang Muka (DP 50%)</span>
                                                <small class="text-muted">Amankan slot dengan DP 50%, sisa dibayar di kasir venue.</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Metode Pembayaran Resmi Venue (Tanpa Nomor Header) -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white p-3 p-sm-4 pb-0 border-0">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">
                                        <i class="bx bx-credit-card text-primary me-2"></i>Metode Pembayaran Resmi Venue
                                    </h5>
                                    <small class="text-muted">Metode pembayaran resmi pengelola {{ $field->branch?->branch_name }}.</small>
                                </div>
                                <span class="badge bg-label-info px-2 py-1"><i class="bx bx-check-shield me-1"></i>Aman & Terverifikasi</span>
                            </div>
                        </div>
                        <div class="card-body p-3 p-sm-4">
                            @if ($paymentMethods->isEmpty())
                                <div class="alert alert-warning border-0 small">
                                    Pengelola venue belum mengonfigurasi metode pembayaran online. Silakan hubungi pengelola cabang.
                                </div>
                            @else
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($paymentMethods as $index => $pm)
                                        @php
                                            $isChecked = old('payment_method_id', $index === 0 ? $pm->id : '') == $pm->id;
                                        @endphp
                                        <label class="card border p-3 rounded-3 cursor-pointer payment-method-card {{ $isChecked ? 'active' : '' }}" for="pm_{{ $pm->id }}">
                                            <div class="d-flex align-items-start gap-3">
                                                <input class="form-check-input mt-1" type="radio" name="payment_method_id" id="pm_{{ $pm->id }}" value="{{ $pm->id }}"
                                                    {{ $isChecked ? 'checked' : '' }} required>
                                                
                                                <div class="flex-grow-1 min-w-0">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="fw-bold text-dark">{{ $pm->name }}</span>
                                                        @if ($pm->type === 'qris')
                                                            <span class="badge bg-label-primary text-uppercase">QRIS</span>
                                                        @elseif ($pm->type === 'bank_transfer')
                                                            <span class="badge bg-label-info text-uppercase">Transfer Bank</span>
                                                        @else
                                                            <span class="badge bg-label-success text-uppercase">Tunai</span>
                                                        @endif
                                                    </div>

                                                    @if ($pm->account_number)
                                                        <div class="small text-muted mb-1">
                                                            No. Rekening / VA: <strong class="text-dark">{{ $pm->account_number }}</strong>
                                                            @if ($pm->account_name)
                                                                (a.n {{ $pm->account_name }})
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if ($pm->instructions)
                                                        <small class="text-muted d-block" style="font-size: 12px;">{{ $pm->instructions }}</small>
                                                    @endif
                                                </div>

                                                @if ($pm->type === 'qris' && $pm->qr_image)
                                                    <img src="{{ asset('storage/' . $pm->qr_image) }}" alt="QR" style="width: 45px; height: 45px; object-fit: contain;" class="rounded border p-1 d-none d-sm-block flex-shrink-0">
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Catatan Tambahan -->
                            <div class="mt-4">
                                <label class="form-label small fw-semibold text-dark">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Nama tim sparring, jersey warna, dll.">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Ringkasan Reservasi (Sticky Desktop) -->
                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px;">
                        <div class="card-header bg-white p-4 pb-0 border-0">
                            <h5 class="fw-bold text-dark mb-0">Ringkasan Reservasi</h5>
                        </div>
                        <div class="card-body p-4 pt-3">
                            <hr class="mt-2 mb-3">

                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Nama Pemesan:</span>
                                <span class="fw-semibold text-dark">{{ auth()->user()->name }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">No. WhatsApp / HP:</span>
                                <span class="fw-semibold text-dark">{{ auth()->user()->phone ?? '-' }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Venue:</span>
                                <span class="fw-semibold text-dark text-truncate" style="max-width: 160px;">{{ $field->branch?->branch_name }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Lapangan:</span>
                                <span class="fw-semibold text-dark">{{ $field->field_name }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Tanggal:</span>
                                <span class="fw-semibold text-dark" id="summaryDateLabel">-</span>
                            </div>

                            <div class="d-flex justify-content-between mb-3 small">
                                <span class="text-muted">Jam & Durasi:</span>
                                <span class="fw-semibold text-primary" id="summaryTimeLabel">-</span>
                            </div>

                            <hr class="my-3">

                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Tarif Sewa:</span>
                                <span class="fw-semibold text-dark">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }} x <span id="summaryDurationText">1</span> Jam</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-dark fw-bold">Total Biaya Sewa:</span>
                                <span class="fw-bold text-dark" id="summaryTotalAmount">Rp 0</span>
                            </div>

                            <div class="p-3 bg-primary bg-opacity-10 rounded-3 my-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-primary d-block fw-semibold" id="paymentDueLabel">Harus Dibayar Sekarang (100%):</small>
                                    <h4 class="fw-extrabold text-primary mb-0" id="summaryAmountDue">Rp 0</h4>
                                </div>
                                <i class="bx bx-wallet-alt fs-2 text-primary"></i>
                            </div>

                            <button type="submit" id="btnSubmitBooking" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm">
                                <i class="bx bx-check-double me-1"></i> Konfirmasi Booking
                            </button>

                            <small class="text-muted d-block text-center mt-3" style="font-size: 11px;">
                                <i class="bx bx-info-circle me-1"></i> Batas waktu pembayaran 30 menit setelah reservasi dibuat.
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Sticky Bottom Bar: Memudahkan konfirmasi di layar HP -->
            <div class="mobile-booking-bottom-bar d-lg-none">
                <div class="container d-flex align-items-center justify-content-between gap-3 px-0">
                    <div>
                        <small class="text-muted d-block" style="font-size: 10px;">Total Pembayaran</small>
                        <span class="fw-extrabold text-primary fs-5" id="mobileAmountDue">Rp 0</span>
                        <small class="text-muted d-block" style="font-size: 10px;" id="mobileSummaryText">0 Jam</small>
                    </div>
                    <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm flex-shrink-0" onclick="submitBookingFormMobile()">
                        <i class="bx bx-check-double me-1"></i> Konfirmasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pricePerHour = {{ (float) $field->price_per_hour }};
    const bookingForm = document.getElementById('bookingForm');
    const inputBookingDate = document.getElementById('inputBookingDate');
    const hiddenStartTime = document.getElementById('hiddenStartTime');
    const hiddenDuration = document.getElementById('hiddenDuration');
    const typeFullRadio = document.getElementById('typeFull');
    const typeDPRadio = document.getElementById('typeDP');

    const activeDateDisplayBadge = document.getElementById('activeDateDisplayBadge');
    const btnOpenDatePicker = document.getElementById('btnOpenDatePicker');
    const manualDatePicker = document.getElementById('manualDatePicker');
    const datePillsContainer = document.getElementById('datePillsContainer');

    const slotsGrid = document.getElementById('createSlotsGrid');
    const selectedSlotsOverviewText = document.getElementById('selectedSlotsOverviewText');
    const selectedDurationBadge = document.getElementById('selectedDurationBadge');

    const summaryDateLabel = document.getElementById('summaryDateLabel');
    const summaryTimeLabel = document.getElementById('summaryTimeLabel');
    const summaryDurationText = document.getElementById('summaryDurationText');
    const summaryTotalAmount = document.getElementById('summaryTotalAmount');
    const summaryAmountDue = document.getElementById('summaryAmountDue');
    const paymentDueLabel = document.getElementById('paymentDueLabel');
    const mobileAmountDue = document.getElementById('mobileAmountDue');
    const mobileSummaryText = document.getElementById('mobileSummaryText');

    const INDO_DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const INDO_MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const INDO_MONTHS_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    function formatIndoFull(dateStr) {
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const d = new Date(parts[0], parts[1] - 1, parts[2]);
        return `${INDO_DAYS[d.getDay()]}, ${d.getDate()} ${INDO_MONTHS[d.getMonth()]} ${d.getFullYear()}`;
    }

    function formatIndoShort(dateStr) {
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const d = new Date(parts[0], parts[1] - 1, parts[2]);
        return `${d.getDate()} ${INDO_MONTHS_SHORT[d.getMonth()]} ${d.getFullYear()}`;
    }

    function rupiah(num) {
        return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Hitung ringkasan harga dan waktu
    function updateSummary() {
        const duration = parseInt(hiddenDuration.value) || 0;
        const startTime = hiddenStartTime.value;
        const total = pricePerHour * duration;
        const isDP = typeDPRadio && typeDPRadio.checked;
        const amountDue = isDP ? total * 0.5 : total;

        summaryDurationText.textContent = duration > 0 ? duration : 1;
        summaryTotalAmount.textContent = rupiah(total);
        summaryAmountDue.textContent = rupiah(amountDue);
        if (mobileAmountDue) mobileAmountDue.textContent = rupiah(amountDue);

        if (isDP) {
            paymentDueLabel.textContent = 'Harus Dibayar Sekarang (DP 50%):';
        } else {
            paymentDueLabel.textContent = 'Harus Dibayar Sekarang (Lunas 100%):';
        }

        // Tanggal
        if (inputBookingDate && inputBookingDate.value) {
            summaryDateLabel.textContent = formatIndoShort(inputBookingDate.value);
        } else {
            summaryDateLabel.textContent = '-';
        }

        // Jam & Durasi
        if (startTime && duration > 0) {
            // Hitung jam selesai
            const [h, m] = startTime.split(':').map(Number);
            const endHour = h + duration;
            const endFormatted = String(endHour).padStart(2, '0') + ':' + String(m).padStart(2, '0');

            const overview = `${startTime} - ${endFormatted} WITA (${duration} Jam)`;
            summaryTimeLabel.textContent = overview;
            selectedSlotsOverviewText.textContent = overview;
            selectedDurationBadge.textContent = `${duration} Jam`;
            if (mobileSummaryText) mobileSummaryText.textContent = `${overview}`;
        } else {
            summaryTimeLabel.textContent = 'Belum dipilih';
            selectedSlotsOverviewText.textContent = 'Belum ada slot dipilih';
            selectedDurationBadge.textContent = '0 Jam';
            if (mobileSummaryText) mobileSummaryText.textContent = 'Pilih slot jadwal';
        }
    }

    // Logika pemilihan slot multi-checkbox
    function handleSlotSelection(clickedCard) {
        const checkbox = clickedCard.querySelector('.slot-check-input');
        if (!checkbox || checkbox.disabled) return;

        // Toggle state
        checkbox.checked = !checkbox.checked;
        syncSlotsFromCheckboxes(checkbox.value, checkbox.checked);
    }

    function syncSlotsFromCheckboxes(lastClickedTime, wasChecked) {
        if (!slotsGrid) return;
        const allCards = Array.from(slotsGrid.querySelectorAll('.slot-checkbox-card:not(.disabled)'));
        const checkedBoxes = allCards
            .map(c => c.querySelector('.slot-check-input'))
            .filter(cb => cb && cb.checked);

        if (checkedBoxes.length === 0) {
            hiddenStartTime.value = '';
            hiddenDuration.value = '0';
            allCards.forEach(c => c.classList.remove('selected'));
            updateSummary();
            return;
        }

        // Urutkan slot berdasarkan start_time
        checkedBoxes.sort((a, b) => a.value.localeCompare(b.value));

        // Cek apakah slot berurutan
        let isConsecutive = true;
        for (let i = 0; i < checkedBoxes.length - 1; i++) {
            const currentEnd = checkedBoxes[i].getAttribute('data-end');
            const nextStart = checkedBoxes[i + 1].value;
            if (currentEnd !== nextStart) {
                isConsecutive = false;
                break;
            }
        }

        if (!isConsecutive) {
            // Jika tidak berurutan, reset dan pilih hanya yang baru saja diklik
            allCards.forEach(c => {
                const cb = c.querySelector('.slot-check-input');
                if (cb) {
                    cb.checked = (cb.value === lastClickedTime);
                }
            });
            hiddenStartTime.value = lastClickedTime;
            hiddenDuration.value = '1';
        } else {
            hiddenStartTime.value = checkedBoxes[0].value;
            hiddenDuration.value = String(checkedBoxes.length);
        }

        // Update card classes
        allCards.forEach(card => {
            const cb = card.querySelector('.slot-check-input');
            if (cb && cb.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });

        updateSummary();
    }

    function attachSlotEvents() {
        if (!slotsGrid) return;
        const activeCards = slotsGrid.querySelectorAll('.slot-checkbox-card:not(.disabled)');
        activeCards.forEach(card => {
            card.onclick = function (e) {
                if (e.target.tagName.toLowerCase() === 'input') {
                    // Jika langsung klik checkbox-nya
                    syncSlotsFromCheckboxes(e.target.value, e.target.checked);
                } else {
                    e.preventDefault();
                    handleSlotSelection(this);
                }
            };
        });
    }

    // Payment Type Card Visual Sync
    if (typeFullRadio && typeDPRadio) {
        [typeFullRadio, typeDPRadio].forEach(radio => {
            radio.addEventListener('change', function () {
                document.querySelectorAll('.payment-type-card').forEach(c => c.classList.remove('active'));
                const parent = this.closest('.payment-type-card');
                if (parent) parent.classList.add('active');
                updateSummary();
            });
        });
    }

    // Payment Method Card Visual Sync
    document.querySelectorAll('input[name="payment_method_id"]').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.payment-method-card').forEach(c => c.classList.remove('active'));
            const parent = this.closest('.payment-method-card');
            if (parent) parent.classList.add('active');
        });
    });

    // Date Carousel Pills Click Event
    function attachDatePillEvents() {
        const pillBtns = document.querySelectorAll('.date-pill-btn');
        pillBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const date = this.getAttribute('data-date');
                const formatted = this.getAttribute('data-formatted');
                if (!date) return;

                pillBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                if (inputBookingDate) inputBookingDate.value = date;
                if (manualDatePicker) manualDatePicker.value = date;
                if (activeDateDisplayBadge) activeDateDisplayBadge.textContent = formatted || date;

                loadSlotsForDate(date);
            });
        });
    }

    // Date Picker Manual
    if (btnOpenDatePicker && manualDatePicker) {
        btnOpenDatePicker.addEventListener('click', function () {
            manualDatePicker.showPicker ? manualDatePicker.showPicker() : manualDatePicker.focus();
        });

        manualDatePicker.addEventListener('change', function () {
            const date = this.value;
            if (!date) return;

            if (inputBookingDate) inputBookingDate.value = date;

            // Update badge text dalam Bahasa Indonesia
            if (activeDateDisplayBadge) activeDateDisplayBadge.textContent = formatIndoFull(date);

            // Highlight corresponding pill if exists
            const pillBtns = document.querySelectorAll('.date-pill-btn');
            let foundPill = false;
            pillBtns.forEach(b => {
                if (b.getAttribute('data-date') === date) {
                    b.classList.add('active');
                    foundPill = true;
                    b.scrollIntoView({ behavior: 'smooth', inline: 'center' });
                } else {
                    b.classList.remove('active');
                }
            });

            loadSlotsForDate(date);
        });
    }

    // AJAX Reload Slots saat tanggal berubah
    function loadSlotsForDate(selectedDate) {
        hiddenStartTime.value = '';
        hiddenDuration.value = '0';
        updateSummary();

        if (slotsGrid) {
            slotsGrid.innerHTML = `
                <div class="col-12 text-center py-4 text-muted">
                    <span class="spinner-border spinner-border-sm text-primary mb-2"></span>
                    <p class="small mb-0">Memuat ketersediaan jadwal...</p>
                </div>
            `;
        }

        fetch("{{ route('lapangan.slots', $field->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ date: selectedDate })
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success' && Array.isArray(res.data)) {
                if (!slotsGrid) return;
                slotsGrid.innerHTML = '';

                if (res.data.length === 0) {
                    slotsGrid.innerHTML = `
                        <div class="col-12 text-center py-4 text-muted small">
                            <i class="bx bx-time-five fs-3 mb-1"></i>
                            <p class="mb-0">Tidak ada jadwal operasional aktif pada tanggal ini.</p>
                        </div>
                    `;
                    return;
                }

                res.data.forEach(slot => {
                    const isKosong = slot.status === 'kosong' || slot.status === 'available';
                    const isTerisi = slot.status === 'terisi' || slot.status === 'booked';
                    const isKadaluarsa = slot.status === 'kadaluarsa' || slot.status === 'expired';
                    const team = slot.team_name ? slot.team_name : 'Sedang Mengisi';

                    const col = document.createElement('div');
                    col.className = 'col-12 col-sm-6 col-md-4';

                    if (isKosong) {
                        col.innerHTML = `
                            <label class="slot-checkbox-card d-block h-100" data-time="${slot.start_time}" data-end="${slot.end_time}">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <input type="checkbox" class="form-check-input slot-check-input mt-0 fs-5 flex-shrink-0"
                                            value="${slot.start_time}" data-end="${slot.end_time}">
                                        <span class="fw-bold text-dark text-truncate" style="font-size: 13px;">${slot.time_text}</span>
                                    </div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0 flex-shrink-0" style="font-size: 9.5px;">Kosong</span>
                                </div>
                                <small class="text-muted d-block ps-4 ms-1" style="font-size: 11px;">Rp ${pricePerHour.toLocaleString('id-ID')} / jam</small>
                            </label>
                        `;
                    } else if (isTerisi) {
                        col.innerHTML = `
                            <div class="slot-checkbox-card disabled p-3 rounded-3 border border-danger-subtle bg-light h-100 opacity-75">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <input type="checkbox" class="form-check-input mt-0 fs-5 flex-shrink-0" disabled>
                                        <span class="fw-semibold text-dark text-truncate" style="font-size: 13px;">${slot.time_text}</span>
                                    </div>
                                    <span class="badge bg-danger text-white rounded-pill px-2 py-0 flex-shrink-0" style="font-size: 9.5px;">Terisi</span>
                                </div>
                                <div class="ps-4 ms-1 text-truncate">
                                    <small class="text-danger fw-semibold d-flex align-items-center gap-1 text-truncate" style="font-size: 11px;">
                                        <i class="bx bxs-user-detail"></i> ${team}
                                    </small>
                                </div>
                            </div>
                        `;
                    } else {
                        col.innerHTML = `
                            <div class="slot-checkbox-card disabled p-3 rounded-3 border bg-light h-100 opacity-50">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <input type="checkbox" class="form-check-input mt-0 fs-5 flex-shrink-0" disabled>
                                        <span class="fw-semibold text-muted text-truncate" style="font-size: 13px;">${slot.time_text}</span>
                                    </div>
                                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-0 flex-shrink-0" style="font-size: 9.5px;">Kadaluarsa</span>
                                </div>
                                <div class="ps-4 ms-1">
                                    <small class="text-muted" style="font-size: 11px;">Waktu telah lewat</small>
                                </div>
                            </div>
                        `;
                    }
                    slotsGrid.appendChild(col);
                });

                attachSlotEvents();
            }
        })
        .catch(err => {
            console.error(err);
            if (slotsGrid) {
                slotsGrid.innerHTML = `
                    <div class="col-12 text-center py-4 text-danger small">
                        <i class="bx bx-error-circle fs-3 mb-1"></i>
                        <p class="mb-0">Gagal memuat slot. Silakan coba lagi.</p>
                    </div>
                `;
            }
        });
    }

    // Validasi form submit
    bookingForm.addEventListener('submit', function (e) {
        const startTime = hiddenStartTime.value;
        const duration = parseInt(hiddenDuration.value) || 0;

        if (!startTime || duration < 1) {
            e.preventDefault();
            alert('Silakan centang minimal 1 slot jadwal bermain yang tersedia.');
            return false;
        }
    });

    window.submitBookingFormMobile = function () {
        if (bookingForm) {
            const submitBtn = document.getElementById('btnSubmitBooking');
            if (submitBtn) {
                submitBtn.click();
            } else {
                bookingForm.requestSubmit();
            }
        }
    };

    // Init events
    attachDatePillEvents();
    attachSlotEvents();

    // Init initial summary state
    const initialChecked = slotsGrid ? slotsGrid.querySelectorAll('.slot-checkbox-card.selected .slot-check-input') : [];
    if (initialChecked.length > 0) {
        hiddenStartTime.value = initialChecked[0].value;
        hiddenDuration.value = String(initialChecked.length);
    }
    updateSummary();
});
</script>
@endpush
