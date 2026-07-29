<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link gap-1">
            <span class="app-brand-logo demo">
                <img src="{{ asset('img/logo.png') }}" style="width:40px;height:auto;object-fit:contain;">
            </span>

            <span class="app-brand-text demo text-heading fw-bold">
                bkngftsl.
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- Dashboard --}}
        <li class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        {{-- Master Data --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
        </li>

        <li class="menu-item">
            <a href="{{ route('pelanggan.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Pelanggan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('pemilik.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Pemilik</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-building-house"></i>
                <div>Cabang</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-football"></i>
                <div>Lapangan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div>Jadwal</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div>Metode Pembayaran</div>
            </a>
        </li>

    </ul>

    <div class="mt-auto border-top p-3">

        <a href="{{ route('profil.index') }}"
            class="d-flex align-items-center text-decoration-none text-reset rounded p-2 mb-2">

            <div class="avatar avatar-sm me-2">
                <span class="avatar-initial rounded-circle bg-label-primary">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
            </div>

            <div class="flex-grow-1 overflow-hidden">
                <h6 class="mb-0 text-truncate">
                    {{ auth()->user()->name }}
                </h6>

                <small class="text-muted text-capitalize">
                    {{ auth()->user()->getRoleNames()->first() }}
                </small>
            </div>

            <i class="bx bx-chevron-right fs-4 text-muted"></i>
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="btn btn-outline-danger w-100">
                <i class="bx bx-log-out me-1"></i>
                Keluar
            </button>
        </form>

    </div>
</aside>
