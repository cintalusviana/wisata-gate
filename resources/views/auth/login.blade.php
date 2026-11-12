
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - WisataGate</title>

    <!-- Mengikuti tema dari Landing Page -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('wisataGateTheme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* =====================================================
           THEME VARIABLES
        ===================================================== */

        :root {
            color-scheme: light;

            --orange: #F04400;
            --orange-dark: #D92D00;
            --orange-hover: #C2410C;
            --orange-soft: #FFF1EB;

            --page-bg: #F7F5F2;
            --panel: #FFFFFF;
            --panel-soft: #FAF9F7;

            --text: #1F2933;
            --text-secondary: #6B7280;
            --text-muted: #9CA3AF;

            --border: #E5E7EB;
            --border-soft: #F0EEEB;

            --role-bg: #F8F7F5;
            --role-icon-bg: #ECEAE7;
            --role-icon-color: #6B7280;

            --demo-bg: #FAF9F7;
            --demo-border: #EEEAE6;
            --demo-title: #57534E;

            --shadow: 0 25px 70px rgba(31, 41, 51, .12);
        }

        [data-theme="dark"] {
            color-scheme: dark;

            --orange: #FF6A2A;
            --orange-dark: #F04400;
            --orange-hover: #FF7A42;
            --orange-soft: #3B2118;

            --page-bg: #10161D;
            --panel: #19212A;
            --panel-soft: #202A35;

            --text: #F3F4F6;
            --text-secondary: #B8C0CA;
            --text-muted: #929EAA;

            --border: #35414D;
            --border-soft: #303A45;

            --role-bg: #141C24;
            --role-icon-bg: #293440;
            --role-icon-color: #AAB4C0;

            --demo-bg: #202A35;
            --demo-border: #35414D;
            --demo-title: #E5E7EB;

            --shadow: 0 25px 70px rgba(0, 0, 0, .35);
        }

        /* =====================================================
           GLOBAL
        ===================================================== */

        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 24px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                radial-gradient(
                    circle at 15% 10%,
                    rgba(249, 115, 22, .10),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 90% 90%,
                    rgba(249, 115, 22, .07),
                    transparent 30%
                ),
                var(--page-bg);

            color: var(--text);
            transition: background .25s ease, color .25s ease;
        }

        [data-theme="dark"] body {
            background:
                radial-gradient(
                    circle at 15% 10%,
                    rgba(249, 115, 22, .10),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 90% 90%,
                    rgba(249, 115, 22, .06),
                    transparent 30%
                ),
                var(--page-bg);
        }

        /* =====================================================
           LOGIN CONTAINER
        ===================================================== */

        .login-container {
            width: 100%;
            max-width: 1040px;
            min-height: 620px;

            display: grid;
            grid-template-columns: .95fr 1.05fr;

            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 26px;
            overflow: hidden;

            box-shadow: var(--shadow);

            transition: background .25s ease, border .25s ease;
        }

        /* =====================================================
           LEFT PANEL
        ===================================================== */

        .intro-panel {
            position: relative;
            overflow: hidden;
            padding: 46px;

            background:
                linear-gradient(
                    145deg,
                    #FB923C 0%,
                    #F97316 48%,
                    #EA580C 100%
                );

            color: white;
        }

        .intro-panel::before {
            content: "";
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            right: -160px;
            top: -130px;
            border: 1px solid rgba(255, 255, 255, .16);
        }

        .intro-panel::after {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            left: -150px;
            bottom: -130px;
            background: rgba(255, 255, 255, .08);
        }

        .intro-content {
            position: relative;
            z-index: 2;
            height: 100%;

            display: flex;
            flex-direction: column;
        }

        /* =====================================================
           BRAND
        ===================================================== */

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 45px;
            height: 45px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 13px;
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .20);

            color: white;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .brand-name {
            margin: 0;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.3px;
        }

        .brand-subtitle {
            margin: 3px 0 0;
            color: rgba(255, 255, 255, .70);
            font-size: 10px;
        }

        /* =====================================================
           INTRO TEXT
        ===================================================== */

        .intro-text {
            margin-top: 100px;
        }

        .intro-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 7px 10px;
            border-radius: 7px;

            background: rgba(255, 255, 255, .13);
            border: 1px solid rgba(255, 255, 255, .16);

            color: white;
            font-size: 10px;
            font-weight: 600;
        }

        .intro-label span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #BBF7D0;
            box-shadow: 0 0 0 4px rgba(187, 247, 208, .15);
        }

        .intro-title {
            max-width: 390px;
            margin: 20px 0 14px;

            font-size: 34px;
            line-height: 1.18;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .intro-description {
            max-width: 400px;
            margin: 0;

            color: rgba(255, 255, 255, .76);
            font-size: 13px;
            line-height: 1.8;
        }

        /* =====================================================
           GATE INFO
        ===================================================== */

        .gate-info {
            margin-top: auto;
        }

        .gate-line {
            height: 1px;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, .18);
        }

        .gate-info-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .gate-location {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .gate-icon {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;
            background: rgba(255, 255, 255, .14);

            color: white;
            font-size: 17px;
        }

        .gate-location strong {
            display: block;
            color: white;
            font-size: 12px;
        }

        .gate-location span {
            display: block;
            margin-top: 2px;
            color: rgba(255, 255, 255, .62);
            font-size: 10px;
        }

        .gate-status {
            padding: 6px 9px;
            border-radius: 7px;

            background: rgba(255, 255, 255, .13);
            border: 1px solid rgba(255, 255, 255, .10);

            color: white;
            font-size: 9px;
            font-weight: 600;
        }

        /* =====================================================
           RIGHT PANEL
        ===================================================== */

        .form-panel {
            display: flex;
            align-items: center;
            padding: 55px 65px;

            background: var(--panel);
            transition: background .25s ease;
        }

        .form-wrapper {
            width: 100%;
            max-width: 400px;
            margin: auto;
        }

        /* =====================================================
           HEADER
        ===================================================== */

        .form-header {
            margin-bottom: 28px;
        }

        .form-label-small {
            margin-bottom: 8px;
            color: var(--orange);

            font-size: 10px;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        .login-title {
            margin: 0 0 8px;
            color: var(--text);

            font-size: 29px;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .login-description {
            margin: 0;
            color: var(--text-secondary);
            font-size: 13px;
            line-height: 1.7;
        }

        /* =====================================================
           ROLE SELECTOR
        ===================================================== */

        .role-section {
            margin-bottom: 25px;
        }

        .section-label {
            display: block;
            margin-bottom: 9px;

            color: var(--text);
            font-size: 11px;
            font-weight: 700;
        }

        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;

            padding: 5px;
            border: 1px solid var(--border);
            border-radius: 13px;

            background: var(--role-bg);
            transition: background .25s ease, border .25s ease;
        }

        .role-option {
            position: relative;

            display: flex;
            align-items: center;
            gap: 10px;

            min-height: 55px;
            padding: 9px 12px;

            border: 0;
            border-radius: 9px;
            background: transparent;

            color: var(--text-secondary);
            text-align: left;
            cursor: pointer;

            transition: .2s ease;
        }

        .role-option:hover {
            color: var(--text);
        }

        .role-option.active {
            background: var(--panel);
            color: var(--text);

            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        [data-theme="dark"] .role-option.active {
            box-shadow: 0 2px 9px rgba(0, 0, 0, .25);
        }

        .role-icon {
            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;

            border-radius: 9px;
            background: var(--role-icon-bg);
            color: var(--role-icon-color);

            font-size: 15px;
            transition: .2s ease;
        }

        .role-option.active .role-icon {
            background: var(--orange-soft);
            color: var(--orange);
        }

        .role-text strong {
            display: block;
            color: inherit;
            font-size: 11px;
            font-weight: 700;
        }

        .role-text span {
            display: block;
            margin-top: 2px;
            color: var(--text-muted);
            font-size: 9px;
        }

        /* =====================================================
           FORM
        ===================================================== */

        .form-label {
            margin-bottom: 7px;
            color: var(--text);
            font-size: 11px;
            font-weight: 700;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);

            color: var(--text-muted);
            font-size: 16px;
            pointer-events: none;

            transition: .2s ease;
        }

        .form-control {
            height: 49px;
            padding: 0 14px 0 42px;

            border: 1px solid var(--border);
            border-radius: 11px;

            color: var(--text);
            background: var(--panel);

            font-size: 12px;
            transition: .2s ease;
        }

        .form-control::placeholder {
            color: var(--text-muted);
        }

        .form-control:hover {
            border-color: var(--text-muted);
        }

        .form-control:focus {
            border-color: var(--orange);
            background: var(--panel);
            color: var(--text);

            box-shadow: 0 0 0 3px rgba(249, 115, 22, .12);
        }

        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus {
            -webkit-text-fill-color: var(--text);
            -webkit-box-shadow: 0 0 0 1000px var(--panel) inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        .input-wrapper:focus-within .input-icon {
            color: var(--orange);
        }

        /* =====================================================
           PASSWORD
        ===================================================== */

        .password-toggle {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);

            padding: 3px;
            border: 0;
            background: transparent;

            color: var(--text-muted);
            cursor: pointer;
            font-size: 15px;
        }

        .password-toggle:hover {
            color: var(--orange);
        }

        /* =====================================================
           LOGIN BUTTON
        ===================================================== */

        .btn-login {
            width: 100%;
            height: 49px;
            margin-top: 3px;

            border: 0;
            border-radius: 11px;

            background: linear-gradient(135deg, #F97316, #EA580C);
            color: white;

            font-size: 12px;
            font-weight: 700;

            box-shadow: 0 7px 16px rgba(249, 115, 22, .20);
            transition: .2s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #EA580C, #C2410C);
            color: white;
            transform: translateY(-1px);

            box-shadow: 0 9px 20px rgba(234, 88, 12, .25);
        }

        /* =====================================================
           DEMO ACCOUNT
        ===================================================== */

        .demo-account {
            display: flex;
            align-items: center;
            gap: 10px;

            margin-top: 18px;
            padding: 12px 13px;

            border: 1px solid var(--demo-border);
            border-radius: 11px;

            background: var(--demo-bg);
            transition: background .25s ease, border .25s ease;
        }

        .demo-icon {
            width: 30px;
            height: 30px;

            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;

            border-radius: 8px;
            background: var(--orange-soft);
            color: var(--orange);

            font-size: 13px;
        }

        .demo-text {
            min-width: 0;
        }

        .demo-text strong {
            display: block;
            color: var(--demo-title);
            font-size: 10px;
        }

        .demo-text span {
            display: block;
            margin-top: 2px;

            color: var(--text-muted);
            font-size: 9px;

            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        .login-footer {
            margin-top: 25px;
            padding-top: 18px;

            border-top: 1px solid var(--border-soft);

            text-align: center;
            color: var(--text-muted);

            font-size: 10px;
            line-height: 1.7;
        }

        /* =====================================================
           ALERT
        ===================================================== */

        .alert {
            margin-bottom: 20px;
            padding: 11px 13px;

            border: 0;
            border-radius: 10px;
            font-size: 11px;
        }

        [data-theme="dark"] .alert-danger {
            background: #431D1D;
            color: #FCA5A5;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 850px) {
            body {
                padding: 16px;
            }

            .login-container {
                max-width: 560px;
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .intro-panel {
                min-height: 280px;
                padding: 30px 35px;
            }

            .intro-text {
                margin-top: 45px;
            }

            .intro-title {
                font-size: 28px;
            }

            .gate-info {
                display: none;
            }

            .form-panel {
                padding: 42px 38px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .login-container {
                border-radius: 20px;
            }

            .intro-panel {
                min-height: 230px;
                padding: 25px;
            }

            .brand-icon {
                width: 40px;
                height: 40px;
            }

            .brand-name {
                font-size: 17px;
            }

            .intro-text {
                margin-top: 30px;
            }

            .intro-title {
                margin-top: 14px;
                font-size: 24px;
            }

            .intro-description {
                font-size: 11px;
            }

            .form-panel {
                padding: 32px 23px;
            }

            .login-title {
                font-size: 25px;
            }

            .role-option {
                min-height: 52px;
                padding: 8px;
            }

            .role-icon {
                width: 31px;
                height: 31px;
            }

            .role-text span {
                display: none;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        <!-- LEFT PANEL -->
        <section class="intro-panel">

            <div class="intro-content">

                <div class="brand">

                    <div class="brand-icon">
                        WG
                    </div>

                    <div>
                        <p class="brand-name">
                            WisataGate
                        </p>

                        <p class="brand-subtitle">
                            Vehicle Monitoring System
                        </p>
                    </div>

                </div>

                <div class="intro-text">

                    <div class="intro-label">
                        <span></span>
                        Gate Utama Aktif
                    </div>

                    <h1 class="intro-title">
                        Monitoring
                        kendaraan area
                        wisata.
                    </h1>

                    <p class="intro-description">
                        Kelola pencatatan kendaraan masuk dan
                        keluar melalui satu sistem yang terpusat
                        untuk Gate Utama Area Wisata.
                    </p>

                </div>

                <div class="gate-info">

                    <div class="gate-line"></div>

                    <div class="gate-info-content">

                        <div class="gate-location">

                            <div class="gate-icon">
                                <i class="bi bi-signpost-2"></i>
                            </div>

                            <div>
                                <strong>Area Wisata</strong>
                                <span>Gate Utama · 1 Pos Aktif</span>
                            </div>

                        </div>

                        <div class="gate-status">
                            Sistem Aktif
                        </div>

                    </div>

                </div>

            </div>

        </section>

        <!-- RIGHT PANEL -->
        <section class="form-panel">

            <div class="form-wrapper">

                <div class="form-header">

                    <div class="form-label-small">
                        Akses Sistem
                    </div>

                    <h2 class="login-title">
                        Selamat Datang
                    </h2>

                    <p class="login-description">
                        Pilih akses sesuai peran Anda,
                        kemudian masuk ke dashboard.
                    </p>

                </div>

                <!-- ERROR -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- ROLE -->
                <div class="role-section">

                    <span class="section-label">
                        Masuk sebagai
                    </span>

                    <div class="role-selector">

                        <button
                            type="button"
                            class="role-option active"
                            data-role="admin"
                            data-email="admin@wisatagate.test">

                            <div class="role-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>

                            <div class="role-text">
                                <strong>Admin</strong>
                                <span>Kelola sistem</span>
                            </div>

                        </button>

                        <button
                            type="button"
                            class="role-option"
                            data-role="petugas"
                            data-email="petugas@wisatagate.test">

                            <div class="role-icon">
                                <i class="bi bi-person-badge"></i>
                            </div>

                            <div class="role-text">
                                <strong>Petugas Gate</strong>
                                <span>Monitoring gate</span>
                            </div>

                        </button>

                    </div>

                </div>

                <!-- LOGIN FORM -->
                <form action="{{ route('login.process') }}" method="POST">
                    @csrf

                    <!-- EMAIL -->
                    <div class="mb-3">

                        <label for="email" class="form-label">
                            Email
                        </label>

                        <div class="input-wrapper">

                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                value="{{ old('email', 'admin@wisatagate.test') }}"
                                placeholder="Masukkan email"
                                required
                                autofocus>

                            <i class="bi bi-envelope input-icon"></i>

                        </div>

                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-4">

                        <label for="password" class="form-label">
                            Password
                        </label>

                        <div class="input-wrapper">

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                value="password"
                                placeholder="Masukkan password"
                                required>

                            <i class="bi bi-lock input-icon"></i>

                            <button
                                type="button"
                                class="password-toggle"
                                id="togglePassword"
                                aria-label="Tampilkan password">

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>

                    <!-- LOGIN BUTTON -->
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        Masuk ke Dashboard
                    </button>

                </form>

                <!-- DEMO ACCOUNT -->
                <div class="demo-account">

                    <div class="demo-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>

                    <div class="demo-text">

                        <strong>Akun Demo</strong>

                        <span id="demoAccount">
                            admin@wisatagate.test · password
                        </span>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="login-footer">
                    WisataGate &copy; {{ date('Y') }}
                    <br>
                    Sistem Gate Kendaraan Area Wisata
                </div>

            </div>

        </section>

    </div>

    <script>
        /* =====================================================
           ROLE SELECTOR
        ===================================================== */

        const roleOptions = document.querySelectorAll('.role-option');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const demoAccount = document.getElementById('demoAccount');

        roleOptions.forEach(option => {

            option.addEventListener('click', function () {

                roleOptions.forEach(item => {
                    item.classList.remove('active');
                });

                this.classList.add('active');

                const email = this.dataset.email;

                emailInput.value = email;
                passwordInput.value = 'password';

                demoAccount.textContent = email + ' · password';

            });

        });

        /* =====================================================
           SHOW / HIDE PASSWORD
        ===================================================== */

        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {

            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {

                passwordInput.type = 'text';

                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');

                this.setAttribute('aria-label', 'Sembunyikan password');

            } else {

                passwordInput.type = 'password';

                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');

                this.setAttribute('aria-label', 'Tampilkan password');

            }

        });
    </script>

</body>
</html>
