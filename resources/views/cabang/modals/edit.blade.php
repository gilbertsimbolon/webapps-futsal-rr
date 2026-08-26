<div class="modal fade" id="modalEditBranch{{ $branch->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" action="{{ route('cabang.update', $branch->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Cabang: {{ $branch->branch_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nama Cabang <span class="text-danger">*</span></label>
                        <input type="text" name="branch_name" class="form-control"
                            value="{{ old('branch_name', $branch->branch_name) }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Pemilik / Penanggung Jawab</label>
                        <select name="user_id" class="form-select">
                            <option value="">-- Tetapkan Pemilik (Opsional) --</option>
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}"
                                    {{ old('user_id', $branch->user_id) == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }} ({{ $owner->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nomor WhatsApp / Telp <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone', $branch->phone) }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Status Operasional <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $branch->status) == 'active' ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="inactive"
                                {{ old('status', $branch->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" required>{{ old('address', $branch->address) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Cabang</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $branch->description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Fasilitas Cabang</label>
                        <p class="text-muted small mb-2">Tambahkan fasilitas yang tersedia pada cabang ini.</p>
                        <div id="edit-facility-container-{{ $branch->id }}" class="d-flex flex-column gap-2 mb-2">
                            @php
                                $branchFacilities = is_array($branch->facilities) ? $branch->facilities : [];
                            @endphp
                            @forelse ($branchFacilities as $facility)
                                <div class="input-group">
                                    <input type="text" name="facilities[]" class="form-control"
                                        value="{{ $facility }}" placeholder="Contoh: Parkir Luas">
                                    <button type="button" class="btn btn-outline-danger btn-remove-facility">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="input-group">
                                    <input type="text" name="facilities[]" class="form-control"
                                        placeholder="Contoh: Parkir Luas">
                                    <button type="button" class="btn btn-outline-danger btn-remove-facility" disabled>
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-add-edit-facility"
                            data-target="edit-facility-container-{{ $branch->id }}">
                            <i class="bx bx-plus me-1"></i> Tambah Baris Fasilitas
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
