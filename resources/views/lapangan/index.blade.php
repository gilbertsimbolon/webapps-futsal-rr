@extends('layouts.app')

@section('title', 'Data Lapangan | bkngftsl.')

@section('content')
<!-- Header Halaman -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Data Lapangan</h4>
        <p class="text-muted mb-0 small">Kelola unit lapangan futsal, jenis lantai, dan tarif sewa per jam.</p>
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
                    <th>JENIS LANTAI</th>
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
                                <img src="{{ asset('storage/' . $field->image) }}" alt="Foto Lapangan" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="avatar avatar-md rounded bg-label-secondary d-flex align-items-center justify-content-center">
                                    <i class="bx bx-image fs-3 text-secondary"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $field->field_name }}</span>
                            @if ($field->description)
                                <br><small class="text-muted">{{ Str::limit($field->description, 35) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="fw-semibold text-primary"><i class="bx bx-building-house me-1"></i>{{ $field->branch?->branch_name ?? '-' }}</span>
                        </td>
                        <td>
                            <span class="badge bg-label-info text-capitalize">{{ $field->field_type }}</span>
                        </td>
                        <td>
                            <span class="fw-bold text-success">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            @if ($field->status === 'available')
                                <span class="badge bg-label-success">Tersedia</span>
                            @elseif ($field->status === 'maintenance')
                                <span class="badge bg-label-warning">Perawatan</span>
                            @else
                                <span class="badge bg-label-secondary">Nonaktif</span>
                            @endif
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

<!-- ================= MODAL TAMBAH LAPANGAN ================= -->
<div class="modal fade" id="modalCreateField" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content" action="{{ route('lapangan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Lapangan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
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
                <div class="mb-3">
                    <label class="form-label">Nama / Nomor Lapangan <span class="text-danger">*</span></label>
                    <input type="text" name="field_name" class="form-control" placeholder="Contoh: Lapangan A (Sintetis VIP)" value="{{ old('field_name') }}" required>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Jenis Lantai <span class="text-danger">*</span></label>
                        <select name="field_type" class="form-select" required>
                            <option value="sintetis" {{ old('field_type') == 'sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                            <option value="vinyl" {{ old('field_type') == 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                            <option value="interlock" {{ old('field_type') == 'interlock' ? 'selected' : '' }}>Interlock</option>
                            <option value="matras" {{ old('field_type') == 'matras' ? 'selected' : '' }}>Matras</option>
                            <option value="semen" {{ old('field_type') == 'semen' ? 'selected' : '' }}>Semen</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">Harga / Jam (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="price_per_hour" class="form-control" placeholder="150000" min="0" value="{{ old('price_per_hour') }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Foto Lapangan</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi / Spesifikasi</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Ukuran standar nasional, jaring gawang baru...">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Lapangan <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Tersedia (Bisa Dibooking)</option>
                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Perawatan / Maintenance</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Lapangan</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL EDIT & DELETE (DI LUAR TABEL) ================= -->
@foreach ($fields as $field)
    <!-- Modal Edit Lapangan -->
    <div class="modal fade" id="modalEditField{{ $field->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('lapangan.update', $field->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Lapangan: {{ $field->field_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cabang Venue <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-select" required>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id', $field->branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama / Nomor Lapangan <span class="text-danger">*</span></label>
                        <input type="text" name="field_name" class="form-control" value="{{ old('field_name', $field->field_name) }}" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Jenis Lantai <span class="text-danger">*</span></label>
                            <select name="field_type" class="form-select" required>
                                <option value="sintetis" {{ old('field_type', $field->field_type) == 'sintetis' ? 'selected' : '' }}>Rumput Sintetis</option>
                                <option value="vinyl" {{ old('field_type', $field->field_type) == 'vinyl' ? 'selected' : '' }}>Vinyl</option>
                                <option value="interlock" {{ old('field_type', $field->field_type) == 'interlock' ? 'selected' : '' }}>Interlock</option>
                                <option value="matras" {{ old('field_type', $field->field_type) == 'matras' ? 'selected' : '' }}>Matras</option>
                                <option value="semen" {{ old('field_type', $field->field_type) == 'semen' ? 'selected' : '' }}>Semen</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Harga / Jam (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="price_per_hour" class="form-control" value="{{ old('price_per_hour', (int)$field->price_per_hour) }}" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Lapangan</label>
                        @if ($field->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $field->image) }}" alt="Preview" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi / Spesifikasi</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $field->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Lapangan <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="available" {{ old('status', $field->status) == 'available' ? 'selected' : '' }}>Tersedia (Bisa Dibooking)</option>
                            <option value="maintenance" {{ old('status', $field->status) == 'maintenance' ? 'selected' : '' }}>Perawatan / Maintenance</option>
                            <option value="inactive" {{ old('status', $field->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
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
                        Data <strong>{{ $field->field_name }}</strong> pada cabang <strong>{{ $field->branch?->branch_name }}</strong> akan dihapus permanen.
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