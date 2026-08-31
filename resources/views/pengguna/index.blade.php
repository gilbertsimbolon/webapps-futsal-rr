@extends('layouts.app')

@section('title', 'Manajemen Pengguna | bkngftsl.')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-1">Manajemen Pengguna</h4>
            <p class="text-muted mb-0 small">Kelola data akun, peran akses, dan status pengguna</p>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateUser">
            <i class="bx bx-user-plus me-1"></i> Tambah Pengguna
        </button>
    </div>

    <!-- Filter & Pencarian -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('pengguna.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..."
                            value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Pilih Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="bx bx-filter-alt me-1"></i> Filter
                    </button>
                    @if (request()->hasAny(['search', 'role', 'status']))
                        <a href="{{ route('pengguna.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bx bx-reset"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Pengguna -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        @php
                            $userRole = $user->roles->first()?->name ?? 'Belum ada role';
                        @endphp
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2 flex-shrink-0">
                                        <span class="avatar-initial rounded-circle bg-label-primary fw-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if (strtolower($userRole) == 'admin')
                                    <span class="badge bg-label-danger"><i class="bx bx-shield-quarter me-1"></i>
                                        Admin</span>
                                @elseif (strtolower($userRole) == 'owner')
                                    <span class="badge bg-label-warning"><i class="bx bx-building-house me-1"></i>
                                        Pemilik</span>
                                @elseif (strtolower($userRole) == 'pelanggan')
                                    <span class="badge bg-label-info"><i class="bx bx-user me-1"></i> Pelanggan</span>
                                @else
                                    <span class="badge bg-label-secondary">{{ $userRole }}</span>
                                @endif
                            </td>
                            <td>
                                <!-- Switch Toggle Status -->
                                <form id="form-toggle-{{ $user->id }}"
                                    action="{{ route('pengguna.toggle-status', $user->id) }}" method="POST"
                                    class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch m-0 d-inline-block">
                                        <input class="form-check-input btn-toggle-status" type="checkbox" role="switch"
                                            data-user-name="{{ $user->name }}" data-current-status="{{ $user->status }}"
                                            data-form-id="form-toggle-{{ $user->id }}"
                                            {{ $user->status === 'aktif' ? 'checked' : '' }}
                                            style="cursor: pointer; width: 2.5em; height: 1.3em;">
                                    </div>
                                </form>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-flex gap-1">
                                    <!-- Tombol Buka Modal Edit -->
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-warning"
                                        data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->id }}"
                                        title="Edit Pengguna">
                                        <i class="bx bx-pencil"></i>
                                    </button>

                                    <!-- Tombol Buka Modal Hapus -->
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-danger"
                                        data-bs-toggle="modal" data-bs-target="#modalDeleteUser{{ $user->id }}"
                                        title="Hapus Pengguna" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div
                                        class="avatar avatar-md bg-label-secondary mb-2 rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bx bx-user-x fs-3 text-secondary"></i>
                                    </div>
                                    <h6 class="text-secondary mb-1">Tidak ada data pengguna yang ditemukan</h6>
                                    <p class="text-muted small mb-0">Coba ubah kata kunci filter pencarian atau tambahkan
                                        pengguna baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="card-footer d-flex justify-content-end pb-0 border-top bg-white">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Tambah Pengguna -->
    @include('pengguna.modals.tambah')

    <!-- Edit dan Delete -->
    @foreach ($users as $user)
        @php
            $userRole = $user->roles->first()?->name ?? '';
        @endphp

        <!-- Modal Edit User -->
        @include('pengguna.modals.edit')

        <!-- Modal Konfirmasi Hapus User -->
        @include('pengguna.modals.hapus')
    @endforeach
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Konfirmasi Toggle Switch Status dengan SweetAlert
            document.querySelectorAll('.btn-toggle-status').forEach(checkbox => {
                checkbox.addEventListener('change', function(e) {
                    e.preventDefault();
                    const formId = this.getAttribute('data-form-id');
                    const userName = this.getAttribute('data-user-name');
                    const currentStatus = this.getAttribute('data-current-status');
                    const newStatus = currentStatus === 'aktif' ? 'nonaktif' : 'aktif';

                    // Reset tampilan visual switch ke status awal sebelum dikonfirmasi
                    this.checked = (currentStatus === 'aktif');

                    Swal.fire({
                        title: 'Ubah Status Akun?',
                        text: `Ubah status akun ${userName} menjadi ${newStatus}?`,
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
        });
    </script>
@endpush
