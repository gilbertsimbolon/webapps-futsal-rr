@extends('layouts.app')

@section('title', 'Data Booking Lapangan | bkngftsl.')

@section('content')
<style>
    .slot-card {
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        border: 1.5px solid #e2e8f0;
        background-color: #ffffff;
        border-radius: 8px;
    }
    .slot-card:hover:not(.disabled) {
        border-color: #696cff;
        background-color: #f8f9ff;
        transform: translateY(-1px);
    }
    .slot-card.selected {
        border-color: #696cff !important;
        background-color: #696cff !important;
        color: #ffffff !important;
        box-shadow: 0 3px 10px rgba(105, 108, 255, 0.3);
    }
    .slot-card.selected .slot-time {
        color: #ffffff !important;
    }
    .slot-card.selected .slot-sub {
        color: rgba(255, 255, 255, 0.85) !important;
    }
    .slot-card.disabled {
        cursor: not-allowed;
        background-color: #f1f5f9;
        border-color: #e2e8f0;
        opacity: 0.6;
    }
    .quick-cash-btn {
        font-size: 11px;
        padding: 4px 8px;
    }
</style>

<!-- Header Halaman -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Data Booking Masuk</h4>
        <p class="text-muted mb-0 small">Pantau seluruh riwayat transaksi sewa, verifikasi DP 50%, pelunasan POS, dan ketersediaan lapangan.</p>
    </div>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalWalkInBooking">
        Kasir
    </button>
</div>

