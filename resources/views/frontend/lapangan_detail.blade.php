@extends('layouts.frontend')

@section('title', $field->field_name . ' | bkngftsl.')

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
                    <div class="card-header bg-white p-4 pb-0 border-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge badge-soft-primary px-3 py-1 rounded-pill fw-semibold">Reservasi Lapangan</span>
                            <span class="small text-muted"><i class="bx bx-shield-check text-success"></i> Terverifikasi</span>
                        </div>
                        <h4 class="fw-extrabold text-primary mb-1">
                            Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                            <small class="text-muted fw-normal fs-6">/ jam</small>
                        </h4>
                        <small class="text-muted d-block">{{ $field->field_name }} &bull; {{ $field->branch?->branch_name }}</small>
                    </div>

                    <div class="card-body p-4 pt-3">
                        <hr class="mt-2 mb-3">

                        <!-- Pemilih Tanggal -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <i class="bx bx-calendar me-1 text-primary"></i>Pilih Tanggal Main
                            </label>
                            <input type="date" id="bookingDateInput" class="form-control py-2 fw-semibold"
                                min="{{ $today }}" value="{{ $today }}">
                        </div>

                        <!-- Pemilih Durasi -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark mb-1">
                                <i class="bx bx-timer me-1 text-primary"></i>Durasi Bermain
                            </label>
                            <select id="bookingDurationInput" class="form-select py-2 fw-semibold">
                                <option value="1">1 Jam</option>
                                <option value="2">2 Jam</option>
                                <option value="3">3 Jam</option>
                                <option value="4">4 Jam</option>
                            </select>
                        </div>

                        <!-- Ketersediaan Jadwal Slot Waktu -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label small fw-bold text-dark mb-0">
                                    <i class="bx bx-time me-1 text-primary"></i>Jadwal Tersedia
                                </label>
                                <small id="slotLoadingText" class="text-muted d-none" style="font-size: 11px;">
                                    <span class="spinner-border spinner-border-sm me-1"></span>Memuat...
                                </small>
                            </div>

                            <!-- Container Slot Waktu -->
                            <div id="slotsContainer" class="d-flex flex-column gap-2" style="max-height: 260px; overflow-y: auto; padding-right: 4px;">
                                @forelse ($initialSlots as $slot)
                                    @php
                                        $isAvailable = $slot['status'] === 'available';
                                    @endphp
                                    <button type="button"
                                        class="btn btn-sm text-start d-flex justify-content-between align-items-center p-2 rounded-3 slot-btn {{ $isAvailable ? 'btn-outline-primary' : 'btn-light disabled border' }}"
                                        data-time="{{ $slot['start_time'] }}"
                                        data-available="{{ $isAvailable ? '1' : '0' }}"
                                        {{ !$isAvailable ? 'disabled' : '' }}>
                                        <span class="fw-semibold">{{ $slot['time_text'] }}</span>
                                        @if ($isAvailable)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 10px;">
                                                Tersedia
                                            </span>
                                        @elseif ($slot['status'] === 'booked')
                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size: 10px;">
                                                Sudah Dibooking
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted rounded-pill px-2 py-1" style="font-size: 10px;">
                                                Lewat
                                            </span>
                                        @endif
                                    </button>
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
                            <i class="bx bx-check-circle fs-3 text-primary"></i>
                        </div>

                        <!-- Action Button -->
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
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('bookingDateInput');
    const durationInput = document.getElementById('bookingDurationInput');
    const slotsContainer = document.getElementById('slotsContainer');
    const slotLoadingText = document.getElementById('slotLoadingText');
    const selectedTimeLabel = document.getElementById('selectedTimeLabel');

    let selectedStartTime = '';

    // Event listener pemilihan slot waktu
    function attachSlotClickEvents() {
        const slotButtons = slotsContainer.querySelectorAll('.slot-btn');
        slotButtons.forEach(btn => {
            if (btn.getAttribute('data-available') === '1') {
                btn.addEventListener('click', function () {
                    slotButtons.forEach(b => {
                        if (b.getAttribute('data-available') === '1') {
                            b.classList.remove('btn-primary', 'text-white');
                            b.classList.add('btn-outline-primary');
                        }
                    });

                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-primary', 'text-white');

                    selectedStartTime = this.getAttribute('data-time');
                    selectedTimeLabel.textContent = `${selectedStartTime} WITA (${durationInput.value} Jam)`;
                });
            }
        });
    }

    attachSlotClickEvents();

    // Event date change -> AJAX fetch slots
    if (dateInput) {
        dateInput.addEventListener('change', function () {
            const selectedDate = this.value;
            if (!selectedDate) return;

            if (slotLoadingText) slotLoadingText.classList.remove('d-none');
            selectedStartTime = '';
            selectedTimeLabel.textContent = 'Pilih slot di atas';

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
            });
        });
    }

    // Render slots dynamically
    function renderSlots(slots) {
        if (!slotsContainer) return;
        slotsContainer.innerHTML = '';

        if (slots.length === 0) {
            slotsContainer.innerHTML = `
                <div class="text-center py-3 text-muted small">
                    <i class="bx bx-time-five fs-3 mb-1"></i>
                    <p class="mb-0">Tidak ada jadwal operasional pada tanggal ini.</p>
                </div>
            `;
            return;
        }

        slots.forEach(slot => {
            const isAvailable = slot.status === 'available';
            let badgeHtml = '';
            if (isAvailable) {
                badgeHtml = '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 10px;">Tersedia</span>';
            } else if (slot.status === 'booked') {
                badgeHtml = '<span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1" style="font-size: 10px;">Sudah Dibooking</span>';
            } else {
                badgeHtml = '<span class="badge bg-light text-muted rounded-pill px-2 py-1" style="font-size: 10px;">Lewat</span>';
            }

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `btn btn-sm text-start d-flex justify-content-between align-items-center p-2 rounded-3 slot-btn ${isAvailable ? 'btn-outline-primary' : 'btn-light disabled border'}`;
            btn.setAttribute('data-time', slot.start_time);
            btn.setAttribute('data-available', isAvailable ? '1' : '0');
            if (!isAvailable) btn.disabled = true;

            btn.innerHTML = `
                <span class="fw-semibold">${slot.time_text}</span>
                ${badgeHtml}
            `;

            slotsContainer.appendChild(btn);
        });

        attachSlotClickEvents();
    }

    // Handle duration change
    if (durationInput) {
        durationInput.addEventListener('change', function () {
            if (selectedStartTime) {
                selectedTimeLabel.textContent = `${selectedStartTime} WITA (${this.value} Jam)`;
            }
        });
    }

    // Global action handlers
    window.handleGuestBooking = function () {
        const date = dateInput.value;
        const duration = durationInput.value;
        let url = `{{ route('pelanggan.booking.create', $field->id) }}?date=${date}&duration=${duration}`;
        if (selectedStartTime) {
            url += `&start_time=${selectedStartTime}`;
        }
        window.requireLogin(url);
    };

    window.handleAuthBooking = function () {
        if (!selectedStartTime) {
            alert('Silakan pilih salah satu slot jam yang tersedia terlebih dahulu.');
            return;
        }

        const date = dateInput.value;
        const duration = durationInput.value;
        window.location.href = `{{ route('pelanggan.booking.create', $field->id) }}?date=${date}&duration=${duration}&start_time=${selectedStartTime}`;
    };
});
</script>
@endpush

