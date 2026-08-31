<style>
    /* Styling Bottom Bar Mobile */
    .customer-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 65px;
        background: #ffffff;
        box-shadow: 0 -3px 15px rgba(0, 0, 0, 0.08);
        z-index: 1050;
        display: flex;
        justify-content: space-around;
        align-items: center;
        border-top: 1px solid rgba(0, 0, 0, 0.06);
    }

    .customer-nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: #8592a3;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
        flex: 1;
        height: 100%;
    }

    .customer-nav-item i {
        font-size: 22px;
        margin-bottom: 2px;
        transition: transform 0.2s ease;
    }

    .customer-nav-item.active {
        color: #696cff;
    }

    .customer-nav-item.active i {
        transform: translateY(-2px);
    }

    /* Padding bottom agar konten view tidak tertutup nav bar */
    .mobile-content-wrapper {
        padding-bottom: 80px !important;
    }

    /* Khusus role pelanggan di desktop/mobile: sembunyikan sidebar sneat default jika ingin full mobile layout */
    @media (min-width: 1200px) {
        .customer-container {
            max-width: 720px;
            margin: 0 auto;
        }
    }
</style>

<nav class="customer-bottom-nav">
    <!-- 1. Sewa Lapangan -->
    <a href="{{ route('sewa.index') }}" class="customer-nav-item {{ request()->routeIs('sewa.*') ? 'active' : '' }}">
        <i class="bx {{ request()->routeIs('sewa.*') ? 'bxs-grid-alt' : 'bx-grid-alt' }}"></i>
        <span>Sewa</span>
    </a>

    <!-- 2. Kalender Ketersediaan -->
    <a href="{{ route('kalender.index') }}" class="customer-nav-item {{ request()->routeIs('kalender.*') ? 'active' : '' }}">
        <i class="bx {{ request()->routeIs('kalender.*') ? 'bxs-calendar' : 'bx-calendar' }}"></i>
        <span>Kalender</span>
    </a>

    <!-- 3. Riwayat Booking -->
    <a href="{{ route('riwayat.index') }}" class="customer-nav-item {{ request()->routeIs('riwayat.*') ? 'active' : '' }}">
        <i class="bx {{ request()->routeIs('riwayat.*') ? 'bxs-time-five' : 'bx-time-five' }}"></i>
        <span>Riwayat</span>
    </a>

    <!-- 4. Profil Pengguna -->
    <a href="{{ route('profil.index') }}" class="customer-nav-item {{ request()->routeIs('profil.*') ? 'active' : '' }}">
        <i class="bx {{ request()->routeIs('profil.*') ? 'bxs-user' : 'bx-user' }}"></i>
        <span>Profil</span>
    </a>
</nav>