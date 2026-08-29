<div class="modal fade" id="modalPOSPayment" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header bg-white border-bottom py-3 px-4">
                <div class="d-flex align-items-center">
                    <div
                        class="avatar avatar-sm bg-label-primary rounded me-2 d-flex align-items-center justify-content-center">
                        <i class="bx bx-calculator fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Pembayaran Kasir (POS)</h5>
                        <small class="text-muted">Proses transaksi pembayaran reservasi lapangan</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="row g-3">
                    <!-- Panel Kiri: Ringkasan Reservasi -->
                    <div class="col-12 col-md-5">
                        <div class="card border-0 shadow-none bg-white p-3 h-100 rounded-3 border">
                            <span class="fw-bold text-muted small text-uppercase mb-3 d-block border-bottom pb-2">
                                <i class="bx bx-receipt me-1"></i> Ringkasan Sewa
                            </span>

                            <div class="mb-2">
                                <small class="text-muted d-block">Nama Pemesan</small>
                                <span class="fw-bold text-dark" id="pos_cust_name">-</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted d-block">Unit Lapangan</small>
                                <span class="fw-bold text-dark" id="pos_field_name">-</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted d-block">Jadwal Bermain</small>
                                <span class="fw-bold text-primary" id="pos_schedule">-</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted d-block">Harga Asli Sewa</small>
                                <span class="fw-semibold text-dark" id="pos_original_price">-</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Jenis Pembayaran</small>
                                <span class="badge bg-label-warning" id="pos_pay_type_badge">DP 50%</span>
                            </div>

                            <div class="mt-auto pt-3 border-top bg-light p-2 rounded text-center">
                                <small class="text-muted d-block fw-semibold text-uppercase" id="pos_charge_label"
                                    style="font-size: 11px;">Nominal DP yang Harus Dibayar (50%)</small>
                                <h3 class="fw-bold text-primary mb-0 mt-1" id="pos_total_display">Rp 0</h3>
                                <small class="text-danger d-block mt-1" id="pos_remaining_note"
                                    style="font-size: 11px;">Sisa 50% dilunasi saat main</small>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Kanan: Metode Pembayaran & Kasir Tunai -->
                    <div class="col-12 col-md-7">
                        <div class="card border-0 shadow-none bg-white p-3 h-100 rounded-3 border">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Metode Pembayaran <span
                                        class="text-danger">*</span></label>
                                <select name="payment_method" id="pos_payment_method" class="form-select" required>
                                    <option value="cash" selected>Tunai (Cash di Lokasi)</option>
                                    @foreach ($paymentMethods as $pm)
                                        <option value="{{ $pm->code ?? $pm->name }}">{{ $pm->name }}
                                            ({{ strtoupper($pm->type ?? 'Transfer') }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Bagian Perhitungan Tunai -->
                            <div id="cash_calculator_section">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark">Uang Diterima dari Pelanggan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text fw-bold text-muted">Rp</span>
                                        <input type="number" id="cash_received_input"
                                            class="form-control form-control-lg fw-bold text-dark" placeholder="0"
                                            min="0">
                                    </div>
                                </div>

                                <!-- Tombol Cepat Nominal Uang -->
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1" style="font-size: 11px;">Nominal
                                        Cepat:</small>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary quick-cash-btn"
                                            id="btnCashExact">Uang Pas</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-btn"
                                            data-val="50000">50.000</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-btn"
                                            data-val="75000">75.000</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-btn"
                                            data-val="100000">100.000</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-btn"
                                            data-val="150000">150.000</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-btn"
                                            data-val="200000">200.000</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-btn"
                                            data-val="300000">300.000</button>
                                    </div>
                                </div>

                                <!-- Kartu Kembalian Bersih -->
                                <div class="p-3 rounded-3 border d-flex justify-content-between align-items-center bg-label-success"
                                    id="change_card">
                                    <div>
                                        <small class="text-muted d-block fw-semibold" style="font-size: 11px;">UANG
                                            KEMBALIAN</small>
                                        <h4 class="fw-bold text-success mb-0" id="cash_change_display">Rp 0</h4>
                                    </div>
                                    <i class="bx bx-wallet fs-1 text-success opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="btnBackToStep1">
                    <i class="bx bx-arrow-back me-1"></i> Kembali
                </button>
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="bx bx-check-circle me-1"></i> Konfirmasi Transaksi
                </button>
            </div>
        </div>
    </div>
</div>
