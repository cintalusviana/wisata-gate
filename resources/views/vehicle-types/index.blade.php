@extends('layouts.app')

@section('title', 'Jenis Kendaraan')

@section('content')
@php
    use App\Models\GateLog;

    $vehicleTypes = $vehicleTypes ?? collect();
    $totalTypes = $vehicleTypes->count();
    $totalInside = GateLog::where('status', 'Di Dalam')->count();

    $activeTypes = $vehicleTypes->filter(function ($type) {
        return GateLog::where('status', 'Di Dalam')
            ->where('vehicle_type', $type->name)
            ->exists();
    })->count();
@endphp

<style>
    .vt-page {
        --primary: #ea580c;
        --primary-hover: #c2410c;
        --text: #1f2937;
        --muted: #6b7280;
        --border: #e5e7eb;
        --surface: #ffffff;
        font-family: 'Inter', sans-serif;
        color: var(--text);
        width: 100%;
    }

    .vt-page * { box-sizing: border-box; }

    .vt-page .vt-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 23px;
    }

    .vt-page .vt-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-bottom: 9px;
        color: var(--muted);
        font-size: 12px;
    }

    .vt-page .vt-breadcrumb .current {
        color: var(--primary);
        font-weight: 600;
    }

    .vt-page .vt-title {
        margin: 0;
        font-size: 26px;
        line-height: 1.4;
        font-weight: 750;
        letter-spacing: -.6px;
        color: var(--text);
    }

    .vt-page .vt-description {
        margin: 6px 0 0;
        font-size: 13px;
        color: var(--muted);
        line-height: 1.7;
    }

    .vt-page .vt-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 0 15px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .vt-page .vt-btn-primary {
        color: #fff;
        border-color: var(--primary);
        background: var(--primary);
    }

    .vt-page .vt-btn-primary:hover {
        color: #fff;
        border-color: var(--primary-hover);
        background: var(--primary-hover);
    }

    .vt-page .vt-btn-secondary {
        color: #4b5563;
        background: #fff;
    }

    .vt-page .vt-btn-secondary:hover {
        background: #f3f4f6;
    }

    .vt-page .vt-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 23px;
    }

    .vt-page .vt-stat {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
        padding: 17px;
        border: 1px solid var(--border);
        border-radius: 11px;
        background: var(--surface);
        box-shadow: 0 2px 5px rgba(15, 23, 42, .025);
    }

    .vt-page .vt-stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        border-radius: 10px;
        font-size: 19px;
    }

    .vt-page .orange { background: #fff1e8; color: #ea580c; }
    .vt-page .blue { background: #eff6ff; color: #2563eb; }
    .vt-page .green { background: #ecfdf5; color: #059669; }

    .vt-page .vt-stat-label {
        margin: 0 0 5px;
        color: var(--muted);
        font-size: 12px;
    }

    .vt-page .vt-stat-number {
        margin: 0;
        color: var(--text);
        font-size: 24px;
        font-weight: 750;
        line-height: 1.2;
    }

    .vt-page .vt-stat-note {
        display: block;
        margin-top: 5px;
        color: var(--muted);
        font-size: 11px;
    }

    .vt-page .vt-panel {
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface);
        box-shadow: 0 2px 6px rgba(15, 23, 42, .025);
    }

    .vt-page .vt-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 19px 21px;
        border-bottom: 1px solid var(--border);
    }

    .vt-page .vt-panel-title {
        margin: 0;
        color: var(--text);
        font-size: 15px;
        font-weight: 700;
    }

    .vt-page .vt-panel-subtitle {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .vt-page .vt-count {
        padding: 6px 9px;
        border-radius: 7px;
        background: #fff1e8;
        color: #c2410c;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* FORM TAMBAH DAN EDIT */
    .vt-page .vt-form-panel {
        display: none;
        overflow: hidden;
        margin-bottom: 23px;
        border: 1px solid #fed7aa;
        border-radius: 12px;
        background: var(--surface);
        box-shadow: 0 4px 15px rgba(15, 23, 42, .04);
    }

    .vt-page .vt-form-panel.show {
        display: block;
        animation: vtFormAppear .22s ease-out;
    }

    @keyframes vtFormAppear {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .vt-page .vt-form-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 21px;
        border-bottom: 1px solid var(--border);
        background: #fffaf5;
    }

    .vt-page .vt-form-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        flex-shrink: 0;
        border-radius: 10px;
        background: #ffedd5;
        color: var(--primary);
        font-size: 19px;
    }

    .vt-page .vt-form-title {
        margin: 0;
        color: #9a3412;
        font-size: 16px;
        font-weight: 750;
    }

    .vt-page .vt-form-subtitle {
        margin: 4px 0 0;
        color: #9a3412;
        font-size: 12px;
        line-height: 1.6;
    }

    .vt-page .vt-form-body {
        padding: 22px 21px;
    }

    .vt-page .vt-section-title {
        margin: 0 0 6px;
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
    }

    .vt-page .vt-section-description {
        margin: 0 0 20px;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .vt-page .vt-form-group {
        max-width: 650px;
        margin-bottom: 5px;
    }

    .vt-page .vt-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text);
        font-size: 12px;
        font-weight: 650;
    }

    .vt-page .vt-required { color: #dc2626; }

    .vt-page .vt-input {
        display: block;
        width: 100%;
        min-height: 43px;
        padding: 10px 13px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        background: var(--surface);
        color: var(--text);
        font-family: inherit;
        font-size: 13px;
        transition: .2s ease;
    }

    .vt-page .vt-input::placeholder { color: #9ca3af; }

    .vt-page .vt-input:focus {
        border-color: #fb923c;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, .1);
    }

    .vt-page .vt-field-hint {
        display: block;
        margin-top: 7px;
        color: var(--muted);
        font-size: 11px;
        line-height: 1.7;
    }

    .vt-page .vt-form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
        padding: 15px 21px;
        border-top: 1px solid var(--border);
        background: #fafafa;
    }

    .vt-page .vt-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 21px;
        border-bottom: 1px solid var(--border);
    }

    .vt-page .vt-search {
        position: relative;
        width: 100%;
        max-width: 330px;
    }

    .vt-page .vt-search i {
        position: absolute;
        top: 50%;
        left: 13px;
        color: #9ca3af;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .vt-page .vt-search input {
        width: 100%;
        height: 39px;
        padding: 0 12px 0 36px;
        border: 1px solid var(--border);
        border-radius: 8px;
        outline: none;
        background: var(--surface);
        color: var(--text);
        font-family: inherit;
        font-size: 12px;
    }

    .vt-page .vt-search input:focus {
        border-color: #fb923c;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, .08);
    }

    .vt-page .vt-result {
        color: var(--muted);
        font-size: 12px;
    }

    .vt-page .vt-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
        padding: 21px;
    }

    .vt-page .vt-card {
        min-width: 0;
        padding: 17px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--surface);
        transition: border-color .2s, box-shadow .2s, transform .2s;
    }

    .vt-page .vt-card:hover {
        border-color: #fed7aa;
        box-shadow: 0 5px 15px rgba(15, 23, 42, .04);
        transform: translateY(-2px);
    }

    .vt-page .vt-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .vt-page .vt-type-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        border-radius: 10px;
        background: #fff1e8;
        color: var(--primary);
        font-size: 20px;
    }

    .vt-page .vt-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 8px;
        border: 1px solid #d1fae5;
        border-radius: 6px;
        background: #ecfdf5;
        color: #047857;
        font-size: 10px;
        font-weight: 650;
    }

    .vt-page .vt-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #10b981;
    }

    .vt-page .vt-type-name {
        margin: 16px 0 5px;
        overflow-wrap: anywhere;
        color: var(--text);
        font-size: 16px;
        font-weight: 700;
    }

    .vt-page .vt-type-description {
        min-height: 36px;
        margin: 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .vt-page .vt-divider {
        height: 1px;
        margin: 15px 0 12px;
        background: var(--border);
    }

    .vt-page .vt-metric {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .vt-page .vt-metric-label {
        color: var(--muted);
        font-size: 11px;
    }

    .vt-page .vt-metric-value {
        color: var(--text);
        font-size: 15px;
        font-weight: 750;
    }

    .vt-page .vt-actions {
        display: flex;
        gap: 8px;
        margin-top: 16px;
    }

    .vt-page .vt-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 34px;
        padding: 0 11px;
        border: 1px solid var(--border);
        border-radius: 7px;
        background: var(--surface);
        color: #4b5563;
        font-family: inherit;
        font-size: 11px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .vt-page .vt-action-edit { flex: 1; }

    .vt-page .vt-action-edit:hover {
        border-color: #fdba74;
        background: #fff7ed;
        color: #c2410c;
    }

    .vt-page .vt-action-delete {
        width: 35px;
        padding: 0;
        color: #dc2626;
    }

    .vt-page .vt-action-delete:hover {
        border-color: #fecaca;
        background: #fef2f2;
    }

    .vt-page .vt-empty {
        grid-column: 1 / -1;
        padding: 40px 20px;
        text-align: center;
    }

    .vt-page .vt-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        margin: 0 auto 14px;
        border-radius: 13px;
        background: #fff1e8;
        color: var(--primary);
        font-size: 23px;
    }

    .vt-page .vt-empty h3 {
        margin: 0 0 7px;
        color: var(--text);
        font-size: 15px;
        font-weight: 700;
    }

    .vt-page .vt-empty p {
        margin: 0 auto 15px;
        max-width: 350px;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.7;
    }

    .vt-page .vt-hidden { display: none !important; }

    .vt-page .vt-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 17px;
        padding: 13px 15px;
        border: 1px solid #fed7aa;
        border-radius: 9px;
        background: #fffaf5;
        color: #9a3412;
        font-size: 11px;
        line-height: 1.7;
    }

    .vt-page .vt-note i { font-size: 15px; }

    .vt-page .vt-alert {
        margin-bottom: 16px;
        padding: 12px 15px;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        background: #f0fdf4;
        color: #166534;
        font-size: 12px;
    }

    .vt-page .vt-alert.error {
        border-color: #fecaca;
        background: #fef2f2;
        color: #991b1b;
    }

    .vt-page .vt-validation {
        margin-top: 7px;
        color: #dc2626;
        font-size: 11px;
    }

    @media (max-width: 1050px) {
        .vt-page .vt-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }

    @media (max-width: 700px) {
        .vt-page .vt-header {
            align-items: stretch;
            flex-direction: column;
        }

        .vt-page .vt-header .vt-btn { width: 100%; }

        .vt-page .vt-summary { grid-template-columns: 1fr; gap: 10px; }

        .vt-page .vt-stat { padding: 14px; }

        .vt-page .vt-panel-header { padding: 16px; }

        .vt-page .vt-toolbar {
            align-items: stretch;
            flex-direction: column;
            padding: 14px 16px;
        }

        .vt-page .vt-search { max-width: none; }

        .vt-page .vt-grid {
            grid-template-columns: 1fr;
            padding: 14px;
            gap: 12px;
        }

        .vt-page .vt-form-heading { padding: 16px; }

        .vt-page .vt-form-body { padding: 18px 16px; }

        .vt-page .vt-form-footer { padding: 13px 16px; }

        .vt-page .vt-form-footer .vt-btn { flex: 1; }
    }

    [data-bs-theme="dark"] .vt-page,
    .dark-mode .vt-page,
    body.dark .vt-page {
        --text: #f3f4f6;
        --muted: #9ca3af;
        --border: #374151;
        --surface: #1f2937;
    }

    [data-bs-theme="dark"] .vt-page .vt-form-heading,
    .dark-mode .vt-page .vt-form-heading,
    body.dark .vt-page .vt-form-heading {
        background: #292524;
    }

    [data-bs-theme="dark"] .vt-page .vt-form-title,
    [data-bs-theme="dark"] .vt-page .vt-form-subtitle,
    .dark-mode .vt-page .vt-form-title,
    .dark-mode .vt-page .vt-form-subtitle,
    body.dark .vt-page .vt-form-title,
    body.dark .vt-page .vt-form-subtitle {
        color: #fed7aa;
    }

    [data-bs-theme="dark"] .vt-page .vt-form-footer,
    .dark-mode .vt-page .vt-form-footer,
    body.dark .vt-page .vt-form-footer {
        background: #111827;
    }

    [data-bs-theme="dark"] .vt-page .vt-note,
    .dark-mode .vt-page .vt-note,
    body.dark .vt-page .vt-note {
        border-color: #7c2d12;
        background: #431407;
        color: #fed7aa;
    }
