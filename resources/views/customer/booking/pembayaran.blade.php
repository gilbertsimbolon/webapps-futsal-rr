@extends('layouts.frontend')

@section('title', 'Selesaikan Pembayaran: ' . $booking->booking_code . ' | bkngftsl.')

@section('content')
<div class="py-4 py-lg-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="{{ route('landing') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pelanggan.riwayat.index') }}">Booking Saya</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $booking->booking_code }}</li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
            <div>
                <h2 class="fw-extrabold text-dark mb-1">Instruksi Pembayaran</h2>
                <p class="text-muted mb-0">Selesaikan transfer untuk mengamankan slot jadwal lapangan futsal Anda.</p>
            </div>
            <a href="{{ route('pelanggan.riwayat.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bx bx-arrow-back me-1"></i> Ke Booking Saya
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <!-- Alert Status & Countdown Timer -->
                @if ($booking->status === 'pending' && empty($booking->payment_proof))
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-warning bg-opacity-10 border-start border-4 border-warning">
                        <div class="card-body p-4 d-flex align-items-center gap-3">
                            <i class="bx bx-time-five text-warning display-5"></i>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold text-dark mb-1">Slot Waktu Sedang Ditahan Khusus Untuk Anda</h5>
                                <p class="text-muted small mb-2">Selesaikan transfer dan unggah bukti sebelum batas waktu berakhir agar slot tidak dibatalkan:</p>
                                <div class="fw-bold text-danger fs-5" id="paymentCountdown" data-deadline="{{ $booking->payment_deadline ? $booking->payment_deadline->toIso8601String() : '' }}">
                                    Menghitung batas waktu...
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($booking->status === 'pending' && !empty($booking->payment_proof))
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-info bg-opacity-10 border-start border-4 border-info">
                        <div class="card-body p-4 d-flex align-items-center gap-3">
                            <i class="bx bx-loader-circle bx-spin text-info display-5"></i>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Bukti Pembayaran Sedang Diverifikasi</h5>
                                <p class="text-muted small mb-0">Bukti transfer Anda telah berhasil diunggah. Pengelola venue akan segera memeriksa dan mengonfirmasi reservasi Anda.</p>
                            </div>
                        </div>
                    </div>
                @elseif (in_array($booking->status, ['paid', 'confirmed']))
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-success bg-opacity-10 border-start border-4 border-success">
                        <div class="card-body p-4 d-flex align-items-center gap-3">
                            <i class="bx bx-check-double text-success display-5"></i>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Pemesanan Terkonfirmasi & Berhasil</h5>
                                <p class="text-muted small mb-0">Jadwal main Anda telah resmi terkunci di sistem. Tunjukkan kode booking ini kepada kasir saat tiba di lapangan.</p>
                            </div>
                        </div>
                    </div>
                @elseif ($booking->status === 'cancelled')
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-danger bg-opacity-10 border-start border-4 border-danger">
                        <div class="card-body p-4 d-flex align-items-center gap-3">
                            <i class="bx bx-x-circle text-danger display-5"></i>
                            <div>
                                <h5 class="fw-bold text-dark mb-1">Pemesanan Telah Dibatalkan</h5>
                                <p class="text-muted small mb-0">Batas waktu pembayaran telah habis atau pesanan ini telah dibatalkan.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Card Rincian Reservasi -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white p-4 pb-0 border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-label-primary px-3 py-1 rounded-pill mb-1">Kode Booking</span>
                            <h4 class="fw-bold text-dark mb-0 font-monospace">{{ $booking->booking_code }}</h4>
                        </div>
                        <div>
                            @if ($booking->status === 'paid')
                                <span class="badge bg-success px-3 py-2 rounded-pill text-uppercase">Lunas</span>
                            @elseif ($booking->status === 'confirmed')
                                <span class="badge bg-info px-3 py-2 rounded-pill text-uppercase">DP Terbayar</span>
                            @elseif ($booking->status === 'pending' && !empty($booking->payment_proof))
                                <span class="badge bg-primary px-3 py-2 rounded-pill text-uppercase">Menunggu Verifikasi</span>
                            @elseif ($booking->status === 'pending')
                                <span class="badge bg-warning px-3 py-2 rounded-pill text-uppercase">Menunggu Bayar</span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill text-uppercase">Batal</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <hr class="mt-2 mb-4">

                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <small class="text-muted d-block" style="font-size: 11px;">Venue & Cabang</small>
                                <span class="fw-bold text-dark">{{ $booking->branch?->branch_name ?? '-' }}</span>
                                <small class="text-muted d-block">{{ $booking->branch?->address }}</small>
                            </div>

                            <div class="col-12 col-sm-6">
                                <small class="text-muted d-block" style="font-size: 11px;">Unit Lapangan</small>
                                <span class="fw-bold text-dark">{{ $booking->field?->field_name ?? '-' }}</span>
                                <small class="text-muted d-block text-uppercase">Lantai {{ $booking->field?->field_type ?? 'Futsal' }}</small>
                            </div>

                            <div class="col-12 col-sm-6">
                                <small class="text-muted d-block" style="font-size: 11px;">Tanggal & Waktu Bermain</small>
                                <span class="fw-bold text-primary">
                                    {{ $booking->booking_date ? $booking->booking_date->format('d M Y') : '-' }} &bull;
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WITA
                                </span>
                            </div>

                            <div class="col-12 col-sm-6">
                                <small class="text-muted d-block" style="font-size: 11px;">Skema Pembayaran</small>
                                <span class="badge bg-label-secondary text-uppercase">
                                    {{ $booking->payment_type === 'dp' ? 'Uang Muka (DP 50%)' : 'Pembayaran Penuh 100%' }}
                                </span>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Detail Biaya & Tagihan -->
                        <div class="p-3 bg-light rounded-3">
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Total Biaya Sewa:</span>
                                <span class="fw-semibold text-dark">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            </div>

                            @if ($booking->payment_type === 'dp')
                                <div class="d-flex justify-content-between mb-2 small">
                                    <span class="text-muted">Sisa Pelunasan di Lokasi:</span>
                                    <span class="fw-semibold text-dark">Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between pt-2 border-top">
                                <h6 class="fw-bold text-dark mb-0">Total yang Harus Dibayar:</h6>
                                <h4 class="fw-extrabold text-primary mb-0">
                                    Rp {{ number_format($booking->payment_type === 'dp' ? $booking->dp_amount : $booking->total_amount, 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Instruksi Rekening / QRIS Resmi Pemilik -->
                @if ($booking->paymentMethod)
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white p-4 pb-0 border-0">
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="bx bx-wallet text-primary me-2"></i>Instruksi Pembayaran: {{ $booking->paymentMethod->name }}
                            </h5>
                            <small class="text-muted">Gunakan rincian rekening atau QRIS resmi pemilik venue di bawah ini.</small>
                        </div>
                        <div class="card-body p-4">
                            @if ($booking->paymentMethod->type === 'bank_transfer')
                                <div class="p-4 bg-light rounded-3 mb-3">
                                    <div class="row align-items-center g-3">
                                        <div class="col-12 col-sm-8">
                                            <small class="text-muted d-block">Nomor Rekening Tujuan:</small>
                                            <div class="d-flex align-items-center gap-2">
                                                <h4 class="fw-bold text-dark font-monospace mb-0" id="accNumber">{{ $booking->paymentMethod->account_number }}</h4>
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="copyAccNumber()">
                                                    <i class="bx bx-copy me-1"></i> Salin
                                                </button>
                                            </div>
                                            <small class="text-muted d-block mt-1">Atas Nama: <strong class="text-dark">{{ $booking->paymentMethod->account_name }}</strong></small>
                                        </div>
                                        <div class="col-12 col-sm-4 text-sm-end">
                                            <span class="badge bg-primary px-3 py-2 rounded-pill fs-7">{{ $booking->paymentMethod->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($booking->paymentMethod->type === 'qris')
                                <div class="text-center p-4 bg-light rounded-3 mb-3">
                                    <h6 class="fw-bold text-dark mb-2">Scan Kode QRIS</h6>
                                    @if ($booking->paymentMethod->qr_image)
                                        <img src="{{ asset('storage/' . $booking->paymentMethod->qr_image) }}" alt="QRIS" class="img-fluid rounded border p-2 bg-white shadow-sm mb-2" style="max-height: 240px;">
                                    @else
                                        <div class="p-4 bg-white rounded border d-inline-block">
                                            <i class="bx bx-qr-scan display-4 text-muted"></i>
                                            <p class="small text-muted mb-0">Tersedia di Kasir Venue</p>
                                        </div>
                                    @endif
                                    <small class="text-muted d-block">Dapat discan menggunakan GoPay, OVO, Dana, BCA Mobile, Livin, dll.</small>
                                </div>
                            @else
                                <div class="p-3 bg-light rounded-3 mb-3">
                                    <h6 class="fw-bold text-dark mb-1">Pembayaran Tunai di Lokasi</h6>
                                    <p class="text-muted small mb-0">Silakan lakukan pembayaran langsung di meja kasir venue {{ $booking->branch?->branch_name }} paling lambat sebelum sesi main dimulai.</p>
                                </div>
                            @endif

                            @if ($booking->paymentMethod->instructions)
                                <div class="alert alert-secondary border-0 small mb-0">
                                    <strong>Petunjuk Transfer:</strong><br>
                                    {{ $booking->paymentMethod->instructions }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Card Formulir Unggah Bukti Transfer -->
                @if ($booking->status === 'pending')
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white p-4 pb-0 border-0">
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="bx bx-cloud-upload text-primary me-2"></i>Unggah Bukti Transfer / Struk
                            </h5>
                            <small class="text-muted">Kirimkan foto bukti transfer agar tim verifikasi segera mengunci pesanan Anda.</small>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('pelanggan.booking.upload-proof', $booking->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-dark">Pilih Foto Bukti Transfer <span class="text-danger">*</span></label>
                                    <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" accept="image/*" required>
                                    <small class="text-muted d-block mt-1">Format gambar: JPG, PNG, WEBP. Maksimal ukuran file 2MB.</small>
                                    @error('payment_proof')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if ($booking->payment_proof)
                                    <div class="mb-3 p-3 bg-light rounded-3 text-center">
                                        <small class="text-muted d-block mb-2 fw-semibold">Bukti Pembayaran yang Terunggah:</small>
                                        <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Bukti Transfer" class="img-fluid rounded border shadow-sm" style="max-height: 180px;">
                                        </a>
                                        <small class="text-muted d-block mt-1">Klik gambar untuk melihat ukuran asli</small>
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm">
                                    <i class="bx bx-upload me-1"></i> {{ $booking->payment_proof ? 'Unggah Ulang Bukti Pembayaran' : 'Kirim Bukti Pembayaran' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const countdownEl = document.getElementById('paymentCountdown');
    if (countdownEl && countdownEl.getAttribute('data-deadline')) {
        const deadline = new Date(countdownEl.getAttribute('data-deadline')).getTime();

        const timer = setInterval(function () {
            const now = new Date().getTime();
            const distance = deadline - now;

            if (distance <= 0) {
                clearInterval(timer);
                countdownEl.innerHTML = "WAKTU PEMBAYARAN TELAH HABIS";
                setTimeout(() => window.location.reload(), 1500);
            } else {
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                countdownEl.innerHTML = `${String(minutes).padStart(2, '0')} Menit ${String(seconds).padStart(2, '0')} Detik Tersisa`;
            }
        }, 1000);
    }

    window.copyAccNumber = function () {
        const acc = document.getElementById('accNumber');
        if (acc) {
            navigator.clipboard.writeText(acc.textContent.trim());
            alert('Nomor rekening berhasil disalin!');
        }
    };
});
</script>
@endpush

