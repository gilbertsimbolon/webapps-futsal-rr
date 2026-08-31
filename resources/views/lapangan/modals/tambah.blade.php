<div class="modal fade" id="modalCreateField" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form
            class="modal-content"
            action="{{ route(
                auth()->user()->hasRole('admin')
                    ? 'admin.lapangan.store'
                    : 'pemilik.lapangan.store'
            ) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title">
                    Tambah Lapangan Baru
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <div class="row g-3">

                    {{-- Cabang --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            Cabang Venue
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="branch_id"
                            class="form-select @error('branch_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                -- Pilih Cabang --
                            </option>

                            @foreach ($branches as $branch)
                                <option
                                    value="{{ $branch->id }}"
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                >
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('branch_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Nama Lapangan --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            Nama / Nomor Lapangan
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="field_name"
                            class="form-control @error('field_name') is-invalid @enderror"
                            placeholder="Contoh: Lapangan 1"
                            value="{{ old('field_name') }}"
                            required
                        >

                        @error('field_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Jenis Lantai --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            Jenis Lantai
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="field_type"
                            class="form-select @error('field_type') is-invalid @enderror"
                            required
                        >
                            <option
                                value="sintetis"
                                {{ old('field_type', 'sintetis') === 'sintetis' ? 'selected' : '' }}
                            >
                                Rumput Sintetis
                            </option>

                            <option
                                value="vinyl"
                                {{ old('field_type') === 'vinyl' ? 'selected' : '' }}
                            >
                                Vinyl / Karpet
                            </option>

                            <option
                                value="interlock"
                                {{ old('field_type') === 'interlock' ? 'selected' : '' }}
                            >
                                Interlock Flooring
                            </option>

                            <option
                                value="matras"
                                {{ old('field_type') === 'matras' ? 'selected' : '' }}
                            >
                                Matras
                            </option>

                            <option
                                value="semen"
                                {{ old('field_type') === 'semen' ? 'selected' : '' }}
                            >
                                Semen / Plester
                            </option>
                        </select>

                        @error('field_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Harga --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            Harga Sewa / Jam (Rp)
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="number"
                            name="price_per_hour"
                            class="form-control @error('price_per_hour') is-invalid @enderror"
                            placeholder="150000"
                            min="0"
                            value="{{ old('price_per_hour') }}"
                            required
                        >

                        @error('price_per_hour')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            Foto Lapangan
                        </label>

                        <input
                            type="file"
                            name="image"
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                        >

                        <small class="text-muted">
                            Format: JPG, PNG, WEBP. Maksimal 2MB.
                        </small>

                        @error('image')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            Status Lapangan
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required
                        >
                            <option
                                value="available"
                                {{ old('status', 'available') === 'available' ? 'selected' : '' }}
                            >
                                Tersedia (Bisa Disewa)
                            </option>

                            <option
                                value="maintenance"
                                {{ old('status') === 'maintenance' ? 'selected' : '' }}
                            >
                                Perawatan / Maintenance
                            </option>

                            <option
                                value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}
                            >
                                Nonaktif
                            </option>
                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label">
                            Deskripsi Lapangan
                        </label>

                        <textarea
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="2"
                            placeholder="Catatan singkat tentang kondisi atau kelebihan lapangan ini..."
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Spesifikasi --}}
                    <div class="col-12">
                        <label class="form-label fw-bold">
                            Spesifikasi & Fitur Lapangan
                        </label>

                        <p class="text-muted small mb-2">
                            Tambahkan detail ukuran, perlengkapan gawang,
                            pencahayaan, atau fasilitas khusus lapangan.
                        </p>

                        <div
                            id="create-spec-container"
                            class="d-flex flex-column gap-2 mb-2"
                        >
                            <div class="input-group">
                                <input
                                    type="text"
                                    name="specifications[]"
                                    class="form-control"
                                    placeholder="Contoh: Ukuran Standar 25x15 Meter"
                                >

                                <button
                                    type="button"
                                    class="btn btn-outline-danger btn-remove-spec"
                                    disabled
                                >
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary"
                            id="btn-add-create-spec"
                        >
                            <i class="bx bx-plus me-1"></i>
                            Tambah Baris Spesifikasi
                        </button>
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bx bx-save me-1"></i>
                    Simpan Lapangan
                </button>
            </div>

        </form>
    </div>
</div>