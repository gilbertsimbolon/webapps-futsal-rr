<aside id="layout-menu"
    class="layout-menu menu-vertical menu bg-menu-theme d-flex flex-column vh-100">

    <!-- BRAND -->
    <div class="app-brand demo flex-shrink-0">

        <a href="/" class="app-brand-link gap-2">

            <span class="app-brand-logo demo">

                <img src="{{ asset('img/logo.png') }}"
                    style="width:40px;height:auto;object-fit:contain;">

            </span>

            <div class="d-flex flex-column">

                <span class="app-brand-text text-heading fw-bold">
                    bkngftsl.
                </span>

                <div class="lh-sm mt-1">

                    <div id="current-date"
                        class="text-muted"
                        style="font-size: 11px;">
                    </div>

                    <div id="current-time"
                        class="text-primary fw-semibold"
                        style="font-size: 11px;">
                    </div>

                </div>

            </div>

        </a>


        <a href="javascript:void(0);"
            class="layout-menu-toggle menu-link text-large ms-auto">

            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>

        </a>

    </div>


    <div class="menu-divider mt-0 flex-shrink-0"></div>

    <div class="menu-inner-shadow"></div>


    <!-- MENU -->
    <ul class="menu-inner py-1 flex-grow-1 overflow-auto">

        <!-- Dashboard -->
        <li class="menu-item">

            <a href="{{ route('admin.dashboard') }}"
                class="menu-link">

                <i class="menu-icon tf-icons bx bx-home-circle"></i>

                <div>
                    Dashboard
                </div>

            </a>

        </li>


        <!-- Master Data -->
        <li class="menu-header small text-uppercase">

            <span class="menu-header-text">
                Master Data
            </span>

        </li>


        <!-- Pelanggan -->
        <li class="menu-item">

            <a href="{{ route('admin.pelanggan.index') }}"
                class="menu-link">

                <i class="menu-icon tf-icons bx bx-user"></i>

                <div>
                    Pelanggan
                </div>

            </a>

        </li>


        <!-- Pemilik -->
        <li class="menu-item">

            <a href="{{ route('admin.pemilik.index') }}"
                class="menu-link">

                <i class="menu-icon tf-icons bx bx-user"></i>

                <div>
                    Pemilik
                </div>

            </a>

        </li>


        <!-- Cabang -->
        <li class="menu-item">

            <a href="{{ route('admin.cabang.index') }}"
                class="menu-link">

                <i class="menu-icon tf-icons bx bx-building-house"></i>

                <div>
                    Cabang
                </div>

            </a>

        </li>


        <!-- Lapangan -->
        <li class="menu-item">

            <a href="#"
                class="menu-link">

                <i class="menu-icon tf-icons bx bx-football"></i>

                <div>
                    Lapangan
                </div>

            </a>

        </li>

    </ul>


    <!-- USER AREA -->
    <div class="border-top p-3 flex-shrink-0">

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

            <button type="submit"
                class="btn btn-outline-danger w-100">

                <i class="bx bx-log-out me-1"></i>

                Keluar

            </button>

        </form>

    </div>

</aside>


<script>

    function updateDateTime() {

        const now = new Date();


        const dateOptions = {
            weekday: 'long',
            day: '2-digit',
            month: 'long',
            year: 'numeric',
            timeZone: 'Asia/Makassar'
        };


        const timeOptions = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
            timeZone: 'Asia/Makassar'
        };


        const date =
            new Intl.DateTimeFormat('id-ID', dateOptions)
                .format(now);


        const time =
            new Intl.DateTimeFormat('id-ID', timeOptions)
                .format(now);


        document.getElementById('current-date').textContent = date;

        document.getElementById('current-time').textContent =
            time + ' WITA';

    }


    updateDateTime();

    setInterval(updateDateTime, 1000);

</script>