<div class="modal fade" id="modalGenerateSlot" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('pemilik.jadwal.generate') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Generate Slot Jam Otomatis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Sistem akan secara otomatis memecah jam buka s/d jam tutup menjadi slot
                    berdurasi 1 jam.</p>
                <div class="mb-3">
                    <label class="form-label">Pilih Lapangan Target <span class="text-danger">*</span></label>
                    <select name="field_id" class="form-select" required>
                        <option value="">-- Pilih Lapangan --</option>
                        @foreach ($fields as $field)
                            <option value="{{ $field->id }}">
                                {{ $field->branch?->branch_name }} - {{ $field->field_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Hari Berlaku <span class="text-danger">*</span></label>
                    <select name="day" class="form-select" required>
                        <option value="all">Setiap Hari (Senin - Minggu)</option>
                        <option value="monday">Senin</option>
                        <option value="tuesday">Selasa</option>
                        <option value="wednesday">Rabu</option>
                        <option value="thursday">Kamis</option>
                        <option value="friday">Jumat</option>
                        <option value="saturday">Sabtu</option>
                        <option value="sunday">Minggu</option>
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label">Jam Buka <span class="text-danger">*</span></label>
                        <input type="text" name="open_time" class="form-control" placeholder="08:00" value="08:00"
                            required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Jam Tutup <span class="text-danger">*</span></label>
                        <input type="text" name="close_time" class="form-control" placeholder="23:00" value="23:00"
                            required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Mulai Generate</button>
            </div>
        </form>
    </div>
</div>
