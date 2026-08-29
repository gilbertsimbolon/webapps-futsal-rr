<div class="modal fade" id="modalWalkInBooking" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="bx bx-calendar-plus text-primary me-1"></i> Form Booking di Kasir
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Cabang Venue <span class="text-danger">*</span></label>
                            <select name="branch_id" id="walkin_branch_id" class="form-select" required>
                                <option value="">-- Pilih Cabang --</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Unit Lapangan <span class="text-danger">*</span></label>
                            <select name="field_id" id="walkin_field_id" class="form-select" required>
                                <option value="">-- Pilih Lapangan --</option>
                                @foreach ($fields as $field)
                                    <option value="{{ $field->id }}" data-branch="{{ $field->branch_id }}" data-price="{{ $field->price_per_hour }}" data-name="{{ $field->field_name }}">
                                        {{ $field->field_name }} (Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}/jam)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Nama Tim / Pemesan <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" id="walkin_customer_name" class="form-control" placeholder="Contoh: Rajawali FC" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-semibold">Tanggal Main <span class="text-danger">*</span></label>
                            <input type="date" name="booking_date" id="walkin_booking_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Pilihan Mode Jadwal -->
                        <div class="col-12 pt-2">
                            <label class="form-label fw-semibold text-dark mb-2">Pilihan Waktu Main <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-4 p-2 bg-light rounded border">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="time_mode" id="mode_slot" value="slot" checked>
                                    <label class="form-check-label fw-semibold" for="mode_slot">
                                        Jadwal
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="time_mode" id="mode_custom" value="custom">
                                    <label class="form-check-label fw-semibold text-primary" for="mode_custom">
                                        Input Jam Bebas
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Grid Slot Jadwal Tersedia -->
                        <div class="col-12" id="slot_selection_container">
                            <div class="p-3 bg-light rounded border">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-bold text-muted text-uppercase" style="font-size: 11px;">
                                        <i class="bx bx-grid-alt me-1"></i>Klik Salah Satu Slot Waktu Tersedia:
                                    </small>
                                    <span class="badge bg-label-primary" id="selected_slot_indicator">Belum Dipilih</span>
                                </div>

                                <div id="loading_slots" class="text-center py-4 text-muted d-none">
                                    <i class="bx bx-loader-circle bx-spin fs-4 d-block mb-1 text-primary"></i>
                                    <span class="small">Menghitung jadwal dinamis...</span>
                                </div>

                                <div id="available_slots_grid" class="row g-2">
                                    <div class="col-12 text-muted text-center py-3">
                                        <small>Silakan tentukan cabang dan lapangan untuk menampilkan slot waktu.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input Jam Mulai (Hanya Muncul di Mode Bebas) -->
                        <div class="col-12 col-md-6 d-none" id="start_time_container">
                            <label class="form-label fw-semibold">Jam Mulai Bermain <span class="text-danger">*</span></label>
                            <input type="text" name="start_time" id="walkin_start_time" class="form-control" placeholder="12:32" required>
                            <small class="text-muted" style="font-size: 11px;">Format 24 jam (JJ:MM). Jadwal berikutnya akan otomatis menyesuaikan.</small>
                        </div>

                        <div class="col-12 col-md-6" id="duration_container">
                            <label class="form-label fw-semibold">Durasi Bermain <span class="text-danger">*</span></label>
                            <select name="duration" id="walkin_duration" class="form-select" required>
                                <option value="1">1 Jam</option>
                                <option value="2">2 Jam</option>
                                <option value="3">3 Jam</option>
                            </select>
                        </div>

                        <!-- Pilihan Tipe Reservasi & Aturan DP 50% -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Tipe Pembayaran Reservasi <span class="text-danger">*</span></label>
                            <select name="reservation_type" id="walkin_reservation_type" class="form-select" required>
                                <option value="dp_pay" selected>Down Payment 50%</option>
                                <option value="full_pay">Lunas</option>
                            </select>
                            <small class="text-muted d-block mt-1" id="res_type_desc">
                                Membuka kasir POS untuk pembayaran uang muka 50% sebagai jaminan kunci jadwal. Sisa 50% dilunasi saat datang.
                            </small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Catatan Tambahan</label>
                            <textarea name="notes" id="walkin_notes" class="form-control" rows="2" placeholder="Catatan transaksi..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnNextStep" class="btn btn-primary px-4">
                        <span id="btnNextText">Lanjut ke Pembayaran Kasir (POS)</span> <i class="bx bx-right-arrow-alt ms-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
