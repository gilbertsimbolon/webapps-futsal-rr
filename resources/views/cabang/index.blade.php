@extends('layouts.app')

@section('title', 'Data Cabang | bkngftsl.')

@section('content')

@php
    $isAdmin = auth()->user()->hasRole('admin');
    $isPemilik = auth()->user()->hasRole('pemilik');

    // Route berdasarkan role pengguna
    $cabangIndexRoute = $isAdmin
        ? 'admin.cabang.index'
        : 'pemilik.cabang.index';

    $cabangStoreRoute = $isAdmin
        ? 'admin.cabang.store'
        : 'pemilik.cabang.store';

    $cabangToggleStatusRoute = $isAdmin
        ? 'admin.cabang.toggle-status'
        : 'pemilik.cabang.toggle-status';

    $cabangUpdateRoute = $isAdmin
        ? 'admin.cabang.update'
        : 'pemilik.cabang.update';

    $cabangDeleteRoute = $isAdmin
        ? 'admin.cabang.destroy'
        : 'pemilik.cabang.destroy';
@endphp

{{-- Header Halaman --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">

    <div>
        <h4 class="fw-bold mb-1">
            Data Cabang
        </h4>

        <p class="text-muted mb-0 small">
            @if ($isAdmin)
                Kelola seluruh data cabang venue futsal yang terdaftar pada sistem.
            @else
                Kelola data cabang venue futsal milik Anda.
            @endif
        </p>
    </div>

    {{-- Tombol Tambah --}}
    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalCreateBranch"
    >
        <i class="bx bx-plus me-1"></i>
        Tambah Cabang
    </button>

</div>

{{-- Card Utama --}}
<div class="card border-0 shadow-sm">

    {{-- Card Header --}}
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 border-bottom">

        <h5 class="mb-0 fw-bold">
            Daftar Cabang
        </h5>

        {{-- Search --}}
        <form
            action="{{ route($cabangIndexRoute) }}"
            method="GET"
            class="m-0"
            style="min-width: 280px;"
        >
            <div class="input-group input-group-merge">

                <span class="input-group-text">
                    <i class="bx bx-search"></i>
                </span>

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama atau alamat..."
                    value="{{ request('search') }}"
                >

            </div>
        </form>

    </div>

    {{-- Table Responsive --}}
    <div class="table-responsive text-nowrap">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>

                    <th style="width: 60px;">
                        NO
                    </th>

                    <th>
                        NAMA CABANG
                    </th>

                    {{-- Admin melihat pemilik --}}
                    @if ($isAdmin)
                        <th>
                            PEMILIK
                        </th>
                    @endif

                    <th>
                        KONTAK (WA)
                    </th>

                    <th>
                        FASILITAS
                    </th>

                    <th>
                        STATUS
                    </th>

                    <th
                        class="text-center"
                        style="width: 120px;"
                    >
                        AKSI
                    </th>

                </tr>
            </thead>

            <tbody>

                @forelse ($branches as $index => $branch)

                    <tr>

                        {{-- Nomor --}}
                        <td>
                            {{ $branches->firstItem() + $index }}
                        </td>

                        {{-- Nama Cabang --}}
                        <td>

                            <span class="fw-bold text-dark">
                                {{ $branch->branch_name }}
                            </span>

                            @if ($branch->description)

                                <br>

                                <small class="text-muted">
                                    {{ Str::limit($branch->description, 35) }}
                                </small>

                            @endif

                        </td>

                        {{-- Pemilik --}}
                        @if ($isAdmin)

                            <td>

                                <div class="d-flex align-items-center">

                                    <div class="avatar avatar-xs me-2 flex-shrink-0">

                                        <span
                                            class="avatar-initial rounded-circle bg-label-info fw-bold"
                                            style="font-size: 10px;"
                                        >
                                            {{ strtoupper(substr($branch->user?->name ?? 'P', 0, 1)) }}
                                        </span>

                                    </div>

                                    <span class="small fw-semibold text-muted">
                                        {{ $branch->user?->name ?? '-' }}
                                    </span>

                                </div>

                            </td>

                        @endif

                        {{-- Kontak --}}
                        <td>
                            {{ $branch->phone }}
                        </td>

                        {{-- Fasilitas --}}
                        <td
                            class="text-wrap"
                            style="max-width: 260px;"
                        >

                            @if (!empty($branch->facilities) && is_array($branch->facilities))

                                <div class="d-flex flex-wrap gap-1">

                                    @foreach (array_slice($branch->facilities, 0, 3) as $facility)

                                        <span
                                            class="badge bg-label-primary"
                                            style="font-size: 11px;"
                                        >
                                            {{ $facility }}
                                        </span>

                                    @endforeach

                                    @if (count($branch->facilities) > 3)

                                        <span
                                            class="badge bg-label-secondary"
                                            style="font-size: 11px;"
                                        >
                                            +{{ count($branch->facilities) - 3 }} lainnya
                                        </span>

                                    @endif

                                </div>

                            @else

                                <span class="text-muted small">
                                    -
                                </span>

                            @endif

                        </td>

                        {{-- Status --}}
                        <td>

                            <form
                                id="form-toggle-branch-{{ $branch->id }}"
                                action="{{ route($cabangToggleStatusRoute, $branch->id) }}"
                                method="POST"
                                class="m-0"
                            >

                                @csrf

                                @method('PATCH')

                                <div class="form-check form-switch m-0 d-inline-block">

                                    <input
                                        class="form-check-input btn-toggle-branch"
                                        type="checkbox"
                                        role="switch"
                                        data-branch-name="{{ $branch->branch_name }}"
                                        data-current-status="{{ $branch->status }}"
                                        data-form-id="form-toggle-branch-{{ $branch->id }}"
                                        {{ $branch->status === 'active' ? 'checked' : '' }}
                                        style="cursor: pointer; width: 2.5em; height: 1.3em;"
                                    >

                                </div>

                            </form>

                        </td>

                        {{-- Aksi --}}
                        <td class="text-center">

                            <div class="d-inline-flex gap-1">

                                {{-- Edit --}}
                                <button
                                    type="button"
                                    class="btn btn-icon btn-sm btn-outline-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditBranch{{ $branch->id }}"
                                    title="Edit Cabang"
                                >
                                    <i class="bx bx-pencil"></i>
                                </button>

                                {{-- Hapus --}}
                                <button
                                    type="button"
                                    class="btn btn-icon btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteBranch{{ $branch->id }}"
                                    title="Hapus Cabang"
                                >
                                    <i class="bx bx-trash"></i>
                                </button>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="{{ $isAdmin ? 7 : 6 }}"
                            class="text-center py-5"
                        >

                            <div class="d-flex flex-column align-items-center justify-content-center">

                                <div
                                    class="avatar avatar-md bg-label-secondary mb-2 rounded-circle d-flex align-items-center justify-content-center"
                                >
                                    <i class="bx bx-building-house fs-3 text-secondary"></i>
                                </div>

                                <h6 class="text-secondary mb-1">
                                    Tidak ada data cabang yang ditemukan.
                                </h6>

                                <p class="text-muted small mb-0">
                                    Klik tombol Tambah Cabang untuk menambahkan cabang baru.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    @if ($branches->hasPages())

        <div class="card-footer d-flex justify-content-end pb-0 border-top bg-white">

            {{ $branches->links() }}

        </div>

    @endif

</div>

{{-- Modal Tambah Cabang --}}
@include('cabang.modals.tambah')

{{-- Modal Edit & Delete --}}
@foreach ($branches as $branch)

    @include('cabang.modals.edit')

    @include('cabang.modals.hapus')

@endforeach

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Switch Toggle Status Cabang
    document.querySelectorAll('.btn-toggle-branch').forEach(function (checkbox) {

        checkbox.addEventListener('change', function (e) {

            e.preventDefault();

            const formId = this.getAttribute('data-form-id');
            const branchName = this.getAttribute('data-branch-name');
            const currentStatus = this.getAttribute('data-current-status');

            const newStatusIndo =
                currentStatus === 'active'
                    ? 'nonaktif'
                    : 'aktif';

            // Kembalikan switch ke posisi semula
            this.checked = currentStatus === 'active';

            Swal.fire({
                title: 'Ubah Status Cabang?',
                text: `Ubah status operasional cabang "${branchName}" menjadi ${newStatusIndo}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#8592a3',
                confirmButtonText: 'Ya, Ubah',
                cancelButtonText: 'Batal'
            }).then(function (result) {

                if (result.isConfirmed) {

                    document
                        .getElementById(formId)
                        .submit();

                }

            });

        });

    });

    // Tambah Baris Fasilitas Modal Create
    const createContainer =
        document.getElementById('create-facility-container');

    const btnAddCreate =
        document.getElementById('btn-add-create-facility');

    if (btnAddCreate) {

        btnAddCreate.addEventListener('click', function () {

            const row = document.createElement('div');

            row.className = 'input-group';

            row.innerHTML = `
                <input
                    type="text"
                    name="facilities[]"
                    class="form-control"
                    placeholder="Contoh: Toilet & Kamar Mandi"
                >

                <button
                    type="button"
                    class="btn btn-outline-danger btn-remove-facility"
                >
                    <i class="bx bx-trash"></i>
                </button>
            `;

            createContainer.appendChild(row);

            updateRemoveButtons(createContainer);

        });

    }

    // Tambah Baris Fasilitas Modal Edit
    document.querySelectorAll('.btn-add-edit-facility').forEach(function (button) {

        button.addEventListener('click', function () {

            const targetId =
                this.getAttribute('data-target');

            const container =
                document.getElementById(targetId);

            if (container) {

                const row =
                    document.createElement('div');

                row.className = 'input-group';

                row.innerHTML = `
                    <input
                        type="text"
                        name="facilities[]"
                        class="form-control"
                        placeholder="Contoh: Toilet & Kamar Mandi"
                    >

                    <button
                        type="button"
                        class="btn btn-outline-danger btn-remove-facility"
                    >
                        <i class="bx bx-trash"></i>
                    </button>
                `;

                container.appendChild(row);

                updateRemoveButtons(container);

            }

        });

    });

    // Event Delegation untuk Hapus Baris Fasilitas
    document.addEventListener('click', function (e) {

        const button =
            e.target.closest('.btn-remove-facility');

        if (!button) {
            return;
        }

        const container =
            button.closest('.facility-container');

        if (!container) {
            return;
        }

        const inputGroups =
            container.querySelectorAll('.input-group');

        if (inputGroups.length > 1) {

            button.closest('.input-group').remove();

            updateRemoveButtons(container);

        }

    });

    // Update tombol hapus fasilitas
    function updateRemoveButtons(container) {

        const inputGroups =
            container.querySelectorAll('.input-group');

        inputGroups.forEach(function (group) {

            const removeBtn =
                group.querySelector('.btn-remove-facility');

            if (removeBtn) {

                removeBtn.disabled =
                    inputGroups.length === 1;

            }

        });

    }

});
</script>

@endpush