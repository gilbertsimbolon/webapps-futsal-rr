<style>
    :root {
        --sidebar-width: 260px;
    }

    #layout-menu {
        width: var(--sidebar-width) !important;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        z-index: 1075;
    }

    .layout-page {
        padding-left: var(--sidebar-width) !important;
        transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        min-height: 100vh;
    }

    html.sidebar-fully-hidden #layout-menu {
        transform: translateX(-100%) !important;
    }

    html.sidebar-fully-hidden .layout-page {
        padding-left: 0 !important;
    }

    #btn-sneat-toggle {
        position: absolute;
        top: 50%;
        right: -15px;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        z-index: 1090;
        cursor: pointer;
    }

    .menu-inner::-webkit-scrollbar {
        width: 4px;
    }

    .menu-inner::-webkit-scrollbar-thumb {
        background-color: rgba(67, 89, 113, 0.15);
        border-radius: 4px;
    }

    @media (max-width: 1199.98px) {
        .layout-page {
            padding-left: 0 !important;
        }
    }
</style>

<aside id="layout-menu"
    class="layout-menu menu-vertical menu bg-menu-theme position-fixed d-flex flex-column vh-100 start-0 top-0 overflow-visible shadow-sm">

    <!-- Tombol Toggle Buka/Tutup  -->
    <a href="javascript:void(0);" id="btn-sneat-toggle"
        class="btn btn-sm btn-primary btn-icon rounded-circle shadow d-none d-xl-flex align-items-center justify-content-center border border-2 border-white p-0"
        title="Buka / Tutup Sidebar">
        <i id="btn-sneat-icon" class="bx bx-chevron-left fs-5"></i>
    </a>

    <!-- Header -->
    <div class="app-brand demo d-flex flex-column align-items-center justify-content-center text-center p-3 flex-shrink-0 border-bottom bg-menu-theme position-sticky top-0"
        style="z-index: 10; height: auto !important;">
        <a href="#"
            class="app-brand-link d-flex flex-column align-items-center text-decoration-none w-100 p-0 m-0">
            <span class="app-brand-logo demo mb-2">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="rounded"
                    style="width: 42px; height: 42px; object-fit: contain;"
                    onerror="this.style.display='none'; document.getElementById('alt-logo-icon').style.display='block';">
                <i id="alt-logo-icon" class="bx bx-football fs-2 text-primary" style="display: none;"></i>
            </span>
            <span class="app-brand-text demo text-heading fw-bold fs-5 text-truncate px-1">
                bkngftsl.
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <!-- Menu -->
    <ul class="menu-inner py-2 flex-grow-1 overflow-y-auto overflow-x-hidden m-0">

        <li class="menu-item active">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- Menu Pelanggan -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Menu Pelanggan</span>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-search-alt"></i>
                <div data-i18n="Cari & Sewa">Cari & Sewa Lapangan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div data-i18n="Kalender Ketersediaan">Kalender Ketersediaan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-history"></i>
                <div data-i18n="Riwayat Booking">Riwayat Booking Saya</div>
            </a>
        </li>

        <!-- Kelola Venua (Menu Owner) -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Kelola Venue (Owner)</span>
        </li>

        <li class="menu-item {{ request()->routeIs('cabang.*') ? 'active' : '' }}">
            <a href="{{ route('cabang.index') }}" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-building-house"></i>
                <div data-i18n="Data Cabang">Data Cabang / Lokasi</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('lapangan.*') ? 'active' : '' }}">
            <a href="{{ route('lapangan.index') }}" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div data-i18n="Data Lapangan">Data Lapangan</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
            <a href="{{ route('jadwal.index') }}" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-time-five"></i>
                <div data-i18n="Slot Jam Operasional">Slot Jam Operasional</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
            <a href="{{ route('bookings.index') }}" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                <div data-i18n="Booking Masuk">Booking Masuk Lapangan</div>
            </a>
        </li>

        <!-- Algoritma Round Robin -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Algoritma Round Robin</span>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-sync"></i>
                <div data-i18n="Monitoring Antrean">Monitoring Antrean RR</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-calculator"></i>
                <div data-i18n="Simulasi Alur">Simulasi Alur RR</div>
            </a>
        </li>

        <!-- Laporan -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Laporan</span>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Laporan Booking">Laporan Booking</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                <div data-i18n="Laporan Pendapatan">Laporan Pendapatan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                <div data-i18n="Laporan Lapangan">Laporan Lapangan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div data-i18n="Laporan Pelanggan">Laporan Pelanggan</div>
            </a>
        </li>

        <!-- Pengelolaan Pengguna -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Admin</span>
        </li>

        <li class="menu-item {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
            <a href="{{ route('pengguna.index') }}" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-user-check"></i>
                <div data-i18n="Manajemen User">Manajemen Pengguna</div>
            </a>
        </li>

    </ul>

    <!-- Menu User -->
    <div class="p-3 border-top mt-auto flex-shrink-0 bg-menu-theme w-100 position-sticky bottom-0"
        style="z-index: 10;">
        <a href="{{ route('profil.index') }}"
            class="d-flex align-items-center text-decoration-none text-heading p-2 rounded hover-light mb-2 w-100"
            title="{{ auth()->user()?->name ?? 'Pengguna' }}">
            <div class="avatar avatar-sm me-2 flex-shrink-0">
                <span class="avatar-initial rounded-circle bg-label-primary fw-bold">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                </span>
            </div>
            <div class="menu-text overflow-hidden">
                <h6 class="mb-0 text-truncate fs-7 fw-bold">
                    {{ auth()->user()?->name ?? 'Pengguna' }}
                </h6>
                <small class="text-muted text-truncate d-block" style="font-size: 11px;">
                    {{ auth()->user()?->email ?? 'user@futsal.com' }}
                </small>
            </div>
        </a>

        <form action="#" method="POST" class="w-100 m-0">
            @csrf
            <button type="submit"
                class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center p-2"
                title="Keluar">
                <i class="bx bx-log-out fs-5 me-1"></i>
                <span class="menu-text">Keluar</span>
            </button>
        </form>
    </div>

</aside>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('btn-sneat-toggle');
            const toggleIcon = document.getElementById('btn-sneat-icon');
            const htmlTag = document.documentElement;

            const isHidden = localStorage.getItem('sidebar-fully-hidden') === 'true';
            if (isHidden) {
                htmlTag.classList.add('sidebar-fully-hidden');
                if (toggleIcon) {
                    toggleIcon.classList.remove('bx-chevron-left');
                    toggleIcon.classList.add('bx-chevron-right');
                }
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    htmlTag.classList.toggle('sidebar-fully-hidden');

                    const hidden = htmlTag.classList.contains('sidebar-fully-hidden');
                    localStorage.setItem('sidebar-fully-hidden', hidden);

                    if (hidden) {
                        toggleIcon.classList.remove('bx-chevron-left');
                        toggleIcon.classList.add('bx-chevron-right');
                    } else {
                        toggleIcon.classList.remove('bx-chevron-right');
                        toggleIcon.classList.add('bx-chevron-left');
                    }
                });
            }
        });
    </script>
@endpush
