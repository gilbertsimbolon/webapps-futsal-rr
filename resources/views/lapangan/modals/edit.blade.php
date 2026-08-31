@php
    $isAdmin = auth()->user()->hasRole('admin');
    $isPemilik = auth()->user()->hasRole('pemilik');

    // Route berdasarkan role pengguna
    $lapanganUpdateRoute = $isAdmin
        ? 'admin.lapangan.update'
        : 'pemilik.lapangan.update';
@endphp

<div class="modal fade" id="modalEditField{{ $field->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">

        <form
            class="modal-content"
            action="{{ route($lapanganUpdateRoute, $field->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            {{-- Header --}}
            <div class="modal-header">
                <h5 class="modal-title">
                    Edit Lapangan: {{ $field->field_name }}
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
                            class="form-select"
                            required
                        >
                            <option value="">
                                -- Pilih Cabang --
                            </option>

                            @foreach ($branches as $branch)
                                <option
                                    value="{{ $branch->id }}"
                                    {{ old('branch_id', $field->branch_id) == $branch->id ? 'selected' : '' }}
                                >
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
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
                            class="form-control"
                            value="{{ old('field_name', $field->field_name) }}"
                            placeholder="Contoh: Lapangan 1"
                            required
                        >
                    </div>

                    {{-- Jenis Lantai --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            Jenis Lantai
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="field_type"
                            class="form-select"
                            required
                        >
                            <option
                                value="sintetis"
                                {{ old('field_type', $field->field_type) == 'sintetis' ? 'selected' : '' }}
                            >
                                Rumput Sintetis
                            </option>

                            <option
                                value="vinyl"
                                {{ old('field_type', $field->field_type) == 'vinyl' ? 'selected' : '' }}
                            >
                                Vinyl / Karpet
                            </option>

                            <option
                                value="interlock"
                                {{ old('field_type', $field->field_type) == 'interlock' ? 'selected' : '' }}
                            >
                                Interlock Flooring
                            </option>

                            <option
                                value="matras"
                                {{ old('field_type', $field->field_type) == 'matras' ? 'selected' : '' }}
                            >
                                Matras
                            </option>

                            <option
                                value="semen"
                                {{ old('field_type', $field->field_type) == 'semen' ? 'selected' : '' }}
                            >
                                Semen / Plester
                            </option>
                        </select>
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
                            class="form-control"
                            value="{{ old('price_per_hour', (int) $field->price_per_hour) }}"
                            placeholder="150000"
                            min="0"
                            required
                        >
                    </div>

                    {{-- Foto --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            Foto Lapangan
                        </label>

                        @if ($field->image)
                            <div class="mb-2">
                                <img
                                    src="{{ asset('storage/' . $field->image) }}"
                                    alt="Foto {{ $field->field_name }}"
                                    class="rounded border"
                                    style="width: 70px; height: 70px; object-fit: cover;"
                                >
                            </div>
                        @endif

                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                        >

                        <small class="text-muted">
                            Kosongkan jika tidak ingin mengganti foto.
                            Maks. 2MB.
                        </small>
                    </div>

                    {{-- Status --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            Status Lapangan
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >
                            <option
                                value="available"
                                {{ old('status', $field->status) == 'available' ? 'selected' : '' }}
                            >
                                Tersedia (Bisa Disewa)
                            </option>

                            <option
                                value="maintenance"
                                {{ old('status', $field->status) == 'maintenance' ? 'selected' : '' }}
                            >
                                Perawatan / Maintenance
                            </option>

                            <option
                                value="inactive"
                                {{ old('status', $field->status) == 'inactive' ? 'selected' : '' }}
                            >
                                Nonaktif
                            </option>
                        </select>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label">
                            Deskripsi Lapangan
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="2"
                            placeholder="Catatan singkat tentang kondisi atau kelebihan lapangan ini..."
                        >{{ old('description', $field->description) }}</textarea>
                    </div>

                    {{-- Spesifikasi --}}
                    <div class="col-12">

                        <label class="form-label fw-bold">
                            Spesifikasi & Fitur Lapangan
                        </label>

                        <p class="text-muted small mb-2">
                            Tambahkan detail ukuran, perlengkapan gawang,
                            pencahayaan, atau spesifikasi lainnya.
                        </p>

                        @php
                            $fieldSpecs = is_array($field->specifications)
                                ? $field->specifications
                                : [];
                        @endphp

                        <div
                            id="edit-spec-container-{{ $field->id }}"
                            class="d-flex flex-column gap-2 mb-2"
                        >

                            @forelse ($fieldSpecs as $spec)

                                <div class="input-group">

                                    <input
                                        type="text"
                                        name="specifications[]"
                                        class="form-control"
                                        value="{{ old('specifications.' . $loop->index, $spec) }}"
                                        placeholder="Contoh: Ukuran Standar 25x15 Meter"
                                    >

                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-remove-spec"
                                    >
                                        <i class="bx bx-trash"></i>
                                    </button>

                                </div>

                            @empty

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

                            @endforelse

                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary btn-add-edit-spec"
                            data-target="edit-spec-container-{{ $field->id }}"
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
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>
</div>