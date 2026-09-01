<div class="modal fade" id="modalDetailBooking{{ $booking->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold">Detail Reservasi: {{ $booking->booking_code }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <!-- Informasi Pemesanan -->
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded h-100 border">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Pemesanan</h6>
                            <p class="mb-1 small"><strong>Nama Pemesan:</strong> {{ $booking->user?->name ?? 'Tamu Walk-in' }}</p>
                            <p class="mb-1 small"><strong>Email:</strong> {{ $booking->user?->email ?? '-' }}</p>
                            <p class="mb-1 small"><strong>Venue:</strong> {{ $booking->branch?->branch_name ?? '-' }}</p>
                            <p class="mb-1 small"><strong>Unit Lapangan:</strong> {{ $booking->field?->field_name ?? '-' }}</p>
                            <p class="mb-1 small"><strong>Tanggal Main:</strong> {{ $booking->booking_date ? $booking->booking_date->format('d F Y') : '-' }}</p>
                            <p class="mb-1 small"><strong>Jadwal Main:</strong> {{ $startTime }} - {{ $endTime }} WITA</p>
                            <p class="mb-1 small"><strong>Metode Bayar:</strong> <span class="badge bg-label-secondary text-uppercase">{{ $booking->paymentMethod?->name ?? ($booking->payment_method ?? '-') }}</span></p>
                            <p class="mb-1 small"><strong>Status Check-in:</strong>
                                @if ($booking->check_in_at)
                                    <span class="badge bg-label-success">Hadir ({{ $booking->check_in_at->format('H:i') }} WITA)</span>
                                @else
                                    <span class="badge bg-label-warning">Belum Check-In</span>
                                @endif
                            </p>
                            <p class="mb-1 small"><strong>Catatan:</strong> {{ $booking->notes ?: '-' }}</p>
                            <hr>
                            <p class="mb-1 small"><strong>Total Sewa:</strong> <span class="text-dark fw-bold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span></p>
                            <p class="mb-1 small"><strong>DP Masuk:</strong> <span class="text-success fw-bold">Rp {{ number_format($booking->dp_amount > 0 ? $booking->dp_amount : ($booking->status === 'confirmed' ? $booking->total_amount * 0.5 : ($booking->status === 'paid' ? $booking->total_amount : 0)), 0, ',', '.') }}</span></p>
                            <p class="mb-0 small"><strong>Sisa Tagihan:</strong> <span class="text-danger fw-bold">Rp {{ number_format($remaining, 0, ',', '.') }}</span></p>
                        </div>
                    </div>

                    <!-- Bukti Pembayaran (Rata Tengah) -->
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded h-100 border d-flex flex-column">
                            <h6 class="fw-bold mb-3 border-bottom pb-2 text-start">Bukti Pembayaran</h6>

                            <div class="my-auto d-flex flex-column align-items-center justify-content-center text-center p-3">
                                @if ($booking->payment_proof)
                                    <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="d-inline-block mb-2">
                                        <img src="{{ asset('storage/' . $booking->payment_proof) }}"
                                            class="rounded border img-fluid shadow-sm"
                                            style="max-height: 180px; object-fit: contain;"
                                            alt="Bukti Transfer">
                                    </a>
                                    <small class="text-muted d-block">Klik gambar untuk melihat ukuran penuh</small>
                                @else
                                    <div class="avatar avatar-xl bg-label-secondary mb-3 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bx-image-alt fs-1 text-secondary"></i>
                                    </div>
                                    <h6 class="text-muted mb-1 fw-semibold" style="font-size: 13px;">Tidak Ada Berkas</h6>
                                    <p class="text-muted small mb-0 px-2">
                                        {{ ($booking->paymentMethod && $booking->paymentMethod->type === 'cash') || strtolower($booking->payment_method ?? '') === 'cash' ? 'Pembayaran Tunai di Lokasi (Cash)' : 'Pelanggan belum mengunggah bukti transfer.' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
