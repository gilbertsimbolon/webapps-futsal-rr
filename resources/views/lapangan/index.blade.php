@extends('layouts.app')

@section('title', 'Data Lapangan | bkngftsl.')

@section('content')
<!-- Header Halaman -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Data Lapangan</h4>
        <p class="text-muted mb-0 small">Kelola unit lapangan futsal, jenis lantai, spesifikasi, dan tarif sewa per jam.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateField">
        <i class="bx bx-plus me-1"></i> Tambah Lapangan
    </button>
</div>

<!-- Filter & Pencarian -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('lapangan.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama lapangan..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <select name="branch_id" class="form-select">
                    <option value="">-- Semua Cabang --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3">
                <select name="field_type" class="form-select">
                    <option value="">-- Semua Jenis Lantai --</option>
                    <option value="sintetis" {{ request('field_type') == 'sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                    <option value="vinyl" {{ request('field_type') == 'vinyl' ? 'selected' : '' }}>Vinyl / Karpet</option>
                    <option value="interlock" {{ request('field_type') == 'interlock' ? 'selected' : '' }}>Interlock Flooring</option>
                    <option value="matras" {{ request('field_type') == 'matras' ? 'selected' : '' }}>Matras</option>
                    <option value="semen" {{ request('field_type') == 'semen' ? 'selected' : '' }}>Semen / Plester</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bx bx-filter-alt me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'branch_id', 'field_type']))
                    <a href="{{ route('lapangan.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bx bx-reset"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Card Tabel Utama -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">NO</th>
                    <th>FOTO</th>
                    <th>NAMA LAPANGAN</th>
                    <th>CABANG VENUE</th>
                    <th>JENIS & SPESIFIKASI</th>
                    <th>HARGA / JAM</th>
                    <th>STATUS</th>
                    <th class="text-center" style="width: 120px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fields as $index => $field)
                    <tr>
                        <td>{{ $fields->firstItem() + $index }}</td>
                        <td>
                            @if ($field->image)
                                <img src="{{ asset('storage/' . $field->image) }}" alt="Foto Lapangan" class="rounded border" style="width: 52px; height: 52px; object-fit: cover;">
                            @else
                                <div class="avatar avatar-md rounded bg-label-secondary d-flex align-items-center justify-content-center">
                                    <i class="bx bx-image fs-3 text-secondary"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $field->field_name }}</span>
                            @if ($field->description)
                                <br><small class="text-muted">{{ Str::limit($field->description, 30) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold text-primary"><i class="bx bx-building-house me-1"></i>{{ $field->branch?->branch_name ?? '-' }}</span>
                        </td>
                        <td class="text-wrap" style="max-width: 260px;">
                            <span class="badge bg-label-info text-capitalize mb-1">{{ $field->field_type }}</span>
                            @if (!empty($field->specifications) && is_array($field->specifications))
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach (array_slice($field->specifications, 0, 2) as $spec)
                                        <span class="badge bg-label-secondary" style="font-size: 11px;">{{ $spec }}</span>
                                    @endforeach
                                    @if (count($field->specifications) > 2)
                                        <span class="badge bg-label-light text-muted border" style="font-size: 10px;">+{{ count($field->specifications) - 2 }}</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-success">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            <!-- Switch Toggle Status -->
                            <form id="form-toggle-field-{{ $field->id }}" action="{{ route('lapangan.toggle-status', $field->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch m-0 d-inline-block">
                                    <input class="form-check-input btn-toggle-field" type="checkbox" role="switch"
                                        data-field-name="{{ $field->field_name }}"
                                        data-current-status="{{ $field->status }}"
                                        data-form-id="form-toggle-field-{{ $field->id }}"
                                        {{ $field->status === 'available' ? 'checked' : '' }}
                                        style="cursor: pointer; width: 2.5em; height: 1.3em;">
                                </div>
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <!-- Tombol Modal Edit -->
                                <button type="button" class="btn btn-icon btn-sm btn-outline-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditField{{ $field->id }}"
                                    title="Edit Lapangan">
                                    <i class="bx bx-pencil"></i>
                                </button>

                                <!-- Tombol Modal Hapus -->
                                <button type="button" class="btn btn-icon btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteField{{ $field->id }}"
                                    title="Hapus Lapangan">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="avatar avatar-md bg-label-secondary mb-2 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-layer fs-3 text-secondary"></i>
                                </div>
                                <h6 class="text-secondary mb-1">Tidak ada data lapangan yang ditemukan.</h6>
                                <p class="text-muted small mb-0">Klik tombol Tambah Lapangan untuk mendaftarkan unit lapangan baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($fields->hasPages())
        <div class="card-footer d-flex justify-content-end pb-0 border-top bg-white">
            {{ $fields->links() }}
        </div>
    @endif
</div>

<!-- Modal Tambah Lapangan -->
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
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nama / Nomor Lapangan <span class="text-danger">*</span></label>
                        <input type="text" name="field_name" class="form-control" placeholder="Contoh: Lapangan 1 (Rumput Sintetis)" value="{{ old('field_name') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Jenis Lantai <span class="text-danger">*</span></label>
                        <select name="field_type" class="form-select" required>
                            <option value="sintetis" {{ old('field_type') == 'sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                            <option value="vinyl" {{ old('field_type') == 'vinyl' ? 'selected' : '' }}>Vinyl / Karpet</option>
                            <option value="interlock" {{ old('field_type') == 'interlock' ? 'selected' : '' }}>Interlock Flooring</option>
                            <option value="matras" {{ old('field_type') == 'matras' ? 'selected' : '' }}>Matras</option>
                            <option value="semen" {{ old('field_type') == 'semen' ? 'selected' : '' }}>Semen / Plester</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Harga Sewa / Jam (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="price_per_hour" class="form-control" placeholder="150000" min="0" value="{{ old('price_per_hour') }}" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Foto Lapangan</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Status Lapangan <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Tersedia (Bisa Disewa)</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Perawatan / Maintenance</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Lapangan</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Catatan singkat tentang kondisi atau kelebihan lapangan ini...">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Spesifikasi & Fitur Lapangan</label>
                        <p class="text-muted small mb-2">Tambahkan detail ukuran, perlengkapan gawang, atau pencahayaan lapangan.</p>
                        <div id="create-spec-container" class="d-flex flex-column gap-2 mb-2">
                            <div class="input-group">
                                <input type="text" name="specifications[]" class="form-control" placeholder="Contoh: Ukuran Standar 25x15 Meter">
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

<!-- Modal Edit & Delete (Di Luar Tabel) -->
@foreach ($fields as $field)
    <!-- Modal Edit Lapangan -->
    <div class="modal fade" id="modalEditField{{ $field->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" action="{{ route('lapangan.update', $field->id) }}" method="POST" enctype="multipart/form-data">
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
                                    <option value="{{ $branch->id }}" {{ old('branch_id', $field->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Nama / Nomor Lapangan <span class="text-danger">*</span></label>
                            <input type="text" name="field_name" class="form-control" value="{{ old('field_name', $field->field_name) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Jenis Lantai <span class="text-danger">*</span></label>
                            <select name="field_type" class="form-select" required>
                                <option value="sintetis" {{ old('field_type', $field->field_type) == 'sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                                <option value="vinyl" {{ old('field_type', $field->field_type) == 'vinyl' ? 'selected' : '' }}>Vinyl / Karpet</option>
                                <option value="interlock" {{ old('field_type', $field->field_type) == 'interlock' ? 'selected' : '' }}>Interlock Flooring</option>
                                <option value="matras" {{ old('field_type', $field->field_type) == 'matras' ? 'selected' : '' }}>Matras</option>
                                <option value="semen" {{ old('field_type', $field->field_type) == 'semen' ? 'selected' : '' }}>Semen / Plester</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Harga Sewa / Jam (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price_per_hour" class="form-control" value="{{ old('price_per_hour', (int)$field->price_per_hour) }}" min="0" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Foto Lapangan</label>
                            @if ($field->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $field->image) }}" alt="Preview" class="rounded border" style="width: 60px; height: 60px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Status Lapangan <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="available" {{ old('status', $field->status) == 'available' ? 'selected' : '' }}>Tersedia (Bisa Disewa)</option>
                                <option value="maintenance" {{ old('status', $field->status) == 'maintenance' ? 'selected' : '' }}>Perawatan / Maintenance</option>
                                <option value="inactive" {{ old('status', $field->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
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
                                        <input type="text" name="specifications[]" class="form-control" value="{{ $spec }}" placeholder="Contoh: Ukuran Standar 25x15 Meter">
                                        <button type="button" class="btn btn-outline-danger btn-remove-spec">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                @empty
                                    <div class="input-group">
                                        <input type="text" name="specifications[]" class="form-control" placeholder="Contoh: Ukuran Standar 25x15 Meter">
                                        <button type="button" class="btn btn-outline-danger btn-remove-spec" disabled>
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary btn-add-edit-spec" data-target="edit-spec-container-{{ $field->id }}">
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

    <!-- Modal Konfirmasi Hapus Lapangan -->
    <div class="modal fade" id="modalDeleteField{{ $field->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('lapangan.destroy', $field->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Lapangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bx bx-error-circle text-danger display-3 mb-2"></i>
                    <h5 class="mb-1">Yakin ingin menghapus lapangan ini?</h5>
                    <p class="text-muted mb-0">
                        Lapangan <strong>{{ $field->field_name }}</strong> di cabang <strong>{{ $field->branch?->branch_name }}</strong> beserta foto dan jadwal terkait akan dihapus secara permanen.
                    </p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Data</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Switch Toggle Status Lapangan
    document.querySelectorAll('.btn-toggle-field').forEach(checkbox => {
        checkbox.addEventListener('change', function (e) {
            e.preventDefault();
            const formId = this.getAttribute('data-form-id');
            const fieldName = this.getAttribute('data-field-name');
            const currentStatus = this.getAttribute('data-current-status');
            const newStatusIndo = (currentStatus === 'available') ? 'nonaktif' : 'tersedia';

            this.checked = (currentStatus === 'available');

            Swal.fire({
                title: 'Ubah Status Lapangan?',
                text: `Ubah status operasional ${fieldName} menjadi ${newStatusIndo}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Ya, Ubah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        });
    });

    // Tambah Baris Spesifikasi (Modal Create)
    const createSpecContainer = document.getElementById('create-spec-container');
    const btnAddCreateSpec = document.getElementById('btn-add-create-spec');

    if (btnAddCreateSpec) {
        btnAddCreateSpec.addEventListener('click', function () {
            const row = document.createElement('div');
            row.className = 'input-group';
            row.innerHTML = `
                <input type="text" name="specifications[]" class="form-control" placeholder="Contoh: Pencahayaan Lampu LED">
                <button type="button" class="btn btn-outline-danger btn-remove-spec">
                    <i class="bx bx-trash"></i>
                </button>
            `;
            createSpecContainer.appendChild(row);
            updateSpecRemoveButtons(createSpecContainer);
        });
    }

    // Tambah Baris Spesifikasi (Modal Edit)
    document.querySelectorAll('.btn-add-edit-spec').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const container = document.getElementById(targetId);
            if (container) {
                const row = document.createElement('div');
                row.className = 'input-group';
                row.innerHTML = `
                    <input type="text" name="specifications[]" class="form-control" placeholder="Contoh: Pencahayaan Lampu LED">
                    <button type="button" class="btn btn-outline-danger btn-remove-spec">
                        <i class="bx bx-trash"></i>
                    </button>
                `;
                container.appendChild(row);
                updateSpecRemoveButtons(container);
            }
        });
    });

    // Event Delegation untuk Hapus Baris Spesifikasi
    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove-spec')) {
            const btn = e.target.closest('.btn-remove-spec');
            const container = btn.closest('.d-flex');
            if (container && container.querySelectorAll('.input-group').length > 1) {
                btn.closest('.input-group').remove();
                updateSpecRemoveButtons(container);
            }
        }
    });

    function updateSpecRemoveButtons(container) {
        const inputGroups = container.querySelectorAll('.input-group');
        inputGroups.forEach((group, index) => {
            const removeBtn = group.querySelector('.btn-remove-spec');
            if (removeBtn) {
                removeBtn.disabled = (inputGroups.length === 1);
            }
        });
    }
});
</script>
@endpush