<!-- Filter & Pencarian -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('bookings.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari kode / nama..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <select name="branch_id" class="form-select">
                    <option value="">-- Semua Cabang --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" title="Filter Tanggal Main">
            </div>
            <div class="col-12 col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Status Booking --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu Bayar)</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>DP 50% (Terkonfirmasi)</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid (Lunas 100%)</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled (Batal / Hangus)</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bx bx-filter-alt me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'branch_id', 'date', 'status']))
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bx bx-reset"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Card Tabel Booking -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">NO</th>
                    <th>KODE & PEMESAN</th>
                    <th>VENUE & LAPANGAN</th>
                    <th>JADWAL MAIN</th>
                    <th>METODE BAYAR</th>
                    <th>RINCIAN PEMBAYARAN</th>
                    <th>STATUS & KEHADIRAN</th>
                    <th class="text-center" style="width: 170px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $index => $booking)
                    @php
                        $startTime = $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : ($booking->schedule ? \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') : '-');
                        $endTime = $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : ($booking->schedule ? \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') : '-');
                        $remaining = $booking->remaining_amount > 0 ? $booking->remaining_amount : ($booking->status === 'confirmed' ? $booking->total_amount * 0.5 : ($booking->status === 'paid' ? 0 : $booking->total_amount));
                    @endphp
                    <tr>
                        <td>{{ $bookings->firstItem() + $index }}</td>
                        <td>
                            <span class="fw-bold text-primary">{{ $booking->booking_code }}</span>
                            <br><span class="fw-semibold text-dark">{{ $booking->user?->name ?? 'Tamu Walk-in' }}</span>
                            @if ($booking->user?->email)
                                <br><small class="text-muted">{{ $booking->user->email }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $booking->field?->field_name ?? '-' }}</span>
                            <br><small class="text-muted"><i class="bx bx-building-house me-1"></i>{{ $booking->branch?->branch_name ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark"><i class="bx bx-calendar me-1"></i>{{ $booking->booking_date ? $booking->booking_date->format('d M Y') : '-' }}</span>
                            <br><span class="badge bg-label-info mt-1"><i class="bx bx-time-five me-1"></i>{{ $startTime }} - {{ $endTime }} WITA</span>
                        </td>
                        <td>
                            @php
                                $method = strtolower($booking->payment_method ?? '');
                            @endphp
                            @if ($method === 'cash' || $method === 'tunai')
                                <span class="badge bg-label-success text-uppercase"><i class="bx bx-money me-1"></i>Tunai (Cash)</span>
                            @elseif ($method === 'qris')
                                <span class="badge bg-label-primary text-uppercase"><i class="bx bx-qr-scan me-1"></i>QRIS</span>
                            @elseif (str_contains($method, 'bank') || str_contains($method, 'transfer'))
                                <span class="badge bg-label-info text-uppercase"><i class="bx bx-credit-card me-1"></i>Transfer Bank</span>
                            @else
                                <span class="badge bg-label-secondary text-uppercase">{{ $booking->payment_method ?? '-' }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark">Total: Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            @if ($booking->status === 'confirmed')
                                <br><small class="text-primary fw-semibold">DP: Rp {{ number_format($booking->dp_amount > 0 ? $booking->dp_amount : $booking->total_amount * 0.5, 0, ',', '.') }}</small>
                                <br><small class="text-danger">Sisa: Rp {{ number_format($remaining, 0, ',', '.') }}</small>
                            @elseif ($booking->status === 'paid' || $booking->status === 'completed')
                                <br><small class="text-success fw-bold"><i class="bx bx-check me-1"></i>Lunas Penuh</small>
                            @else
                                <br><small class="text-muted">DP 50%: Rp {{ number_format($booking->total_amount * 0.5, 0, ',', '.') }}</small>
                            @endif
                        </td>
                        <td>
                            @if ($booking->status === 'paid')
                                @if ($booking->check_in_at)
                                    <span class="badge bg-label-success"><i class="bx bx-check-double me-1"></i>Sedang Main (Hadir)</span>
                                @else
                                    <span class="badge bg-label-primary"><i class="bx bx-check me-1"></i>Lunas 100%</span>
                                @endif
                            @elseif ($booking->status === 'confirmed')
                                @if ($booking->check_in_at)
                                    <span class="badge bg-label-warning"><i class="bx bx-time-five me-1"></i>Main (Belum Lunas 50%)</span>
                                @else
                                    <span class="badge bg-label-info"><i class="bx bx-wallet me-1"></i>DP 50% Masuk</span>
                                @endif
                            @elseif ($booking->status === 'completed')
                                <span class="badge bg-label-info"><i class="bx bx-flag me-1"></i>Selesai Main</span>
                            @elseif ($booking->status === 'pending')
                                @if ($booking->payment_proof)
                                    <span class="badge bg-label-warning"><i class="bx bx-loader-alt me-1"></i>Menunggu Verifikasi DP</span>
                                @else
                                    <span class="badge bg-label-secondary"><i class="bx bx-time me-1"></i>Menunggu Bayar DP</span>
                                @endif
                            @else
                                <span class="badge bg-label-danger"><i class="bx bx-x me-1"></i>Batal / Hangus</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <!-- Tombol Check-In Pemain di Lapangan -->
                                @if (in_array($booking->status, ['paid', 'confirmed']) && empty($booking->check_in_at))
                                    <form action="{{ route('bookings.check-in', $booking->id) }}" method="POST" class="d-inline m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-icon btn-sm btn-success" title="Check-In Pemain (Mulai Main)">
                                            <i class="bx bx-user-check"></i>
                                        </button>
                                    </form>
                                @endif

                                <!-- Tombol Buka POS Kasir: Hanya untuk yang BELUM LUNAS (Pending / DP 50%) -->
                                @if (in_array($booking->status, ['pending', 'confirmed']))
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-success btn-open-pay-modal"
                                        data-id="{{ $booking->id }}"
                                        data-code="{{ $booking->booking_code }}"
                                        data-name="{{ $booking->user?->name ?? 'Tamu Walk-in' }}"
                                        data-field="{{ $booking->field?->field_name ?? '-' }}"
                                        data-date="{{ $booking->booking_date ? $booking->booking_date->format('d M Y') : '-' }}"
                                        data-time="{{ $startTime }} - {{ $endTime }} WITA"
                                        data-status="{{ $booking->status }}"
                                        data-remaining="{{ $remaining }}"
                                        title="{{ $booking->status === 'confirmed' ? 'Pelunasan Sisa 50% di Kasir' : 'Bayar Kasir POS' }}">
                                        <i class="bx bx-calculator"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalStatusBooking{{ $booking->id }}"
                                        title="Ubah Status">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                @endif

                                <!-- Tombol Detail & Bukti Transfer -->
                                <button type="button" class="btn btn-icon btn-sm btn-outline-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetailBooking{{ $booking->id }}"
                                    title="Lihat Detail">
                                    <i class="bx bx-show"></i>
                                </button>

                                <!-- Tombol Hapus Booking -->
                                <button type="button" class="btn btn-icon btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteBooking{{ $booking->id }}"
                                    title="Hapus Data">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="avatar avatar-md bg-label-secondary mb-2 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-calendar-x fs-3 text-secondary"></i>
                                </div>
                                <h6 class="text-secondary mb-1">Tidak ada data booking yang ditemukan.</h6>
                                <p class="text-muted small mb-0">Belum ada transaksi sewa lapangan pada filter yang dipilih.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($bookings->hasPages())
        <div class="card-footer d-flex justify-content-end pb-0 border-top bg-white">
            {{ $bookings->links() }}
        </div>
    @endif
</div>

<!-- Form Transaksi Walk-in / Kasir -->
<form id="formWalkIn" action="{{ route('bookings.store-walkin') }}" method="POST">
    @csrf

    <!-- Modal 1: Pemilihan Jadwal & Identitas Pemesan -->
    @include('booking.modals.form')

    <!-- Modal 2: Layar Pembayaran Kasir Walk-in (POS) -->
    @include('booking.modals.kasir')
</form>

<!-- Modal Kasir POS untuk Pelunasan Sisa DP 50% atau Pembayaran Baru dari Tabel -->
@include('booking.modals.pelunasan')

<!-- Modal Modals Detail & Hapus -->
@foreach ($bookings as $booking)
    @php
        $startTime = $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:i') : ($booking->schedule ? \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') : '-');
        $endTime = $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:i') : ($booking->schedule ? \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') : '-');
        $remaining = $booking->remaining_amount > 0 ? $booking->remaining_amount : ($booking->status === 'confirmed' ? $booking->total_amount * 0.5 : 0);
    @endphp

    <!-- Modal Detail -->
    @include('booking.modals.detail')

    <!-- Modal Ubah Status Booking -->
    @include('booking.modals.status_booking')

    <!-- Modal Konfirmasi Hapus Booking -->
    @include('booking.modals.hapus')
@endforeach
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formWalkIn = document.getElementById('formWalkIn');
    const branchSelect = document.getElementById('walkin_branch_id');
    const fieldSelect = document.getElementById('walkin_field_id');
    const dateInput = document.getElementById('walkin_booking_date');
    const customerNameInput = document.getElementById('walkin_customer_name');
    const durationSelect = document.getElementById('walkin_duration');
    const resTypeSelect = document.getElementById('walkin_reservation_type');
    const resTypeDesc = document.getElementById('res_type_desc');
    const btnNextStep = document.getElementById('btnNextStep');
    const btnNextText = document.getElementById('btnNextText');

    const startTimeInput = document.getElementById('walkin_start_time');
    const startTimeContainer = document.getElementById('start_time_container');
    const slotSelectionContainer = document.getElementById('slot_selection_container');
    const slotsGrid = document.getElementById('available_slots_grid');
    const loadingSlots = document.getElementById('loading_slots');
    const modeSlot = document.getElementById('mode_slot');
    const modeCustom = document.getElementById('mode_custom');
    const selectedSlotIndicator = document.getElementById('selected_slot_indicator');

    const modalStep1 = new bootstrap.Modal(document.getElementById('modalWalkInBooking'));
    const modalPOS = new bootstrap.Modal(document.getElementById('modalPOSPayment'));
    const btnBackToStep1 = document.getElementById('btnBackToStep1');
    const posCustName = document.getElementById('pos_cust_name');
    const posFieldName = document.getElementById('pos_field_name');
    const posSchedule = document.getElementById('pos_schedule');
    const posOriginalPrice = document.getElementById('pos_original_price');
    const posPayTypeBadge = document.getElementById('pos_pay_type_badge');
    const posChargeLabel = document.getElementById('pos_charge_label');
    const posTotalDisplay = document.getElementById('pos_total_display');
    const posRemainingNote = document.getElementById('pos_remaining_note');
    const posPaymentMethod = document.getElementById('pos_payment_method');
    const cashCalcSection = document.getElementById('cash_calculator_section');
    const cashReceivedInput = document.getElementById('cash_received_input');
    const cashChangeDisplay = document.getElementById('cash_change_display');
    const btnCashExact = document.getElementById('btnCashExact');

    // Modal Pelunasan POS dari Tombol di Baris Tabel
    const modalTablePOS = new bootstrap.Modal(document.getElementById('modalTablePOSPayment'));
    const formTablePOS = document.getElementById('formTablePOS');
    const tablePosCode = document.getElementById('table_pos_code');
    const tablePosName = document.getElementById('table_pos_name');
    const tablePosField = document.getElementById('table_pos_field');
    const tablePosSchedule = document.getElementById('table_pos_schedule');
    const tablePosStatusBadge = document.getElementById('table_pos_status_badge');
    const tablePosAmount = document.getElementById('table_pos_amount');
    const tablePosMethod = document.getElementById('table_pos_method');
    const tableCashSection = document.getElementById('table_cash_section');
    const tableCashReceived = document.getElementById('table_cash_received');
    const tableCashChange = document.getElementById('table_cash_change');
    const btnTableCashExact = document.getElementById('btnTableCashExact');
    let currentTableAmount = 0;

    let calculatedFullTotal = 0;
    let calculatedCharge = 0;

    if (branchSelect && fieldSelect) {
        branchSelect.addEventListener('change', function () {
            const selectedBranch = this.value;
            Array.from(fieldSelect.options).forEach(opt => {
                if (!opt.value) return;
                const branch = opt.getAttribute('data-branch');
                opt.style.display = (!selectedBranch || branch === selectedBranch) ? 'block' : 'none';
            });
            fieldSelect.value = '';
            fetchDynamicSlots();
        });
    }

    function toggleScheduleMode() {
        if (modeCustom.checked) {
            slotSelectionContainer.classList.add('d-none');
            startTimeContainer.classList.remove('d-none');
            startTimeInput.readOnly = false;
        } else {
            slotSelectionContainer.classList.remove('d-none');
            startTimeContainer.classList.add('d-none');
            startTimeInput.readOnly = true;
            fetchDynamicSlots();
        }
    }

    modeSlot.addEventListener('change', toggleScheduleMode);
    modeCustom.addEventListener('change', toggleScheduleMode);

    resTypeSelect.addEventListener('change', function() {
        if (this.value === 'hold_booking') {
            btnNextText.textContent = "Simpan Booking (Hold 15 Mnt)";
            resTypeDesc.textContent = "Menyimpan transaksi status pending & mengaktifkan Time Quantum 15 menit tanpa bayar langsung.";
        } else if (this.value === 'dp_pay') {
            btnNextText.textContent = "Lanjut ke Pembayaran DP 50% (POS)";
            resTypeDesc.textContent = "Membuka kasir POS untuk pembayaran uang muka 50% sebagai jaminan kunci jadwal. Sisa 50% dilunasi saat datang.";
        } else {
            btnNextText.textContent = "Lanjut ke Pembayaran Lunas 100% (POS)";
            resTypeDesc.textContent = "Membuka kasir POS untuk pembayaran lunas penuh 100% secara langsung.";
        }
    });

    function fetchDynamicSlots() {
        const fieldId = fieldSelect.value;
        const date = dateInput.value;

        if (!fieldId || !date) {
            slotsGrid.innerHTML = `<div class="col-12 text-muted text-center py-3"><small>Silakan tentukan cabang dan lapangan untuk menampilkan slot waktu.</small></div>`;
            selectedSlotIndicator.innerHTML = "Belum Dipilih";
            selectedSlotIndicator.className = "badge bg-label-primary";
            return;
        }

        loadingSlots.classList.remove('d-none');
        slotsGrid.innerHTML = '';

        fetch('{{ route("bookings.available-slots") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ field_id: fieldId, date: date })
        })
        .then(response => response.json())
        .then(res => {
            loadingSlots.classList.add('d-none');
            if (res.status === 'success' && res.data && res.data.length > 0) {
                let html = '';
                let hasAvailable = false;

                res.data.forEach(slot => {
                    if (slot.is_booked) {
                        html += `
                            <div class="col-6 col-sm-4 col-md-3">
                                <div class="slot-card disabled p-2 text-center">
                                    <span class="slot-time fw-semibold d-block text-muted" style="font-size: 13px;">${slot.time_text}</span>
                                    <small class="slot-sub text-danger" style="font-size: 10px;">Terisi / Lewat</small>
                                </div>
                            </div>`;
                    } else {
                        hasAvailable = true;
                        html += `
                            <div class="col-6 col-sm-4 col-md-3">
                                <div class="slot-card p-2 text-center" data-start="${slot.start_time}" data-label="${slot.time_text}">
                                    <span class="slot-time fw-bold d-block text-dark" style="font-size: 13px;">${slot.time_text}</span>
                                    <small class="slot-sub text-success" style="font-size: 10px;">Tersedia</small>
                                </div>
                            </div>`;
                    }
                });

                slotsGrid.innerHTML = html;

                document.querySelectorAll('.slot-card:not(.disabled)').forEach(card => {
                    card.addEventListener('click', function () {
                        document.querySelectorAll('.slot-card').forEach(c => c.classList.remove('selected'));
                        this.classList.add('selected');
                        const startTime = this.getAttribute('data-start');
                        const labelTime = this.getAttribute('data-label');

                        startTimeInput.value = startTime;
                        selectedSlotIndicator.innerHTML = `<i class="bx bx-check me-1"></i>${labelTime}`;
                        selectedSlotIndicator.className = "badge bg-success";
                    });
                });

                if (!hasAvailable) {
                    selectedSlotIndicator.innerHTML = "Penuh";
                    selectedSlotIndicator.className = "badge bg-label-danger";
                }
            } else {
                slotsGrid.innerHTML = `<div class="col-12 text-muted text-center py-3"><small>Tidak ada jadwal operasional aktif pada tanggal ini.</small></div>`;
            }
        })
        .catch(err => {
            loadingSlots.classList.add('d-none');
            slotsGrid.innerHTML = `<div class="col-12 text-danger text-center py-3"><small>Gagal memuat slot jadwal.</small></div>`;
        });
    }

    btnNextStep.addEventListener('click', function() {
        if (!fieldSelect.value || !customerNameInput.value || !dateInput.value || !startTimeInput.value) {
            alert('Mohon lengkapi semua kolom bertanda bintang (*) dan tentukan jam mulai!');
            return;
        }

        const resType = resTypeSelect.value;

        if (resType === 'hold_booking') {
            formWalkIn.submit();
        } else {
            const selectedOpt = fieldSelect.options[fieldSelect.selectedIndex];
            const pricePerHour = parseFloat(selectedOpt.getAttribute('data-price') || 0);
            const duration = parseInt(durationSelect.value || 1);
            calculatedFullTotal = pricePerHour * duration;

            if (resType === 'dp_pay') {
                calculatedCharge = calculatedFullTotal * 0.5;
                posPayTypeBadge.textContent = "DP 50% (Uang Muka)";
                posPayTypeBadge.className = "badge bg-label-warning";
                posChargeLabel.textContent = "Nominal DP yang Harus Dibayar (50%)";
                posRemainingNote.textContent = "Sisa 50% (Rp " + calculatedCharge.toLocaleString('id-ID') + ") dilunasi saat main";
                posRemainingNote.classList.remove('d-none');
            } else {
                calculatedCharge = calculatedFullTotal;
                posPayTypeBadge.textContent = "Lunas Penuh (100%)";
                posPayTypeBadge.className = "badge bg-label-success";
                posChargeLabel.textContent = "Total Pembayaran Lunas (100%)";
                posRemainingNote.classList.add('d-none');
            }

            posCustName.textContent = customerNameInput.value;
            posFieldName.textContent = selectedOpt.getAttribute('data-name');
            posSchedule.textContent = `${dateInput.value} (${startTimeInput.value} WITA)`;
            posOriginalPrice.textContent = 'Rp ' + calculatedFullTotal.toLocaleString('id-ID');
            posTotalDisplay.textContent = 'Rp ' + calculatedCharge.toLocaleString('id-ID');

            cashReceivedInput.value = '';
            cashChangeDisplay.textContent = 'Rp 0';
            document.getElementById('change_card').className = 'p-3 rounded-3 border d-flex justify-content-between align-items-center bg-label-success';

            modalStep1.hide();
            setTimeout(() => {
                modalPOS.show();
            }, 300);
        }
    });

    btnBackToStep1.addEventListener('click', function() {
        modalPOS.hide();
        setTimeout(() => {
            modalStep1.show();
        }, 300);
    });

    posPaymentMethod.addEventListener('change', function() {
        if (this.value === 'cash') {
            cashCalcSection.classList.remove('d-none');
        } else {
            cashCalcSection.classList.add('d-none');
        }
    });

    cashReceivedInput.addEventListener('input', calculateChange);

    function calculateChange() {
        const received = parseFloat(cashReceivedInput.value || 0);
        const change = received - calculatedCharge;
        const changeCard = document.getElementById('change_card');

        if (change >= 0) {
            cashChangeDisplay.textContent = 'Rp ' + change.toLocaleString('id-ID');
            cashChangeDisplay.className = 'fw-bold text-success mb-0';
            changeCard.className = 'p-3 rounded-3 border d-flex justify-content-between align-items-center bg-label-success';
        } else {
            cashChangeDisplay.textContent = 'Kurang Rp ' + Math.abs(change).toLocaleString('id-ID');
            cashChangeDisplay.className = 'fw-bold text-danger mb-0';
            changeCard.className = 'p-3 rounded-3 border d-flex justify-content-between align-items-center bg-label-danger';
        }
    }

    btnCashExact.addEventListener('click', function() {
        cashReceivedInput.value = calculatedCharge;
        calculateChange();
    });

    document.querySelectorAll('#modalPOSPayment .quick-cash-btn[data-val]').forEach(btn => {
        btn.addEventListener('click', function() {
            const val = parseFloat(this.getAttribute('data-val'));
            cashReceivedInput.value = val;
            calculateChange();
        });
    });

    // Event Klik Tombol POS di Baris Tabel untuk Pelunasan
    document.querySelectorAll('.btn-open-pay-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            const bookingId = this.getAttribute('data-id');
            const bookingCode = this.getAttribute('data-code');
            const customerName = this.getAttribute('data-name');
            const fieldName = this.getAttribute('data-field');
            const bookingSchedule = `${this.getAttribute('data-date')} (${this.getAttribute('data-time')})`;
            const bookingStatus = this.getAttribute('data-status');
            currentTableAmount = parseFloat(this.getAttribute('data-remaining') || 0);

            formTablePOS.action = `/bookings/${bookingId}/pay`;
            tablePosCode.textContent = bookingCode;
            tablePosName.textContent = customerName;
            tablePosField.textContent = fieldName;
            tablePosSchedule.textContent = bookingSchedule;
            tablePosAmount.textContent = 'Rp ' + currentTableAmount.toLocaleString('id-ID');

            if (bookingStatus === 'confirmed') {
                tablePosStatusBadge.textContent = "Pelunasan Sisa DP 50%";
                tablePosStatusBadge.className = "badge bg-label-warning";
            } else {
                tablePosStatusBadge.textContent = "Pembayaran Penuh 100%";
                tablePosStatusBadge.className = "badge bg-label-primary";
            }

            tableCashReceived.value = '';
            tableCashChange.textContent = 'Rp 0';
            document.getElementById('table_change_card').className = 'p-3 rounded-3 border d-flex justify-content-between align-items-center bg-label-success';

            modalTablePOS.show();
        });
    });

    tablePosMethod.addEventListener('change', function() {
        if (this.value === 'cash') {
            tableCashSection.classList.remove('d-none');
        } else {
            tableCashSection.classList.add('d-none');
        }
    });

    tableCashReceived.addEventListener('input', calculateTableChange);

    function calculateTableChange() {
        const received = parseFloat(tableCashReceived.value || 0);
        const change = received - currentTableAmount;
        const changeCard = document.getElementById('table_change_card');

        if (change >= 0) {
            tableCashChange.textContent = 'Rp ' + change.toLocaleString('id-ID');
            tableCashChange.className = 'fw-bold text-success mb-0';
            changeCard.className = 'p-3 rounded-3 border d-flex justify-content-between align-items-center bg-label-success';
        } else {
            tableCashChange.textContent = 'Kurang Rp ' + Math.abs(change).toLocaleString('id-ID');
            tableCashChange.className = 'fw-bold text-danger mb-0';
            changeCard.className = 'p-3 rounded-3 border d-flex justify-content-between align-items-center bg-label-danger';
        }
    }

    btnTableCashExact.addEventListener('click', function() {
        tableCashReceived.value = currentTableAmount;
        calculateTableChange();
    });

    document.querySelectorAll('#modalTablePOSPayment .quick-cash-btn[data-val]').forEach(btn => {
        btn.addEventListener('click', function() {
            const val = parseFloat(this.getAttribute('data-val'));
            tableCashReceived.value = val;
            calculateTableChange();
        });
    });

    fieldSelect.addEventListener('change', fetchDynamicSlots);
    dateInput.addEventListener('change', fetchDynamicSlots);
    toggleScheduleMode();
});
</script>
@endpush
