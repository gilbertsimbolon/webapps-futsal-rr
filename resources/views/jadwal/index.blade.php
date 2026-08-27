@extends('layouts.app')

@section('title', 'Slot Jam Operasional | bkngftsl.')

@section('content')
<!-- Header Halaman -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Slot Jam Operasional</h4>
        <p class="text-muted mb-0 small">Atur ketersediaan jam sewa per sesi untuk setiap lapangan futsal.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalGenerateSlot">
            <i class="bx bx-bolt-circle me-1"></i> Generate Otomatis
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreateSlot">
            <i class="bx bx-plus me-1"></i> Tambah Slot
        </button>
    </div>
</div>

<!-- Filter & Pencarian -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('jadwal.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <select name="field_id" class="form-select">
                    <option value="">-- Semua Lapangan --</option>
                    @foreach ($fields as $field)
                        <option value="{{ $field->id }}" {{ request('field_id') == $field->id ? 'selected' : '' }}>
                            {{ $field->branch?->branch_name }} - {{ $field->field_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <select name="day" class="form-select">
                    <option value="">-- Semua Hari --</option>
                    <option value="all" {{ request('day') == 'all' ? 'selected' : '' }}>Setiap Hari (Senin - Minggu)</option>
                    <option value="monday" {{ request('day') == 'monday' ? 'selected' : '' }}>Senin</option>
                    <option value="tuesday" {{ request('day') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                    <option value="wednesday" {{ request('day') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                    <option value="thursday" {{ request('day') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                    <option value="friday" {{ request('day') == 'friday' ? 'selected' : '' }}>Jumat</option>
                    <option value="saturday" {{ request('day') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                    <option value="sunday" {{ request('day') == 'sunday' ? 'selected' : '' }}>Minggu</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bx bx-filter-alt me-1"></i> Filter
                </button>
                @if(request()->hasAny(['field_id', 'day']))
                    <a href="{{ route('jadwal.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bx bx-reset"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Card Tabel Slot -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 60px;">NO</th>
                    <th>CABANG & LAPANGAN</th>
                    <th>HARI</th>
                    <th>SLOT WAKTU</th>
                    <th>TARIF SEWA</th>
                    <th>STATUS</th>
                    <th class="text-center" style="width: 120px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($schedules as $index => $schedule)
                    @php
                        $dayMap = [
                            'all'       => 'Setiap Hari',
                            'monday'    => 'Senin',
                            'tuesday'   => 'Selasa',
                            'wednesday' => 'Rabu',
                            'thursday'  => 'Kamis',
                            'friday'    => 'Jumat',
                            'saturday'  => 'Sabtu',
                            'sunday'    => 'Minggu',
                        ];
                        $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
                        $endTime = \Carbon\Carbon::parse($schedule->end_time)->format('H:i');
                        $fieldPrice = $schedule->custom_price ?? $schedule->field?->price_per_hour ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $schedules->firstItem() + $index }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $schedule->field?->field_name ?? '-' }}</span>
                            <br><small class="text-muted"><i class="bx bx-building-house me-1"></i>{{ $schedule->field?->branch?->branch_name ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $schedule->day == 'all' ? 'bg-label-primary' : 'bg-label-secondary' }}">
                                {{ $dayMap[$schedule->day] ?? ucfirst($schedule->day) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-label-info fs-7 px-3 py-1">
                                <i class="bx bx-time-five me-1"></i>{{ $startTime }} - {{ $endTime }} WITA
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-success">Rp {{ number_format($fieldPrice, 0, ',', '.') }}</span>
                            @if ($schedule->custom_price)
                                <br><small class="text-warning" style="font-size: 11px;">(Harga Khusus)</small>
                            @endif
                        </td>
                        <td>
                            <!-- Switch Toggle Status -->
                            <form id="form-toggle-slot-{{ $schedule->id }}" action="{{ route('jadwal.toggle-status', $schedule->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch m-0 d-inline-block">
                                    <input class="form-check-input btn-toggle-slot" type="checkbox" role="switch"
                                        data-slot-name="{{ $schedule->field?->field_name }} ({{ $startTime }} - {{ $endTime }})"
                                        data-current-status="{{ $schedule->status }}"
                                        data-form-id="form-toggle-slot-{{ $schedule->id }}"
                                        {{ $schedule->status === 'active' ? 'checked' : '' }}
                                        style="cursor: pointer; width: 2.5em; height: 1.3em;">
                                </div>
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <!-- Tombol Modal Edit -->
                                <button type="button" class="btn btn-icon btn-sm btn-outline-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditSlot{{ $schedule->id }}"
                                    title="Edit Slot">
                                    <i class="bx bx-pencil"></i>
                                </button>

                                <!-- Tombol Modal Hapus -->
                                <button type="button" class="btn btn-icon btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteSlot{{ $schedule->id }}"
                                    title="Hapus Slot">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="avatar avatar-md bg-label-secondary mb-2 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bx bx-time-five fs-3 text-secondary"></i>
                                </div>
                                <h6 class="text-secondary mb-1">Tidak ada slot jam operasional yang ditemukan.</h6>
                                <p class="text-muted small mb-0">Klik tombol <strong>Generate Otomatis</strong> untuk membuat slot per jam secara instan.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($schedules->hasPages())
        <div class="card-footer d-flex justify-content-end pb-0 border-top bg-white">
            {{ $schedules->links() }}
        </div>
    @endif
</div>

<!-- Modal Tambah Slot Manual -->
@include('jadwal.modals.tambah')

<!-- Modal Generate Slot Otomatis -->
@include('jadwal.modals.generate')

<!-- Modal Edit & Delete (Di Luar Tabel) -->
@foreach ($schedules as $schedule)
    @php
        $startTimeVal = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
        $endTimeVal = \Carbon\Carbon::parse($schedule->end_time)->format('H:i');
    @endphp

    <!-- Modal Edit Slot -->
    @include('jadwal.modals.edit')

    <!-- Modal Konfirmasi Hapus Slot -->
    <div class="modal fade" id="modalDeleteSlot{{ $schedule->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('jadwal.destroy', $schedule->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bx bx-error-circle text-danger display-3 mb-2"></i>
                    <h5 class="mb-1">Yakin ingin menghapus slot ini?</h5>
                    <p class="text-muted mb-0">
                        Slot jam <strong>{{ $startTimeVal }} - {{ $endTimeVal }}</strong> pada <strong>{{ $schedule->field?->field_name }}</strong> akan dihapus.
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
    // Switch Toggle Status Slot
    document.querySelectorAll('.btn-toggle-slot').forEach(checkbox => {
        checkbox.addEventListener('change', function (e) {
            e.preventDefault();
            const formId = this.getAttribute('data-form-id');
            const slotName = this.getAttribute('data-slot-name');
            const currentStatus = this.getAttribute('data-current-status');
            const newStatusIndo = (currentStatus === 'active') ? 'nonaktif' : 'aktif';

            this.checked = (currentStatus === 'active');

            Swal.fire({
                title: 'Ubah Status Slot?',
                text: `Ubah status ${slotName} menjadi ${newStatusIndo}?`,
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
