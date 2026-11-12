
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'WisataGate | Monitoring Kendaraan Area Wisata')</title>

    <!-- Theme -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('wisataGateTheme');
            document.documentElement.setAttribute(
                'data-theme',
                savedTheme === 'dark' ? 'dark' : 'light'
            );
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* =====================================================
           THEME
        ===================================================== */

        :root {
            --primary: #F04400;
            --primary-dark: #CF3700;
            --primary-soft: #FFF1EA;
            --bg: #F6F7F9;
            --surface: #FFFFFF;
            --surface-soft: #FAFBFC;
            --text: #17212B;
            --muted: #667085;
            --border: #E7E9ED;
            --navy: #17212B;
            --navy-soft: #25313D;
            --green: #16A36A;
            --shadow: 0 18px 45px rgba(23, 33, 43, .07);
        }

        [data-theme="dark"] {
            --primary: #FF6428;
            --primary-dark: #F04400;
            --primary-soft: #3A2119;
            --bg: #10161D;
            --surface: #19212A;
            --surface-soft: #202A35;
            --text: #F4F6F8;
            --muted: #AAB4C0;
            --border: #303B47;
            --navy: #0B1117;
            --navy-soft: #18222C;
            --green: #4ADE80;
            --shadow: 0 18px 45px rgba(0, 0, 0, .18);
        }

        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            transition: background .25s ease, color .25s ease;
        }

        a {
            text-decoration: none;
        }

        button {
            font-family: inherit;
        }

        /* =====================================================
           NAVBAR
        ===================================================== */

        .landing-navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 78px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            transition: background .25s ease;
        }

        .navbar-container {
            width: min(1280px, 100%);
            height: 100%;
            margin: auto;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .landing-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text);
            flex-shrink: 0;
        }

        .landing-brand:hover {
            color: var(--text);
        }

        .landing-brand-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, #FF6428, #D93600);
            color: white;
            font-size: 15px;
            font-weight: 800;
            letter-spacing: -1px;
            box-shadow: 0 5px 13px rgba(240, 68, 0, .19);
        }

        .landing-brand-name {
            font-size: 19px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .landing-brand-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 500;
            letter-spacing: .06em;
        }

        .landing-nav {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .landing-nav a {
            position: relative;
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
            transition: color .2s ease;
        }

        .landing-nav a:hover {
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .theme-button,
        .mobile-nav-button {
            width: 41px;
            height: 41px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: var(--surface);
            color: var(--muted);
            font-size: 17px;
            cursor: pointer;
            transition: .2s ease;
        }

        .theme-button:hover,
        .mobile-nav-button:hover {
            color: var(--primary);
            border-color: var(--primary);
        }

        .login-button {
            min-height: 41px;
            padding: 0 17px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 10px;
            background: var(--primary);
            color: white;
            font-size: 12px;
            font-weight: 700;
            transition: .2s ease;
        }

        .login-button:hover {
            background: var(--primary-dark);
            color: white;
            transform: translateY(-1px);
        }

        .mobile-nav-button {
            display: none;
        }

        /* =====================================================
           HERO
        ===================================================== */

        .landing-hero {
            position: relative;
            overflow: hidden;
            padding: 100px 30px 105px;
            background: #17212B;
            color: white;
        }

        .landing-hero::before {
            content: "";
            position: absolute;
            width: 620px;
            height: 620px;
            top: -360px;
            right: -100px;
            border-radius: 50%;
            background: rgba(240, 68, 0, .13);
            pointer-events: none;
        }

        .landing-hero::after {
            content: "";
            position: absolute;
            width: 450px;
            height: 450px;
            bottom: -330px;
            left: 28%;
            border-radius: 50%;
            background: rgba(240, 68, 0, .08);
            pointer-events: none;
        }

        .hero-container {
            position: relative;
            z-index: 2;
            width: min(1220px, 100%);
            margin: auto;
            display: grid;
            grid-template-columns: 1.08fr .92fr;
            align-items: center;
            gap: 70px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 8px 12px;
            margin-bottom: 23px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 7px;
            background: rgba(255,255,255,.055);
            color: #E7B9A6;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .09em;
            text-transform: uppercase;
        }

        .hero-badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #FF6428;
            box-shadow: 0 0 0 4px rgba(255,100,40,.13);
        }

        .hero-title {
            max-width: 690px;
            margin: 0;
            color: #FFFFFF;
            font-size: clamp(40px, 5vw, 64px);
            line-height: 1.09;
            font-weight: 800;
            letter-spacing: -2.8px;
        }

        .hero-title span {
            color: #FF6428;
        }

        .hero-description {
            max-width: 590px;
            margin: 24px 0 0;
            color: #B9C3CD;
            font-size: 15px;
            line-height: 1.9;
        }

        .hero-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 31px;
        }

        .hero-primary-button,
        .hero-secondary-button {
            min-height: 49px;
            padding: 0 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            transition: .2s ease;
        }

        .hero-primary-button {
            background: var(--primary);
            color: white;
            box-shadow: 0 9px 24px rgba(240,68,0,.2);
        }

        .hero-primary-button:hover {
            background: #FF6428;
            color: white;
            transform: translateY(-2px);
        }

        .hero-secondary-button {
            border: 1px solid rgba(255,255,255,.2);
            background: rgba(255,255,255,.04);
            color: #F4F6F8;
        }

        .hero-secondary-button:hover {
            border-color: rgba(255,255,255,.4);
            background: rgba(255,255,255,.09);
            color: white;
        }

        .hero-note {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-top: 30px;
            color: #9CAAB8;
            font-size: 11px;
        }

        .hero-note i {
            color: #FF6428;
            font-size: 14px;
        }

        /* =====================================================
           HERO GATE PANEL
        ===================================================== */

        .hero-visual {
            position: relative;
            min-height: 405px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .visual-glow {
            position: absolute;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(240,68,0,.2), transparent 70%);
        }

        .gate-card {
            position: relative;
            width: 100%;
            max-width: 420px;
            padding: 25px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 17px;
            background: rgba(35,47,59,.88);
            box-shadow: 0 28px 65px rgba(0,0,0,.27);
            backdrop-filter: blur(14px);
        }

        .gate-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }

        .gate-card-heading {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .gate-card-caption {
            color: #8F9BA8;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .gate-card-title {
            color: white;
            font-size: 15px;
            font-weight: 700;
        }

        .gate-status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 10px;
            border: 1px solid rgba(74,222,128,.15);
            border-radius: 6px;
            background: rgba(34,197,94,.09);
            color: #86EFAC;
            font-size: 10px;
            font-weight: 700;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #4ADE80;
        }

        .gate-main {
            padding: 25px 0 23px;
        }

        .gate-icon {
            width: 61px;
            height: 61px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            border-radius: 13px;
            background: linear-gradient(145deg, #FF6428, #D93600);
            color: white;
            font-size: 21px;
            font-weight: 800;
            letter-spacing: -1px;
            box-shadow: 0 9px 22px rgba(217,45,0,.25);
        }

        .gate-main-title {
            margin: 0;
            color: white;
            font-size: 21px;
            font-weight: 750;
            letter-spacing: -.5px;
        }

        .gate-main-text {
            max-width: 330px;
            margin: 9px 0 0;
            color: #AEBAC6;
            font-size: 12px;
            line-height: 1.8;
        }

        .gate-data {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 11px;
        }

        .gate-data-box {
            padding: 14px;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 9px;
            background: rgba(255,255,255,.035);
        }

        .gate-data-label {
            color: #8997A5;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .08em;
        }

        .gate-data-value {
            margin-top: 7px;
            color: #F7F9FB;
            font-size: 14px;
            font-weight: 700;
        }

        .gate-card-footer {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 17px;
            color: #93A0AE;
            font-size: 10px;
        }

        .gate-card-footer i {
            color: #FF6428;
        }

        /* =====================================================
           COMMON SECTION
        ===================================================== */

        .section {
            padding: 88px 30px;
        }

        .section-container {
            width: min(1180px, 100%);
            margin: auto;
        }

        .section-heading {
            max-width: 680px;
            margin: 0 auto 48px;
            text-align: center;
        }

        .section-label {
            margin-bottom: 12px;
            color: var(--primary);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .13em;
            text-transform: uppercase;
        }

        .section-title {
            margin: 0;
            color: var(--text);
            font-size: 35px;
            line-height: 1.25;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .section-description {
            margin: 15px auto 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.85;
        }

        /* =====================================================
           FEATURES
        ===================================================== */

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .feature-card {
            min-height: 235px;
            padding: 24px 21px;
            border: 1px solid var(--border);
            border-radius: 13px;
            background: var(--surface);
            transition: .25s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            border-color: rgba(240,68,0,.35);
            box-shadow: var(--shadow);
        }

        .feature-icon {
            width: 46px;
            height: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 23px;
            border: 1px solid rgba(240,68,0,.12);
            border-radius: 11px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 19px;
        }

        .feature-title {
            margin: 0;
            color: var(--text);
            font-size: 14px;
            font-weight: 750;
            line-height: 1.5;
        }

        .feature-text {
            margin: 9px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        /* =====================================================
           FLOW
        ===================================================== */

        .flow-section {
            background: var(--surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .flow-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .flow-item {
            position: relative;
            padding: 27px 25px;
            border: 1px solid var(--border);
            border-radius: 13px;
            background: var(--surface-soft);
            text-align: left;
        }

        .flow-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .flow-number {
            color: var(--primary);
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .flow-symbol {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 17px;
        }

        .flow-title {
            margin: 0;
            color: var(--text);
            font-size: 15px;
            font-weight: 750;
        }

        .flow-text {
            margin: 9px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        /* =====================================================
           MONITORING
        ===================================================== */

        .monitor-section {
            background: var(--bg);
        }

        .monitor-layout {
            display: grid;
            grid-template-columns: .9fr 1.1fr;
            align-items: center;
            gap: 75px;
        }

        .monitor-content .section-label {
            text-align: left;
        }

        .monitor-content .section-title {
            font-size: 34px;
        }

        .monitor-content .section-description {
            margin-left: 0;
            max-width: 490px;
        }

        .monitor-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 27px;
        }

        .monitor-list-item {
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
        }

        .monitor-check {
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 7px;
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 12px;
        }

        /* =====================================================
           MONITOR CARD
        ===================================================== */

        .monitor-card {
            padding: 23px;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .monitor-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }

        .monitor-card-heading {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .monitor-card-caption {
            color: var(--muted);
            font-size: 9px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .monitor-card-title {
            color: var(--text);
            font-size: 15px;
            font-weight: 750;
        }

        .monitor-live {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 9px;
            border-radius: 6px;
            background: rgba(22,163,106,.09);
            color: var(--green);
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .monitor-live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--green);
        }

        .vehicle-row {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 16px 0;
            border-bottom: 1px solid var(--border);
        }

        .vehicle-row:last-child {
            padding-bottom: 2px;
            border-bottom: 0;
        }

        .vehicle-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--surface-soft);
            color: var(--text);
            font-size: 19px;
        }

        .vehicle-info {
            min-width: 0;
            flex: 1;
        }

        .vehicle-plate {
            color: var(--text);
            font-size: 12px;
            font-weight: 750;
        }

        .vehicle-detail {
            margin-top: 4px;
            color: var(--muted);
            font-size: 10px;
        }

        .vehicle-status {
            padding: 6px 9px;
            border-radius: 5px;
            background: rgba(22,163,106,.09);
            color: var(--green);
            font-size: 9px;
            font-weight: 700;
            white-space: nowrap;
        }

        .monitor-card-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 17px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 10px;
        }

        /* =====================================================
           CTA
        ===================================================== */

        .cta-section {
            padding: 80px 30px;
            background: var(--surface);
        }

        .cta-card {
            position: relative;
            overflow: hidden;
            width: min(1180px, 100%);
            margin: auto;
            padding: 58px 60px;
            border-radius: 16px;
            background: #17212B;
            color: white;
        }

        .cta-card::before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            right: -100px;
            top: -200px;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 50%;
            box-shadow: 0 0 0 35px rgba(255,255,255,.025),
                        0 0 0 70px rgba(255,255,255,.02);
        }

        .cta-content {
            position: relative;
            z-index: 2;
            max-width: 670px;
        }

        .cta-label {
            margin-bottom: 13px;
            color: #FF8A60;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .cta-title {
            margin: 0;
            color: white;
            font-size: 32px;
            line-height: 1.3;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .cta-text {
            margin: 13px 0 0;
            color: #B9C3CD;
            font-size: 13px;
            line-height: 1.8;
        }

        .cta-button {
            min-height: 46px;
            margin-top: 24px;
            padding: 0 19px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border-radius: 8px;
            background: var(--primary);
            color: white;
            font-size: 12px;
            font-weight: 750;
            transition: .2s ease;
        }

        .cta-button:hover {
            background: #FF6428;
            color: white;
            transform: translateY(-2px);
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        .landing-footer {
            padding: 27px 30px;
            background: #111922;
            color: #94A3B8;
        }

        .footer-container {
            width: min(1180px, 100%);
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            font-size: 14px;
            font-weight: 750;
        }

        .footer-brand-logo {
            width: 31px;
            height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: linear-gradient(135deg, #FF6428, #D93600);
            color: white;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: -.5px;
        }

        .footer-text {
            color: #8996A4;
            font-size: 10px;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1100px) {
            .hero-container {
                grid-template-columns: 1fr;
                gap: 45px;
            }

            .hero-content {
                text-align: center;
            }

            .hero-description {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-buttons,
            .hero-note {
                justify-content: center;
            }

            .hero-visual {
                min-height: 370px;
            }

            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .monitor-layout {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .monitor-content {
                text-align: center;
            }

            .monitor-content .section-label {
                text-align: center;
            }

            .monitor-content .section-description {
                margin-left: auto;
                margin-right: auto;
            }

            .monitor-list {
                width: fit-content;
                margin-left: auto;
                margin-right: auto;
                align-items: flex-start;
            }

            .monitor-card {
                width: 100%;
                max-width: 650px;
                margin: auto;
            }
        }

        @media (max-width: 768px) {
            .landing-navbar {
                height: 70px;
            }

            .navbar-container {
                padding: 0 18px;
            }

            .landing-nav {
                display: none;
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                padding: 12px 18px 18px;
                flex-direction: column;
                align-items: stretch;
                gap: 2px;
                background: var(--surface);
                border-bottom: 1px solid var(--border);
                box-shadow: 0 12px 25px rgba(0,0,0,.08);
            }

            .landing-nav.show {
                display: flex;
            }

            .landing-nav a {
                padding: 12px 10px;
                border-radius: 7px;
            }

            .landing-nav a:hover {
                background: var(--primary-soft);
            }

            .mobile-nav-button {
                display: flex;
            }

            .landing-brand-subtitle {
                display: none;
            }

            .nav-actions {
                gap: 7px;
            }

            .login-button {
                min-height: 40px;
                padding: 0 12px;
                font-size: 11px;
            }

            .theme-button,
            .mobile-nav-button {
                width: 39px;
                height: 39px;
            }

            .landing-hero {
                padding: 75px 20px 70px;
            }

            .hero-title {
                font-size: clamp(38px, 8vw, 52px);
                letter-spacing: -1.7px;
            }

            .hero-description {
                font-size: 13px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .hero-primary-button,
            .hero-secondary-button {
                width: 100%;
                max-width: 330px;
            }

            .hero-note {
                font-size: 10px;
            }

            .section {
                padding: 70px 20px;
            }

            .section-title {
                font-size: 29px;
            }

            .section-heading {
                margin-bottom: 35px;
            }

            .feature-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .feature-card {
                min-height: 220px;
                padding: 19px 16px;
            }

            .feature-icon {
                margin-bottom: 17px;
            }

            .flow-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .flow-item {
                padding: 22px;
            }

            .flow-top {
                margin-bottom: 15px;
            }

            .monitor-content .section-title {
                font-size: 29px;
            }

            .cta-section {
                padding: 65px 20px;
            }

            .cta-card {
                padding: 42px 27px;
            }

            .cta-title {
                font-size: 27px;
            }

            .footer-container {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }
        }

        @media (max-width: 480px) {
            .landing-brand {
                gap: 9px;
            }

            .landing-brand-icon {
                width: 39px;
                height: 39px;
                font-size: 14px;
            }

            .landing-brand-name {
                font-size: 16px;
            }

            .login-button span {
                display: none;
            }

            .login-button {
                width: 39px;
                padding: 0;
                font-size: 15px;
            }

            .hero-title {
                font-size: 37px;
            }

            .hero-badge {
                font-size: 9px;
            }

            .hero-visual {
                min-height: auto;
            }

            .gate-card {
                padding: 19px;
            }

            .gate-main-title {
                font-size: 19px;
            }

            .gate-data {
                grid-template-columns: 1fr 1fr;
            }

            .gate-data-box {
                padding: 11px;
            }

            .gate-data-value {
                font-size: 12px;
            }

            .section-title {
                font-size: 26px;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .feature-card {
                min-height: auto;
            }

            .monitor-card {
                padding: 16px;
            }

            .monitor-card-top {
                align-items: flex-start;
            }

            .monitor-live {
                font-size: 9px;
            }

            .vehicle-row {
                gap: 9px;
            }

            .vehicle-icon {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }

            .vehicle-plate {
                font-size: 11px;
            }

            .vehicle-detail {
                font-size: 9px;
            }

            .vehicle-status {
                padding: 5px 6px;
                font-size: 8px;
            }

            .monitor-card-bottom {
                font-size: 9px;
            }

            .cta-card {
                padding: 35px 22px;
            }

            .cta-title {
                font-size: 24px;
            }

            .cta-text {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    <!-- =====================================================
         NAVBAR
    ===================================================== -->

    <header class="landing-navbar">
        <div class="navbar-container">

            <a href="{{ route('landing') }}" class="landing-brand">
                <div class="landing-brand-icon">WG</div>

                <div>
                    <div class="landing-brand-name">WisataGate</div>
                    <div class="landing-brand-subtitle">VEHICLE MONITORING SYSTEM</div>
                </div>
            </a>

            <nav class="landing-nav" id="landingNav">
                <a href="#beranda">Beranda</a>
                <a href="#fitur">Fitur</a>
                <a href="#alur">Alur Sistem</a>
                <a href="#monitoring">Monitoring</a>
            </nav>

            <div class="nav-actions">

                <button
                    type="button"
                    class="theme-button"
                    id="themeToggle"
                    title="Ganti mode tampilan"
                    aria-label="Ganti mode tampilan"
                >
                    <i class="bi bi-moon-fill" id="themeIcon"></i>
                </button>

                <a href="{{ route('login') }}" class="login-button">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Masuk ke Sistem</span>
                </a>

                <button
                    type="button"
                    class="mobile-nav-button"
                    id="mobileNavButton"
                    aria-label="Buka menu navigasi"
                    aria-expanded="false"
                >
                    <i class="bi bi-list"></i>
                </button>

            </div>
        </div>
    </header>


    <!-- =====================================================
         HERO
    ===================================================== -->

    <section class="landing-hero" id="beranda">

        <div class="hero-container">

            <div class="hero-content">

                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    Sistem Monitoring Area Wisata
                </div>

                <h1 class="hero-title">
                    Pengelolaan Kendaraan
                    <span>Lebih Teratur</span>
                    di Area Wisata
                </h1>

                <p class="hero-description">
                    WisataGate membantu petugas mencatat kendaraan masuk dan keluar,
                    memantau kendaraan yang masih berada di area wisata, serta
                    mengelola riwayat aktivitas gate dalam satu sistem terpusat.
                </p>

                <div class="hero-buttons">

                    <a href="{{ route('login') }}" class="hero-primary-button">
                        Masuk ke Sistem
                        <i class="bi bi-arrow-up-right"></i>
                    </a>

                    <a href="#fitur" class="hero-secondary-button">
                        Pelajari Fitur
                        <i class="bi bi-arrow-down"></i>
                    </a>

                </div>

                <div class="hero-note">
                    <i class="bi bi-shield-check"></i>
                    Pencatatan kendaraan lebih tertata dan mudah dipantau
                </div>

            </div>


            <!-- GATE PANEL -->

            <div class="hero-visual">

                <div class="visual-glow"></div>

                <div class="gate-card">

                    <div class="gate-card-header">

                        <div class="gate-card-heading">
                            <div class="gate-card-caption">Sistem Gerbang</div>
                            <div class="gate-card-title">Gate Utama</div>
                        </div>

                        <div class="gate-status">
                            <span class="status-dot"></span>
                            Sistem Aktif
                        </div>

                    </div>

                    <div class="gate-main">

                        <div class="gate-icon">WG</div>

                        <h3 class="gate-main-title">
                            Vehicle Monitoring
                        </h3>

                        <p class="gate-main-text">
                            Pemantauan kendaraan yang berada di dalam kawasan
                            wisata melalui pencatatan terpusat.
                        </p>

                    </div>

                    <div class="gate-data">

                        <div class="gate-data-box">
                            <div class="gate-data-label">STATUS GATE</div>
                            <div class="gate-data-value">Beroperasi</div>
                        </div>

                        <div class="gate-data-box">
                            <div class="gate-data-label">LOKASI AKSES</div>
                            <div class="gate-data-value">Gate Utama</div>
                        </div>

                    </div>

                    <div class="gate-card-footer">
                        <i class="bi bi-shield-lock"></i>
                        Sistem pencatatan kendaraan area wisata
                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         FITUR
    ===================================================== -->

    <section class="section" id="fitur">

        <div class="section-container">

            <div class="section-heading">

                <div class="section-label">Fitur Utama</div>

                <h2 class="section-title">
                    Pengelolaan aktivitas gate dalam satu tempat
                </h2>

                <p class="section-description">
                    Berbagai fitur yang membantu petugas melakukan pencatatan,
                    pemantauan, dan pengelolaan data kendaraan secara lebih terstruktur.
                </p>

            </div>

            <div class="feature-grid">

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>

                    <h3 class="feature-title">
                        Pencatatan Kendaraan Masuk
                    </h3>

                    <p class="feature-text">
                        Catat plat nomor, jenis kendaraan, pengemudi,
                        tujuan, dan waktu masuk melalui Gate Utama.
                    </p>

                </div>

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="bi bi-display"></i>
                    </div>

                    <h3 class="feature-title">
                        Monitoring Kendaraan
                    </h3>

                    <p class="feature-text">
                        Pantau kendaraan yang masih berada di dalam
                        area wisata beserta informasi pencatatannya.
                    </p>

                </div>

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="bi bi-box-arrow-right"></i>
                    </div>

                    <h3 class="feature-title">
                        Pencatatan Kendaraan Keluar
                    </h3>

                    <p class="feature-text">
                        Catat waktu kendaraan keluar agar status
                        kendaraan dapat diperbarui dengan jelas.
                    </p>

                </div>

                <div class="feature-card">

                    <div class="feature-icon">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>

                    <h3 class="feature-title">
                        Riwayat dan Laporan
                    </h3>

                    <p class="feature-text">
                        Lihat riwayat aktivitas serta rekap kendaraan
                        masuk dan keluar berdasarkan periode.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         ALUR SISTEM
    ===================================================== -->

    <section class="section flow-section" id="alur">

        <div class="section-container">

            <div class="section-heading">

                <div class="section-label">Alur Sistem</div>

                <h2 class="section-title">
                    Proses pencatatan yang lebih sederhana
                </h2>

                <p class="section-description">
                    Setiap kendaraan melalui tahapan pencatatan yang jelas
                    mulai dari memasuki area hingga meninggalkan kawasan wisata.
                </p>

            </div>

            <div class="flow-grid">

                <div class="flow-item">

                    <div class="flow-top">
                        <div class="flow-number">01</div>
                        <div class="flow-symbol">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                    </div>

                    <h3 class="flow-title">Kendaraan Masuk</h3>

                    <p class="flow-text">
                        Petugas mencatat informasi kendaraan ketika
                        memasuki area wisata melalui Gate Utama.
                    </p>

                </div>

                <div class="flow-item">

                    <div class="flow-top">
                        <div class="flow-number">02</div>
                        <div class="flow-symbol">
                            <i class="bi bi-clipboard-data"></i>
                        </div>
                    </div>

                    <h3 class="flow-title">Monitoring Area</h3>

                    <p class="flow-text">
                        Data kendaraan tersimpan dan dapat dipantau
                        selama kendaraan masih berada di dalam area.
                    </p>

                </div>

                <div class="flow-item">

                    <div class="flow-top">
                        <div class="flow-number">03</div>
                        <div class="flow-symbol">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                    </div>

                    <h3 class="flow-title">Kendaraan Keluar</h3>

                    <p class="flow-text">
                        Petugas mencatat kendaraan saat keluar dan
                        sistem memperbarui status pencatatannya.
                    </p>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         MONITORING
    ===================================================== -->

    <section class="section monitor-section" id="monitoring">

        <div class="section-container">

            <div class="monitor-layout">

                <div class="monitor-content">

                    <div class="section-label">Monitoring Area</div>

                    <h2 class="section-title">
                        Informasi kendaraan lebih mudah dipantau
                    </h2>

                    <p class="section-description">
                        Halaman monitoring menampilkan daftar kendaraan
                        yang masih berada di Area Wisata. Petugas dapat
                        melihat informasi kendaraan tanpa harus memeriksa
                        catatan secara manual.
                    </p>

                    <div class="monitor-list">

                        <div class="monitor-list-item">
                            <span class="monitor-check">
                                <i class="bi bi-check-lg"></i>
                            </span>
                            Plat nomor kendaraan
                        </div>

                        <div class="monitor-list-item">
                            <span class="monitor-check">
                                <i class="bi bi-check-lg"></i>
                            </span>
                            Jenis kendaraan dan pengemudi
                        </div>

                        <div class="monitor-list-item">
                            <span class="monitor-check">
                                <i class="bi bi-check-lg"></i>
                            </span>
                            Waktu kendaraan masuk
                        </div>

                        <div class="monitor-list-item">
                            <span class="monitor-check">
                                <i class="bi bi-check-lg"></i>
                            </span>
                            Informasi petugas pencatat
                        </div>

                    </div>

                </div>


                <!-- MONITORING PREVIEW -->

                <div class="monitor-card">

                    <div class="monitor-card-top">

                        <div class="monitor-card-heading">
                            <div class="monitor-card-caption">Preview Sistem</div>
                            <div class="monitor-card-title">Kendaraan di Area</div>
                        </div>

                        <div class="monitor-live">
                            <span class="monitor-live-dot"></span>
                            Monitoring Aktif
                        </div>

                    </div>


                    <div class="vehicle-row">

                        <div class="vehicle-icon">
                            <i class="bi bi-car-front-fill"></i>
                        </div>

                        <div class="vehicle-info">
                            <div class="vehicle-plate">S 1234 AB</div>
                            <div class="vehicle-detail">
                                Mobil · Cinta · Gate Utama
                            </div>
                        </div>

                        <div class="vehicle-status">Di Dalam</div>

                    </div>


                    <div class="vehicle-row">

                        <div class="vehicle-icon">
                            <i class="bi bi-bicycle"></i>
                        </div>

                        <div class="vehicle-info">
                            <div class="vehicle-plate">S 5678 CD</div>
                            <div class="vehicle-detail">
                                Motor · Pengunjung · Gate Utama
                            </div>
                        </div>

                        <div class="vehicle-status">Di Dalam</div>

                    </div>


                    <div class="vehicle-row">

                        <div class="vehicle-icon">
                            <i class="bi bi-bus-front"></i>
                        </div>

                        <div class="vehicle-info">
                            <div class="vehicle-plate">S 9012 EF</div>
                            <div class="vehicle-detail">
                                Bus · Rombongan · Gate Utama
                            </div>
                        </div>

                        <div class="vehicle-status">Di Dalam</div>

                    </div>


                    <div class="monitor-card-bottom">
                        <span>Contoh tampilan monitoring kendaraan</span>
                        <span><i class="bi bi-shield-check"></i> WisataGate</span>
                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         CTA
    ===================================================== -->

    <section class="cta-section">

        <div class="cta-card">

            <div class="cta-content">

                <div class="cta-label">WisataGate System</div>

                <h2 class="cta-title">
                    Kelola aktivitas kendaraan area wisata dengan lebih teratur.
                </h2>

                <p class="cta-text">
                    Masuk ke WisataGate untuk mengelola data kendaraan,
                    mencatat kendaraan masuk dan keluar, serta memantau
                    kendaraan yang masih berada di kawasan wisata.
                </p>

                <a href="{{ route('login') }}" class="cta-button">
                    Masuk ke Sistem
                    <i class="bi bi-arrow-right"></i>
                </a>

            </div>

        </div>

    </section>


    <!-- =====================================================
         FOOTER
    ===================================================== -->

    <footer class="landing-footer">

        <div class="footer-container">

            <div class="footer-brand">
                <span class="footer-brand-logo">WG</span>
                WisataGate
            </div>

            <div class="footer-text">
                Sistem Monitoring Kendaraan Area Wisata
            </div>

            <div class="footer-text">
                © {{ date('Y') }} WisataGate. All rights reserved.
            </div>

        </div>

    </footer>


    <!-- =====================================================
         JAVASCRIPT
    ===================================================== -->

    <script>
        /* =====================================================
           THEME
        ===================================================== */

        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');

        function updateThemeIcon() {
            const currentTheme =
                document.documentElement.getAttribute('data-theme');

            if (currentTheme === 'dark') {
                themeIcon.className = 'bi bi-sun-fill';
                themeToggle.setAttribute('title', 'Aktifkan Light Mode');
                themeToggle.setAttribute('aria-label', 'Aktifkan Light Mode');
            } else {
                themeIcon.className = 'bi bi-moon-fill';
                themeToggle.setAttribute('title', 'Aktifkan Dark Mode');
                themeToggle.setAttribute('aria-label', 'Aktifkan Dark Mode');
            }
        }

        updateThemeIcon();

        if (themeToggle) {
            themeToggle.addEventListener('click', function () {
                const currentTheme =
                    document.documentElement.getAttribute('data-theme');

                const newTheme =
                    currentTheme === 'dark' ? 'light' : 'dark';

                document.documentElement.setAttribute('data-theme', newTheme);

                localStorage.setItem('wisataGateTheme', newTheme);

                updateThemeIcon();
            });
        }


        /* =====================================================
           MOBILE NAVIGATION
        ===================================================== */

        const mobileNavButton =
            document.getElementById('mobileNavButton');

        const landingNav =
            document.getElementById('landingNav');

        if (mobileNavButton && landingNav) {
            mobileNavButton.addEventListener('click', function () {
                const isOpen = landingNav.classList.toggle('show');

                mobileNavButton.setAttribute(
                    'aria-expanded',
                    isOpen ? 'true' : 'false'
                );

                mobileNavButton.innerHTML = isOpen
                    ? '<i class="bi bi-x-lg"></i>'
                    : '<i class="bi bi-list"></i>';
            });

            document.querySelectorAll('.landing-nav a').forEach(function (link) {
                link.addEventListener('click', function () {
                    landingNav.classList.remove('show');

                    mobileNavButton.setAttribute('aria-expanded', 'false');
                    mobileNavButton.innerHTML = '<i class="bi bi-list"></i>';
                });
            });

            document.addEventListener('click', function (event) {
                const clickedInsideNav =
                    landingNav.contains(event.target) ||
                    mobileNavButton.contains(event.target);

                if (!clickedInsideNav) {
                    landingNav.classList.remove('show');
                    mobileNavButton.setAttribute('aria-expanded', 'false');
                    mobileNavButton.innerHTML = '<i class="bi bi-list"></i>';
                }
            });
        }
    </script>

</body>
</html>
