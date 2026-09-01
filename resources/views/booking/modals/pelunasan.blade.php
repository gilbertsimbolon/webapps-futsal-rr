<div class="modal fade" id="modalTablePOSPayment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="formTablePOS" class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;"
            method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="modal-header bg-white border-bottom py-3 px-4">
                <div class="d-flex align-items-center">
                    <div
                        class="avatar avatar-sm bg-label-success rounded me-2 d-flex align-items-center justify-content-center">
                        <i class="bx bx-credit-card fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Kasir Pelunasan Lapangan</h5>
                        <small class="text-muted">Proses pelunasan booking: <strong class="text-primary"
                                id="table_pos_code">-</strong></small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <div class="row g-3">
                    <!-- Panel Kiri: Ringkasan Pelunasan -->
                    <div class="col-12 col-md-5">
                        <div class="card border-0 shadow-none bg-white p-3 h-100 rounded-3 border">
                            <span class="fw-bold text-muted small text-uppercase mb-3 d-block border-bottom pb-2">
                                <i class="bx bx-receipt me-1"></i> Detail Tagihan
                            </span>

                            <div class="mb-2">
                                <small class="text-muted d-block">Nama Pemesan</small>
                                <span class="fw-bold text-dark" id="table_pos_name">-</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted d-block">Unit Lapangan</small>
                                <span class="fw-bold text-dark" id="table_pos_field">-</span>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted d-block">Jadwal Main</small>
                                <span class="fw-bold text-primary" id="table_pos_schedule">-</span>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block">Status Saat Ini</small>
                                <span class="badge bg-label-warning" id="table_pos_status_badge">DP 50%</span>
                            </div>

                            <div class="mt-auto pt-3 border-top bg-light p-2 rounded text-center">
                                <small class="text-muted d-block fw-semibold text-uppercase"
                                    style="font-size: 11px;">Sisa yang Harus Dibayar</small>
                                <h3 class="fw-bold text-danger mb-0 mt-1" id="table_pos_amount">-</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Panel Kanan: Kalkulator Kasir -->
                    <div class="col-12 col-md-7">
                        <div class="card border-0 shadow-none bg-white p-3 h-100 rounded-3 border">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Metode Pembayaran <span
                                        class="text-danger">*</span></label>
                                <select name="payment_method_id" id="table_pos_method" class="form-select" required>
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    @foreach ($paymentMethods as $pm)
                                        <option value="{{ $pm->id }}" data-type="{{ $pm->type }}" {{ $pm->type === 'cash' ? 'selected' : '' }}>
                                            {{ $pm->name }} ({{ strtoupper($pm->type === 'cash' ? 'Tunai' : ($pm->type === 'qris' ? 'QRIS' : 'Transfer')) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="table_cash_section">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark">Uang Diterima dari Pelanggan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text fw-bold text-muted">Rp</span>
                                        <input type="number" id="table_cash_received"
                                            class="form-control form-control-lg fw-bold text-dark" placeholder="0"
                                            min="0">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1" style="font-size: 11px;">Nominal
                                        Cepat:</small>
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary quick-cash-btn"
                                            id="btnTableCashExact">Uang Pas</button>
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

                                <div class="p-3 rounded-3 border d-flex justify-content-between align-items-center bg-label-success"
                                    id="table_change_card">
                                    <div>
                                        <small class="text-muted d-block fw-semibold" style="font-size: 11px;">UANG
                                            KEMBALIAN</small>
                                        <h4 class="fw-bold text-success mb-0" id="table_cash_change">Rp 0</h4>
                                    </div>
                                    <i class="bx bx-wallet fs-1 text-success opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-white border-top py-3 px-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success px-4 py-2">
                    <i class="bx bx-check-double me-1"></i> Pelunasan Berhasil & LUNAS
                </button>
            </div>
        </form>
    </div>
</div>
