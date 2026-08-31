@extends('layouts.app')

@section('title', 'Simulasi Alur Round Robin | bkngftsl.')

@section('content')
<!-- Header Halaman -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1">Simulasi Algoritma Round Robin (RR)</h4>
        <p class="text-muted mb-0 small">Uji coba interaktif pengalokasian slot dan rotasi Time Quantum saat terjadi bentrok waktu.</p>
    </div>
    <a href="{{ route('admin.round-robin.monitoring') }}" class="btn btn-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali ke Monitoring
    </a>
</div>

<div class="row g-4">
    <!-- Form Input Uji Coba Masuk Antrean -->
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bx bx-plus-circle me-2 text-primary"></i>Tambah Proses Antrean Baru</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.round-robin.enqueue') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pilih Unit Lapangan <span class="text-danger">*</span></label>
                        <select name="field_id" class="form-select" required>
                            @foreach ($fields as $field)
                                <option value="{{ $field->id }}">{{ $field->field_name }} ({{ $field->branch?->branch_name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Tim / Pemesan (Proses P) <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" placeholder="Contoh: Tim Rajawali / Tim Garuda" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="booking_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Jam Target <span class="text-danger">*</span></label>
                            <input type="text" name="start_time" class="form-control" placeholder="15:50" value="15:50" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-log-in me-1"></i> Masukkan ke Ready Queue
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Penjelasan Cara Kerja Simulasi -->
    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold"><i class="bx bx-info-circle me-2 text-primary"></i>Cara Kerja Parameter Simulasi</h5>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-start mb-3">
                    <div class="avatar avatar-sm bg-label-primary rounded-circle me-3 flex-shrink-0 d-flex align-items-center justify-content-center">
                        <i class="bx bx-time fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Time Quantum ($q = 15\text{ Menit}$)</h6>
                        <p class="text-muted small mb-0">Setiap proses antrean yang masuk pertama kali diberikan hak eksklusif untuk mengunci slot selama 15 menit untuk menyelesaikan verifikasi transfer.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-3">
                    <div class="avatar avatar-sm bg-label-warning rounded-circle me-3 flex-shrink-0 d-flex align-items-center justify-content-center">
                        <i class="bx bx-git-merge fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Ready Queue & Preemption</h6>
                        <p class="text-muted small mb-0">Jika ada pemesan kedua memasukkan jam yang sama saat proses pertama aktif, pemesan kedua ditempatkan di <em>Ready Queue</em>. Jika pemesan pertama gagal/habis waktu, kendali dioper (*preempted*) secara otomatis.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <div class="avatar avatar-sm bg-label-success rounded-circle me-3 flex-shrink-0 d-flex align-items-center justify-content-center">
                        <i class="bx bx-check fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Pergeseran Waktu Otomatis (Dynamic Shift)</h6>
                        <p class="text-muted small mb-0">Jika transaksi selesai pada jam ganjil (15:50 - 16:50), jadwal ketersediaan berikutnya secara otomatis menghitung slot baru mulai 16:50.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
