@extends('layouts.app')

@section('title', 'Data Lapangan | bkngftsl.')

@section('content')

@php
    $isAdmin = auth()->user()->hasRole('admin');
    $isPemilik = auth()->user()->hasRole('pemilik');

    /*
    |--------------------------------------------------------------------------
    | Route berdasarkan role pengguna
    |--------------------------------------------------------------------------
    */

    $lapanganIndexRoute = $isAdmin
        ? 'admin.lapangan.index'
        : 'pemilik.lapangan.index';

    $lapanganStoreRoute = $isAdmin
        ? 'admin.lapangan.store'
        : 'pemilik.lapangan.store';

    $lapanganToggleStatusRoute = $isAdmin
        ? 'admin.lapangan.toggle-status'
        : 'pemilik.lapangan.toggle-status';

    $lapanganUpdateRoute = $isAdmin
        ? 'admin.lapangan.update'
        : 'pemilik.lapangan.update';

    $lapanganDeleteRoute = $isAdmin
        ? 'admin.lapangan.destroy'
        : 'pemilik.lapangan.destroy';
@endphp

{{-- Header Halaman --}}
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">

    <div>
        <h4 class="fw-bold mb-1">
            Data Lapangan
        </h4>

        <p class="text-muted mb-0 small">
            @if ($isAdmin)
                Kelola seluruh data lapangan futsal yang terdaftar pada sistem.
            @else
                Kelola data lapangan pada cabang venue futsal milik Anda.
            @endif
        </p>
    </div>

    {{-- Tombol Tambah --}}
    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalCreateField"
    >
        <i class="bx bx-plus me-1"></i>
        Tambah Lapangan
    </button>

</div>

{{-- Filter & Pencarian --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-body p-3">

        <form
            action="{{ route($lapanganIndexRoute) }}"
            method="GET"
            class="row g-2 align-items-center"
        >

            {{-- Search --}}
            <div class="col-12 col-md-4">

                <div class="input-group input-group-merge">

                    <span class="input-group-text">
                        <i class="bx bx-search"></i>
                    </span>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari nama lapangan..."
                        value="{{ request('search') }}"
                    >

                </div>

            </div>

            {{-- Filter Cabang --}}
            <div class="col-12 col-md-3">

                <select
                    name="branch_id"
                    class="form-select"
                >

                    <option value="">
                        -- Semua Cabang --
                    </option>

                    @foreach ($branches as $branch)

                        <option
                            value="{{ $branch->id }}"
                            {{ request('branch_id') == $branch->id ? 'selected' : '' }}
                        >
                            {{ $branch->branch_name }}
                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Filter Jenis Lantai --}}
            <div class="col-12 col-md-3">

                <select
                    name="field_type"
                    class="form-select"
                >

                    <option value="">
                        -- Semua Jenis Lantai --
                    </option>

                    <option
                        value="sintetis"
                        {{ request('field_type') == 'sintetis' ? 'selected' : '' }}
                    >
                        Rumput Sintetis
                    </option>

                    <option
                        value="vinyl"
                        {{ request('field_type') == 'vinyl' ? 'selected' : '' }}
                    >
                        Vinyl / Karpet
                    </option>

                    <option
                        value="interlock"
                        {{ request('field_type') == 'interlock' ? 'selected' : '' }}
                    >
                        Interlock Flooring
                    </option>

                    <option
                        value="matras"
                        {{ request('field_type') == 'matras' ? 'selected' : '' }}
                    >
                        Matras
                    </option>

                    <option
                        value="semen"
                        {{ request('field_type') == 'semen' ? 'selected' : '' }}
                    >
                        Semen / Plester
                    </option>

                </select>

            </div>

            {{-- Tombol Filter --}}
            <div class="col-12 col-md-2 d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-secondary w-100"
                >
                    <i class="bx bx-filter-alt me-1"></i>
                    Filter
                </button>

                @if (request()->hasAny(['search', 'branch_id', 'field_type']))

                    <a
                        href="{{ route($lapanganIndexRoute) }}"
                        class="btn btn-outline-secondary"
                        title="Reset Filter"
                    >
                        <i class="bx bx-reset"></i>
                    </a>

                @endif

            </div>

        </form>

    </div>

</div>