</style>

<div class="vt-page">

    {{-- HEADER --}}
    <div class="vt-header">
        <div>
            <div class="vt-breadcrumb">
                <span>Manajemen Data</span>
                <i class="bi bi-chevron-right"></i>
                <span class="current">Jenis Kendaraan</span>
            </div>

            <h1 class="vt-title">Jenis Kendaraan</h1>
            <p class="vt-description">
                Kelola kategori kendaraan yang tercatat masuk dan keluar area wisata.
            </p>
        </div>

        <button type="button" class="vt-btn vt-btn-primary" id="btnAddType">
            <i class="bi bi-plus-lg"></i>
            Tambah Jenis Kendaraan
        </button>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="vt-alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="vt-alert error">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="vt-alert error">
            <strong>Periksa kembali data yang dimasukkan.</strong>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- RINGKASAN --}}
    <div class="vt-summary">
        <div class="vt-stat">
            <div class="vt-stat-icon orange">
                <i class="bi bi-tags"></i>
            </div>
            <div>
                <p class="vt-stat-label">Total Jenis Kendaraan</p>
                <p class="vt-stat-number">{{ $totalTypes }}</p>
                <span class="vt-stat-note">Kategori terdaftar</span>
            </div>
        </div>

        <div class="vt-stat">
            <div class="vt-stat-icon blue">
                <i class="bi bi-car-front"></i>
            </div>
            <div>
                <p class="vt-stat-label">Kendaraan di Area</p>
                <p class="vt-stat-number">{{ number_format($totalInside, 0, ',', '.') }}</p>
                <span class="vt-stat-note">Kendaraan belum keluar</span>
            </div>
        </div>

        <div class="vt-stat">
            <div class="vt-stat-icon green">
                <i class="bi bi-check2-circle"></i>
            </div>
            <div>
                <p class="vt-stat-label">Jenis Digunakan</p>
                <p class="vt-stat-number">{{ $activeTypes }}</p>
                <span class="vt-stat-note">Jenis yang ada di area</span>
            </div>
        </div>
    </div>

    {{-- FORM INLINE: TAMBAH DAN EDIT --}}
    <section class="vt-form-panel" id="vehicleTypeFormPanel">
        <div class="vt-form-heading">
            <div class="vt-form-icon" id="vehicleTypeFormIcon">
                <i class="bi bi-plus-lg"></i>
            </div>
            <div>
                <h2 class="vt-form-title" id="vehicleTypeFormTitle">
                    Tambah Jenis Kendaraan
                </h2>
                <p class="vt-form-subtitle" id="vehicleTypeFormSubtitle">
                    Masukkan nama kategori kendaraan yang akan didaftarkan.
                </p>
            </div>
        </div>

        <form
            id="vehicleTypeForm"
            action="{{ route('vehicle-types.store') }}"
            method="POST"
        >
            @csrf

            <input
                type="hidden"
                name="_method"
                id="vehicleTypeMethod"
                value="PUT"
                disabled
            >

            <div class="vt-form-body">
                <h3 class="vt-section-title">Informasi Kategori</h3>
                <p class="vt-section-description">
                    Lengkapi nama jenis kendaraan dengan benar sebelum menyimpan data.
                </p>

                <div class="vt-form-group">
                    <label for="vehicleTypeName" class="vt-label">
                        Nama Jenis Kendaraan <span class="vt-required">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        id="vehicleTypeName"
                        class="vt-input"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Mobil, Motor, Bus"
                        maxlength="100"
                        required
                    >

                    <span class="vt-field-hint">
                        Gunakan nama kategori yang jelas dan konsisten dengan data monitoring.
                    </span>

                    @error('name')
                        <div class="vt-validation">{{ $message }}</div>
                    @enderror
                </div>

                <div class="vt-note">
                    <i class="bi bi-info-circle"></i>
                    <span>
                        Pastikan nama kategori sesuai dengan jenis kendaraan yang dicatat
                        petugas di Gate Utama.
                    </span>
                </div>
            </div>

            <div class="vt-form-footer">
                <button type="button" class="vt-btn vt-btn-secondary" id="btnCancelType">
                    <i class="bi bi-x-lg"></i>
                    Batal
                </button>

                <button type="submit" class="vt-btn vt-btn-primary" id="btnSaveType">
                    <i class="bi bi-check-lg"></i>
                    Simpan Data
                </button>
            </div>
        </form>
    </section>

    {{-- DAFTAR JENIS KENDARAAN --}}
    <section class="vt-panel">
        <div class="vt-panel-header">
            <div>
                <h2 class="vt-panel-title">Daftar Jenis Kendaraan</h2>
                <p class="vt-panel-subtitle">
                    Kategori kendaraan untuk monitoring Gate Utama.
                </p>
            </div>

            <span class="vt-count">{{ $totalTypes }} jenis</span>
        </div>

        <div class="vt-toolbar">
            <div class="vt-search">
                <i class="bi bi-search"></i>
                <input
                    type="search"
                    id="vehicleTypeSearch"
                    placeholder="Cari jenis kendaraan..."
                    aria-label="Cari jenis kendaraan"
                >
            </div>

            <span class="vt-result" id="vehicleTypeResult">
                Menampilkan {{ $totalTypes }} jenis kendaraan
            </span>
        </div>

        <div class="vt-grid" id="vehicleTypeGrid">
            @forelse($vehicleTypes as $vehicleType)
                @php
                    $currentCount = GateLog::where('status', 'Di Dalam')
                        ->where('vehicle_type', $vehicleType->name)
                        ->count();

                    $typeName = strtolower($vehicleType->name ?? '');

                    $icon = match (true) {
                        str_contains($typeName, 'motor') => 'bi-bicycle',
                        str_contains($typeName, 'mobil') => 'bi-car-front-fill',
                        str_contains($typeName, 'bus') => 'bi-bus-front-fill',
                        str_contains($typeName, 'truk') => 'bi-truck',
                        str_contains($typeName, 'sepeda') => 'bi-bicycle',
                        default => 'bi-car-front',
                    };

                    $description = match (true) {
                        str_contains($typeName, 'motor') => 'Kendaraan roda dua yang memasuki area wisata.',
                        str_contains($typeName, 'mobil') => 'Kendaraan roda empat pengunjung wisata.',
                        str_contains($typeName, 'bus') => 'Kendaraan pengangkut rombongan pengunjung.',
                        str_contains($typeName, 'truk') => 'Kendaraan angkutan barang atau operasional.',
                        default => 'Kategori kendaraan dalam sistem monitoring wisata.',
                    };
                @endphp

                <article
                    class="vt-card"
                    data-name="{{ strtolower($vehicleType->name) }}"
                >
                    <div class="vt-card-top">
                        <div class="vt-type-icon">
                            <i class="bi {{ $icon }}"></i>
                        </div>

                        <span class="vt-status">
                            <span class="vt-dot"></span>
                            Terdaftar
                        </span>
                    </div>

                    <h3 class="vt-type-name">{{ $vehicleType->name }}</h3>
                    <p class="vt-type-description">{{ $description }}</p>

                    <div class="vt-divider"></div>

                    <div class="vt-metric">
                        <span class="vt-metric-label">
                            <i class="bi bi-activity"></i>
                            Kendaraan di area
                        </span>
                        <span class="vt-metric-value">
                            {{ number_format($currentCount, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="vt-actions">
                        <button
                            type="button"
                            class="vt-action vt-action-edit js-edit-type"
                            data-id="{{ $vehicleType->id }}"
                            data-name="{{ $vehicleType->name }}"
                            data-update-url="{{ route('vehicle-types.update', $vehicleType->id) }}"
                        >
                            <i class="bi bi-pencil-square"></i>
                            Edit
                        </button>

                        <form
                            action="{{ route('vehicle-types.destroy', $vehicleType->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus jenis kendaraan ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="vt-action vt-action-delete"
                                title="Hapus"
                                aria-label="Hapus {{ $vehicleType->name }}"
                            >
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="vt-empty" id="vehicleTypeEmpty">
                    <div class="vt-empty-icon">
                        <i class="bi bi-car-front"></i>
                    </div>
                    <h3>Belum Ada Jenis Kendaraan</h3>
                    <p>
                        Tambahkan kategori kendaraan agar data monitoring dapat dikelola.
                    </p>
                    <button type="button" class="vt-btn vt-btn-primary" id="btnAddTypeEmpty">
                        <i class="bi bi-plus-lg"></i>
                        Tambah Jenis Kendaraan
                    </button>
                </div>
            @endforelse

            <div class="vt-empty vt-hidden" id="vehicleTypeNoResults">
                <div class="vt-empty-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h3>Jenis Kendaraan Tidak Ditemukan</h3>
                <p>Coba gunakan kata kunci pencarian yang berbeda.</p>
            </div>
        </div>
    </section>

    <div class="vt-note">
        <i class="bi bi-info-circle"></i>
        <span>
            Jumlah kendaraan per kategori dihitung berdasarkan data monitoring
            yang berstatus Di Dalam.
        </span>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const panel = document.getElementById('vehicleTypeFormPanel');
    const form = document.getElementById('vehicleTypeForm');
    const methodInput = document.getElementById('vehicleTypeMethod');
    const nameInput = document.getElementById('vehicleTypeName');
    const title = document.getElementById('vehicleTypeFormTitle');
    const subtitle = document.getElementById('vehicleTypeFormSubtitle');
    const icon = document.getElementById('vehicleTypeFormIcon');
    const saveButton = document.getElementById('btnSaveType');

    const addButton = document.getElementById('btnAddType');
    const addEmptyButton = document.getElementById('btnAddTypeEmpty');
    const cancelButton = document.getElementById('btnCancelType');

    const searchInput = document.getElementById('vehicleTypeSearch');
    const resultText = document.getElementById('vehicleTypeResult');
    const noResults = document.getElementById('vehicleTypeNoResults');
    const grid = document.getElementById('vehicleTypeGrid');

    const storeUrl = @json(route('vehicle-types.store'));
    const cards = grid ? Array.from(grid.querySelectorAll('.vt-card')) : [];

    function openForm(mode, data = {}) {
        panel.classList.add('show');

        if (mode === 'edit') {
            title.textContent = 'Edit Jenis Kendaraan';
            subtitle.textContent = 'Perbarui nama jenis kendaraan yang dipilih.';
            icon.innerHTML = '<i class="bi bi-pencil-square"></i>';

            form.action = data.updateUrl;
            methodInput.disabled = false;
            nameInput.value = data.name || '';

            saveButton.innerHTML =
                '<i class="bi bi-check-lg"></i> Simpan Perubahan';
        } else {
            title.textContent = 'Tambah Jenis Kendaraan';
            subtitle.textContent = 'Masukkan nama kategori kendaraan yang akan didaftarkan.';
            icon.innerHTML = '<i class="bi bi-plus-lg"></i>';

            form.action = storeUrl;
            methodInput.disabled = true;
            nameInput.value = '';

            saveButton.innerHTML =
                '<i class="bi bi-check-lg"></i> Simpan Data';
        }

        nameInput.classList.remove('is-invalid');

        panel.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

        window.setTimeout(function () {
            nameInput.focus({ preventScroll: true });
        }, 250);
    }

    function closeForm() {
        panel.classList.remove('show');
        form.reset();
        form.action = storeUrl;
        methodInput.disabled = true;
        nameInput.classList.remove('is-invalid');
    }

    if (addButton) {
        addButton.addEventListener('click', function () {
            openForm('create');
        });
    }

    if (addEmptyButton) {
        addEmptyButton.addEventListener('click', function () {
            openForm('create');
        });
    }

    if (cancelButton) {
        cancelButton.addEventListener('click', closeForm);
    }

    document.querySelectorAll('.js-edit-type').forEach(function (button) {
        button.addEventListener('click', function () {
            openForm('edit', {
                id: button.dataset.id,
                name: button.dataset.name,
                updateUrl: button.dataset.updateUrl
            });
        });
    });

    function filterTypes() {
        if (!searchInput || !resultText) return;

        const keyword = searchInput.value.trim().toLowerCase();
        let visible = 0;

        cards.forEach(function (card) {
            const name = card.dataset.name || '';
            const matches = name.includes(keyword);

            card.classList.toggle('vt-hidden', !matches);

            if (matches) visible++;
        });

        if (noResults) {
            noResults.classList.toggle(
                'vt-hidden',
                !(keyword !== '' && visible === 0 && cards.length > 0)
            );
        }

        resultText.textContent = keyword === ''
            ? 'Menampilkan ' + cards.length + ' jenis kendaraan'
            : 'Ditemukan ' + visible + ' dari ' + cards.length + ' jenis kendaraan';
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterTypes);
    }

    // Jika validasi Laravel gagal, tampilkan kembali form dengan data sebelumnya.
    @if($errors->any())
        openForm('create');
    @endif
});
</script>
@endsection
