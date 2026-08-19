@extends('layouts.app')

@section('title', 'Master Data Cabang | bkngftsl.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold py-1 mb-0">Master Data Cabang</h4>
        <p class="text-muted mb-0 small">Kelola informasi cabang dan lokasi lapangan futsal.</p>
    </div>

    <!-- Tombol Tambah Cabang (Membuka Modal) -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahCabangModal">
        <i class="bx bx-plus me-1"></i> Tambah Cabang
    </button>
</div>

<!-- GRID CARD STATIS -->
<div class="row g-4">

    <!-- Card Cabang 1 -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
            <!-- Gambar Cabang -->
            <img class="card-img-top" src="{{ asset('img/default-cabang.jpg') }}" alt="Golden Futsal Center" style="height: 180px; object-fit: cover;" />

            <div class="card-body">
                <h5 class="card-title fw-bold mb-2">Golden Futsal Center</h5>

                <p class="card-text text-muted mb-2 small">
                    <i class="bx bx-map text-danger me-1"></i> Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan
                </p>

                <p class="card-text text-muted mb-3 small">
                    <i class="bx bx-phone text-success me-1"></i> 0812-3456-7890
                </p>

                <!-- Informasi Pemilik (Khusus Admin) -->
                <div class="badge bg-label-info">
                    <i class="bx bx-user me-1"></i> Pemilik: Budi Santoso
                </div>
            </div>

            <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center">
                <span class="badge bg-label-primary">
                    <i class="bx bx-football me-1"></i> 3 Lapangan
                </span>

                <div>
                    <!-- Tombol Edit -->
                    <button type="button" class="btn btn-sm btn-icon btn-outline-warning me-1"
                            data-bs-toggle="modal" data-bs-target="#editCabangModal1" title="Edit">
                        <i class="bx bx-edit-alt"></i>
                    </button>
                    <!-- Tombol Hapus -->
                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#hapusCabangModal1" title="Hapus">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Cabang 2 -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
            <img class="card-img-top" src="{{ asset('img/default-cabang.jpg') }}" alt="Champion Futsal Arena" style="height: 180px; object-fit: cover;" />

            <div class="card-body">
                <h5 class="card-title fw-bold mb-2">Champion Futsal Arena</h5>

                <p class="card-text text-muted mb-2 small">
                    <i class="bx bx-map text-danger me-1"></i> Jl. Gatot Subroto No. 88, Bandung
                </p>

                <p class="card-text text-muted mb-3 small">
                    <i class="bx bx-phone text-success me-1"></i> 0819-8765-4321
                </p>

                <div class="badge bg-label-info">
                    <i class="bx bx-user me-1"></i> Pemilik: Andi Pratama
                </div>
            </div>

            <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center">
                <span class="badge bg-label-primary">
                    <i class="bx bx-football me-1"></i> 2 Lapangan
                </span>

                <div>
                    <button type="button" class="btn btn-sm btn-icon btn-outline-warning me-1"
                            data-bs-toggle="modal" data-bs-target="#editCabangModal2" title="Edit">
                        <i class="bx bx-edit-alt"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#hapusCabangModal2" title="Hapus">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Cabang 3 -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
            <img class="card-img-top" src="{{ asset('img/default-cabang.jpg') }}" alt="Garuda Futsal Hub" style="height: 180px; object-fit: cover;" />

            <div class="card-body">
                <h5 class="card-title fw-bold mb-2">Garuda Futsal Hub</h5>

                <p class="card-text text-muted mb-2 small">
                    <i class="bx bx-map text-danger me-1"></i> Jl. Pemuda No. 12, Surabaya
                </p>

                <p class="card-text text-muted mb-3 small">
                    <i class="bx bx-phone text-success me-1"></i> 0857-1122-3344
                </p>

                <div class="badge bg-label-info">
                    <i class="bx bx-user me-1"></i> Pemilik: Budi Santoso
                </div>
            </div>

            <div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center">
                <span class="badge bg-label-primary">
                    <i class="bx bx-football me-1"></i> 4 Lapangan
                </span>

                <div>
                    <button type="button" class="btn btn-sm btn-icon btn-outline-warning me-1"
                            data-bs-toggle="modal" data-bs-target="#editCabangModal3" title="Edit">
                        <i class="bx bx-edit-alt"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger"
                            data-bs-toggle="modal" data-bs-target="#hapusCabangModal3" title="Hapus">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- MODAL TAMBAH CABANG STATIS -->
<div class="modal fade" id="tambahCabangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Cabang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Cabang</label>
                        <input type="text" class="form-control" placeholder="Contoh: Golden Futsal Center" required>
                    </div>

                    <!-- Pilihan Pemilik (Tampil jika Login sebagai Admin) -->
                    <div class="mb-3">
                        <label class="form-label">Pemilik Cabang</label>
                        <select class="form-select">
                            <option selected disabled>-- Pilih Pemilik --</option>
                            <option value="1">Budi Santoso</option>
                            <option value="2">Andi Pratama</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Kontak / WhatsApp</label>
                        <input type="text" class="form-control" placeholder="Contoh: 081234567890">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" rows="3" placeholder="Alamat lengkap cabang..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Cabang</label>
                        <input type="file" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Simpan Cabang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
