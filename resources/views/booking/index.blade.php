@extends('layouts.app')

@section('title', 'Data Booking Lapangan | bkngftsl.')

@section('content')
<!-- Header Halaman -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Data Booking Masuk</h4>
        <p class="text-muted mb-0 small">Pantau seluruh riwayat transaksi sewa, periksa bukti transfer, dan verifikasi ketersediaan lapangan.</p>
    </div>
</div>

<!-- Filter & Pencarian -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form action="{{ route('bookings.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-12 col-md-3">
                <div class="input-group input-group-merge">
                    <span class="input-group-text"><i class="bx bx-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari kode / nama..." value="{{ request('search') }}">
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
            <div class="col-12 col-md-2">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}" title="Filter Tanggal Main">
            </div>
            <div class="col-12 col-md-2">
                <select name="status" class="form-select">
                    <option value="">-- Status --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100">
                    <i class="bx bx-filter-alt me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'branch_id', 'date', 'status']))
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bx bx-reset"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Card Tabel Booking -->
<div class="card border-0 shadow-sm">
    <div class="table-responsive text-nowrap">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 50px;">NO</th>
                    <th>KODE & PEMESAN</th>
                    <th>VENUE & LAPANGAN</th>
                    <th>JADWAL MAIN</th>
                    <th>TOTAL BAYAR</th>
                    <th>STATUS</th>
                    <th class="text-center" style="width: 150px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $index => $booking)
                    @php
                        $startTime = $booking->schedule ? \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') : '-';
                        $endTime = $booking->schedule ? \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') : '-';
                    @endphp
                    <tr>
                        <td>{{ $bookings->firstItem() + $index }}</td>
                        <td>
                            <span class="fw-bold text-primary">{{ $booking->booking_code }}</span>
                            <br><span class="fw-semibold text-dark">{{ $booking->user?->name ?? 'Tamu' }}</span>
                            <br><small class="text-muted">{{ $booking->user?->email }}</small>
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $booking->field?->field_name ?? '-' }}</span>
                            <br><small class="text-muted"><i class="bx bx-building-house me-1"></i>{{ $booking->branch?->branch_name ?? '-' }}</small>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark"><i class="bx bx-calendar me-1"></i>{{ $booking->booking_date ? $booking->booking_date->format('d M Y') : '-' }}</span>
                            <br><span class="badge bg-label-info mt-1"><i class="bx bx-time-five me-1"></i>{{ $startTime }} - {{ $endTime }} WITA</span>
                        </td>
                        <td>
                            <span class="fw-bold text-success">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            <br><small class="text-muted text-uppercase">Via {{ $booking->payment_method }}</small>
                        </td>
                        <td>
                            @if ($booking->status === 'paid')
                                <span class="badge bg-label-success">Lunas</span>
                            @elseif ($booking->status === 'confirmed')
                                <span class="badge bg-label-primary">Terkonfirmasi</span>
                            @elseif ($booking->status === 'completed')
                                <span class="badge bg-label-info">Selesai Main</span>
                            @elseif ($booking->status === 'pending')
                                <span class="badge bg-label-warning">Menunggu</span>
                            @else
                                <span class="badge bg-label-danger">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <!-- 1. Tombol Detail & Bukti Pembayaran -->
                                <button type="button" class="btn btn-icon btn-sm btn-outline-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetailBooking{{ $booking->id }}"
                                    title="Lihat Detail & Bukti Transfer">
                                    <i class="bx bx-show"></i>
                                </button>

                                <!-- 2. Tombol ACC Lunas Cepat / Ubah Status -->
                                @if ($booking->status !== 'paid')
                                    <form action="{{ route('bookings.approve', $booking->id) }}" method="POST" class="d-inline m-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-icon btn-sm btn-outline-success" title="ACC & Tandai Lunas">
                                            <i class="bx bx-check"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-icon btn-sm btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalStatusBooking{{ $booking->id }}"
                                        title="Ubah Status">
                                        <i class="bx bx-edit-alt"></i>
                                    </button>
                                @endif

                                <!-- 3. Tombol Hapus Booking -->
                                <button type="button" class="btn btn-icon btn-sm btn-outline-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteBooking{{ $booking->id }}"
                                    title="Hapus Data">
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
                                    <i class="bx bx-calendar-x fs-3 text-secondary"></i>
                                </div>
                                <h6 class="text-secondary mb-1">Tidak ada data booking yang ditemukan.</h6>
                                <p class="text-muted small mb-0">Belum ada transaksi sewa lapangan pada filter yang dipilih.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($bookings->hasPages())
        <div class="card-footer d-flex justify-content-end pb-0 border-top bg-white">
            {{ $bookings->links() }}
        </div>
    @endif
