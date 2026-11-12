@extends('layouts.petugas')

@section('content')

<style>
    .petugas-dashboard {
        width: 100%;
    }

    /* =========================================================
       HERO
    ========================================================= */

    .wg-hero {
        position: relative;
        overflow: hidden;
        min-height: 304px;
        padding: 42px 38px 30px;
        margin-bottom: 26px;
        border-radius: 20px;
        background:
            linear-gradient(
                135deg,
                #17202b 0%,
                #1f2937 52%,
                #27364a 100%
            );
        color: #fff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .10);
    }

    .wg-hero::before {
        content: "";
        position: absolute;
        width: 430px;
        height: 430px;
        right: -180px;
        top: -260px;
        border: 1px solid rgba(255,255,255,.05);
        border-radius: 50%;
    }

    .wg-hero::after {
        content: "";
        position: absolute;
        width: 330px;
        height: 330px;
        right: 12%;
        bottom: -290px;
        border: 1px solid rgba(255,255,255,.04);
        border-radius: 50%;
    }

    .wg-hero-content {
        position: relative;
        z-index: 2;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 335px;
        align-items: center;
        gap: 40px;
    }

    .wg-hero-label {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 20px;
        color: #fdba74;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
    }

    .wg-hero-label-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 0 5px rgba(34,197,94,.10);
    }

    .wg-hero-number-row {
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 18px;
    }

    .wg-hero-number {
        color: #fff;
        font-size: 76px;
        line-height: .9;
        font-weight: 800;
        letter-spacing: -3px;
    }

    .wg-hero-number-text {
        color: #f3f4f6;
        font-size: 19px;
        font-weight: 700;
    }

    .wg-hero-description {
        max-width: 650px;
        margin: 14px 0 0;
        color: #9ca3af;
        font-size: 13px;
        line-height: 1.6;
    }

    .wg-hero-condition {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-top: 25px;
        padding: 11px 16px;
        border: 1px solid rgba(255,255,255,.10);
        border-radius: 10px;
        background: rgba(255,255,255,.025);
        color: #9ca3af;
        font-size: 11px;
    }

    .wg-hero-condition strong {
        color: #4ade80;
        font-weight: 700;
    }

    .wg-hero-condition-value {
        color: #cbd5e1;
    }

    .wg-hero-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .wg-hero-button {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-height: 56px;
        padding: 13px 18px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        transition: .2s ease;
    }

    .wg-hero-button-primary {
        background: #ea580c;
        color: #fff;
        border: 1px solid #ea580c;
        box-shadow: 0 8px 18px rgba(234,88,12,.18);
    }

    .wg-hero-button-primary:hover {
        background: #c2410c;
        border-color: #c2410c;
        color: #fff;
        transform: translateY(-1px);
    }

    .wg-hero-button-secondary {
        background: rgba(255,255,255,.08);
        color: #f8fafc;
        border: 1px solid rgba(255,255,255,.18);
    }

    .wg-hero-button-secondary:hover {
        background: rgba(255,255,255,.13);
        color: #fff;
    }

    .wg-hero-note {
        margin-top: 13px;
        text-align: center;
        color: #94a3b8;
        font-size: 10px;
    }

    .wg-hero-footer {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 25px;
        padding-top: 16px;
        border-top: 1px solid rgba(255,255,255,.08);
        color: #94a3b8;
        font-size: 10px;
    }

    .wg-gate-status {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .wg-gate-status i {
        color: #22c55e;
    }


    /* =========================================================
       STAT CARDS
    ========================================================= */

    .wg-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 26px;
    }

    .wg-stat-card {
        min-height: 170px;
        padding: 26px 26px 23px;
        background: #fff;
        border: 1px solid #e1e7ee;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(15,23,42,.035);
    }

    .wg-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .wg-stat-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
    }

    .wg-stat-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #fff7ed;
        color: #ea580c;
        font-size: 18px;
        flex-shrink: 0;
    }

    .wg-stat-value {
        margin-top: 21px;
        color: #111827;
        font-size: 36px;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .wg-stat-unit {
        margin-left: 5px;
        color: #475569;
        font-size: 13px;
        font-weight: 500;
        letter-spacing: 0;
    }

    .wg-stat-description {
        margin-top: 13px;
        color: #94a3b8;
        font-size: 11px;
    }


    /* =========================================================
       GENERAL PANELS
    ========================================================= */

    .wg-panel-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.25fr) minmax(370px, .75fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .wg-panel {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e1e7ee;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(15,23,42,.035);
    }

    .wg-panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 25px 27px 19px;
    }

    .wg-panel-title {
        margin: 0;
        color: #111827;
        font-size: 18px;
        font-weight: 800;
    }

    .wg-panel-description {
        max-width: 560px;
        margin: 6px 0 0;
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.5;
    }

    .wg-panel-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #ea580c;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .wg-panel-link:hover {
        color: #c2410c;
    }


    /* =========================================================
       CHART
    ========================================================= */

    .wg-chart {
        padding: 5px 27px 25px;
    }

    .wg-chart-legend {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 18px;
        margin-bottom: 13px;
    }

    .wg-legend {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #475569;
        font-size: 11px;
        font-weight: 600;
    }

    .wg-legend-box {
        width: 12px;
        height: 12px;
        border-radius: 3px;
    }

    .wg-legend-in {
        background: #ea580c;
    }

    .wg-legend-out {
        background: #f59e0b;
    }

    .wg-chart-area {
        position: relative;
        height: 275px;
        display: flex;
        align-items: flex-end;
        justify-content: space-around;
        gap: 18px;
        padding: 0 12px 28px;
        border-bottom: 1px solid #e5e7eb;
    }

    .wg-chart-line {
        position: absolute;
        left: 0;
        right: 0;
        border-top: 1px solid #eef2f7;
    }

    .wg-line-1 {
        top: 10%;
    }

    .wg-line-2 {
        top: 40%;
    }

    .wg-line-3 {
        top: 70%;
    }

    .wg-chart-group {
        position: relative;
        z-index: 2;
        height: 100%;
        min-width: 45px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 5px;
    }

    .wg-bar {
        width: 22px;
        min-height: 4px;
        border-radius: 5px 5px 0 0;
        transition: .2s ease;
    }

    .wg-bar:hover {
        opacity: .75;
    }

    .wg-bar-in {
        background: #ea580c;
    }

    .wg-bar-out {
        background: #f59e0b;
    }

    .wg-chart-label {
        position: absolute;
        bottom: -23px;
        color: #64748b;
        font-size: 10px;
        white-space: nowrap;
    }

    .wg-chart-y {
        position: absolute;
        left: -1px;
        color: #94a3b8;
        font-size: 9px;
    }

    .wg-y-top {
        top: 3px;
    }

    .wg-y-mid {
        top: 46%;
    }

    .wg-y-bottom {
        bottom: 28px;
    }


    /* =========================================================
       KOMPOSISI JENIS KENDARAAN
    ========================================================= */

    .wg-composition {
        padding: 7px 27px 27px;
    }

    .wg-composition-count {
        padding: 6px 10px;
        border-radius: 7px;
        background: #f3f4f6;
        color: #475569;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .wg-type {
        margin-bottom: 18px;
    }

    .wg-type:last-child {
        margin-bottom: 0;
    }

    .wg-type-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 7px;
        gap: 10px;
    }

    .wg-type-name {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
        min-width: 0;
    }

    .wg-type-icon {
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 12px;
        flex-shrink: 0;
    }

    .wg-type-icon.orange {
        background: #fff7ed;
        color: #ea580c;
    }

    .wg-type-icon.blue {
        background: #eff6ff;
        color: #2563eb;
    }

    .wg-type-icon.green {
        background: #ecfdf5;
        color: #16a34a;
    }

    .wg-type-icon.purple {
        background: #f5f3ff;
        color: #7c3aed;
    }

    .wg-type-icon.red {
        background: #fef2f2;
        color: #dc2626;
    }

    .wg-type-icon.cyan {
        background: #ecfeff;
        color: #0891b2;
    }

    .wg-type-value {
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .wg-type-percent {
        margin-left: 7px;
        color: #94a3b8;
        font-weight: 500;
    }

    .wg-type-track {
        width: 100%;
        height: 9px;
        overflow: hidden;
        border-radius: 20px;
        background: #eef2f7;
    }

    .wg-type-progress {
        height: 100%;
        border-radius: 20px;
    }

    .wg-type-progress.orange {
        background: #ea580c;
    }

    .wg-type-progress.blue {
        background: #2563eb;
    }

    .wg-type-progress.green {
        background: #16a34a;
    }

    .wg-type-progress.purple {
        background: #7c3aed;
    }

    .wg-type-progress.red {
        background: #dc2626;
    }

    .wg-type-progress.cyan {
        background: #0891b2;
    }


    /* =========================================================
       BOTTOM PANELS
    ========================================================= */

    .wg-bottom-grid {
        display: grid;
        grid-template-columns: minmax(0, .78fr) minmax(0, 1.22fr);
        gap: 20px;
    }


    /* =========================================================
       AKTIVITAS TERKINI
    ========================================================= */

    .wg-activity-list {
        padding: 4px 27px 23px;
    }

    .wg-activity-item {
        display: grid;
        grid-template-columns: 58px minmax(0, 1fr) auto;
        align-items: center;
        gap: 14px;
        min-height: 76px;
        border-bottom: 1px solid #eef2f7;
    }

    .wg-activity-item:last-child {
        border-bottom: none;
    }

    .wg-activity-time {
        padding: 7px 5px;
        border-radius: 6px;
        background: #f8fafc;
        color: #334155;
        text-align: center;
        font-size: 10px;
        font-weight: 800;
    }

    .wg-activity-main {
        min-width: 0;
    }

    .wg-activity-plate {
        color: #111827;
        font-size: 12px;
        font-weight: 800;
    }

    .wg-activity-detail {
        margin-top: 4px;
        overflow: hidden;
        color: #64748b;
        font-size: 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .wg-activity-detail span {
        color: #94a3b8;
    }

    .wg-activity-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-width: 78px;
        padding: 7px 9px;
        border: 1px solid #fed7aa;
        border-radius: 7px;
        background: #fff7ed;
        color: #ea580c;
        text-decoration: none;
        font-size: 9px;
        font-weight: 700;
    }

    .wg-activity-button:hover {
        background: #ea580c;
        color: #fff;
    }


    /* =========================================================
       AREA TABLE
    ========================================================= */

    .wg-area-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 0 27px 18px;
    }

    .wg-search-box {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1;
        min-height: 38px;
        padding: 0 12px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #fff;
        color: #94a3b8;
        font-size: 10px;
    }

    .wg-filter-list {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .wg-filter {
        padding: 8px 11px;
        border: none;
        border-radius: 7px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 9px;
        font-weight: 700;
    }

    .wg-filter.active {
        background: #1f2937;
        color: #fff;
    }

    .wg-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .wg-table {
        width: 100%;
        min-width: 680px;
        border-collapse: collapse;
    }

    .wg-table th {
        padding: 14px 12px;
        background: #fafafa;
        border-top: 1px solid #eef2f7;
        border-bottom: 1px solid #eef2f7;
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
    }

    .wg-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #eef2f7;
        color: #475569;
        font-size: 10px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .wg-table tbody tr:last-child td {
        border-bottom: none;
    }

    .wg-table tbody tr:hover {
        background: #fffaf5;
    }

    .wg-plate {
        display: inline-block;
        padding: 5px 7px;
        border-radius: 4px;
        background: #f1f5f9;
        color: #111827;
        font-size: 10px;
        font-weight: 800;
    }

    .wg-driver {
        color: #111827;
        font-weight: 700;
    }

    .wg-purpose {
        color: #64748b;
    }

    .wg-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border: 1px solid #bbf7d0;
        border-radius: 20px;
        background: #ecfdf5;
        color: #15803d;
        font-size: 9px;
        font-weight: 700;
    }

    .wg-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #22c55e;
    }

    .wg-exit-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 8px 10px;
        border: 1px solid #fed7aa;
        border-radius: 7px;
        background: #fffaf5;
        color: #ea580c;
        font-size: 9px;
        font-weight: 700;
        text-decoration: none;
    }

    .wg-exit-button:hover {
        background: #ea580c;
        color: #fff;
    }


    /* =========================================================
       EMPTY
    ========================================================= */

    .wg-empty {
        padding: 50px 20px;
        text-align: center;
        color: #94a3b8;
        font-size: 11px;
    }

    .wg-empty i {
        display: block;
        margin-bottom: 9px;
        color: #cbd5e1;
        font-size: 28px;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1199px) {

        .wg-hero-content {
            grid-template-columns: minmax(0, 1fr) 280px;
        }

        .wg-hero-number {
            font-size: 65px;
        }

        .wg-panel-grid {
            grid-template-columns: 1fr;
        }

        .wg-bottom-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 991px) {

        .wg-hero {
            padding: 30px;
        }

        .wg-hero-content {
            grid-template-columns: 1fr;
        }

        .wg-hero-actions {
            width: 100%;
            flex-direction: row;
        }

        .wg-hero-button {
            flex: 1;
        }

        .wg-stats {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {

        .wg-hero {
            padding: 25px;
        }

        .wg-hero-number {
            font-size: 52px;
        }

        .wg-hero-number-text {
            font-size: 15px;
        }

        .wg-hero-actions {
            flex-direction: column;
        }

        .wg-stats {
            grid-template-columns: 1fr;
        }

        .wg-chart-area {
            height: 220px;
        }

        .wg-panel-header {
            padding: 20px;
        }

        .wg-chart,
        .wg-composition,
        .wg-activity-list {
            padding-left: 20px;
            padding-right: 20px;
        }

        .wg-area-toolbar {
            padding-left: 20px;
            padding-right: 20px;
            align-items: stretch;
            flex-direction: column;
        }
    }

    @media (max-width: 576px) {

        .wg-hero {
            padding: 21px;
            border-radius: 14px;
        }

        .wg-hero-number {
            font-size: 44px;
        }

        .wg-hero-number-text {
            font-size: 13px;
        }

        .wg-hero-description {
            font-size: 11px;
        }

        .wg-hero-footer {
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }

        .wg-stat-card {
            min-height: 145px;
            padding: 22px;
        }

        .wg-panel-title {
            font-size: 16px;
        }

        .wg-panel-description {
            font-size: 11px;
        }

        .wg-chart-area {
            min-width: 520px;
        }

        .wg-chart {
            overflow-x: auto;
        }

        .wg-activity-item {
            grid-template-columns: 50px minmax(0, 1fr);
            padding: 10px 0;
        }

        .wg-activity-button {
            grid-column: 2;
            justify-self: start;
        }

        .wg-type-top {
            align-items: flex-start;
        }

        .wg-type-value {
            text-align: right;
        }
    }
</style>


<div class="petugas-dashboard">

    {{-- =====================================================
         HERO
    ====================================================== --}}

    <section class="wg-hero">

        <div class="wg-hero-content">

            <div>

                <div class="wg-hero-label">

                    <span class="wg-hero-label-dot"></span>

                    KENDARAAN DI AREA

                    <span style="color:#64748b;">
                        •
                    </span>

                    <span style="color:#f8fafc;">
                        Gate Utama Terhubung
                    </span>

                </div>

                <div class="wg-hero-number-row">

                    <span class="wg-hero-number">
                        {{ $totalInside ?? 0 }}
                    </span>

                    <span class="wg-hero-number-text">
                        kendaraan sedang berada di Area Wisata
                    </span>

                </div>

                <p class="wg-hero-description">
                    Data diperbarui berdasarkan pencatatan kendaraan
                    yang masuk dan keluar melalui Gate Utama.
                </p>

                <div class="wg-hero-condition">

                    Kondisi Area Saat Ini:

                    <strong>
                        <i class="bi bi-check-circle me-1"></i>
                        Lancar & Terkendali
                    </strong>

                    <span class="wg-hero-condition-value">
                        ({{ $totalInside ?? 0 }} / ~100 daya tampung)
                    </span>

                </div>

            </div>


            <div class="wg-hero-actions">

                <a
                    href="{{ route('gate-logs.create') }}"
                    class="wg-hero-button wg-hero-button-primary"
                >
                    <i class="bi bi-box-arrow-in-right"></i>
                    + Catat Kendaraan Masuk
                </a>

                <a
                    href="{{ route('monitoring.index') }}"
                    class="wg-hero-button wg-hero-button-secondary"
                >
                    <i class="bi bi-box-arrow-right"></i>
                    Catat Kendaraan Keluar
                </a>

                <div class="wg-hero-note">
                    Pencatatan langsung pada palang Gate Utama
                </div>

            </div>

        </div>


        <div class="wg-hero-footer">

            <div class="wg-gate-status">

                <i class="bi bi-geo-alt-fill"></i>

                Gate Utama

                <span>•</span>

                <span style="color:#4ade80;">
                    Aktif
                </span>

            </div>

            <div>
                {{ now()->locale('id')->translatedFormat('l, d F Y') }}
            </div>

        </div>

    </section>


    {{-- =====================================================
         STATISTIK
    ====================================================== --}}

    <section class="wg-stats">

        {{-- MASUK --}}

        <div class="wg-stat-card">

            <div class="wg-stat-top">

                <span class="wg-stat-label">
                    KENDARAAN MASUK HARI INI
                </span>

                <div class="wg-stat-icon">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>

            </div>

            <div class="wg-stat-value">

                {{-- DATA ASLI DARI GATE LOG --}}
                {{ $totalEntry ?? 0 }}

                <span class="wg-stat-unit">
                    unit kendaraan
                </span>

            </div>

            <div class="wg-stat-description">
                <i class="bi bi-arrow-up-right me-1"></i>
                Tercatat di Gate Utama
            </div>

        </div>


        {{-- KELUAR --}}

        <div class="wg-stat-card">

            <div class="wg-stat-top">

                <span class="wg-stat-label">
                    KENDARAAN KELUAR HARI INI
                </span>

                <div
                    class="wg-stat-icon"
                    style="
                        background:#fffbeb;
                        color:#f59e0b;
                    "
                >
                    <i class="bi bi-box-arrow-right"></i>
                </div>

            </div>

            <div class="wg-stat-value">

                {{ $totalExit ?? 0 }}

                <span class="wg-stat-unit">
                    unit kendaraan
                </span>

            </div>

            <div class="wg-stat-description">

                <i
                    class="bi bi-arrow-down-right me-1"
                    style="color:#f59e0b;"
                ></i>

                Sudah meninggalkan area

            </div>

        </div>


        {{-- TOTAL --}}

        <div class="wg-stat-card">

            <div class="wg-stat-top">

                <span class="wg-stat-label">
                    TOTAL KENDARAAN HARI INI
                </span>

                <div
                    class="wg-stat-icon"
                    style="
                        background:#f5f3ff;
                        color:#6366f1;
                    "
                >
                    <i class="bi bi-layers"></i>
                </div>

            </div>

            <div class="wg-stat-value">

                {{-- DATA ASLI DARI GATE LOG --}}
                {{ $totalToday ?? 0 }}

                <span class="wg-stat-unit">
                    total kedatangan
                </span>

            </div>

            <div class="wg-stat-description">
                Arus operasional wisata harian
            </div>

        </div>

    </section>


    {{-- =====================================================
         GRAFIK + KOMPOSISI
    ====================================================== --}}

    <section class="wg-panel-grid">


        {{-- =================================================
             GRAFIK AKTIVITAS
        ================================================== --}}

        <div class="wg-panel">

            <div class="wg-panel-header">

                <div>

                    <h3 class="wg-panel-title">
                        Aktivitas Kendaraan Hari Ini
                    </h3>

                    <p class="wg-panel-description">
                        Perbandingan volume kendaraan masuk dan keluar
                        melalui Gate Utama per interval waktu.
                    </p>

                </div>

                <div class="wg-chart-legend">

                    <div class="wg-legend">
                        <span class="wg-legend-box wg-legend-in"></span>
                        Masuk
                    </div>

                    <div class="wg-legend">
                        <span class="wg-legend-box wg-legend-out"></span>
                        Keluar
                    </div>

                </div>

            </div>


            @php

                /*
                |--------------------------------------------------------------------------
                | GRAFIK DARI DATA GATE LOG
                |--------------------------------------------------------------------------
                */

                $chartEntryValues = $chartEntries ?? [];
                $chartExitValues = $chartExits ?? [];

                $hours = $chartHours ?? [
                    '07:00',
                    '09:00',
                    '11:00',
                    '13:00',
                    '15:00',
                    '17:00'
                ];

                $chartEntryValues = array_pad(
                    $chartEntryValues,
                    count($hours),
                    0
                );

                $chartExitValues = array_pad(
                    $chartExitValues,
                    count($hours),
                    0
                );

                $chartEntryValues = array_slice(
                    $chartEntryValues,
                    0,
                    count($hours)
                );

                $chartExitValues = array_slice(
                    $chartExitValues,
                    0,
                    count($hours)
                );

                $chartMax = max(
                    !empty($chartEntryValues)
                        ? max($chartEntryValues)
                        : 0,

                    !empty($chartExitValues)
                        ? max($chartExitValues)
                        : 0,

                    1
                );

            @endphp


            <div class="wg-chart">

                <div class="wg-chart-area">

                    <span class="wg-chart-y wg-y-top">
                        {{ $chartMax }}
                    </span>

                    <span class="wg-chart-y wg-y-mid">
                        {{ max(1, round($chartMax / 2)) }}
                    </span>

                    <span class="wg-chart-y wg-y-bottom">
                        0
                    </span>

                    <div class="wg-chart-line wg-line-1"></div>
                    <div class="wg-chart-line wg-line-2"></div>
                    <div class="wg-chart-line wg-line-3"></div>


                    @foreach ($hours as $index => $hour)

                        @php

                            $entry =
                                (int) (
                                    $chartEntryValues[$index]
                                    ?? 0
                                );

                            $exit =
                                (int) (
                                    $chartExitValues[$index]
                                    ?? 0
                                );

                            $entryHeight =
                                $entry > 0
                                    ? max(
                                        5,
                                        ($entry / $chartMax) * 100
                                    )
                                    : 4;

                            $exitHeight =
                                $exit > 0
                                    ? max(
                                        4,
                                        ($exit / $chartMax) * 100
                                    )
                                    : 4;

                        @endphp


                        <div class="wg-chart-group">

                            <div
                                class="wg-bar wg-bar-in"
                                style="height:{{ $entryHeight }}%;"
                                title="Masuk: {{ $entry }}"
                            ></div>

                            <div
                                class="wg-bar wg-bar-out"
                                style="height:{{ $exitHeight }}%;"
                                title="Keluar: {{ $exit }}"
                            ></div>

                            <span class="wg-chart-label">
                                {{ $hour }}
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- =================================================
             KOMPOSISI JENIS KENDARAAN
        ================================================== --}}

        <div class="wg-panel">

            <div class="wg-panel-header">

                <div>

                    <h3 class="wg-panel-title">
                        Komposisi Jenis Kendaraan
                    </h3>

                    <p class="wg-panel-description">
                        Distribusi kendaraan yang saat ini aktif
                        berada di dalam Area Wisata.
                    </p>

                </div>

                <span class="wg-composition-count">
                    {{ $totalInside ?? 0 }} di area
                </span>

            </div>


            @php

                /*
                |--------------------------------------------------------------------------
                | PENTING:
                | Komposisi sekarang memakai $vehicleTypeCounts
                | dari GateLog kendaraan yang masih berada di area.
                |
                | TIDAK memakai tabel master $vehicles.
                |--------------------------------------------------------------------------
                */

                $vehicleTypes = $vehicleTypeCounts ?? [];

                arsort($vehicleTypes);

                $vehicleTypes = array_slice(
                    $vehicleTypes,
                    0,
                    6,
                    true
                );

                $totalTypeVehicles = array_sum($vehicleTypes);

                $maxType = !empty($vehicleTypes)
                    ? max($vehicleTypes)
                    : 1;


                $compositionColors = [
                    'orange',
                    'blue',
                    'green',
                    'purple',
                    'red',
                    'cyan',
                ];


                $compositionIcons = [
                    'bi-car-front-fill',
                    'bi-scooter',
                    'bi-bus-front-fill',
                    'bi-truck-front-fill',
                    'bi-taxi-front-fill',
                    'bi-truck',
                ];


                $compositionIndex = 0;

            @endphp


            <div class="wg-composition">

                @forelse ($vehicleTypes as $type => $typeTotal)

                    @php

                        $colorIndex =
                            $compositionIndex
                            % count($compositionColors);

                        $iconIndex =
                            $compositionIndex
                            % count($compositionIcons);

                        $colorClass =
                            $compositionColors[$colorIndex];

                        $iconClass =
                            $compositionIcons[$iconIndex];


                        /*
                        | Persentase berdasarkan seluruh kendaraan
                        | yang sedang berada di area.
                        */

                        $percent = $totalTypeVehicles > 0
                            ? round(
                                (
                                    $typeTotal
                                    / $totalTypeVehicles
                                ) * 100
                            )
                            : 0;


                        $width = max(
                            8,
                            (
                                $typeTotal
                                / max($maxType, 1)
                            ) * 100
                        );


                        $compositionIndex++;

                    @endphp


                    <div class="wg-type">

                        <div class="wg-type-top">

                            <div class="wg-type-name">

                                <span
                                    class="wg-type-icon {{ $colorClass }}"
                                >

                                    <i
                                        class="bi {{ $iconClass }}"
                                    ></i>

                                </span>

                                {{ $type }}

                            </div>


                            <div class="wg-type-value">

                                {{ $typeTotal }} unit

                                <span class="wg-type-percent">
                                    ({{ $percent }}%)
                                </span>

                            </div>

                        </div>


                        <div class="wg-type-track">

                            <div
                                class="wg-type-progress {{ $colorClass }}"
                                style="width:{{ $width }}%;"
                            ></div>

                        </div>

                    </div>


                @empty

                    <div class="wg-empty">

                        <i class="bi bi-car-front"></i>

                        Belum ada kendaraan yang sedang berada
                        di dalam area.

                    </div>

                @endforelse

            </div>

        </div>

    </section>


    {{-- =====================================================
         AKTIVITAS TERKINI + KENDARAAN DI AREA
    ====================================================== --}}

    <section class="wg-bottom-grid">


        {{-- =================================================
             AKTIVITAS TERKINI
        ================================================== --}}

        <div class="wg-panel">

            <div class="wg-panel-header">

                <div>

                    <h3 class="wg-panel-title">
                        Aktivitas Terkini Gate Utama
                    </h3>

                    <p class="wg-panel-description">
                        Kronologi riil kendaraan melintasi
                        Gate Utama Area Wisata.
                    </p>

                </div>

                <a
                    href="{{ route('petugas.riwayat') }}"
                    class="wg-panel-link"
                >
                    Lihat Riwayat Lengkap
                    <i class="bi bi-arrow-right"></i>
                </a>
                
            </div>


            <div class="wg-activity-list">

                @if (
                    isset($recentGateLogs)
                    && $recentGateLogs->count()
                )

                    @foreach ($recentGateLogs as $gateLog)

                        @php

                            // Data kendaraan tersimpan langsung pada tabel gate_logs.
                            $plate = $gateLog->plate_number ?: '-';
                            $driver = $gateLog->driver_name ?: '-';
                            $type = $gateLog->vehicle_type ?: 'Lainnya';


                            $isExit =
                                $gateLog->status
                                === 'Sudah Keluar';

                            $activityTime =
                                $isExit
                                    ? $gateLog->gate_out
                                    : $gateLog->gate_in;

                        @endphp


                        <div class="wg-activity-item">

                            <div class="wg-activity-time">

                                {{
                                    $activityTime
                                        ? $activityTime->format('H:i')
                                        : '--:--'
                                }}

                            </div>


                            <div class="wg-activity-main">

                                <div class="wg-activity-plate">

                                    {{ $plate }}

                                </div>


                                <div class="wg-activity-detail">

                                    {{ $type }}

                                    <span>•</span>

                                    {{ $driver }}

                                    <span>•</span>

                                    {{ $isExit ? 'Keluar' : 'Masuk' }}

                                    <span>•</span>

                                    Petugas:
                                    {{
                                        $gateLog->officer?->name
                                        ?? '-'
                                    }}

                                </div>

                            </div>


                            @if ($isExit)

                                <span
                                    class="wg-activity-button"
                                    style="
                                        background:#fffbeb;
                                        border-color:#fde68a;
                                        color:#d97706;
                                    "
                                >

                                    <i class="bi bi-box-arrow-right"></i>

                                    Keluar

                                </span>

                            @else

                                <span
                                    class="wg-activity-button"
                                >

                                    <i class="bi bi-box-arrow-in-right"></i>

                                    Masuk

                                </span>

                            @endif

                        </div>

                    @endforeach

                @else

                    <div class="wg-empty">

                        <i class="bi bi-clock-history"></i>

                        Belum ada aktivitas kendaraan.

                    </div>

                @endif

            </div>

        </div>


        {{-- =================================================
             KENDARAAN DI AREA
        ================================================== --}}

        <div class="wg-panel">

            <div class="wg-panel-header">

                <div>

                    <div
                        style="
                            display:flex;
                            align-items:center;
                            gap:10px;
                            flex-wrap:wrap;
                        "
                    >

                        <h3 class="wg-panel-title">
                            Kendaraan Sedang Berada di Area
                        </h3>


                        <span
                            style="
                                display:inline-flex;
                                align-items:center;
                                gap:6px;
                                padding:6px 10px;
                                border-radius:20px;
                                background:#ecfdf5;
                                border:1px solid #a7f3d0;
                                color:#15803d;
                                font-size:10px;
                                font-weight:700;
                            "
                        >

                            <span
                                style="
                                    width:6px;
                                    height:6px;
                                    border-radius:50%;
                                    background:#22c55e;
                                "
                            ></span>

                            {{ $totalInside ?? 0 }} Di Dalam

                        </span>

                    </div>


                    <p class="wg-panel-description">

                        Daftar kendaraan pengunjung dan operasional
                        yang belum dicatat keluar melalui Gate Utama.

                    </p>

                </div>


                <a
                    href="{{ route('monitoring.index') }}"
                    class="wg-panel-link"
                >

                    Semua Kendaraan di Area

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>


            <div class="wg-area-toolbar">

                <div class="wg-search-box">

                    <i class="bi bi-search"></i>

                    Cari plat nomor, nama pengemudi, atau jenis...

                </div>


                <div class="wg-filter-list">

                    <button
                        type="button"
                        class="wg-filter active"
                    >
                        Semua
                    </button>

                    <button
                        type="button"
                        class="wg-filter"
                    >
                        Mobil
                    </button>

                    <button
                        type="button"
                        class="wg-filter"
                    >
                        Motor
                    </button>

                    <button
                        type="button"
                        class="wg-filter"
                    >
                        Bus
                    </button>

                    <button
                        type="button"
                        class="wg-filter"
                    >
                        Minibus
                    </button>

                    <button
                        type="button"
                        class="wg-filter"
                    >
                        Truk
                    </button>

                </div>

            </div>


            <div class="wg-table-wrapper">

                @if (
                    isset($insideVehicles)
                    && $insideVehicles->count()
                )

                    <table class="wg-table">

                        <thead>

                            <tr>

                                <th>Plat</th>
                                <th>Jenis</th>
                                <th>Pengemudi</th>
                                <th>Tujuan</th>
                                <th>Masuk</th>
                                <th>Status</th>
                                <th>Aksi</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach (
                                $insideVehicles->take(7)
                                as $gateLog
                            )

                                @php

                                    // Data kendaraan tersimpan langsung pada tabel gate_logs.
                                    $plate = $gateLog->plate_number ?: '-';
                                    $driver = $gateLog->driver_name ?: '-';
                                    $type = $gateLog->vehicle_type ?: 'Lainnya';

                                @endphp


                                <tr>

                                    <td>

                                        <span class="wg-plate">

                                            {{ $plate }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $type }}

                                    </td>


                                    <td>

                                        <span class="wg-driver">

                                            {{ $driver }}

                                        </span>

                                    </td>


                                    <td>

                                        <span class="wg-purpose">

                                            {{
                                                $gateLog->purpose
                                                ?? 'Area Wisata'
                                            }}

                                        </span>

                                    </td>


                                    <td>

                                        {{
                                            $gateLog->gate_in
                                                ? $gateLog->gate_in->format('H:i')
                                                : '--:--'
                                        }}

                                    </td>


                                    <td>

                                        <span class="wg-status">

                                            <span
                                                class="wg-status-dot"
                                            ></span>

                                            {{ $gateLog->status }}

                                        </span>

                                    </td>


                                    <td>

                                        <a
                                            href="{{ route('monitoring.index') }}"
                                            class="wg-exit-button"
                                        >

                                            <i class="bi bi-box-arrow-right"></i>

                                            Catat Keluar

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                @else

                    <div class="wg-empty">

                        <i class="bi bi-car-front"></i>

                        Belum ada kendaraan yang berada di area.

                    </div>

                @endif

            </div>

        </div>

    </section>

</div>

@endsection