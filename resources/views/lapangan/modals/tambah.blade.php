<div class="modal fade" id="modalCreateField" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" action="{{ route('lapangan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Lapangan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Cabang Venue <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select" required>
                            <option value="">-- Pilih Cabang --</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nama / Nomor Lapangan <span class="text-danger">*</span></label>
                        <input type="text" name="field_name" class="form-control"
                            placeholder="Contoh: Lapangan 1 (Rumput Sintetis)" value="{{ old('field_name') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Jenis Lantai <span class="text-danger">*</span></label>
                        <select name="field_type" class="form-select" required>
                            <option value="sintetis" {{ old('field_type') == 'sintetis' ? 'selected' : '' }}>Rumput
                                Sintetis</option>
                            <option value="vinyl" {{ old('field_type') == 'vinyl' ? 'selected' : '' }}>Vinyl / Karpet
                            </option>
                            <option value="interlock" {{ old('field_type') == 'interlock' ? 'selected' : '' }}>Interlock
                                Flooring</option>
                            <option value="matras" {{ old('field_type') == 'matras' ? 'selected' : '' }}>Matras</option>
                            <option value="semen" {{ old('field_type') == 'semen' ? 'selected' : '' }}>Semen / Plester
                            </option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Harga Sewa / Jam (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="price_per_hour" class="form-control" placeholder="150000"
                            min="0" value="{{ old('price_per_hour') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Foto Lapangan</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Status Lapangan <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="available"
                                {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Tersedia (Bisa
                                Disewa)</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>
                                Perawatan / Maintenance</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Lapangan</label>
                        <textarea name="description" class="form-control" rows="2"
                            placeholder="Catatan singkat tentang kondisi atau kelebihan lapangan ini...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Spesifikasi & Fitur Lapangan</label>
                        <p class="text-muted small mb-2">Tambahkan detail ukuran, perlengkapan gawang, atau pencahayaan
                            lapangan.</p>
                        <div id="create-spec-container" class="d-flex flex-column gap-2 mb-2">
                            <div class="input-group">
                                <input type="text" name="specifications[]" class="form-control"
                                    placeholder="Contoh: Ukuran Standar 25x15 Meter">
                                <button type="button" class="btn btn-outline-danger btn-remove-spec" disabled>
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-create-spec">
                            <i class="bx bx-plus me-1"></i> Tambah Baris Spesifikasi
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Lapangan</button>
            </div>
        </form>
    </div>
</div>
