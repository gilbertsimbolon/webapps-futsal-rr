<style>
    /* Ukuran Lebar Default Sidebar Sneat */
    :root {
        --sidebar-width: 260px;
    }

    #layout-menu {
        width: var(--sidebar-width) !important;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        z-index: 1075;
    }

    /* Beri jarak pada konten utama agar TIDAK tertimpa sidebar */
    .layout-page {
        padding-left: var(--sidebar-width) !important;
        transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        min-height: 100vh;
    }

    /* Saat Sidebar Ditutup: Sembunyikan sidebar ke kiri & normalkan padding konten */
    html.sidebar-fully-hidden #layout-menu {
        transform: translateX(-100%) !important;
    }

    html.sidebar-fully-hidden .layout-page {
        padding-left: 0 !important;
    }

    /* Tombol Toggle Buka/Tutup di Sisi Kanan Sidebar */
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

    /* Scrollbar Halus */
    .menu-inner::-webkit-scrollbar {
        width: 4px;
    }
    .menu-inner::-webkit-scrollbar-thumb {
        background-color: rgba(67, 89, 113, 0.15);
        border-radius: 4px;
    }

    /* Responsif Mobile */
    @media (max-width: 1199.98px) {
        .layout-page {
            padding-left: 0 !important;
        }
    }
</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme position-fixed d-flex flex-column vh-100 start-0 top-0 overflow-visible shadow-sm">

    <!-- Tombol Toggle Buka/Tutup (< dan >) -->
    <a href="javascript:void(0);" id="btn-sneat-toggle"
        class="btn btn-sm btn-primary btn-icon rounded-circle shadow d-none d-xl-flex align-items-center justify-content-center border border-2 border-white p-0"
        title="Buka / Tutup Sidebar">
        <i id="btn-sneat-icon" class="bx bx-chevron-left fs-5"></i>
    </a>

    <!-- 1. HEADER BRAND -->
    <div class="app-brand demo d-flex flex-column align-items-center justify-content-center text-center p-3 flex-shrink-0 border-bottom bg-menu-theme position-sticky top-0" style="z-index: 10; height: auto !important;">
        <a href="#" class="app-brand-link d-flex flex-column align-items-center text-decoration-none w-100 p-0 m-0">
            <span class="app-brand-logo demo mb-2">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="rounded"
                    style="width: 42px; height: 42px; object-fit: contain;">
            </span>
            <span class="app-brand-text demo text-heading fw-bold fs-5 text-truncate px-1">
                Project Roy
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <!-- 2. MENU LIST -->
    <ul class="menu-inner py-2 flex-grow-1 overflow-y-auto overflow-x-hidden m-0">

        <!-- ================= UTAMA / DASHBOARD ================= -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Utama</span>
        </li>

        <li class="menu-item active">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- ================= PORTAL PELANGGAN / USER ================= -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Area Pelanggan (User)</span>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-football"></i>
                <div data-i18n="Booking Lapangan">Cari & Booking Lapangan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div data-i18n="Kalender Booking">Kalender Booking Lapangan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-history"></i>
                <div data-i18n="Riwayat Booking">Riwayat Booking Saya</div>
            </a>
        </li>

        <!-- ================= MASTER DATA (ADMIN & PENGELOLA) ================= -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-building-house"></i>
                <div data-i18n="Data Cabang">Data Cabang</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-layer"></i>
                <div data-i18n="Data Lapangan">Data Lapangan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-time-five"></i>
                <div data-i18n="Jadwal & Jam">Jadwal & Jam Operasional</div>
            </a>
        </li>

        <!-- ================= TRANSAKSI & OPERASIONAL ================= -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi & Operasional</span>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                <div data-i18n="Semua Booking">Semua Data Booking</div>
            </a>
        </li>

        <!-- ================= RISET ALGORITMA ROUND ROBIN ================= -->
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
                <div data-i18n="Simulasi Alur RR">Simulasi & Log Alur RR</div>
            </a>
        </li>

        <!-- ================= LAPORAN REKAPITULASI ================= -->
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

        <!-- ================= ADMINISTRATOR & SETTINGS ================= -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Sistem & User</span>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-user-check"></i>
                <div data-i18n="Manajemen User">Manajemen User</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link px-3">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="Pengaturan Web">Pengaturan Web</div>
            </a>
        </li>

    </ul>

    <!-- 3. USER PROFILE & LOGOUT -->
    <div class="p-3 border-top mt-auto flex-shrink-0 bg-menu-theme w-100 position-sticky bottom-0" style="z-index: 10;">
        <a href="#" class="d-flex align-items-center text-decoration-none text-heading p-2 rounded hover-light mb-2 w-100" title="{{ auth()->user()?->name ?? 'Pengguna' }}">
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
            <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center p-2" title="Keluar">
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

    // Restore status sidebar dari localStorage
    const isHidden = localStorage.getItem('sidebar-fully-hidden') === 'true';
    if (isHidden) {
        htmlTag.classList.add('sidebar-fully-hidden');
        if (toggleIcon) {
            toggleIcon.classList.remove('bx-chevron-left');
            toggleIcon.classList.add('bx-chevron-right');
        }
    }

    // Event Klik Buka / Tutup
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
