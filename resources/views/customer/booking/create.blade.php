@extends('layouts.frontend')

@section('title', 'Konfirmasi Booking Lapangan | bkngftsl.')

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
            <p class="text-muted mb-0">Periksa kembali detail jadwal main dan tentukan opsi pembayaran Anda.</p>
        </div>

        <form action="{{ route('pelanggan.booking.store') }}" method="POST" id="bookingForm">
            @csrf
            <input type="hidden" name="field_id" value="{{ $field->id }}">

            <div class="row g-4">
                <!-- Kolom Kiri: Form Detail Booking & Metode Pembayaran -->
                <div class="col-12 col-lg-8">
                    <!-- Card 1: Rincian Lapangan & Jadwal -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white p-4 pb-0 border-0">
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="bx bx-calendar-event text-primary me-2"></i>1. Jadwal & Durasi Bermain
                            </h5>
                            <small class="text-muted">Pilih tanggal dan jam bermain yang tersedia.</small>
                        </div>
                        <div class="card-body p-4">
                            <!-- Ringkasan Singkat Lapangan -->
                            <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-4">
                                <div class="rounded-3 overflow-hidden" style="width: 70px; height: 70px; background: #334155; flex-shrink: 0;">
                                    @if ($field->image)
                                        <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->field_name }}" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                            <i class="bx bx-football fs-3"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <span class="badge bg-primary text-uppercase" style="font-size: 10px;">{{ $field->field_type ?? 'Futsal' }}</span>
                                    <h6 class="fw-bold text-dark mb-0 mt-1">{{ $field->field_name }}</h6>
                                    <small class="text-muted"><i class="bx bx-map-pin me-1"></i>{{ $field->branch?->branch_name }}</small>
                                </div>
                                <div class="text-end d-none d-sm-block">
                                    <small class="text-muted d-block" style="font-size: 11px;">Tarif / Jam</small>
                                    <span class="fw-bold text-primary">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="row g-3">
                                <!-- Tanggal Main -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-semibold text-dark">Tanggal Main <span class="text-danger">*</span></label>
                                    <input type="date" name="booking_date" id="inputBookingDate" class="form-control py-2 fw-semibold @error('booking_date') is-invalid @enderror"
                                        min="{{ date('Y-m-d') }}" value="{{ old('booking_date', $selectedDate) }}" required>
                                    @error('booking_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Jam Mulai -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-semibold text-dark">Jam Mulai <span class="text-danger">*</span></label>
                                    <select name="start_time" id="selectStartTime" class="form-select py-2 fw-semibold @error('start_time') is-invalid @enderror" required>
                                        <option value="">-- Pilih Jam --</option>
                                        @foreach ($slots as $slot)
                                            @if ($slot['status'] === 'available')
                                                <option value="{{ $slot['start_time'] }}" {{ old('start_time', $selectedTime) === $slot['start_time'] ? 'selected' : '' }}>
                                                    {{ $slot['time_text'] }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Durasi -->
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-semibold text-dark">Durasi <span class="text-danger">*</span></label>
                                    <select name="duration" id="selectDuration" class="form-select py-2 fw-semibold @error('duration') is-invalid @enderror" required>
                                        <option value="1" {{ old('duration', $selectedDuration) == 1 ? 'selected' : '' }}>1 Jam</option>
                                        <option value="2" {{ old('duration', $selectedDuration) == 2 ? 'selected' : '' }}>2 Jam</option>
                                        <option value="3" {{ old('duration', $selectedDuration) == 3 ? 'selected' : '' }}>3 Jam</option>
                                        <option value="4" {{ old('duration', $selectedDuration) == 4 ? 'selected' : '' }}>4 Jam</option>
                                    </select>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2: Opsi Pembayaran (Lunas / DP 50%) -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white p-4 pb-0 border-0">
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="bx bx-wallet text-primary me-2"></i>2. Skema Pembayaran
                            </h5>
                            <small class="text-muted">Pilih apakah Anda ingin membayar lunas langsung atau uang muka (DP 50%).</small>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
                                    <label class="card border p-3 rounded-3 cursor-pointer h-100 payment-type-card" for="typeFull">
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
                                    <label class="card border p-3 rounded-3 cursor-pointer h-100 payment-type-card" for="typeDP">
                                        <div class="d-flex align-items-center gap-3">
                                            <input class="form-check-input mt-0" type="radio" name="payment_type" id="typeDP" value="dp" {{ old('payment_type') === 'dp' ? 'checked' : '' }}>
                                            <div>
                                                <span class="fw-bold text-dark d-block">Uang Muka (DP 50%)</span>
                                                <small class="text-muted">Amankan slot dengan DP 50%, sisa dibayar di kasir lapangan.</small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Metode Pembayaran Resmi Milik Pemilik Venue -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white p-4 pb-0 border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="fw-bold text-dark mb-1">
                                        <i class="bx bx-credit-card text-primary me-2"></i>3. Metode Pembayaran Resmi Venue
                                    </h5>
                                    <small class="text-muted">Metode transaksi resmi yang disediakan oleh pengelola venue {{ $field->branch?->branch_name }}.</small>
                                </div>
                                <span class="badge bg-label-info px-2 py-1"><i class="bx bx-check-shield me-1"></i>Aman & Terverifikasi</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @if ($paymentMethods->isEmpty())
                                <div class="alert alert-warning border-0 small">
                                    Pengelola venue belum mengonfigurasi metode pembayaran online. Silakan hubungi pengelola cabang.
                                </div>
                            @else
                                <div class="d-flex flex-column gap-3">
                                    @foreach ($paymentMethods as $index => $pm)
                                        <label class="card border p-3 rounded-3 cursor-pointer payment-method-card" for="pm_{{ $pm->id }}">
                                            <div class="d-flex align-items-start gap-3">
                                                <input class="form-check-input mt-1" type="radio" name="payment_method_id" id="pm_{{ $pm->id }}" value="{{ $pm->id }}"
                                                    {{ old('payment_method_id', $index === 0 ? $pm->id : '') == $pm->id ? 'checked' : '' }} required>
                                                
                                                <div class="flex-grow-1">
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
                                                    <img src="{{ asset('storage/' . $pm->qr_image) }}" alt="QR" style="width: 45px; height: 45px; object-fit: contain;" class="rounded border p-1 d-none d-sm-block">
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Catatan Tambahan -->
                            <div class="mt-4">
                                <label class="form-label small fw-semibold text-dark">Catatan Tambahan (Opsional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Nama tim sparring, permintaan khusus, dll.">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Ringkasan Biaya & Konfirmasi -->
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

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm">
                                <i class="bx bx-check-double me-1"></i> Konfirmasi Booking
                            </button>

                            <small class="text-muted d-block text-center mt-3" style="font-size: 11px;">
                                <i class="bx bx-info-circle me-1"></i> Anda memiliki waktu 30 menit untuk menyelesaikan pembayaran setelah konfirmasi.
                            </small>
                        </div>
                    </div>
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
    const dateInput = document.getElementById('inputBookingDate');
    const timeSelect = document.getElementById('selectStartTime');
    const durationSelect = document.getElementById('selectDuration');
    const typeFullRadio = document.getElementById('typeFull');
    const typeDPRadio = document.getElementById('typeDP');

    const summaryDateLabel = document.getElementById('summaryDateLabel');
    const summaryTimeLabel = document.getElementById('summaryTimeLabel');
    const summaryDurationText = document.getElementById('summaryDurationText');
    const summaryTotalAmount = document.getElementById('summaryTotalAmount');
    const summaryAmountDue = document.getElementById('summaryAmountDue');
    const paymentDueLabel = document.getElementById('paymentDueLabel');

    function rupiah(num) {
        return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateSummary() {
        const duration = parseInt(durationSelect.value) || 1;
        const total = pricePerHour * duration;
        const isDP = typeDPRadio && typeDPRadio.checked;
        const amountDue = isDP ? total * 0.5 : total;

        summaryDurationText.textContent = duration;
        summaryTotalAmount.textContent = rupiah(total);
        summaryAmountDue.textContent = rupiah(amountDue);

        if (isDP) {
            paymentDueLabel.textContent = 'Harus Dibayar Sekarang (DP 50%):';
        } else {
            paymentDueLabel.textContent = 'Harus Dibayar Sekarang (Lunas 100%):';
        }

        if (dateInput && dateInput.value) {
            const d = new Date(dateInput.value);
            summaryDateLabel.textContent = d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        } else {
            summaryDateLabel.textContent = '-';
        }

        if (timeSelect && timeSelect.value) {
            summaryTimeLabel.textContent = `${timeSelect.value} WITA (${duration} Jam)`;
        } else {
            summaryTimeLabel.textContent = 'Belum dipilih';
        }
    }

    if (dateInput) dateInput.addEventListener('change', updateSummary);
    if (timeSelect) timeSelect.addEventListener('change', updateSummary);
    if (durationSelect) durationSelect.addEventListener('change', updateSummary);
    if (typeFullRadio) typeFullRadio.addEventListener('change', updateSummary);
    if (typeDPRadio) typeDPRadio.addEventListener('change', updateSummary);

    // Initial calculation
    updateSummary();

    // Date change -> reload slots for this field
    if (dateInput) {
        dateInput.addEventListener('change', function () {
            const selectedDate = this.value;
            if (!selectedDate) return;

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
                    timeSelect.innerHTML = '<option value="">-- Pilih Jam --</option>';
                    res.data.forEach(slot => {
                        if (slot.status === 'available') {
                            const opt = document.createElement('option');
                            opt.value = slot.start_time;
                            opt.textContent = slot.time_text;
                            timeSelect.appendChild(opt);
                        }
                    });
                    updateSummary();
                }
            })
            .catch(err => console.error(err));
        });
    }
});
</script>
@endpush

