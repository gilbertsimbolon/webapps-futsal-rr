<div class="modal fade" id="modalEditField{{ $field->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" action="{{ route('lapangan.update', $field->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Lapangan: {{ $field->field_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Cabang Venue <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select" required>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branch_id', $field->branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nama / Nomor Lapangan <span class="text-danger">*</span></label>
                        <input type="text" name="field_name" class="form-control"
                            value="{{ old('field_name', $field->field_name) }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Jenis Lantai <span class="text-danger">*</span></label>
                        <select name="field_type" class="form-select" required>
                            <option value="sintetis"
                                {{ old('field_type', $field->field_type) == 'sintetis' ? 'selected' : '' }}>Rumput
                                Sintetis</option>
                            <option value="vinyl"
                                {{ old('field_type', $field->field_type) == 'vinyl' ? 'selected' : '' }}>Vinyl / Karpet
                            </option>
                            <option value="interlock"
                                {{ old('field_type', $field->field_type) == 'interlock' ? 'selected' : '' }}>Interlock
                                Flooring</option>
                            <option value="matras"
                                {{ old('field_type', $field->field_type) == 'matras' ? 'selected' : '' }}>Matras
                            </option>
                            <option value="semen"
                                {{ old('field_type', $field->field_type) == 'semen' ? 'selected' : '' }}>Semen /
                                Plester</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Harga Sewa / Jam (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="price_per_hour" class="form-control"
                            value="{{ old('price_per_hour', (int) $field->price_per_hour) }}" min="0" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Foto Lapangan</label>
                        @if ($field->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $field->image) }}" alt="Preview"
                                    class="rounded border" style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Status Lapangan <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="available"
                                {{ old('status', $field->status) == 'available' ? 'selected' : '' }}>Tersedia (Bisa
                                Disewa)</option>
                            <option value="maintenance"
                                {{ old('status', $field->status) == 'maintenance' ? 'selected' : '' }}>Perawatan /
                                Maintenance</option>
                            <option value="inactive"
                                {{ old('status', $field->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Lapangan</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $field->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Spesifikasi & Fitur Lapangan</label>
                        <p class="text-muted small mb-2">Tambahkan detail ukuran atau fasilitas khusus lapangan ini.</p>
                        <div id="edit-spec-container-{{ $field->id }}" class="d-flex flex-column gap-2 mb-2">
                            @php
                                $fieldSpecs = is_array($field->specifications) ? $field->specifications : [];
                            @endphp
                            @forelse ($fieldSpecs as $spec)
                                <div class="input-group">
                                    <input type="text" name="specifications[]" class="form-control"
                                        value="{{ $spec }}" placeholder="Contoh: Ukuran Standar 25x15 Meter">
                                    <button type="button" class="btn btn-outline-danger btn-remove-spec">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="input-group">
                                    <input type="text" name="specifications[]" class="form-control"
                                        placeholder="Contoh: Ukuran Standar 25x15 Meter">
                                    <button type="button" class="btn btn-outline-danger btn-remove-spec" disabled>
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-add-edit-spec"
                            data-target="edit-spec-container-{{ $field->id }}">
                            <i class="bx bx-plus me-1"></i> Tambah Baris Spesifikasi
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