{{-- Card Tabel Utama --}}
<div class="card border-0 shadow-sm">

    {{-- Table Responsive --}}
    <div class="table-responsive text-nowrap">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th style="width: 60px;">
                        NO
                    </th>

                    <th>
                        FOTO
                    </th>

                    <th>
                        NAMA LAPANGAN
                    </th>

                    {{-- Admin dapat melihat cabang --}}
                    <th>
                        CABANG VENUE
                    </th>

                    <th>
                        JENIS & SPESIFIKASI
                    </th>

                    <th>
                        HARGA / JAM
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

                @forelse ($fields as $index => $field)

                    <tr>

                        {{-- Nomor --}}
                        <td>
                            {{ $fields->firstItem() + $index }}
                        </td>

                        {{-- Foto --}}
                        <td>

                            @if ($field->image)

                                <img
                                    src="{{ asset('storage/' . $field->image) }}"
                                    alt="Foto Lapangan"
                                    class="rounded border"
                                    style="width: 52px; height: 52px; object-fit: cover;"
                                >

                            @else

                                <div
                                    class="avatar avatar-md rounded bg-label-secondary d-flex align-items-center justify-content-center"
                                >
                                    <i class="bx bx-image fs-3 text-secondary"></i>
                                </div>

                            @endif

                        </td>

                        {{-- Nama Lapangan --}}
                        <td>

                            <span class="fw-bold text-dark">
                                {{ $field->field_name }}
                            </span>

                            @if ($field->description)

                                <br>

                                <small class="text-muted">
                                    {{ Str::limit($field->description, 35) }}
                                </small>

                            @endif

                        </td>

                        {{-- Cabang --}}
                        <td>

                            <span class="fw-semibold text-primary">

                                <i class="bx bx-building-house me-1"></i>

                                {{ $field->branch?->branch_name ?? '-' }}

                            </span>

                        </td>

                        {{-- Jenis & Spesifikasi --}}
                        <td
                            class="text-wrap"
                            style="max-width: 260px;"
                        >

                            <span class="badge bg-label-info text-capitalize mb-1">
                                {{ $field->field_type }}
                            </span>

                            @if (
                                !empty($field->specifications) &&
                                is_array($field->specifications)
                            )

                                <div class="d-flex flex-wrap gap-1 mt-1">

                                    @foreach (
                                        array_slice($field->specifications, 0, 2)
                                        as $spec
                                    )

                                        <span
                                            class="badge bg-label-secondary"
                                            style="font-size: 11px;"
                                        >
                                            {{ $spec }}
                                        </span>

                                    @endforeach

                                    @if (count($field->specifications) > 2)

                                        <span
                                            class="badge bg-label-light text-muted border"
                                            style="font-size: 10px;"
                                        >
                                            +{{ count($field->specifications) - 2 }}
                                        </span>

                                    @endif

                                </div>

                            @endif

                        </td>

                        {{-- Harga --}}
                        <td>

                            <span class="fw-bold text-success">
                                Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}
                            </span>

                        </td>

                        {{-- Status --}}
                        <td>

                            <form
                                id="form-toggle-field-{{ $field->id }}"
                                action="{{ route($lapanganToggleStatusRoute, $field->id) }}"
                                method="POST"
                                class="m-0"
                            >

                                @csrf

                                @method('PATCH')

                                <div class="form-check form-switch m-0 d-inline-block">

                                    <input
                                        class="form-check-input btn-toggle-field"
                                        type="checkbox"
                                        role="switch"
                                        data-field-name="{{ $field->field_name }}"
                                        data-current-status="{{ $field->status }}"
                                        data-form-id="form-toggle-field-{{ $field->id }}"
                                        {{ $field->status === 'available' ? 'checked' : '' }}
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
                                    data-bs-target="#modalEditField{{ $field->id }}"
                                    title="Edit Lapangan"
                                >
                                    <i class="bx bx-pencil"></i>
                                </button>

                                {{-- Hapus --}}
                                <button
                                    type="button"
                                    class="btn btn-icon btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteField{{ $field->id }}"
                                    title="Hapus Lapangan"
                                >
                                    <i class="bx bx-trash"></i>
                                </button>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-5"
                        >

                            <div
                                class="d-flex flex-column align-items-center justify-content-center"
                            >

                                <div
                                    class="avatar avatar-md bg-label-secondary mb-2 rounded-circle d-flex align-items-center justify-content-center"
                                >
                                    <i class="bx bx-layer fs-3 text-secondary"></i>
                                </div>

                                <h6 class="text-secondary mb-1">
                                    Tidak ada data lapangan yang ditemukan.
                                </h6>

                                <p class="text-muted small mb-0">
                                    Klik tombol Tambah Lapangan untuk mendaftarkan unit lapangan baru.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    @if ($fields->hasPages())

        <div
            class="card-footer d-flex justify-content-end pb-0 border-top bg-white"
        >
            {{ $fields->links() }}
        </div>

    @endif

