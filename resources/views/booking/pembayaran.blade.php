@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran | bkngftsl.')

@section('content')
<!-- Header Halaman -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Konfirmasi & Pembayaran</h4>
        <p class="text-muted mb-0 small">Selesaikan pembayaran untuk mengamankan slot jadwal lapangan futsal Anda.</p>
    </div>
    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar Booking
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">

        <!-- Status & Banner Peringatan Waktu -->
        @if ($booking->status === 'pending' && empty($booking->payment_proof))
            <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                <i class="bx bx-time-five fs-1 me-3 text-warning"></i>
                <div class="w-100">
                    <h6 class="alert-heading fw-bold mb-1 text-dark">Slot Waktu Sedang Ditahan Khusus untuk Anda</h6>
                    <p class="mb-1 small text-muted">Selesaikan transfer dan unggah bukti pembayaran sebelum kuantum waktu berakhir agar slot tidak dialihkan ke antrean lain:</p>
                    <div class="fw-bold fs-5 text-danger mt-1" id="payment-countdown" data-deadline="{{ $booking->payment_deadline ? $booking->payment_deadline->toIso8601String() : '' }}">
                        Menghitung sisa waktu...
                    </div>
                </div>
            </div>
        @elseif ($booking->status === 'pending' && !empty($booking->payment_proof))
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                <i class="bx bx-loader-circle bx-spin fs-1 me-3 text-info"></i>
                <div>
                    <h6 class="alert-heading fw-bold mb-1 text-dark">Bukti Pembayaran Sedang Diverifikasi</h6>
                    <p class="mb-0 small text-muted">Bukti transfer Anda telah terkirim. Admin atau pengelola venue akan segera memeriksa dan mengonfirmasi status booking menjadi Lunas.</p>
                </div>
            </div>
        @elseif ($booking->status === 'paid')
            <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                <i class="bx bx-check-double fs-1 me-3 text-success"></i>
                <div>
                    <h6 class="alert-heading fw-bold mb-1 text-dark">Pembayaran Berhasil & Terkonfirmasi Lunas</h6>
                    <p class="mb-0 small text-muted">Jadwal main Anda telah resmi terkunci. Tunjukkan kode booking ini saat tiba di lokasi lapangan.</p>
                </div>
            </div>
        @elseif ($booking->status === 'cancelled')
            <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-4" role="alert">
                <i class="bx bx-x-circle fs-1 me-3 text-danger"></i>
                <div>
                    <h6 class="alert-heading fw-bold mb-1 text-dark">Pemesanan Dibatalkan / Kedaluwarsa</h6>
                    <p class="mb-0 small text-muted">Batas waktu pembayaran telah habis atau pesanan dibatalkan. Silakan lakukan pemesanan ulang.</p>
                </div>
            </div>
        @endif

        <!-- Card Rincian Reservasi -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Detail Reservasi: {{ $booking->booking_code }}</h5>
                <div>
                    @if ($booking->status === 'paid')
                        <span class="badge bg-label-success px-3 py-2 text-uppercase">Lunas</span>
                    @elseif ($booking->status === 'pending' && !empty($booking->payment_proof))
                        <span class="badge bg-label-info px-3 py-2 text-uppercase">Menunggu ACC</span>
                    @elseif ($booking->status === 'pending')
                        <span class="badge bg-label-warning px-3 py-2 text-uppercase">Menunggu Pembayaran</span>
                    @else
                        <span class="badge bg-label-danger px-3 py-2 text-uppercase">Batal</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <small class="text-muted d-block mb-1">Nama Pemesan</small>
                        <span class="fw-bold text-dark">{{ $booking->user?->name ?? 'Tamu' }}</span>
                    </div>
                    <div class="col-12 col-sm-6">
                        <small class="text-muted d-block mb-1">Cabang Venue</small>
                        <span class="fw-bold text-dark">{{ $booking->branch?->branch_name ?? '-' }}</span>
                    </div>
                    <div class="col-12 col-sm-6">
                        <small class="text-muted d-block mb-1">Unit Lapangan</small>
                        <span class="fw-bold text-dark">{{ $booking->field?->field_name ?? '-' }}</span>
                    </div>
                    <div class="col-12 col-sm-6">
                        <small class="text-muted d-block mb-1">Tanggal & Sesi Jam</small>
                        <span class="fw-bold text-primary">
                            {{ $booking->booking_date ? $booking->booking_date->format('d F Y') : '-' }}
                            ({{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WITA)
                        </span>
                    </div>
                    <div class="col-12 col-sm-6">
                        <small class="text-muted d-block mb-1">Metode Pembayaran</small>
                        <span class="badge bg-label-secondary text-uppercase">{{ $booking->payment_method }}</span>
                    </div>
                    <div class="col-12 col-sm-6">
                        <small class="text-muted d-block mb-1">Catatan</small>
                        <span class="text-dark">{{ $booking->notes ?: '-' }}</span>
                    </div>
                    <div class="col-12 border-top pt-3 mt-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-muted">Total Pembayaran</h6>
                        <h3 class="mb-0 fw-bold text-success">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulir Unggah Bukti Pembayaran -->
        @if ($booking->status === 'pending')
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold"><i class="bx bx-upload me-2 text-primary"></i>Unggah Bukti Transfer</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('booking.upload-proof', $booking->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Berkas Bukti Transfer / Struk <span class="text-danger">*</span></label>
                            <input type="file" name="payment_proof" class="form-control @error('payment_proof') is-invalid @enderror" accept="image/*" required>
                            <small class="text-muted d-block mt-1">Format berkas yang didukung: JPG, JPEG, PNG, WEBP. Ukuran maksimal 2MB.</small>
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if ($booking->payment_proof)
                            <div class="mb-3 p-3 bg-light rounded text-center">
                                <small class="text-muted d-block mb-2 fw-semibold">Bukti Pembayaran yang Terunggah Sebelumnya:</small>
                                <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Bukti Transfer" class="rounded border img-fluid" style="max-height: 200px; object-fit: contain;">
                                </a>
                                <small class="text-muted d-block mt-1">Klik gambar untuk melihat ukuran penuh</small>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bx bx-cloud-upload me-1"></i> {{ $booking->payment_proof ? 'Unggah Ulang Bukti Transfer' : 'Kirim Bukti Pembayaran' }}
                        </button>
                    </form>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const countdownEl = document.getElementById('payment-countdown');
    if (countdownEl && countdownEl.getAttribute('data-deadline')) {
        const deadline = new Date(countdownEl.getAttribute('data-deadline')).getTime();

        const timer = setInterval(function () {
            const now = new Date().getTime();
            const distance = deadline - now;

            if (distance <= 0) {
                clearInterval(timer);
                countdownEl.innerHTML = "WAKTU PEMBAYARAN TELAH HABIS!";
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                countdownEl.innerHTML = `${String(minutes).padStart(2, '0')} Menit ${String(seconds).padStart(2, '0')} Detik Tersisa`;
            }
        }, 1000);
    }
});
</script>
@endpush
