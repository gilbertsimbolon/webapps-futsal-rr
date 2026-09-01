@extends('layouts.frontend')

@section('title', 'bkngftsl. - Booking Lapangan Jadi Lebih Mudah')

@section('content')
    <!-- =========================================================
         1. HERO SECTION
    ========================================================= -->
    <section id="home" class="hero-section py-5 py-lg-6">
        <div class="hero-pattern"></div>
        <div class="container position-relative py-4 py-lg-5">
            <div class="row align-items-center g-5">
                <div class="col-12 col-lg-7 text-center text-lg-start">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-white bg-opacity-10 border border-white border-opacity-10 mb-3">
                        <i class="bx bxs-check-shield text-success"></i>
                        <span class="small text-white fw-semibold">Sistem Reservasi Futsal #1 Terpercaya</span>
                    </div>

                    <h1 class="display-4 fw-extrabold text-white mb-3" style="line-height: 1.15; letter-spacing: -1px;">
                        Booking Lapangan<br>
                        <span style="background: linear-gradient(90deg, #818cf8, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            Jadi Lebih Mudah.
                        </span>
                    </h1>

                    <p class="lead text-white text-opacity-75 mb-4 mb-lg-5" style="max-width: 580px;">
                        Temukan lapangan terbaik, pilih jadwal yang sesuai, dan booking dalam beberapa langkah tanpa ribet antre.
                    </p>

                    <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
                        @guest
                            <button type="button" class="btn btn-primary btn-lg px-4 py-3 fw-bold rounded-pill shadow-lg" onclick="requireLogin('{{ route('lapangan.index') }}')">
                                <i class="bx bx-calendar-check me-2"></i> Booking Sekarang
                            </button>
                        @else
                            <a href="{{ route('lapangan.index') }}" class="btn btn-primary btn-lg px-4 py-3 fw-bold rounded-pill shadow-lg">
                                <i class="bx bx-calendar-check me-2"></i> Booking Sekarang
                            </a>
                        @endguest

                        <a href="#lapangan" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold rounded-pill">
                            <i class="bx bx-search-alt-2 me-2"></i> Lihat Lapangan
                        </a>
                    </div>

                    <!-- Highlight Stats -->
                    <div class="row g-3 mt-4 pt-3 border-top border-white border-opacity-10 text-center text-lg-start">
                        <div class="col-4">
                            <h4 class="fw-bold text-white mb-0">{{ $branches->count() }}+</h4>
                            <small class="text-white text-opacity-75" style="font-size: 12px;">Cabang Venue</small>
                        </div>
                        <div class="col-4">
                            <h4 class="fw-bold text-white mb-0">{{ $fields->count() }}+</h4>
                            <small class="text-white text-opacity-75" style="font-size: 12px;">Unit Lapangan</small>
                        </div>
                        <div class="col-4">
                            <h4 class="fw-bold text-white mb-0">24/7</h4>
                            <small class="text-white text-opacity-75" style="font-size: 12px;">Jadwal Real-Time</small>
                        </div>
                    </div>
                </div>

                <!-- Hero Illustration / Image Card -->
                <div class="col-12 col-lg-5 d-none d-lg-block">
                    <div class="position-relative">
                        <div class="card border-0 shadow-2-strong rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                            <div class="card-body p-4 text-white">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-success rounded-pill px-3 py-1">Slot Tersedia</span>
                                    <span class="text-white-50 small"><i class="bx bx-time-five me-1"></i> Update Otomatis</span>
                                </div>
                                <div class="rounded-3 overflow-hidden mb-3" style="height: 220px; background: #334155;">
                                    @if ($fields->isNotEmpty() && $fields->first()->image)
                                        <img src="{{ asset('storage/' . $fields->first()->image) }}" alt="Lapangan" class="w-100 h-100" style="object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white-50 bg-secondary bg-opacity-25">
                                            <i class="bx bx-football fs-1 mb-2"></i>
                                            <span class="small fw-semibold">Arena Futsal Modern</span>
                                        </div>
                                    @endif
                                </div>
                                <h5 class="fw-bold text-white mb-1">{{ $fields->first()?->field_name ?? 'Lapangan Futsal Utama' }}</h5>
                                <p class="text-white-50 small mb-3"><i class="bx bx-map-pin me-1 text-primary"></i>{{ $fields->first()?->branch?->branch_name ?? 'Sport Center' }}</p>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top border-white border-opacity-10">
                                    <div>
                                        <small class="text-white-50 d-block" style="font-size: 11px;">Mulai dari</small>
                                        <span class="fw-bold text-warning fs-5">Rp {{ number_format($fields->first()?->price_per_hour ?? 120000, 0, ',', '.') }}</span>
                                        <small class="text-white-50">/ jam</small>
                                    </div>
                                    <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="bx bx-check me-1"></i>Siap Main</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================
         2. SEARCH / FIND FIELD WIDGET
    ========================================================= -->
    <section id="search-section" class="py-4" style="margin-top: -40px; position: relative; z-index: 20;">
        <div class="container">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bx bx-filter-alt fs-4 text-primary"></i>
                        <h5 class="fw-bold text-dark mb-0">Temukan Lapangan Favoritmu</h5>
                    </div>

                    <form action="{{ route('lapangan.index') }}" method="GET" class="row g-3 align-items-end">
                        <!-- Cabang / Lokasi -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label small fw-semibold text-muted mb-1"><i class="bx bx-map-pin me-1"></i>Lokasi Venue</label>
                            <select name="branch_id" class="form-select py-2">
                                <option value="">Semua Cabang Venue</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Jenis Lapangan -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label small fw-semibold text-muted mb-1"><i class="bx bx-football me-1"></i>Jenis Lapangan</label>
                            <select name="field_type" class="form-select py-2">
                                <option value="">Semua Tipe Rumput/Lantai</option>
                                @foreach ($fieldTypes as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Harga Maksimal -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label small fw-semibold text-muted mb-1"><i class="bx bx-money me-1"></i>Batas Tarif / Jam</label>
                            <select name="max_price" class="form-select py-2">
                                <option value="">Semua Tarif</option>
                                <option value="100000">Hingga Rp 100.000</option>
                                <option value="150000">Hingga Rp 150.000</option>
                                <option value="200000">Hingga Rp 200.000</option>
                                <option value="300000">Hingga Rp 300.000</option>
                            </select>
                        </div>

                        <!-- Tombol Cari -->
                        <div class="col-12 col-sm-6 col-lg-3">
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm">
                                <i class="bx bx-search me-1"></i> Cari Lapangan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================
         3. DAFTAR LAPANGAN PILIHAN
    ========================================================= -->
    <section id="lapangan" class="py-5">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end mb-4 gap-2">
                <div>
                    <span class="badge badge-soft-primary px-3 py-1 rounded-pill mb-2 fw-semibold">Pilihan Terbaik</span>
                    <h2 class="fw-extrabold text-dark mb-1">Lapangan Pilihan</h2>
                    <p class="text-muted mb-0">Rekomendasi lapangan futsal terbaik dengan spesifikasi standar dan fasilitas lengkap.</p>
                </div>
                <a href="{{ route('lapangan.index') }}" class="btn btn-outline-primary fw-semibold rounded-pill px-4">
                    Lihat Semua Lapangan <i class="bx bx-right-arrow-alt ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @forelse ($fields as $field)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm field-card bg-white">
                            <!-- Foto Lapangan -->
                            <div class="position-relative">
                                @if ($field->image)
                                    <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->field_name }}" class="field-card-img">
                                @else
                                    <div class="field-card-img bg-light d-flex flex-column align-items-center justify-content-center text-muted">
                                        <i class="bx bx-football fs-1 mb-1 text-primary"></i>
                                        <span class="small fw-semibold">bkngftsl. Arena</span>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-dark bg-opacity-75 text-uppercase px-2 py-1 rounded-pill" style="font-size: 11px;">
                                        {{ $field->field_type ?? 'Futsal' }}
                                    </span>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 11px;">
                                        <i class="bx bx-check-circle me-1"></i>Tersedia
                                    </span>
                                </div>
                            </div>

                            <!-- Detail Card -->
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="mb-2">
                                    <h5 class="fw-bold text-dark mb-1 text-truncate">{{ $field->field_name }}</h5>
                                    <small class="text-muted d-flex align-items-center gap-1">
                                        <i class="bx bx-map-pin text-danger"></i> {{ $field->branch?->branch_name ?? 'Venue Futsal' }}
                                    </small>
                                </div>

                                <!-- Fasilitas Singkat -->
                                <div class="d-flex flex-wrap gap-2 my-3 py-2 border-top border-bottom">
                                    <span class="badge bg-light text-muted fw-normal"><i class="bx bx-check me-1 text-success"></i>Indoor</span>
                                    <span class="badge bg-light text-muted fw-normal"><i class="bx bx-check me-1 text-success"></i>Lampu LED</span>
                                    <span class="badge bg-light text-muted fw-normal"><i class="bx bx-check me-1 text-success"></i>Toilet</span>
                                    <span class="badge bg-light text-muted fw-normal"><i class="bx bx-check me-1 text-success"></i>Parkir</span>
                                </div>

                                <div class="mt-auto pt-2 d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 11px;">Harga Sewa</small>
                                        <span class="fw-bold text-primary fs-5">Rp {{ number_format($field->price_per_hour, 0, ',', '.') }}</span>
                                        <small class="text-muted">/ jam</small>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row g-2 mt-3">
                                    <div class="col-6">
                                        <a href="{{ route('lapangan.detail', $field->id) }}" class="btn btn-outline-secondary btn-sm w-100 py-2 fw-semibold rounded-pill">
                                            Lihat Detail
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        @guest
                                            <button type="button" class="btn btn-primary btn-sm w-100 py-2 fw-semibold rounded-pill shadow-sm" onclick="requireLogin('{{ route('pelanggan.booking.create', $field->id) }}')">
                                                Booking
                                            </button>
                                        @else
                                            <a href="{{ route('pelanggan.booking.create', $field->id) }}" class="btn btn-primary btn-sm w-100 py-2 fw-semibold rounded-pill shadow-sm">
                                                Booking
                                            </a>
                                        @endguest
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="bx bx-football fs-1 mb-2 text-primary"></i>
                        <p class="mb-0">Belum ada data lapangan yang aktif saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- =========================================================
         4. FASILITAS VENUE
    ========================================================= -->
    <section id="fasilitas" class="py-5 bg-white border-top border-bottom">
        <div class="container py-3">
            <div class="text-center mb-5">
                <span class="badge badge-soft-primary px-3 py-1 rounded-pill mb-2 fw-semibold">Kenyamanan Maksimal</span>
                <h2 class="fw-extrabold text-dark mb-1">Fasilitas Lengkap Venue</h2>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    Setiap venue dilengkapi sarana pendukung standar kompetisi untuk memastikan kenyamanan tim dan penonton Anda.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-none bg-light rounded-4 p-4 text-center h-100">
                        <div class="avatar avatar-md bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="bx bx-trophy fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Lantai Standar FIFA</h6>
                        <small class="text-muted">Pilihan rumput sintetis, vinyl berkualitas, dan interlock anti slip.</small>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-none bg-light rounded-4 p-4 text-center h-100">
                        <div class="avatar avatar-md bg-warning text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="bx bx-sun fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Lampu Sorot LED</h6>
                        <small class="text-muted">Penerangan terang merata dan hemat energi untuk pertandingan malam hari.</small>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-none bg-light rounded-4 p-4 text-center h-100">
                        <div class="avatar avatar-md bg-info text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="bx bx-car fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Area Parkir Luas</h6>
                        <small class="text-muted">Tempat parkir mobil dan motor yang aman dan dijaga petugas keamanan.</small>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-none bg-light rounded-4 p-4 text-center h-100">
                        <div class="avatar avatar-md bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="bx bx-wifi fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Free WiFi & Loker</h6>
                        <small class="text-muted">Koneksi internet cepat untuk pengunjung dan loker penyimpanan barang.</small>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-none bg-light rounded-4 p-4 text-center h-100">
                        <div class="avatar avatar-md bg-danger text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="bx bx-bath fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Kamar Mandi & Toilet</h6>
                        <small class="text-muted">Kamar mandi shower dan toilet bersih terawat di setiap sudut venue.</small>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-none bg-light rounded-4 p-4 text-center h-100">
                        <div class="avatar avatar-md bg-secondary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="bx bx-coffee fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Kantin & Minuman</h6>
                        <small class="text-muted">Kantin makanan ringan, minuman isotonic dingin, dan kopi hangat.</small>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-none bg-light rounded-4 p-4 text-center h-100">
                        <div class="avatar avatar-md bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="bx bx-church fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Musholla Nyaman</h6>
                        <small class="text-muted">Sarana ibadah yang bersih dan tenang untuk pemain dan pengunjung.</small>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-none bg-light rounded-4 p-4 text-center h-100">
                        <div class="avatar avatar-md bg-dark text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="bx bx-group fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Tribun Penonton</h6>
                        <small class="text-muted">Tribun tempat duduk nyaman untuk menonton pertandingan dan sparring.</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================
         5. CARA BOOKING 4 LANGKAH
    ========================================================= -->
    <section id="cara-booking" class="py-5 bg-light">
        <div class="container py-3">
            <div class="text-center mb-5">
                <span class="badge badge-soft-primary px-3 py-1 rounded-pill mb-2 fw-semibold">Proses Cepat & Mudah</span>
                <h2 class="fw-extrabold text-dark mb-1">Cara Booking Lapangan</h2>
                <p class="text-muted mx-auto" style="max-width: 500px;">
                    Hanya 4 langkah mudah untuk mengamankan jadwal tanding futsal bersama rekan tim Anda.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white position-relative">
                        <div class="badge bg-primary rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 50px; height: 50px;">
                            1
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Pilih Lapangan</h5>
                        <p class="text-muted small mb-0">Cari lokasi venue futsal terdekat dan tentukan lapangan dengan jenis lantai yang Anda inginkan.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white position-relative">
                        <div class="badge bg-primary rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 50px; height: 50px;">
                            2
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Pilih Jadwal & Jam</h5>
                        <p class="text-muted small mb-0">Lihat ketersediaan jadwal slot waktu secara real-time dan tentukan durasi bermain.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white position-relative">
                        <div class="badge bg-primary rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 50px; height: 50px;">
                            3
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Bayar (DP / Penuh)</h5>
                        <p class="text-muted small mb-0">Pilih opsi pembayaran penuh atau DP 50% melalui QRIS atau transfer rekening pemilik venue.</p>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white position-relative">
                        <div class="badge bg-primary rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center fw-bold fs-5" style="width: 50px; height: 50px;">
                            4
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Datang & Main</h5>
                        <p class="text-muted small mb-0">Dapatkan kode booking unik, tiba di venue, lakukan check-in di kasir, dan selamat bertanding!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================
         6. TENTANG KAMI
    ========================================================= -->
    <section id="tentang" class="py-5 bg-white">
        <div class="container py-3">
            <div class="row align-items-center g-5">
                <div class="col-12 col-lg-6">
                    <span class="badge badge-soft-primary px-3 py-1 rounded-pill mb-2 fw-semibold">Tentang Platform</span>
                    <h2 class="fw-extrabold text-dark mb-3">Revolusi Reservasi Olahraga Bersama bkngftsl.</h2>
                    <p class="text-muted mb-3">
                        <strong>bkngftsl.</strong> adalah platform manajemen dan booking lapangan futsal modern yang menghubungkan komunitas pecinta olahraga dengan pengelola venue futsal terbaik di berbagai cabang.
                    </p>
                    <p class="text-muted mb-4">
                        Kami mengintegrasikan sistem penjadwalan adaptif dinamis dengan algoritma anti-bentrok, sistem pelunasan DP yang transparan, dan pengelolaan kasir terpadu yang memudahkan pemilik venue maupun para pemain futsal.
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bxs-check-circle text-success fs-4"></i>
                                <span class="fw-semibold text-dark">Bebas Jadwal Bentrok</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bxs-check-circle text-success fs-4"></i>
                                <span class="fw-semibold text-dark">Metode Bayar Resmi Pemilik</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bxs-check-circle text-success fs-4"></i>
                                <span class="fw-semibold text-dark">Opsi DP 50% Fleksibel</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bx bxs-check-circle text-success fs-4"></i>
                                <span class="fw-semibold text-dark">Riwayat Booking Transparan</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-light text-center">
                        <div class="avatar avatar-xl bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 75px; height: 75px;">
                            <i class="bx bx-football fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Punya Lapangan Futsal?</h4>
                        <p class="text-muted small mb-4">
                            Daftarkan venue Anda sekarang untuk memperluas jangkauan komunitas futsal dan kelola jadwal booking dengan sistem kasir POS otomatis.
                        </p>
                        @guest
                            <a href="{{ route('register.index') }}" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill mx-auto">
                                Daftar Sebagai Pemilik Venue
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================
         7. KONTAK & LOKASI
    ========================================================= -->
    <section id="kontak" class="py-5 bg-light border-top">
        <div class="container py-3">
            <div class="text-center mb-5">
                <span class="badge badge-soft-primary px-3 py-1 rounded-pill mb-2 fw-semibold">Hubungi Kami</span>
                <h2 class="fw-extrabold text-dark mb-1">Kontak & Pusat Bantuan</h2>
                <p class="text-muted mx-auto" style="max-width: 500px;">
                    Ada pertanyaan seputar reservasi jadwal atau kemitraan cabang? Tim kami siap membantu Anda.
                </p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
                        <i class="bx bxs-phone-call text-primary fs-1 mb-2"></i>
                        <h6 class="fw-bold text-dark mb-1">Customer Support</h6>
                        <p class="text-muted small mb-2">Senin - Minggu: 08:00 - 24:00 WITA</p>
                        <span class="fw-bold text-primary">+62 821-xxxx-xxxx</span>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
                        <i class="bx bxs-envelope text-primary fs-1 mb-2"></i>
                        <h6 class="fw-bold text-dark mb-1">Email Resmi</h6>
                        <p class="text-muted small mb-2">Untuk konfirmasi & pertanyaan umum</p>
                        <span class="fw-bold text-primary">support@bkngftsl.com</span>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
                        <i class="bx bxs-map text-primary fs-1 mb-2"></i>
                        <h6 class="fw-bold text-dark mb-1">Pusat Operasional</h6>
                        <p class="text-muted small mb-2">Sulawesi Utara, Indonesia</p>
                        <span class="fw-bold text-dark">Manado Futsal Center</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