</div>

{{-- Modal Tambah Lapangan --}}
@include('lapangan.modals.tambah')

{{-- Modal Edit & Delete --}}
@foreach ($fields as $field)

    {{-- Modal Edit --}}
    @include('lapangan.modals.edit')

    {{-- Modal Hapus --}}
    @include('lapangan.modals.hapus')

@endforeach

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Switch Toggle Status Lapangan
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.btn-toggle-field')
        .forEach(function (checkbox) {

            checkbox.addEventListener('change', function (e) {

                e.preventDefault();

                const formId =
                    this.getAttribute('data-form-id');

                const fieldName =
                    this.getAttribute('data-field-name');

                const currentStatus =
                    this.getAttribute('data-current-status');

                const newStatusIndo =
                    currentStatus === 'available'
                        ? 'nonaktif'
                        : 'tersedia';

                /*
                 * Kembalikan switch ke posisi semula
                 * sampai user melakukan konfirmasi
                 */
                this.checked =
                    currentStatus === 'available';

                Swal.fire({
                    title: 'Ubah Status Lapangan?',

                    text:
                        `Ubah status operasional lapangan "${fieldName}" menjadi ${newStatusIndo}?`,

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


    /*
    |--------------------------------------------------------------------------
    | Tambah Baris Spesifikasi - Modal Create
    |--------------------------------------------------------------------------
    */

    const createSpecContainer =
        document.getElementById(
            'create-spec-container'
        );

    const btnAddCreateSpec =
        document.getElementById(
            'btn-add-create-spec'
        );

    if (btnAddCreateSpec) {

        btnAddCreateSpec.addEventListener(
            'click',
            function () {

                const row =
                    document.createElement('div');

                row.className =
                    'input-group';

                row.innerHTML = `
                    <input
                        type="text"
                        name="specifications[]"
                        class="form-control"
                        placeholder="Contoh: Pencahayaan Lampu LED"
                    >

                    <button
                        type="button"
                        class="btn btn-outline-danger btn-remove-spec"
                    >
                        <i class="bx bx-trash"></i>
                    </button>
                `;

                createSpecContainer.appendChild(row);

                updateSpecRemoveButtons(
                    createSpecContainer
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Tambah Baris Spesifikasi - Modal Edit
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.btn-add-edit-spec')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const targetId =
                        this.getAttribute(
                            'data-target'
                        );

                    const container =
                        document.getElementById(
                            targetId
                        );

                    if (!container) {
                        return;
                    }

                    const row =
                        document.createElement('div');

                    row.className =
                        'input-group';

                    row.innerHTML = `
                        <input
                            type="text"
                            name="specifications[]"
                            class="form-control"
                            placeholder="Contoh: Pencahayaan Lampu LED"
                        >

                        <button
                            type="button"
                            class="btn btn-outline-danger btn-remove-spec"
                        >
                            <i class="bx bx-trash"></i>
                        </button>
                    `;

                    container.appendChild(row);

                    updateSpecRemoveButtons(
                        container
                    );

                }
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Event Delegation - Hapus Baris Spesifikasi
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        function (e) {

            const button =
                e.target.closest(
                    '.btn-remove-spec'
                );

            if (!button) {
                return;
            }

            /*
             * Cari container spesifikasi terdekat.
             *
             * Bisa berupa .spec-container atau
             * parent .d-flex sesuai struktur modal.
             */
            const container =
                button.closest(
                    '.spec-container'
                ) ||
                button.closest(
                    '.d-flex'
                );

            if (!container) {
                return;
            }

            const inputGroups =
                container.querySelectorAll(
                    '.input-group'
                );

            /*
             * Minimal satu baris
             * harus tetap tersedia
             */
            if (inputGroups.length > 1) {

                button
                    .closest('.input-group')
                    .remove();

                updateSpecRemoveButtons(
                    container
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Update Tombol Hapus Spesifikasi
    |--------------------------------------------------------------------------
    */

    function updateSpecRemoveButtons(
        container
    ) {

        const inputGroups =
            container.querySelectorAll(
                '.input-group'
            );

        inputGroups.forEach(
            function (group) {

                const removeBtn =
                    group.querySelector(
                        '.btn-remove-spec'
                    );

                if (removeBtn) {

                    removeBtn.disabled =
                        inputGroups.length === 1;

                }

            }
        );

    }

});
</script>

@endpush