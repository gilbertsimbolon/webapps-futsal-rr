@extends('layouts.app')

@section('title', 'Slot Jam Operasional | bkngftsl.')

@section('content')

    {{-- Header Halaman --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">

        <div>
            <h4 class="fw-bold mb-1">
                Slot Jam Operasional
            </h4>

            <p class="text-muted mb-0 small">
                Atur ketersediaan jam sewa per sesi untuk setiap lapangan futsal milik Anda.
            </p>
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex gap-2">

            <button
                type="button"
                class="btn btn-outline-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalGenerateSlot"
            >
                <i class="bx bx-bolt-circle me-1"></i>
                Generate Otomatis
            </button>

            <button
                type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modalCreateSlot"
            >
                <i class="bx bx-plus me-1"></i>
                Tambah Slot
            </button>

        </div>
    </div>

    {{-- Card Utama --}}
    <div class="card border-0 shadow-sm">

        {{-- Card Header --}}
        <div class="card-header bg-white py-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 border-bottom">

            <h5 class="mb-0 fw-bold">
                Daftar Slot Jam Operasional
            </h5>

            {{-- Filter --}}
            <form
                action="{{ route('pemilik.jadwal.index') }}"
                method="GET"
                class="m-0"
            >
                <div class="d-flex flex-column flex-md-row gap-2">

                    {{-- Filter Lapangan --}}
                    <select
                        name="field_id"
                        class="form-select"
                        style="min-width: 220px;"
                    >
                        <option value="">
                            -- Semua Lapangan --
                        </option>

                        @foreach ($fields as $field)
                            <option
                                value="{{ $field->id }}"
                                {{ request('field_id') == $field->id ? 'selected' : '' }}
                            >
                                {{ $field->branch?->branch_name ?? '-' }}
                                -
                                {{ $field->field_name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Filter Hari --}}
                    <select
                        name="day"
                        class="form-select"
                        style="min-width: 180px;"
                    >
                        <option value="">
                            -- Semua Hari --
                        </option>

                        <option
                            value="all"
                            {{ request('day') == 'all' ? 'selected' : '' }}
                        >
                            Setiap Hari
                        </option>

                        <option
                            value="monday"
                            {{ request('day') == 'monday' ? 'selected' : '' }}
                        >
                            Senin
                        </option>

                        <option
                            value="tuesday"
                            {{ request('day') == 'tuesday' ? 'selected' : '' }}
                        >
                            Selasa
                        </option>

                        <option
                            value="wednesday"
                            {{ request('day') == 'wednesday' ? 'selected' : '' }}
                        >
                            Rabu
                        </option>

                        <option
                            value="thursday"
                            {{ request('day') == 'thursday' ? 'selected' : '' }}
                        >
                            Kamis
                        </option>

                        <option
                            value="friday"
                            {{ request('day') == 'friday' ? 'selected' : '' }}
                        >
                            Jumat
                        </option>

                        <option
                            value="saturday"
                            {{ request('day') == 'saturday' ? 'selected' : '' }}
                        >
                            Sabtu
                        </option>

                        <option
                            value="sunday"
                            {{ request('day') == 'sunday' ? 'selected' : '' }}
                        >
                            Minggu
                        </option>
                    </select>

                    {{-- Tombol Filter --}}
                    <button
                        type="submit"
                        class="btn btn-secondary"
                    >
                        <i class="bx bx-filter-alt me-1"></i>
                        Filter
                    </button>

                    {{-- Reset --}}
                    @if (request()->hasAny(['field_id', 'day']))
                        <a
                            href="{{ route('pemilik.jadwal.index') }}"
                            class="btn btn-outline-secondary"
                            title="Reset Filter"
                        >
                            <i class="bx bx-reset"></i>
                        </a>
                    @endif

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
                            CABANG & LAPANGAN
                        </th>

                        <th>
                            HARI
                        </th>

                        <th>
                            SLOT WAKTU
                        </th>

                        <th>
                            TARIF SEWA
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

                    @forelse ($schedules as $index => $schedule)

                        @php

                            $dayMap = [
                                'all' => 'Setiap Hari',
                                'monday' => 'Senin',
                                'tuesday' => 'Selasa',
                                'wednesday' => 'Rabu',
                                'thursday' => 'Kamis',
                                'friday' => 'Jumat',
                                'saturday' => 'Sabtu',
                                'sunday' => 'Minggu',
                            ];

                            $startTime = \Carbon\Carbon::parse(
                                $schedule->start_time
                            )->format('H:i');

                            $endTime = \Carbon\Carbon::parse(
                                $schedule->end_time
                            )->format('H:i');

                            $fieldPrice =
                                $schedule->custom_price
                                ?? $schedule->field?->price_per_hour
                                ?? 0;

                        @endphp

                        <tr>

                            {{-- Nomor --}}
                            <td>
                                {{ $schedules->firstItem() + $index }}
                            </td>

                            {{-- Cabang & Lapangan --}}
                            <td>

                                <span class="fw-bold text-dark">
                                    {{ $schedule->field?->field_name ?? '-' }}
                                </span>

                                <br>

                                <small class="text-muted">

                                    <i class="bx bx-building-house me-1"></i>

                                    {{ $schedule->field?->branch?->branch_name ?? '-' }}

                                </small>

                            </td>

                            {{-- Hari --}}
                            <td>

                                <span
                                    class="badge {{ $schedule->day === 'all'
                                        ? 'bg-label-primary'
                                        : 'bg-label-secondary' }}"
                                >
                                    {{ $dayMap[$schedule->day] ?? ucfirst($schedule->day) }}
                                </span>

                            </td>

                            {{-- Slot Waktu --}}
                            <td>

                                <span class="badge bg-label-info fs-7 px-3 py-1">

                                    <i class="bx bx-time-five me-1"></i>

                                    {{ $startTime }}
                                    -
                                    {{ $endTime }}

                                </span>

                            </td>

                            {{-- Tarif --}}
                            <td>

                                <span class="fw-bold text-success">

                                    Rp
                                    {{ number_format($fieldPrice, 0, ',', '.') }}

                                </span>

                                @if ($schedule->custom_price !== null)

                                    <br>

                                    <small
                                        class="text-warning"
                                        style="font-size: 11px;"
                                    >
                                        Harga Khusus
                                    </small>

                                @endif

                            </td>

                            {{-- Status --}}
                            <td>

                                <form
                                    id="form-toggle-slot-{{ $schedule->id }}"
                                    action="{{ route('pemilik.jadwal.toggle-status', $schedule->id) }}"
                                    method="POST"
                                    class="m-0"
                                >

                                    @csrf

                                    @method('PATCH')

                                    <div class="form-check form-switch m-0 d-inline-block">

                                        <input
                                            class="form-check-input btn-toggle-slot"
                                            type="checkbox"
                                            role="switch"

                                            data-slot-name="{{ $schedule->field?->field_name ?? 'Lapangan' }} ({{ $startTime }} - {{ $endTime }})"

                                            data-current-status="{{ $schedule->status }}"

                                            data-form-id="form-toggle-slot-{{ $schedule->id }}"

                                            {{ $schedule->status === 'active' ? 'checked' : '' }}

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

                                        data-bs-target="#modalEditSlot{{ $schedule->id }}"

                                        title="Edit Slot"
                                    >

                                        <i class="bx bx-pencil"></i>

                                    </button>

                                    {{-- Hapus --}}
                                    <button
                                        type="button"
                                        class="btn btn-icon btn-sm btn-outline-danger"

                                        data-bs-toggle="modal"

                                        data-bs-target="#modalDeleteSlot{{ $schedule->id }}"

                                        title="Hapus Slot"
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

                                        <i class="bx bx-time-five fs-3 text-secondary"></i>

                                    </div>

                                    <h6 class="text-secondary mb-1">
                                        Tidak ada slot jam operasional yang ditemukan.
                                    </h6>

                                    <p class="text-muted small mb-0">
                                        Klik
                                        <strong>Generate Otomatis</strong>
                                        untuk membuat slot per jam secara instan.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if ($schedules->hasPages())

            <div class="card-footer d-flex justify-content-end pb-0 border-top bg-white">

                {{ $schedules->links() }}

            </div>

        @endif

    </div>

    {{-- Modal Tambah Slot Manual --}}
    @include('jadwal.modals.tambah')

    {{-- Modal Generate Slot Otomatis --}}
    @include('jadwal.modals.generate')

    {{-- Modal Edit & Delete --}}
    @foreach ($schedules as $schedule)

        @php

            $startTimeVal = \Carbon\Carbon::parse(
                $schedule->start_time
            )->format('H:i');

            $endTimeVal = \Carbon\Carbon::parse(
                $schedule->end_time
            )->format('H:i');

        @endphp

        {{-- Modal Edit Slot --}}
        @include('jadwal.modals.edit')

        {{-- Modal Konfirmasi Hapus Slot --}}
        @include('jadwal.modals.hapus')

    @endforeach

@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Switch Toggle Status Slot
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btn-toggle-slot').forEach(function (checkbox) {

        checkbox.addEventListener('change', function (e) {

            e.preventDefault();

            const formId =
                this.getAttribute('data-form-id');

            const slotName =
                this.getAttribute('data-slot-name');

            const currentStatus =
                this.getAttribute('data-current-status');

            const newStatusIndo =
                currentStatus === 'active'
                    ? 'nonaktif'
                    : 'aktif';

            // Kembalikan switch ke posisi semula
            this.checked =
                currentStatus === 'active';

            Swal.fire({

                title: 'Ubah Status Slot?',

                text:
                    `Ubah status ${slotName} menjadi ${newStatusIndo}?`,

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