@extends('layouts.app')

@section('title', 'Metode Pembayaran | bkngftsl.')

@section('content')

    {{-- Header Halaman --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">

        <div>
            <h4 class="fw-bold mb-1">
                Metode Pembayaran
            </h4>

            <p class="text-muted mb-0 small">
                Kelola metode pembayaran yang digunakan untuk menerima pembayaran dari pelanggan.
            </p>
        </div>

        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#modalCreatePaymentMethod"
        >
            <i class="bx bx-plus me-1"></i>
            Tambah Metode
        </button>

    </div>


    {{-- Filter & Pencarian --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-3">

            <form
                action="{{ route('pemilik.metode-pembayaran.index') }}"
                method="GET"
                class="row g-2 align-items-center"
            >

                {{-- Search --}}
                <div class="col-12 col-md-6">

                    <div class="input-group input-group-merge">

                        <span class="input-group-text">
                            <i class="bx bx-search"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari nama bank, rekening, atau atas nama..."
                            value="{{ request('search') }}"
                        >

                    </div>

                </div>


                {{-- Filter Tipe --}}
                <div class="col-12 col-md-4">

                    <select name="type" class="form-select">

                        <option value="">
                            -- Semua Tipe Pembayaran --
                        </option>

                        <option
                            value="bank_transfer"
                            {{ request('type') === 'bank_transfer' ? 'selected' : '' }}
                        >
                            Transfer Bank
                        </option>

                        <option
                            value="qris"
                            {{ request('type') === 'qris' ? 'selected' : '' }}
                        >
                            QRIS
                        </option>

                        <option
                            value="cash"
                            {{ request('type') === 'cash' ? 'selected' : '' }}
                        >
                            Cash / Tunai di Lokasi
                        </option>

                    </select>

                </div>


                {{-- Tombol --}}
                <div class="col-12 col-md-2 d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-secondary w-100"
                    >
                        <i class="bx bx-filter-alt me-1"></i>
                        Filter
                    </button>

                    @if (request()->hasAny(['search', 'type']))

                        <a
                            href="{{ route('pemilik.metode-pembayaran.index') }}"
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


    {{-- Card Tabel --}}
    <div class="card border-0 shadow-sm">

        <div class="table-responsive text-nowrap">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th style="width: 60px;">
                            NO
                        </th>

                        <th>
                            METODE / BANK
                        </th>

                        <th>
                            TIPE
                        </th>

                        <th>
                            NO. REKENING / NO. AKUN
                        </th>

                        <th>
                            ATAS NAMA
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

                    @forelse ($paymentMethods as $index => $pm)

                        <tr>

                            {{-- Nomor --}}
                            <td>
                                {{ $paymentMethods->firstItem() + $index }}
                            </td>


                            {{-- Metode / Bank --}}
                            <td>

                                <div class="d-flex align-items-center">

                                    {{-- Icon / QR --}}
                                    @if ($pm->type === 'qris' && $pm->qr_image)

                                        <img
                                            src="{{ asset('storage/' . $pm->qr_image) }}"
                                            alt="QRIS"
                                            class="rounded border me-2"
                                            style="width: 42px; height: 42px; object-fit: cover;"
                                        >

                                    @else

                                        <div
                                            class="avatar avatar-sm rounded bg-label-primary d-flex align-items-center justify-content-center me-2"
                                        >

                                            <i
                                                class="bx
                                                    {{
                                                        $pm->type === 'bank_transfer'
                                                            ? 'bx-credit-card'
                                                            : (
                                                                $pm->type === 'qris'
                                                                    ? 'bx-qr-scan'
                                                                    : 'bx-money'
                                                            )
                                                    }}
                                                    fs-4"
                                            ></i>

                                        </div>

                                    @endif


                                    {{-- Nama --}}
                                    <div>

                                        <span class="fw-bold text-dark">
                                            {{ $pm->name }}
                                        </span>

                                        @if ($pm->instructions)

                                            <br>

                                            <small class="text-muted">
                                                {{ Str::limit($pm->instructions, 40) }}
                                            </small>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Tipe --}}
                            <td>

                                @if ($pm->type === 'bank_transfer')

                                    <span class="badge bg-label-info">
                                        Transfer Bank
                                    </span>

                                @elseif ($pm->type === 'qris')

                                    <span class="badge bg-label-warning">
                                        QRIS
                                    </span>

                                @else

                                    <span class="badge bg-label-success">
                                        Cash
                                    </span>

                                @endif

                            </td>


                            {{-- Nomor Rekening --}}
                            <td>

                                <span class="fw-bold font-monospace text-dark">
                                    {{ $pm->account_number ?? '-' }}
                                </span>

                            </td>


                            {{-- Atas Nama --}}
                            <td>

                                <span class="fw-semibold text-secondary">
                                    {{ $pm->account_name ?? '-' }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td>

                                <form
                                    id="form-toggle-pm-{{ $pm->id }}"
                                    action="{{ route('pemilik.metode-pembayaran.toggle-status', $pm->id) }}"
                                    method="POST"
                                    class="m-0"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <div class="form-check form-switch m-0 d-inline-block">

                                        <input
                                            class="form-check-input btn-toggle-pm"
                                            type="checkbox"
                                            role="switch"

                                            data-pm-name="{{ $pm->name }}"

                                            data-current-status="{{ $pm->status }}"

                                            data-form-id="form-toggle-pm-{{ $pm->id }}"

                                            {{ $pm->status === 'active' ? 'checked' : '' }}

                                            style="
                                                cursor: pointer;
                                                width: 2.5em;
                                                height: 1.3em;
                                            "
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
                                        data-bs-target="#modalEditPM{{ $pm->id }}"
                                        title="Edit Metode"
                                    >

                                        <i class="bx bx-pencil"></i>

                                    </button>


                                    {{-- Hapus --}}
                                    <button
                                        type="button"
                                        class="btn btn-icon btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDeletePM{{ $pm->id }}"
                                        title="Hapus Metode"
                                    >

                                        <i class="bx bx-trash"></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-5"
                            >

                                <div class="d-flex flex-column align-items-center justify-content-center">

                                    <div
                                        class="avatar avatar-md bg-label-secondary mb-2 rounded-circle d-flex align-items-center justify-content-center"
                                    >

                                        <i class="bx bx-credit-card-front fs-3 text-secondary"></i>

                                    </div>

                                    <h6 class="text-secondary mb-1">
                                        Belum ada metode pembayaran.
                                    </h6>

                                    <p class="text-muted small mb-0">
                                        Klik <strong>Tambah Metode</strong> untuk menambahkan rekening bank,
                                        QRIS, atau pembayaran tunai.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($paymentMethods->hasPages())

            <div class="card-footer d-flex justify-content-end pb-0 border-top bg-white">

                {{ $paymentMethods->links() }}

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- MODAL TAMBAH METODE PEMBAYARAN --}}
    {{-- ========================================================= --}}

    <div
        class="modal fade"
        id="modalCreatePaymentMethod"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-dialog-centered">

            <form
                class="modal-content"
                action="{{ route('pemilik.metode-pembayaran.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf


                <div class="modal-header">

                    <h5 class="modal-title">
                        Tambah Metode Pembayaran
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>


                <div class="modal-body">

                    {{-- Nama --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Nama Bank / Metode
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Contoh: Bank BCA, Mandiri, atau QRIS"
                            value="{{ old('name') }}"
                            required
                        >

                    </div>


                    {{-- Tipe --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Tipe Pembayaran
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="type"
                            class="form-select"
                            required
                        >

                            <option value="bank_transfer">
                                Transfer Bank
                            </option>

                            <option value="qris">
                                QRIS
                            </option>

                            <option value="cash">
                                Cash / Tunai di Lokasi
                            </option>

                        </select>

                    </div>


                    {{-- Nomor Rekening --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Nomor Rekening / No. Akun
                        </label>

                        <input
                            type="text"
                            name="account_number"
                            class="form-control"
                            placeholder="Contoh: 1234567890"
                            value="{{ old('account_number') }}"
                        >

                        <small class="text-muted">
                            Kosongkan jika menggunakan pembayaran tunai.
                        </small>

                    </div>


                    {{-- Atas Nama --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Atas Nama
                        </label>

                        <input
                            type="text"
                            name="account_name"
                            class="form-control"
                            placeholder="Contoh: Nama Pemilik / Nama Usaha"
                            value="{{ old('account_name') }}"
                        >

                    </div>


                    {{-- QRIS --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Upload Gambar QRIS
                        </label>

                        <input
                            type="file"
                            name="qr_image"
                            class="form-control"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                        >

                        <small class="text-muted">
                            Gunakan JPG, PNG, atau WEBP. Maksimal 2MB.
                        </small>

                    </div>


                    {{-- Instruksi --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Petunjuk Pembayaran / Catatan
                        </label>

                        <textarea
                            name="instructions"
                            class="form-control"
                            rows="3"
                            placeholder="Contoh: Mohon sertakan kode booking pada catatan transfer..."
                        >{{ old('instructions') }}</textarea>

                    </div>


                    {{-- Status --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="active"
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}
                            >
                                Aktif
                            </option>

                            <option
                                value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}
                            >
                                Nonaktif
                            </option>

                        </select>

                    </div>

                </div>


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
                        Simpan Metode
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- MODAL EDIT & DELETE --}}
    {{-- ========================================================= --}}

    @foreach ($paymentMethods as $pm)

        {{-- ================= EDIT ================= --}}

        <div
            class="modal fade"
            id="modalEditPM{{ $pm->id }}"
            tabindex="-1"
            aria-hidden="true"
        >

            <div class="modal-dialog modal-dialog-centered">

                <form
                    class="modal-content"
                    action="{{ route('pemilik.metode-pembayaran.update', $pm->id) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf
                    @method('PUT')


                    <div class="modal-header">

                        <h5 class="modal-title">
                            Edit Metode: {{ $pm->name }}
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>

                    </div>


                    <div class="modal-body">

                        {{-- Nama --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Bank / Metode
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $pm->name) }}"
                                required
                            >

                        </div>


                        {{-- Tipe --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Tipe Pembayaran
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="type"
                                class="form-select"
                                required
                            >

                                <option
                                    value="bank_transfer"
                                    {{ old('type', $pm->type) === 'bank_transfer' ? 'selected' : '' }}
                                >
                                    Transfer Bank
                                </option>

                                <option
                                    value="qris"
                                    {{ old('type', $pm->type) === 'qris' ? 'selected' : '' }}
                                >
                                    QRIS
                                </option>

                                <option
                                    value="cash"
                                    {{ old('type', $pm->type) === 'cash' ? 'selected' : '' }}
                                >
                                    Cash / Tunai di Lokasi
                                </option>

                            </select>

                        </div>


                        {{-- Nomor Rekening --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Nomor Rekening / No. Akun
                            </label>

                            <input
                                type="text"
                                name="account_number"
                                class="form-control"
                                value="{{ old('account_number', $pm->account_number) }}"
                            >

                        </div>


                        {{-- Atas Nama --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Atas Nama
                            </label>

                            <input
                                type="text"
                                name="account_name"
                                class="form-control"
                                value="{{ old('account_name', $pm->account_name) }}"
                            >

                        </div>


                        {{-- QRIS --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Gambar QRIS
                            </label>

                            @if ($pm->qr_image)

                                <div class="mb-2">

                                    <img
                                        src="{{ asset('storage/' . $pm->qr_image) }}"
                                        alt="QRIS"
                                        class="rounded border"
                                        style="
                                            width: 80px;
                                            height: 80px;
                                            object-fit: cover;
                                        "
                                    >

                                </div>

                            @endif


                            <input
                                type="file"
                                name="qr_image"
                                class="form-control"
                                accept="image/jpeg,image/png,image/jpg,image/webp"
                            >

                            <small class="text-muted">
                                Kosongkan jika tidak ingin mengganti gambar.
                            </small>

                        </div>


                        {{-- Instruksi --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Petunjuk Pembayaran / Catatan
                            </label>

                            <textarea
                                name="instructions"
                                class="form-control"
                                rows="3"
                            >{{ old('instructions', $pm->instructions) }}</textarea>

                        </div>


                        {{-- Status --}}
                        <div class="mb-3">

                            <label class="form-label">
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required
                            >

                                <option
                                    value="active"
                                    {{ old('status', $pm->status) === 'active' ? 'selected' : '' }}
                                >
                                    Aktif
                                </option>

                                <option
                                    value="inactive"
                                    {{ old('status', $pm->status) === 'inactive' ? 'selected' : '' }}
                                >
                                    Nonaktif
                                </option>

                            </select>

                        </div>

                    </div>


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


        {{-- ================= DELETE ================= --}}

        <div
            class="modal fade"
            id="modalDeletePM{{ $pm->id }}"
            tabindex="-1"
            aria-hidden="true"
        >

            <div class="modal-dialog modal-dialog-centered">

                <form
                    class="modal-content"
                    action="{{ route('pemilik.metode-pembayaran.destroy', $pm->id) }}"
                    method="POST"
                >

                    @csrf
                    @method('DELETE')


                    <div class="modal-header">

                        <h5 class="modal-title">
                            Konfirmasi Hapus Metode
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>

                    </div>


                    <div class="modal-body text-center py-4">

                        <i class="bx bx-error-circle text-danger display-3 mb-2"></i>

                        <h5 class="mb-1">
                            Yakin ingin menghapus metode pembayaran ini?
                        </h5>

                        <p class="text-muted mb-0">

                            Metode

                            <strong>
                                {{ $pm->name }}
                            </strong>

                            @if ($pm->account_number)
                                ({{ $pm->account_number }})
                            @endif

                            akan dihapus permanen dari daftar metode pembayaran Anda.

                        </p>

                    </div>


                    <div class="modal-footer justify-content-center">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            class="btn btn-danger"
                        >
                            <i class="bx bx-trash me-1"></i>
                            Ya, Hapus Data
                        </button>

                    </div>

                </form>

            </div>

        </div>

    @endforeach

@endsection


@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Switch Toggle Status Metode Pembayaran
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.btn-toggle-pm')
        .forEach(function (checkbox) {

            checkbox.addEventListener('change', function (e) {

                e.preventDefault();

                const formId =
                    this.getAttribute('data-form-id');

                const pmName =
                    this.getAttribute('data-pm-name');

                const currentStatus =
                    this.getAttribute('data-current-status');

                const newStatusIndo =
                    currentStatus === 'active'
                        ? 'nonaktif'
                        : 'aktif';


                /*
                |--------------------------------------------------------------------------
                | Kembalikan switch ke posisi semula
                |--------------------------------------------------------------------------
                */

                this.checked =
                    currentStatus === 'active';


                Swal.fire({

                    title: 'Ubah Status Metode?',

                    text:
                        `Ubah status metode pembayaran "${pmName}" menjadi ${newStatusIndo}?`,

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

});

</script>

@endpush