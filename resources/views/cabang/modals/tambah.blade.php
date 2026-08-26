<div class="modal fade" id="modalCreateBranch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" action="{{ route('cabang.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Cabang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nama Cabang <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" class="form-control" placeholder="Contoh: Arena Futsal Pusat" value="{{ old('branch_name') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Pemilik / Penanggung Jawab</label>
                        <select name="user_id" class="form-select">
                            <option value="">-- Tetapkan Pemilik (Opsional) --</option>
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}" {{ old('user_id') == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }} ({{ $owner->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nomor WhatsApp / Telp <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Status Operasional <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Masukkan alamat lengkap..." required>{{ old('address') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Cabang</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi singkat venue...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Fasilitas Cabang</label>
                        <p class="text-muted small mb-2">Tambahkan fasilitas yang tersedia pada cabang ini agar mudah dilihat pengguna.</p>
                        <div id="create-facility-container" class="d-flex flex-column gap-2 mb-2">
                            <!-- Input dinamis pertama -->
                            <div class="input-group">
                                <input type="text" name="facilities[]" class="form-control" placeholder="Contoh: Parkir Luas">
                                <button type="button" class="btn btn-outline-danger btn-remove-facility" disabled>
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-create-facility">
                            <i class="bx bx-plus me-1"></i> Tambah Baris Fasilitas
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Cabang</button>
            </div>
        </form>
    </div>
</div>