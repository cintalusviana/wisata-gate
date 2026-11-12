
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'WisataGate')
    </title>

    {{-- =====================================================
         THEME
    ====================================================== --}}
    <script>
        (function () {
            const savedTheme = localStorage.getItem('wisataGateTheme');

            document.documentElement.setAttribute(
                'data-theme',
                savedTheme === 'dark' ? 'dark' : 'light'
            );
        })();
    </script>

    {{-- =====================================================
         GOOGLE FONT
    ====================================================== --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- =====================================================
         BOOTSTRAP
    ====================================================== --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <style>

        /* =====================================================
           COLOR VARIABLES
        ===================================================== */

        :root {
            --primary: #EA580C;
            --primary-hover: #C2410C;
            --primary-light: #FFF7ED;

            --sidebar: #1F2933;
            --sidebar-card: #2B3642;

            --bg: #F7F5F2;
            --card: #FFFFFF;
            --border: #E5E7EB;

            --text: #1F2933;
            --text-secondary: #6B7280;
            --text-muted: #9CA3AF;

            --sidebar-text: #C9D1D9;
            --sidebar-muted: #8B98A8;

            --topbar: #FFFFFF;
            --input-bg: #FFFFFF;
        }

        [data-theme="dark"] {
            --primary: #F97316;
            --primary-hover: #EA580C;
            --primary-light: #431407;

            --sidebar: #111827;
            --sidebar-card: #1F2937;

            --bg: #111827;
            --card: #1F2937;
            --border: #374151;

            --text: #F9FAFB;
            --text-secondary: #D1D5DB;
            --text-muted: #9CA3AF;

            --sidebar-text: #D1D5DB;
            --sidebar-muted: #9CA3AF;

            --topbar: #1F2937;
            --input-bg: #1F2937;
        }

        /* =====================================================
           GLOBAL
        ===================================================== */

        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-size: 16px;

            transition:
                background 0.25s ease,
                color 0.25s ease;
        }

        /* =====================================================
           SIDEBAR
        ===================================================== */

        .app-sidebar {
            position: fixed;
            top: 0;
            left: 0;

            width: 370px;
            height: 100vh;

            padding: 34px 22px;

            background: var(--sidebar);

            border-right:
                1px solid rgba(255, 255, 255, 0.05);

            overflow-y: auto;

            z-index: 1000;

            transition:
                background 0.25s ease,
                left 0.25s ease;
        }

        .app-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .app-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .app-sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
        }

        /* =====================================================
           BRAND
        ===================================================== */

        .brand {
            display: flex;
            align-items: center;

            gap: 18px;

            padding:
                8px 10px 34px;

            color: white;
            text-decoration: none;
        }

        .brand:hover {
            color: white;
        }

        /* LOGO WG - DISESUAIKAN DENGAN HALAMAN LOGIN */

        .brand-icon {
            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            border-radius: 13px;

            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .20);

            color: white;

            font-size: 16px;
            font-weight: 800;
        }

        .brand-title {
            color: white;

            font-size: 26px;
            line-height: 1.1;
            font-weight: 800;
        }

        .brand-subtitle {
            margin-top: 7px;

            color: #FDBA74;

            font-size: 13px;
            line-height: 1.4;
            font-weight: 500;

            letter-spacing: 0.02em;
        }

        /* =====================================================
           LOCATION
        ===================================================== */

        .location-box {
            margin-bottom: 38px;

            padding: 18px;

            background: var(--sidebar-card);

            border:
                1px solid rgba(255, 255, 255, 0.08);

            border-radius: 14px;
        }

        .location-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 12px;
        }

        .location-title {
            color: var(--sidebar-muted);

            font-size: 13px;
            font-weight: 600;
        }

        .location-count {
            color: #FB923C;

            font-size: 14px;
            font-weight: 700;
        }

        .location-name {
            display: flex;
            align-items: center;

            gap: 9px;

            color: #F3F4F6;

            font-size: 14px;
            font-weight: 600;
        }

        .location-dot {
            width: 9px;
            height: 9px;

            flex-shrink: 0;

            border-radius: 50%;

            background: #FB923C;

            box-shadow:
                0 0 0 4px rgba(251, 146, 60, 0.10);
        }

        /* =====================================================
           MENU LABEL
        ===================================================== */

        .menu-label {
            padding: 0 16px;

            margin-bottom: 13px;

            color: var(--sidebar-muted);

            font-size: 13px;
            font-weight: 700;

            letter-spacing: 0.09em;

            text-transform: uppercase;
        }

        /* =====================================================
           SIDEBAR MENU
        ===================================================== */

        .sidebar-menu {
            display: flex;
            flex-direction: column;

            gap: 7px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;

            gap: 16px;

            min-height: 58px;

            padding: 14px 17px;

            border-radius: 12px;

            color: var(--sidebar-text);

            text-decoration: none;

            font-size: 16px;
            font-weight: 600;

            transition:
                background 0.2s ease,
                color 0.2s ease;
        }

        .sidebar-menu a i {
            width: 26px;
            min-width: 26px;

            text-align: center;

            color: #AAB5C3;

            font-size: 21px;
        }

        .sidebar-menu a:hover {
            background:
                rgba(234, 88, 12, 0.15);

            color: white;
        }

        .sidebar-menu a:hover i {
            color: #FB923C;
        }

        .sidebar-menu a.active {
            background: var(--primary);

            color: white;

            box-shadow:
                0 9px 22px rgba(234, 88, 12, 0.22);
        }

        .sidebar-menu a.active i {
            color: white;
        }

        /* =====================================================
           DISABLED MENU
        ===================================================== */

        .sidebar-menu a.disabled-menu {
            opacity: 0.55;

            cursor: not-allowed;
        }

        .sidebar-menu a.disabled-menu:hover {
            background: transparent;
            color: var(--sidebar-text);
        }

        .sidebar-menu a.disabled-menu:hover i {
            color: #AAB5C3;
        }

        /* =====================================================
           MENU BADGE
        ===================================================== */

        .menu-badge {
            margin-left: auto;

            min-width: 36px;

            padding: 5px 9px;

            border-radius: 20px;

            background:
                rgba(255, 255, 255, 0.16);

            color: white;

            font-size: 12px;
            font-weight: 700;

            text-align: center;
        }

        .menu-badge.green {
            background:
                rgba(16, 185, 129, 0.16);

            color: #6EE7B7;
        }

        /* =====================================================
           LOGOUT
        ===================================================== */

        .sidebar-logout {
            margin-top: 18px;

            padding-top: 18px;

            border-top:
                1px solid rgba(255, 255, 255, 0.08);
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            width: 100%;

            display: flex;
            align-items: center;

            gap: 16px;

            min-height: 58px;

            padding: 14px 17px;

            border: 0;
            border-radius: 12px;

            background: transparent;

            color: #FCA5A5;

            font-family: 'Inter', sans-serif;

            font-size: 16px;
            font-weight: 600;

            text-align: left;

            cursor: pointer;
        }

        .logout-button i {
            width: 26px;
            min-width: 26px;

            text-align: center;

            color: #F87171;

            font-size: 21px;
        }

        .logout-button:hover {
            background:
                rgba(239, 68, 68, 0.12);

            color: #FCA5A5;
        }

        /* =====================================================
           MAIN
        ===================================================== */

        .app-main {
            min-height: 100vh;

            margin-left: 370px;
        }

        /* =====================================================
           WELCOME HEADER
        ===================================================== */

        .welcome-header {
            min-height: 104px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            padding: 17px 52px;

            background: var(--topbar);

            border-bottom:
                1px solid var(--border);
        }

        .welcome-left {
            min-width: 0;
        }

        .welcome-title {
            display: flex;
            align-items: center;
            flex-wrap: wrap;

            gap: 9px;

            margin: 0;

            color: var(--text);

            font-size: 25px;
            line-height: 1.2;
            font-weight: 800;
        }

        .welcome-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 25px;

            padding: 3px 10px;

            border:
                1px solid #FED7AA;

            border-radius: 6px;

            background: #FFF7ED;

            color: #EA580C;

            font-size: 11px;
            font-weight: 700;
        }

        .welcome-description {
            margin: 5px 0 0;

            color: var(--text-secondary);

            font-size: 13px;
            line-height: 1.5;
            font-weight: 500;
        }

        /* =====================================================
           RIGHT HEADER
        ===================================================== */

        .welcome-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;

            gap: 10px;

            flex-shrink: 0;
        }

        .header-info {
            min-height: 42px;

            display: inline-flex;
            align-items: center;

            gap: 8px;

            padding: 7px 13px;

            border:
                1px solid var(--border);

            border-radius: 9px;

            background: var(--card);

            color: var(--text-secondary);

            font-size: 11px;
            font-weight: 600;

            white-space: nowrap;
        }

        .header-info > i {
            color: #64748B;

            font-size: 14px;
        }

        /* =====================================================
           GATE STATUS
        ===================================================== */

        .gate-status {
            display: flex;
            flex-direction: column;
            justify-content: center;

            gap: 1px;

            min-width: 110px;
        }

        .gate-status-top {
            display: flex;
            align-items: center;

            gap: 6px;

            color: var(--text);

            font-size: 11px;
            font-weight: 800;
        }

        .gate-status-bottom {
            padding-left: 13px;

            color: #10B981;

            font-size: 10px;
            font-weight: 700;
        }

        .gate-dot {
            width: 8px;
            height: 8px;

            flex-shrink: 0;

            border-radius: 50%;

            background: #10B981;

            box-shadow:
                0 0 0 3px rgba(16, 185, 129, .12);
        }

        /* =====================================================
           MOBILE BUTTON
        ===================================================== */

        .mobile-menu-button {
            display: none;

            width: 46px;
            height: 46px;

            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            border:
                1px solid var(--border);

            border-radius: 11px;

            background: var(--card);

            color: var(--text);

            font-size: 23px;

            cursor: pointer;
        }

        .mobile-menu-button:hover {
            color: var(--primary);

            border-color: var(--primary);
        }

        /* =====================================================
           OVERLAY
        ===================================================== */

        .sidebar-overlay {
            display: none;

            position: fixed;

            inset: 0;

            background:
                rgba(5, 15, 32, 0.48);

            z-index: 999;
        }

        /* =====================================================
           PAGE CONTENT
        ===================================================== */

        .page-content {
            min-height:
                calc(100vh - 104px);

            padding: 50px 52px;
        }

        .page-content h1 {
            font-size: 38px;
        }

        .page-content h2 {
            font-size: 32px;
        }

        .page-content h3 {
            font-size: 27px;
        }

        .page-content p {
            font-size: 16px;
        }

        .page-content .card {
            border-radius: 20px !important;

            background: var(--card);

            border-color: var(--border);

            color: var(--text);
        }

        /* =====================================================
           DARK MODE
        ===================================================== */

        [data-theme="dark"] .welcome-badge {
            background: #431407;

            border-color: #9A3412;

            color: #FDBA74;
        }

        [data-theme="dark"] .text-dark {
            color: #F9FAFB !important;
        }

        [data-theme="dark"] .text-secondary {
            color: #D1D5DB !important;
        }

        [data-theme="dark"] .bg-white {
            background-color:
                var(--card) !important;
        }

        [data-theme="dark"] .table {
            --bs-table-bg: var(--card);
            --bs-table-color: var(--text);
            --bs-table-border-color: var(--border);
        }

        [data-theme="dark"] .table-light {
            --bs-table-bg: #374151;
            --bs-table-color: #F9FAFB;
        }

        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select {
            background-color:
                var(--input-bg);

            color: var(--text);

            border-color:
                var(--border);
        }

        [data-theme="dark"] .form-control::placeholder {
            color: #9CA3AF;
        }

        [data-theme="dark"] .modal-content {
            background-color:
                var(--card);

            color:
                var(--text);

            border-color:
                var(--border);
        }

        [data-theme="dark"] .btn-close {
            filter: invert(1);
        }

        /* =====================================================
           TABLET
        ===================================================== */

        @media (max-width: 1199px) {

            .app-sidebar {
                width: 320px;
            }

            .app-main {
                margin-left: 320px;
            }

            .welcome-header {
                padding: 16px 36px;
            }

            .welcome-title {
                font-size: 22px;
            }

            .welcome-description {
                font-size: 12px;
            }

            .page-content {
                padding: 40px 36px;
            }
        }

        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 991px) {

            .app-sidebar {
                left: -370px;

                width: 320px;

                transition:
                    left 0.25s ease;
            }

            .app-sidebar.show {
                left: 0;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .app-main {
                margin-left: 0;
            }

            .mobile-menu-button {
                display: flex;
            }

            .welcome-header {
                min-height: 82px;

                padding:
                    13px 24px;

                gap: 12px;
            }

            .welcome-left {
                flex: 1;
            }

            .welcome-title {
                font-size: 20px;
            }

            .welcome-description {
                font-size: 11px;
            }

            .welcome-right {
                gap: 7px;
            }

            .header-info {
                min-height: 39px;

                padding:
                    6px 10px;

                font-size: 10px;
            }

            .gate-status {
                min-width: 100px;
            }

            .page-content {
                min-height:
                    calc(100vh - 82px);

                padding:
                    32px 24px;
            }
        }

        /* =====================================================
           SMALL MOBILE
        ===================================================== */

        @media (max-width: 576px) {

            .app-sidebar {
                width: 300px;
            }

            .welcome-header {
                align-items: flex-start;

                flex-wrap: wrap;

                padding:
                    13px 17px;
            }

            .welcome-left {
                flex:
                    1 1 calc(100% - 55px);
            }

            .welcome-title {
                font-size: 18px;
            }

            .welcome-description {
                font-size: 10px;
            }

            .welcome-right {
                width: 100%;

                display: grid;

                grid-template-columns:
                    1fr 1fr;

                gap: 7px;
            }

            .gate-status {
                grid-column:
                    span 2;

                min-width: 0;
            }

            .header-info {
                width: 100%;
            }

            .page-content {
                padding:
                    26px 17px;
            }
        }

        /* =====================================================
           EXTRA SMALL
        ===================================================== */

        @media (max-width: 430px) {

            .app-sidebar {
                width: 300px;
            }

            .welcome-header {
                padding:
                    12px 14px;
            }

            .mobile-menu-button {
                width: 42px;
                height: 42px;

                font-size: 21px;
            }

            .welcome-title {
                font-size: 17px;
            }

            .welcome-description {
                font-size: 9px;
            }

            .header-info {
                font-size: 9px;
            }
        }

    </style>

    @stack('styles')

</head>

<body>

    {{-- =====================================================
         SIDEBAR OVERLAY
    ====================================================== --}}

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>


    {{-- =====================================================
         SIDEBAR PETUGAS
    ====================================================== --}}

    <aside
        class="app-sidebar"
        id="appSidebar"
    >

        {{-- BRAND --}}

        <a
            href="{{ route('dashboard') }}"
            class="brand"
        >

            <div class="brand-icon">
                WG
            </div>

            <div>

                <div class="brand-title">
                    WisataGate
                </div>

                <div class="brand-subtitle">
                    Vehicle Monitoring System
                </div>

            </div>

        </a>


        {{-- LOCATION --}}

        <div class="location-box">

            <div class="location-header">

                <span class="location-title">
                    Lokasi Terpantau
                </span>

                <span class="location-count">
                    1 Pos
                </span>

            </div>

            <div class="location-name">

                <span class="location-dot"></span>

                Area Wisata (Gate Utama)

            </div>

        </div>


        {{-- =================================================
             MENU PETUGAS
        ================================================== --}}

        <div class="menu-label">
            Menu Petugas Gate
        </div>


        <nav class="sidebar-menu">

            {{-- =================================================
                 DASHBOARD PETUGAS
            ================================================== --}}

            <a
                href="{{ route('petugas.dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >

                <i class="bi bi-grid-1x2"></i>

                <span>
                    Dashboard Petugas
                </span>

            </a>


            {{-- =================================================
                 KENDARAAN MASUK
            ================================================== --}}

            <a
                href="{{ route('gate-logs.create') }}"
                class="{{ request()->routeIs('gate-logs.create') ? 'active' : '' }}"
            >

                <i class="bi bi-box-arrow-in-right"></i>

                <span>
                    Kendaraan Masuk
                </span>

                <span class="menu-badge">
                    + Baru
                </span>

            </a>


            {{-- =================================================
                 KENDARAAN DI AREA
            ================================================== --}}

            <a
                href="{{ route('petugas.kendaraan-area') }}"
                class="{{ request()->routeIs('petugas.kendaraan-area') ? 'active' : '' }}"
            >

                <i class="bi bi-car-front"></i>

                <span>
                    Kendaraan di Area
                </span>

                <span class="menu-badge green">
                    {{ $totalInside ?? 0 }}
                </span>

            </a>


            {{-- =================================================
                 RIWAYAT
            ================================================== --}}

            <a
                href="{{ route('petugas.riwayat') }}"
                class="{{ request()->routeIs('petugas.riwayat') ? 'active' : '' }}"
            >
                <i class="bi bi-clock-history"></i>
                <span>
                    Riwayat
                </span>
            </a>

        </nav>


        {{-- =================================================
             LOGOUT
        ================================================== --}}

        <div class="sidebar-logout">

            <form
                action="{{ route('logout') }}"
                method="POST"
                class="logout-form"
            >

                @csrf

                <button
                    type="submit"
                    class="logout-button"
                >

                    <i class="bi bi-box-arrow-right"></i>

                    <span>
                        Logout
                    </span>

                </button>

            </form>

        </div>

    </aside>


    {{-- =====================================================
         MAIN
    ====================================================== --}}

    <main class="app-main">


        {{-- =================================================
             WELCOME HEADER
        ================================================== --}}

        <header class="welcome-header">


            {{-- MOBILE MENU --}}

            <button
                type="button"
                class="mobile-menu-button"
                id="mobileMenuButton"
                aria-label="Buka menu"
            >

                <i class="bi bi-list"></i>

            </button>


            {{-- WELCOME --}}

            <div class="welcome-left">

                <h1 class="welcome-title">

                    Selamat Datang,
                    {{ auth()->user()->name ?? 'Cinta' }}

                    @if(auth()->check())

                        <span class="welcome-badge">
                            Petugas Gate
                        </span>

                    @endif

                </h1>

                <p class="welcome-description">

                    Pantau kendaraan yang masuk dan keluar
                    Area Wisata melalui Gate Utama.

                </p>

            </div>


            {{-- RIGHT HEADER --}}

            <div class="welcome-right">


                {{-- GATE STATUS --}}

                <div class="header-info gate-status">

                    <div class="gate-status-top">

                        <span class="gate-dot"></span>

                        <span>
                            Gate Utama
                        </span>

                    </div>

                    <div class="gate-status-bottom">
                        Aktif
                    </div>

                </div>


                {{-- DATE --}}

                <div class="header-info">

                    <i class="bi bi-clock"></i>

                    <span>
                        {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                    </span>

                </div>

            </div>

        </header>


        {{-- =================================================
             PAGE CONTENT
        ================================================== --}}

        <div class="page-content">

            @yield('content')

        </div>

    </main>


    {{-- =====================================================
         BOOTSTRAP JS
    ====================================================== --}}

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>


    {{-- =====================================================
         SIDEBAR MOBILE
    ====================================================== --}}

    <script>

        const mobileMenuButton =
            document.getElementById(
                'mobileMenuButton'
            );

        const appSidebar =
            document.getElementById(
                'appSidebar'
            );

        const sidebarOverlay =
            document.getElementById(
                'sidebarOverlay'
            );


        function openSidebar() {

            if (appSidebar) {
                appSidebar.classList.add('show');
            }

            if (sidebarOverlay) {
                sidebarOverlay.classList.add('show');
            }

        }


        function closeSidebar() {

            if (appSidebar) {
                appSidebar.classList.remove('show');
            }

            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('show');
            }

        }


        if (mobileMenuButton) {

            mobileMenuButton.addEventListener(
                'click',
                openSidebar
            );

        }


        if (sidebarOverlay) {

            sidebarOverlay.addEventListener(
                'click',
                closeSidebar
            );

        }


        document
            .querySelectorAll('.app-sidebar a:not(.disabled-menu)')
            .forEach(function (link) {

                link.addEventListener(
                    'click',
                    function () {

                        if (
                            window.innerWidth <= 991
                        ) {

                            closeSidebar();

                        }

                    }
                );

            });


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape'
                ) {

                    closeSidebar();

                }

            }
        );

    </script>


    @stack('scripts')

</body>

</html>
