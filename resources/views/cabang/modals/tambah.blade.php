<div class="modal fade" id="modalCreateBranch" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form class="modal-content" action="{{ route(auth()->user()->hasRole('admin') ? 'admin.cabang.store' : 'pemilik.cabang.store') }}" method="POST">
            @csrf

            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">Tambah Cabang Baru</h5>
                    <small class="text-muted">
                        Tambahkan data cabang venue futsal.
                    </small>
                </div>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>
            </div>

            <div class="modal-body">

                <div class="row g-3">

                    {{-- Nama Cabang --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label">
                            Nama Cabang
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            name="branch_name"
                            class="form-control @error('branch_name') is-invalid @enderror"
                            placeholder="Contoh: Arena Futsal Pusat"
                            value="{{ old('branch_name') }}"
                            required>

                        @error('branch_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Pemilik --}}
                    <div class="col-12 col-md-6">

                        <label class="form-label">
                            Pemilik / Penanggung Jawab
                        </label>

                        @if (auth()->user()->hasRole('admin'))

                            <select name="user_id"
                                class="form-select @error('user_id') is-invalid @enderror">

                                <option value="">
                                    -- Pilih Pemilik --
                                </option>

                                @foreach ($owners as $owner)
                                    <option value="{{ $owner->id }}"
                                        {{ old('user_id') == $owner->id ? 'selected' : '' }}>

                                        {{ $owner->name }}
                                        ({{ $owner->email }})

                                    </option>
                                @endforeach

                            </select>

                            <small class="text-muted">
                                Admin dapat menentukan pemilik cabang.
                            </small>

                            @error('user_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        @else

                            <input type="text"
                                class="form-control"
                                value="{{ auth()->user()->name }}"
                                readonly>

                            <input type="hidden"
                                name="user_id"
                                value="{{ auth()->id() }}">

                            <small class="text-muted">
                                Cabang akan otomatis menjadi milik akun Anda.
                            </small>

                        @endif

                    </div>

                    {{-- Nomor Telepon --}}
                    <div class="col-12 col-md-6">

                        <label class="form-label">
                            Nomor WhatsApp / Telp
                            <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            placeholder="08xxxxxxxxxx"
                            value="{{ old('phone') }}"
                            required>

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Status --}}
                    <div class="col-12 col-md-6">

                        <label class="form-label">
                            Status Operasional
                            <span class="text-danger">*</span>
                        </label>

                        <select name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>

                            <option value="active"
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                Aktif
                            </option>

                            <option value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                Nonaktif
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Alamat --}}
                    <div class="col-12">

                        <label class="form-label">
                            Alamat Lengkap
                            <span class="text-danger">*</span>
                        </label>

                        <textarea name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            rows="2"
                            placeholder="Masukkan alamat lengkap..."
                            required>{{ old('address') }}</textarea>

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">

                        <label class="form-label">
                            Deskripsi Cabang
                        </label>

                        <textarea name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="2"
                            placeholder="Deskripsi singkat venue...">{{ old('description') }}</textarea>

                        @error('description')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Fasilitas --}}
                    <div class="col-12">

                        <label class="form-label fw-bold">
                            Fasilitas Cabang
                        </label>

                        <p class="text-muted small mb-2">
                            Tambahkan fasilitas yang tersedia pada cabang ini.
                        </p>

                        <div id="create-facility-container"
                            class="d-flex flex-column gap-2 mb-2">

                            {{-- Input fasilitas pertama --}}
                            <div class="input-group">

                                <input type="text"
                                    name="facilities[]"
                                    class="form-control"
                                    placeholder="Contoh: Parkir Luas">

                                <button type="button"
                                    class="btn btn-outline-danger btn-remove-facility"
                                    disabled>

                                    <i class="bx bx-trash"></i>

                                </button>

                            </div>

                        </div>

                        <button type="button"
                            class="btn btn-sm btn-outline-primary"
                            id="btn-add-create-facility">

                            <i class="bx bx-plus me-1"></i>
                            Tambah Baris Fasilitas

                        </button>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal">

                    Batal

                </button>

                <button type="submit"
                    class="btn btn-primary">

                    <i class="bx bx-save me-1"></i>
                    Simpan Cabang

                </button>

            </div>

        </form>
    </div>
</div>