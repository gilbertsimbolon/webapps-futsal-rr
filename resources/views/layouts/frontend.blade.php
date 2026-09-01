<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <title>@yield('title', 'bkngftsl. - Booking Lapangan Futsal Jadi Lebih Mudah')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Boxicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #696cff;
            --primary-hover: #5f61e6;
            --primary-light: #f5f5f9;
            --dark-color: #233446;
            --body-bg: #f8f9fa;
        }

        body {
            font-family: 'Public Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--body-bg);
            color: #566a7f;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Sticky Glass Navbar */
        .landing-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(67, 89, 113, 0.08);
            transition: all 0.3s ease;
        }

        .landing-navbar.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--dark-color) !important;
            letter-spacing: -0.5px;
        }

        .navbar-brand span {
            color: var(--primary-color);
        }

        .nav-link {
            font-weight: 600;
            color: #566a7f !important;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-color) !important;
        }

        /* Hero */
        .hero-section {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .hero-pattern {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
        }

        /* Badge Soft */
        .badge-soft-primary {
            background-color: rgba(105, 108, 255, 0.12);
            color: var(--primary-color);
        }

        .badge-soft-success {
            background-color: rgba(113, 221, 55, 0.15);
            color: #71dd37;
        }

        /* Card Hover Effects */
        .field-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            border-radius: 1rem;
            overflow: hidden;
        }

        .field-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(67, 89, 113, 0.12) !important;
        }

        .field-card-img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        /* Footer */
        .landing-footer {
            background-color: #0f172a;
            color: #94a3b8;
            margin-top: auto;
        }

        .landing-footer a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .landing-footer a:hover {
            color: #ffffff;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: #ffffff;
                padding: 1.25rem;
                border-radius: 0.75rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
                margin-top: 0.75rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg landing-navbar py-3">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('landing') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" style="width: 34px; height: 34px; object-fit: contain;">
                <span>bkngftsl<span class="text-primary">.</span></span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <i class="bx bx-menu fs-2 text-dark"></i>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('landing') ? 'active' : '' }}" href="{{ route('landing') }}#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('lapangan.*') ? 'active' : '' }}" href="{{ route('lapangan.index') }}">Lapangan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#fasilitas">Fasilitas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#cara-booking">Cara Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#tentang">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}#kontak">Kontak</a>
                    </li>
                </ul>

                <!-- Auth Buttons / User Dropdown -->
                <div class="d-flex align-items-center gap-2 pt-2 pt-lg-0">
                    @guest
                        <a href="{{ route('login.index') }}" class="btn btn-outline-primary px-3 py-2 fw-semibold rounded-pill">
                            <i class="bx bx-log-in me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register.index') }}" class="btn btn-primary px-3 py-2 fw-semibold rounded-pill shadow-sm">
                            Daftar
                        </a>
                    @else
                        <div class="dropdown">
                            <button class="btn btn-light border dropdown-toggle d-flex align-items-center gap-2 py-1 px-3 rounded-pill shadow-sm" type="button" data-bs-toggle="dropdown">
                                <div class="avatar avatar-xs bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 13px;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold text-dark small text-truncate" style="max-width: 130px;">
                                    {{ auth()->user()->name }}
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2 rounded-3">
                                <li class="px-3 py-2 border-bottom">
                                    <small class="text-muted d-block">Masuk sebagai:</small>
                                    <span class="fw-bold text-dark">{{ auth()->user()->name }}</span>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('pelanggan.riwayat.index') }}">
                                        <i class="bx bx-calendar-check text-primary me-2"></i> Booking Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('profil.index') }}">
                                        <i class="bx bx-user text-secondary me-2"></i> Profil Akun
                                    </a>
                                </li>
                                @if (auth()->user()->hasRole('pemilik'))
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('pemilik.dashboard') }}">
                                            <i class="bx bx-store-alt text-success me-2"></i> Dashboard Pemilik
                                        </a>
                                    </li>
                                @elseif (auth()->user()->hasRole('admin'))
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="bx bx-shield-quarter text-danger me-2"></i> Dashboard Admin
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bx bx-log-out me-2"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Alert Flash Message -->
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
                <i class="bx bx-check-circle fs-4 me-2 text-success"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 d-flex align-items-center" role="alert">
                <i class="bx bx-error-circle fs-4 me-2 text-danger"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="landing-footer py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-12 col-lg-4">
                    <a class="navbar-brand d-inline-flex align-items-center gap-2 mb-3 text-white" href="{{ route('landing') }}">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" style="width: 32px; height: 32px; filter: brightness(1.2);">
                        <span class="text-white">bkngftsl<span class="text-primary">.</span></span>
                    </a>
                    <p class="small text-muted mb-4">
                        Platform terpercaya untuk reservasi lapangan futsal terbaik. Temukan venue berkualitas, cek ketersediaan jadwal secara transparan, dan lakukan booking dalam hitungan detik.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bx bxl-instagram fs-5"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bx bxl-whatsapp fs-5"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;"><i class="bx bxl-facebook fs-5"></i></a>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <h6 class="text-white fw-bold mb-3">Navigasi</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('landing') }}#home">Home</a></li>
                        <li><a href="{{ route('lapangan.index') }}">Daftar Lapangan</a></li>
                        <li><a href="{{ route('landing') }}#fasilitas">Fasilitas</a></li>
                        <li><a href="{{ route('landing') }}#cara-booking">Cara Booking</a></li>
                        <li><a href="{{ route('pelanggan.riwayat.index') }}">Booking Saya</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <h6 class="text-white fw-bold mb-3">Bantuan & Legal</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Aturan Venue</a></li>
                        <li><a href="#">Bantuan Reservasi</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-4 col-lg-4">
                    <h6 class="text-white fw-bold mb-3">Kontak & Lokasi</h6>
                    <p class="small text-muted mb-2">
                        <i class="bx bx-map me-2 text-primary"></i> Manado & Sekitarnya, Sulawesi Utara
                    </p>
                    <p class="small text-muted mb-2">
                        <i class="bx bx-phone me-2 text-primary"></i> +62 821-xxxx-xxxx
                    </p>
                    <p class="small text-muted mb-2">
                        <i class="bx bx-envelope me-2 text-primary"></i> support@bkngftsl.com
                    </p>
                    <p class="small text-muted mb-0">
                        <i class="bx bx-time-five me-2 text-primary"></i> Buka Setiap Hari: 08:00 - 24:00 WITA
                    </p>
                </div>
            </div>

            <div class="border-top border-secondary pt-4 mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 small">
                <div>© 2026 <span class="text-white fw-bold">bkngftsl.</span> All rights reserved.</div>
                <div class="text-muted">Sistem Booking Lapangan Olahraga & Futsal Terintegrasi</div>
            </div>
        </div>
    </footer>

    <!-- Global Modal: Login Requirement Gate -->
    <div class="modal fade" id="loginRequirementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-light border-0 pb-0 justify-content-end">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4 pt-1">
                    <div class="avatar avatar-xl bg-label-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                        <i class="bx bx-lock-alt text-primary fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Masuk Akun Diperlukan</h5>
                    <p class="text-muted small mb-4">
                        Silakan masuk terlebih dahulu untuk melakukan booking dan mengamankan jadwal lapangan pilihan Anda.
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('login.index') }}" id="modalLoginBtn" class="btn btn-primary py-2 fw-semibold rounded-pill">
                            <i class="bx bx-log-in me-1"></i> Masuk Sekarang
                        </a>
                        <a href="{{ route('register.index') }}" id="modalRegisterBtn" class="btn btn-outline-secondary py-2 fw-semibold rounded-pill">
                            Daftar Akun Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sticky Navbar Effect on Scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.landing-navbar');
            if (window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Global function to trigger Login Requirement Modal
        window.requireLogin = function(returnUrl) {
            const modalEl = document.getElementById('loginRequirementModal');
            if (modalEl) {
                const loginBtn = document.getElementById('modalLoginBtn');
                const registerBtn = document.getElementById('modalRegisterBtn');

                const loginBaseUrl = "{{ route('login.index') }}";
                const registerBaseUrl = "{{ route('register.index') }}";

                if (returnUrl) {
                    const encoded = encodeURIComponent(returnUrl);
                    if (loginBtn) loginBtn.href = `${loginBaseUrl}?return_to=${encoded}`;
                    if (registerBtn) registerBtn.href = `${registerBaseUrl}?return_to=${encoded}`;
                } else {
                    if (loginBtn) loginBtn.href = loginBaseUrl;
                    if (registerBtn) registerBtn.href = registerBaseUrl;
                }

                const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                bsModal.show();
            } else {
                window.location.href = "{{ route('login.index') }}";
            }
        };
    </script>
    @stack('scripts')
</body>

</html>

