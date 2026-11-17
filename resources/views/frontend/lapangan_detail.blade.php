@extends('layouts.frontend')

@section('title', $field->field_name . ' | bkngftsl.')

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
        min-width: 72px;
        padding: 0.5rem 0.4rem;
        border-radius: 0.75rem;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #1e293b;
        transition: all 0.2s ease;
        text-align: center;
        flex-shrink: 0;
    }
    .date-pill-btn:hover {
        border-color: #696cff;
        background: #f8faff;
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

    /* Slot Button Interactive */
    .detail-slot-btn {
        border: 1.5px solid #e2e8f0;
        border-radius: 0.75rem;
        background: #ffffff;
        padding: 0.65rem 0.75rem;
        transition: all 0.2s ease;
        cursor: pointer;
        user-select: none;
    }
    .detail-slot-btn:hover:not(.disabled) {
        border-color: #696cff;
        background: #f8faff;
    }
    .detail-slot-btn.selected {
        border-color: #696cff !important;
        background: rgba(105, 108, 255, 0.1) !important;
        box-shadow: 0 4px 12px rgba(105, 108, 255, 0.15);
    }
    .detail-slot-btn.disabled {
        cursor: not-allowed;
    }

    /* Mobile Sticky Bottom CTA */
    @media (max-width: 991.98px) {
        body {
            padding-bottom: 85px;
        }
        .mobile-detail-bottom-bar {
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
                <li class="breadcrumb-item active" aria-current="page">{{ $field->field_name }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Kolom Kiri: Foto, Detail, Fasilitas, Aturan -->
            <div class="col-12 col-lg-8">
                <!-- Foto Utama Lapangan -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="position-relative" style="height: 380px; background-color: #1e293b;">
                        @if ($field->image)
                            <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->field_name }}" class="w-100 h-100" style="object-fit: cover;">
                        @else
                            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white-50">
                                <i class="bx bx-football fs-1 mb-2"></i>
                                <h5 class="text-white-50">{{ $field->field_name }}</h5>
                            </div>
                        @endif
                        <div class="position-absolute top-0 start-0 m-3 d-flex gap-2">
                            <span class="badge bg-dark bg-opacity-75 text-uppercase px-3 py-2 rounded-pill">
                                {{ $field->field_type ?? 'Futsal' }}
                            </span>
                        </div>
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                <i class="bx bx-check-circle me-1"></i>Siap Digunakan
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                            <div>
                                <h2 class="fw-extrabold text-dark mb-1">{{ $field->field_name }}</h2>
                                <p class="text-muted mb-0 d-flex align-items-center gap-1">
                                    <i class="bx bx-map-pin text-danger"></i> {{ $field->branch?->branch_name ?? '-' }} &bull; {{ $field->branch?->address ?? 'Lokasi Venue' }}
                                </p>
                            </div>
                            <div class="text-sm-end">
                                <small class="text-muted d-block" style="font-size: 11px;">Tarif Sewa</small>
                                <h3 class="fw-extrabold text-primary mb-0">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</h3>
                                <small class="text-muted">/ jam</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Deskripsi -->
                        <h5 class="fw-bold text-dark mb-2">Deskripsi Lapangan</h5>
                        <p class="text-muted mb-4" style="line-height: 1.7;">
                            {{ $field->description ?: 'Lapangan futsal modern dengan material lantai berkualitas tinggi yang dirancang untuk kenyamanan pergerakan dan meminimalkan resiko cedera. Dilengkapi pencahayaan LED standar kompetisi untuk pertandingan siang maupun malam.' }}
                        </p>

                        <!-- Fasilitas Cabang Venue -->
                        <h5 class="fw-bold text-dark mb-3">Fasilitas Venue</h5>
                        <div class="row g-2 mb-4">
                            @php
                                $facilities = $field->branch?->facilities ?? ['Parkir Luas', 'Lampu Penerangan LED', 'Toilet & Kamar Mandi', 'Kantin & Minuman', 'Loker Penyimpanan', 'Free WiFi', 'Tribun Penonton'];
                            @endphp
                            @foreach ($facilities as $fac)
                                <div class="col-6 col-sm-4">
                                    <div class="p-2 rounded-3 bg-light d-flex align-items-center gap-2">
                                        <i class="bx bx-check-circle text-success fs-5"></i>
                                        <span class="small fw-semibold text-dark">{{ $fac }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Jam Operasional -->
                        <h5 class="fw-bold text-dark mb-2">Jam Operasional & Jadwal</h5>
                        <div class="card bg-light border-0 rounded-3 p-3 mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bx bx-time-five fs-3 text-primary"></i>
                                    <div>
                                        <span class="fw-bold text-dark d-block">Buka Setiap Hari</span>
                                        <small class="text-muted">Sesi Pagi hingga Malam Hari</small>
                                    </div>
                                </div>
                                <span class="badge bg-label-primary px-3 py-2 fw-semibold fs-7">08:00 - 24:00 WITA</span>
                            </div>
                        </div>

                        <!-- Aturan Booking -->
                        <h5 class="fw-bold text-dark mb-2">Aturan & Ketentuan Booking</h5>
                        <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
                            <li>Wajib hadir di venue minimal 10 menit sebelum jam bermain dimulai.</li>
                            <li>Gunakan sepatu futsal yang sesuai dengan jenis permukaan lantai lapangan.</li>
                            <li>Tersedia opsi pembayaran lunas 100% atau DP 50% untuk mengamankan slot.</li>
                            <li>Pesanan hold memiliki batas waktu pembayaran selama 30 menit.</li>
                            <li>Check-in dapat dilakukan di kasir venue dengan menunjukkan kode booking Anda.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Jadwal Ketersediaan & Booking Form -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px;">
                    <div class="card-header bg-white p-3 p-sm-4 pb-0 border-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge badge-soft-primary px-3 py-1 rounded-pill fw-semibold">Reservasi Lapangan</span>
                            <span class="small text-muted"><i class="bx bx-shield-check text-success"></i> Terverifikasi</span>
                        </div>
                        <h4 class="fw-extrabold text-primary mb-1">
                            Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                            <small class="text-muted fw-normal fs-6">/ jam</small>
                        </h4>
                        <small class="text-muted d-block text-truncate">{{ $field->field_name }} &bull; {{ $field->branch?->branch_name }}</small>
                    </div>

                    <div class="card-body p-3 p-sm-4 pt-3">
                        <hr class="mt-2 mb-3">

                        <!-- Pemilih Tanggal Main User Friendly -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label small fw-bold text-dark mb-0">
                                    <i class="bx bx-calendar me-1 text-primary"></i>Pilih Tanggal Main
                                </label>
                                <div class="d-flex align-items-center gap-1">
                                    <span id="detailActiveDateBadge" class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fw-semibold" style="font-size: 10px;">
                                        {{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}
                                    </span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 rounded-pill" id="btnOpenDetailDatePicker" title="Pilih Tanggal Lain">
                                        <i class="bx bx-calendar-edit"></i>
                                    </button>
                                    <input type="date" id="bookingDateInput" class="visually-hidden" min="{{ $today }}" value="{{ $today }}">
                                </div>
                            </div>

                            <!-- Carousel Date Pills -->
                            <div class="date-scroll-container py-1" id="detailDatePillsContainer">
                                @foreach ($datePills as $dp)
                                    <button type="button"
                                        class="btn date-pill-btn {{ $dp['date'] === $today ? 'active' : '' }}"
                                        data-date="{{ $dp['date'] }}"
                                        data-formatted="{{ $dp['formatted'] }}">
                                        <span class="d-block text-muted small" style="font-size: 10px; font-weight: 600;">{{ $dp['day_name'] }}</span>
                                        <span class="d-block fw-bold fs-5 my-0 lh-1">{{ $dp['day_number'] }}</span>
                                        <span class="d-block text-muted" style="font-size: 10px;">{{ $dp['month_name'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Ketersediaan Jadwal Slot Waktu -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label small fw-bold text-dark mb-0">
                                    <i class="bx bx-time me-1 text-primary"></i>Pilih Jadwal Tersedia
                                </label>
                                <small id="slotLoadingText" class="text-muted d-none" style="font-size: 11px;">
                                    <span class="spinner-border spinner-border-sm me-1"></span>Memuat...
                                </small>
                            </div>

                            <!-- Indikator 3 Status Jadwal -->
                            <div class="d-flex flex-wrap gap-2 mb-2 pb-1">
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

                            <!-- Container Slot Waktu -->
                            <div id="slotsContainer" class="d-flex flex-column gap-2" style="max-height: 280px; overflow-y: auto; padding-right: 2px;">
                                @forelse ($initialSlots as $slot)
                                    @php
                                        $isKosong = in_array($slot['status'], ['kosong', 'available']);
                                        $isTerisi = in_array($slot['status'], ['terisi', 'booked']);
                                        $isKadaluarsa = in_array($slot['status'], ['kadaluarsa', 'expired']);
                                    @endphp
                                    @if ($isKosong)
                                        <div class="detail-slot-btn d-flex justify-content-between align-items-center"
                                            data-time="{{ $slot['start_time'] }}"
                                            data-end="{{ $slot['end_time'] }}"
                                            data-available="1">
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="checkbox" class="form-check-input slot-detail-check mt-0" value="{{ $slot['start_time'] }}" data-end="{{ $slot['end_time'] }}">
                                                <span class="fw-semibold text-dark small">{{ $slot['time_text'] }}</span>
                                            </div>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 10px;">
                                                Kosong
                                            </span>
                                        </div>
                                    @elseif ($isTerisi)
                                        <div class="detail-slot-btn disabled bg-light border border-danger-subtle d-flex justify-content-between align-items-center opacity-75"
                                            data-time="{{ $slot['start_time'] }}"
                                            data-available="0">
                                            <div class="d-flex align-items-center gap-2 min-w-0 me-1">
                                                <input type="checkbox" class="form-check-input mt-0" disabled>
                                                <div class="d-flex flex-column text-truncate">
                                                    <span class="fw-semibold text-dark small text-truncate">{{ $slot['time_text'] }}</span>
                                                    <small class="text-danger fw-semibold d-flex align-items-center gap-1 text-truncate" style="font-size: 10.5px;">
                                                        <i class="bx bxs-user-detail"></i> {{ $slot['team_name'] ?: 'Sedang Mengisi' }}
                                                    </small>
                                                </div>
                                            </div>
                                            <span class="badge bg-danger text-white rounded-pill px-2 py-1 flex-shrink-0" style="font-size: 9.5px;">
                                                Terisi
                                            </span>
                                        </div>
                                    @else
                                        <div class="detail-slot-btn disabled bg-light border d-flex justify-content-between align-items-center opacity-50"
                                            data-time="{{ $slot['start_time'] }}"
                                            data-available="0">
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="checkbox" class="form-check-input mt-0" disabled>
                                                <span class="fw-semibold text-muted small">{{ $slot['time_text'] }}</span>
                                            </div>
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1 flex-shrink-0" style="font-size: 9.5px;">
                                                Kadaluarsa
                                            </span>
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center py-3 text-muted small">
                                        <i class="bx bx-time-five fs-3 mb-1"></i>
                                        <p class="mb-0">Tidak ada jadwal aktif pada tanggal ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Jam Terpilih -->
                        <div class="p-3 bg-light rounded-3 mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block" style="font-size: 11px;">Slot Dipilih</small>
                                <span id="selectedTimeLabel" class="fw-bold text-primary">Pilih slot di atas</span>
                            </div>
                            <span class="badge bg-primary rounded-pill px-2 py-1" id="detailDurationBadge" style="font-size: 11px;">0 Jam</span>
                        </div>

                        <!-- Action Button Desktop -->
                        @guest
                            <button type="button" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm" onclick="handleGuestBooking()">
                                <i class="bx bx-calendar-check me-1"></i> Booking Sekarang
                            </button>
                            <small class="text-muted d-block text-center mt-2" style="font-size: 11px;">
                                <i class="bx bx-lock-alt me-1"></i> Anda akan diminta masuk akun sebelum checkout
                            </small>
                        @else
                            <button type="button" id="btnProceedBooking" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm" onclick="handleAuthBooking()">
                                <i class="bx bx-calendar-check me-1"></i> Lanjutkan Booking
                            </button>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sticky Bottom CTA -->
    <div class="mobile-detail-bottom-bar d-lg-none">
        <div class="container d-flex align-items-center justify-content-between gap-3 px-0">
            <div>
                <small class="text-muted d-block" style="font-size: 10px;">Estimasi Biaya</small>
                <span class="fw-extrabold text-primary fs-5" id="mobileEstimatedPrice">Rp 0</span>
                <small class="text-muted d-block text-truncate" style="font-size: 10px; max-width: 140px;" id="mobileSelectedText">Pilih slot</small>
            </div>
            @guest
                <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm flex-shrink-0" onclick="handleGuestBooking()">
                    <i class="bx bx-calendar-check me-1"></i> Booking
                </button>
            @else
                <button type="button" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm flex-shrink-0" onclick="handleAuthBooking()">
                    <i class="bx bx-calendar-check me-1"></i> Lanjut
                </button>
            @endguest
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pricePerHour = {{ (float) $field->price_per_hour }};
    const dateInput = document.getElementById('bookingDateInput');
    const slotsContainer = document.getElementById('slotsContainer');
    const slotLoadingText = document.getElementById('slotLoadingText');
    const selectedTimeLabel = document.getElementById('selectedTimeLabel');
    const detailDurationBadge = document.getElementById('detailDurationBadge');
    const detailActiveDateBadge = document.getElementById('detailActiveDateBadge');
    const btnOpenDetailDatePicker = document.getElementById('btnOpenDetailDatePicker');
    const mobileEstimatedPrice = document.getElementById('mobileEstimatedPrice');
    const mobileSelectedText = document.getElementById('mobileSelectedText');

    let selectedStartTime = '';
    let selectedDuration = 0;

    const INDO_DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const INDO_MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    function formatIndoFull(dateStr) {
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const d = new Date(parts[0], parts[1] - 1, parts[2]);
        return `${INDO_DAYS[d.getDay()]}, ${d.getDate()} ${INDO_MONTHS[d.getMonth()]} ${d.getFullYear()}`;
    }

    function rupiah(num) {
        return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateSelectionDisplay() {
        if (selectedStartTime && selectedDuration > 0) {
            const [h, m] = selectedStartTime.split(':').map(Number);
            const endHour = h + selectedDuration;
            const endFormatted = String(endHour).padStart(2, '0') + ':' + String(m).padStart(2, '0');
            const total = pricePerHour * selectedDuration;

            const text = `${selectedStartTime} - ${endFormatted} WITA`;
            selectedTimeLabel.textContent = `${text} (${selectedDuration} Jam)`;
            detailDurationBadge.textContent = `${selectedDuration} Jam`;

            if (mobileEstimatedPrice) mobileEstimatedPrice.textContent = rupiah(total);
            if (mobileSelectedText) mobileSelectedText.textContent = `${text} (${selectedDuration} Jam)`;
        } else {
            selectedTimeLabel.textContent = 'Pilih slot di atas';
            detailDurationBadge.textContent = '0 Jam';
            if (mobileEstimatedPrice) mobileEstimatedPrice.textContent = rupiah(0);
            if (mobileSelectedText) mobileSelectedText.textContent = 'Pilih slot';
        }
    }

    function syncDetailSlots(lastClickedTime) {
        if (!slotsContainer) return;
        const allBtns = Array.from(slotsContainer.querySelectorAll('.detail-slot-btn:not(.disabled)'));
        const checkedBoxes = allBtns
            .map(b => b.querySelector('.slot-detail-check'))
            .filter(cb => cb && cb.checked);

        if (checkedBoxes.length === 0) {
            selectedStartTime = '';
            selectedDuration = 0;
            allBtns.forEach(b => b.classList.remove('selected'));
            updateSelectionDisplay();
            return;
        }

        checkedBoxes.sort((a, b) => a.value.localeCompare(b.value));

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
            allBtns.forEach(b => {
                const cb = b.querySelector('.slot-detail-check');
                if (cb) cb.checked = (cb.value === lastClickedTime);
            });
            selectedStartTime = lastClickedTime;
            selectedDuration = 1;
        } else {
            selectedStartTime = checkedBoxes[0].value;
            selectedDuration = checkedBoxes.length;
        }

        allBtns.forEach(btn => {
            const cb = btn.querySelector('.slot-detail-check');
            if (cb && cb.checked) {
                btn.classList.add('selected');
            } else {
                btn.classList.remove('selected');
            }
        });

        updateSelectionDisplay();
    }

    function attachSlotEvents() {
        if (!slotsContainer) return;
        const activeBtns = slotsContainer.querySelectorAll('.detail-slot-btn:not(.disabled)');
        activeBtns.forEach(btn => {
            btn.onclick = function (e) {
                const cb = this.querySelector('.slot-detail-check');
                if (!cb) return;
                if (e.target !== cb) {
                    cb.checked = !cb.checked;
                }
                syncDetailSlots(cb.value);
            };
        });
    }

    // Attach Date Pills
    function attachDatePills() {
        const pillBtns = document.querySelectorAll('#detailDatePillsContainer .date-pill-btn');
        pillBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const date = this.getAttribute('data-date');
                const formatted = this.getAttribute('data-formatted');
                if (!date) return;

                pillBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                if (dateInput) dateInput.value = date;
                if (detailActiveDateBadge) detailActiveDateBadge.textContent = formatted || date;

                loadSlots(date);
            });
        });
    }

    if (btnOpenDetailDatePicker && dateInput) {
        btnOpenDetailDatePicker.addEventListener('click', function () {
            dateInput.showPicker ? dateInput.showPicker() : dateInput.focus();
        });

        dateInput.addEventListener('change', function () {
            const date = this.value;
            if (!date) return;

            // Update badge text dalam Bahasa Indonesia
            if (detailActiveDateBadge) detailActiveDateBadge.textContent = formatIndoFull(date);

            const pillBtns = document.querySelectorAll('#detailDatePillsContainer .date-pill-btn');
            pillBtns.forEach(b => {
                if (b.getAttribute('data-date') === date) {
                    b.classList.add('active');
                    b.scrollIntoView({ behavior: 'smooth', inline: 'center' });
                } else {
                    b.classList.remove('active');
                }
            });

            loadSlots(date);
        });
    }

    function loadSlots(selectedDate) {
        selectedStartTime = '';
        selectedDuration = 0;
        updateSelectionDisplay();

        if (slotLoadingText) slotLoadingText.classList.remove('d-none');
        if (slotsContainer) {
            slotsContainer.innerHTML = `
                <div class="text-center py-4 text-muted small">
                    <span class="spinner-border spinner-border-sm text-primary mb-2"></span>
                    <p class="mb-0">Memuat ketersediaan jadwal...</p>
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
            if (slotLoadingText) slotLoadingText.classList.add('d-none');
            if (res.status === 'success' && Array.isArray(res.data)) {
                renderSlots(res.data);
            }
        })
        .catch(err => {
            if (slotLoadingText) slotLoadingText.classList.add('d-none');
            console.error(err);
            if (slotsContainer) {
                slotsContainer.innerHTML = `
                    <div class="text-center py-4 text-danger small">
                        <i class="bx bx-error-circle fs-3 mb-1"></i>
                        <p class="mb-0">Gagal memuat slot. Silakan coba lagi.</p>
                    </div>
                `;
            }
        });
    }

    function renderSlots(slots) {
        if (!slotsContainer) return;
        slotsContainer.innerHTML = '';

        if (slots.length === 0) {
            slotsContainer.innerHTML = `
                <div class="text-center py-4 text-muted small">
                    <i class="bx bx-time-five fs-3 mb-1"></i>
                    <p class="mb-0">Tidak ada jadwal operasional pada tanggal ini.</p>
                </div>
            `;
            return;
        }

        slots.forEach(slot => {
            const isKosong = slot.status === 'kosong' || slot.status === 'available';
            const isTerisi = slot.status === 'terisi' || slot.status === 'booked';
            const teamName = slot.team_name ? slot.team_name : 'Sedang Mengisi';

            const div = document.createElement('div');
            div.setAttribute('data-time', slot.start_time);
            div.setAttribute('data-end', slot.end_time);

            if (isKosong) {
                div.className = 'detail-slot-btn d-flex justify-content-between align-items-center';
                div.setAttribute('data-available', '1');
                div.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                        <input type="checkbox" class="form-check-input slot-detail-check mt-0" value="${slot.start_time}" data-end="${slot.end_time}">
                        <span class="fw-semibold text-dark small">${slot.time_text}</span>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 10px;">
                        Kosong
                    </span>
                `;
            } else if (isTerisi) {
                div.className = 'detail-slot-btn disabled bg-light border border-danger-subtle d-flex justify-content-between align-items-center opacity-75';
                div.setAttribute('data-available', '0');
                div.innerHTML = `
                    <div class="d-flex align-items-center gap-2 min-w-0 me-1">
                        <input type="checkbox" class="form-check-input mt-0" disabled>
                        <div class="d-flex flex-column text-truncate">
                            <span class="fw-semibold text-dark small text-truncate">${slot.time_text}</span>
                            <small class="text-danger fw-semibold d-flex align-items-center gap-1 text-truncate" style="font-size: 10.5px;">
                                <i class="bx bxs-user-detail"></i> ${teamName}
                            </small>
                        </div>
                    </div>
                    <span class="badge bg-danger text-white rounded-pill px-2 py-1 flex-shrink-0" style="font-size: 9.5px;">
                        Terisi
                    </span>
                `;
            } else {
                div.className = 'detail-slot-btn disabled bg-light border d-flex justify-content-between align-items-center opacity-50';
                div.setAttribute('data-available', '0');
                div.innerHTML = `
                    <div class="d-flex align-items-center gap-2">
                        <input type="checkbox" class="form-check-input mt-0" disabled>
                        <span class="fw-semibold text-muted small">${slot.time_text}</span>
                    </div>
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1 flex-shrink-0" style="font-size: 9.5px;">
                        Kadaluarsa
                    </span>
                `;
            }

            slotsContainer.appendChild(div);
        });

        attachSlotEvents();
    }

    // Booking Handlers
    window.handleGuestBooking = function () {
        const date = dateInput ? dateInput.value : '';
        const dur = selectedDuration > 0 ? selectedDuration : 1;
        let url = `{{ route('pelanggan.booking.create', $field->id) }}?date=${date}&duration=${dur}`;
        if (selectedStartTime) {
            url += `&start_time=${selectedStartTime}`;
        }
        window.requireLogin(url);
    };

    window.handleAuthBooking = function () {
        if (!selectedStartTime || selectedDuration < 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Jadwal',
                text: 'Silakan centang minimal 1 slot jam bermain yang tersedia terlebih dahulu.',
                confirmButtonColor: '#696cff'
            });
            return;
        }

        const date = dateInput ? dateInput.value : '';
        window.location.href = `{{ route('pelanggan.booking.create', $field->id) }}?date=${date}&duration=${selectedDuration}&start_time=${selectedStartTime}`;
    };

    attachDatePills();
    attachSlotEvents();
    updateSelectionDisplay();
});
</script>
@endpush