</div>

<!-- Modal Modals (Detail, Ubah Status, Hapus) -->
@foreach ($bookings as $booking)
    @php
        $startTime = $booking->schedule ? \Carbon\Carbon::parse($booking->schedule->start_time)->format('H:i') : '-';
        $endTime = $booking->schedule ? \Carbon\Carbon::parse($booking->schedule->end_time)->format('H:i') : '-';
    @endphp

    <!-- 1. Modal Detail & Bukti Pembayaran -->
    <div class="modal fade" id="modalDetailBooking{{ $booking->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Reservasi: {{ $booking->booking_code }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded h-100">
                                <h6 class="fw-bold mb-2">Informasi Pemesanan</h6>
                                <p class="mb-1 small"><strong>Nama Pemesan:</strong> {{ $booking->user?->name ?? 'Tamu' }}</p>
                                <p class="mb-1 small"><strong>Email:</strong> {{ $booking->user?->email ?? '-' }}</p>
                                <p class="mb-1 small"><strong>Venue:</strong> {{ $booking->branch?->branch_name ?? '-' }}</p>
                                <p class="mb-1 small"><strong>Unit Lapangan:</strong> {{ $booking->field?->field_name ?? '-' }}</p>
                                <p class="mb-1 small"><strong>Tanggal Main:</strong> {{ $booking->booking_date ? $booking->booking_date->format('d F Y') : '-' }}</p>
                                <p class="mb-1 small"><strong>Jadwal Main:</strong> {{ $startTime }} - {{ $endTime }} WITA</p>
                                <p class="mb-1 small"><strong>Metode Bayar:</strong> <span class="badge bg-label-secondary text-uppercase">{{ $booking->payment_method }}</span></p>
                                <p class="mb-0 small"><strong>Total Bayar:</strong> <span class="text-success fw-bold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span></p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded text-center h-100 d-flex flex-column justify-content-center">
                                <h6 class="fw-bold mb-2 text-start">Bukti Pembayaran</h6>
                                @if ($booking->payment_proof)
                                    <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank" class="d-block mb-2">
                                        <img src="{{ asset('storage/' . $booking->payment_proof) }}" class="rounded border img-fluid" style="max-height: 180px; object-fit: contain;" alt="Bukti Transfer">
                                    </a>
                                    <small class="text-muted d-block">Klik gambar untuk melihat ukuran penuh</small>
                                @else
                                    <div class="py-4 text-muted">
                                        <i class="bx bx-image-alt fs-1 d-block mb-1"></i>
                                        <small>{{ $booking->payment_method === 'cash' ? 'Pembayaran Tunai di Lokasi (Cash)' : 'Pelanggan belum mengunggah bukti transfer.' }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Modal Ubah Status Booking -->
    <div class="modal fade" id="modalStatusBooking{{ $booking->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('bookings.update-status', $booking->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Status: {{ $booking->booking_code }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Status Baru <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed (Terkonfirmasi)</option>
                            <option value="paid" {{ $booking->status == 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed (Selesai Main)</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Status</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 3. Modal Konfirmasi Hapus Booking -->
    <div class="modal fade" id="modalDeleteBooking{{ $booking->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="{{ route('bookings.destroy', $booking->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bx bx-error-circle text-danger display-3 mb-2"></i>
                    <h5 class="mb-1">Yakin ingin menghapus data booking ini?</h5>
                    <p class="text-muted mb-0">
                        Kode Booking <strong>{{ $booking->booking_code }}</strong> pemesan <strong>{{ $booking->user?->name ?? 'Tamu' }}</strong> akan dihapus permanen.
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
