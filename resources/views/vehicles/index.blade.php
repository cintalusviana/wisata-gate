@extends('layouts.app')

@section('title', 'Data Kendaraan - WisataGate')

@section('content')
@php
    $vehicleCount = $vehicles->count();
@endphp

<div class="vehicle-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div class="page-heading">
            <div class="page-eyebrow">
                <i class="bi bi-car-front-fill"></i>
                VEHICLE MANAGEMENT
            </div>

            <h1 class="page-title">Data Kendaraan</h1>

            <p class="page-subtitle">
                Kelola data kendaraan yang tercatat pada monitoring
                kendaraan masuk dan keluar area wisata.
            </p>
        </div>

        <button type="button" class="btn-orange" id="btnAddVehicle">
            <i class="bi bi-plus-lg"></i>
            Tambah Kendaraan
        </button>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}

            <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Periksa kembali data yang dimasukkan.
            </strong>

            <ul class="mb-0 mt-2 ps-4">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM TAMBAH DAN EDIT --}}
    <section class="main-card" id="vehicleFormPanel" hidden>
        <div class="card-top">
            <div class="card-heading">
                <div class="card-icon orange-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>

                <div>
                    <h2 class="card-title" id="vehicleFormTitle">
                        Tambah Kendaraan
                    </h2>

                    <p class="card-description" id="vehicleFormDescription">
                        Masukkan informasi kendaraan yang akan didaftarkan.
                    </p>
                </div>
            </div>

            <button type="button" class="close-panel" id="btnCloseForm"
                    aria-label="Tutup form">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="card-body">
            <form id="vehicleForm"
                  action="{{ route('vehicles.store') }}"
                  method="POST">

                @csrf

                <input type="hidden" name="_method" id="vehicleMethod"
                       value="PUT" disabled>

                <div class="form-section-title">
                    Informasi Kendaraan
                </div>

                <div class="form-grid">
                    <div class="form-field">
                        <label for="plate_number" class="form-label">
                            Plat Nomor <span class="required">*</span>
                        </label>

                        <input type="text"
                               class="form-control"
                               id="plate_number"
                               name="plate_number"
                               placeholder="Contoh: S 1234 AB"
                               maxlength="20"
                               autocomplete="off"
                               required>
                    </div>

                    <div class="form-field">
                        <label for="vehicle_type_id" class="form-label">
                            Jenis Kendaraan <span class="required">*</span>
                        </label>

                        <select class="form-select"
                                id="vehicle_type_id"
                                name="vehicle_type_id"
                                required>
                            <option value="">Pilih jenis kendaraan</option>

                            @foreach($vehicleTypes as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-field form-field-full">
                        <label for="driver_name" class="form-label">
                            Nama Pengemudi <span class="required">*</span>
                        </label>

                        <input type="text"
                               class="form-control"
                               id="driver_name"
                               name="driver_name"
                               placeholder="Masukkan nama pengemudi"
                               maxlength="100"
                               autocomplete="off"
                               required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button"
                            class="btn-light-outline"
                            id="btnCancelForm">
                        Batal
                    </button>

                    <button type="submit"
                            class="btn-orange"
                            id="btnSubmitVehicle">
                        <i class="bi bi-check-lg"></i>
                        Simpan Kendaraan
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- DETAIL KENDARAAN --}}
    <section class="main-card" id="vehicleDetailPanel" hidden>
        <div class="card-top">
            <div class="card-heading">
                <div class="card-icon green-icon">
                    <i class="bi bi-info-circle"></i>
                </div>

                <div>
                    <h2 class="card-title">Detail Kendaraan</h2>
                    <p class="card-description">
                        Informasi dan riwayat aktivitas kendaraan di Gate Utama.
                    </p>
                </div>
            </div>

            <button type="button" class="close-panel" id="btnCloseDetail"
                    aria-label="Tutup detail">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-box">
                    <span>Plat Nomor</span>
                    <strong id="detailPlate">-</strong>
                </div>

                <div class="detail-box">
                    <span>Jenis Kendaraan</span>
                    <strong id="detailType">-</strong>
                </div>

                <div class="detail-box">
                    <span>Nama Pengemudi</span>
                    <strong id="detailDriver">-</strong>
                </div>

                <div class="detail-box">
                    <span>Status Terakhir</span>
                    <strong id="detailStatus">Belum ada aktivitas</strong>
                </div>

                <div class="detail-box">
                    <span>Waktu Masuk Terakhir</span>
                    <strong id="detailGateIn">-</strong>
                </div>

                <div class="detail-box">
                    <span>Waktu Keluar Terakhir</span>
                    <strong id="detailGateOut">-</strong>
                </div>
            </div>

            <div class="section-label">
                <i class="bi bi-clock-history"></i>
                Riwayat Masuk dan Keluar
            </div>

            <div class="table-responsive">
                <table class="vehicle-table history-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tujuan</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody id="vehicleHistoryBody">
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    Belum ada riwayat aktivitas.
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- RINGKASAN TOTAL KENDARAAN --}}
    <div class="summary-card">
        <div class="summary-content">
            <span class="summary-label">
                Total Kendaraan Terdaftar
            </span>

            <div class="summary-number">
                {{ $vehicleCount }}
            </div>

            <span class="summary-caption">
                Jumlah kendaraan yang tercatat dalam sistem.
            </span>
        </div>
    </div>

    {{-- DAFTAR KENDARAAN --}}
    <section class="main-card">
        <div class="card-top">
            <div class="card-heading">
                <div class="card-icon orange-icon">
                    <i class="bi bi-list-ul"></i>
                </div>

                <div>
                    <h2 class="card-title">Daftar Kendaraan</h2>
                    <p class="card-description">
                        Data kendaraan yang terdaftar pada sistem.
                    </p>
                </div>
            </div>

            <a href="{{ route('vehicles.export') }}"
               class="btn-light-outline">
                <i class="bi bi-download"></i>
                Export CSV
            </a>
        </div>

        <div class="card-body">
            <div class="table-toolbar">
                <div class="search-wrapper">
                    <i class="bi bi-search"></i>

                    <input type="search"
                           id="vehicleSearch"
                           class="search-control"
                           placeholder="Cari plat, jenis, atau pengemudi..."
                           autocomplete="off">
                </div>

                <div class="table-info">
                    Menampilkan
                    <strong id="visibleVehicleCount">{{ $vehicleCount }}</strong>
                    dari <strong>{{ $vehicleCount }}</strong> kendaraan
                </div>
            </div>

            <div class="table-responsive">
                <table class="vehicle-table" id="vehicleTable">
                    <thead>
                        <tr>
                            <th class="number-column">No.</th>
                            <th>Plat Nomor</th>
                            <th>Jenis Kendaraan</th>
                            <th>Pengemudi</th>
                            <th>Status Terakhir</th>
                            <th>Waktu Masuk Terakhir</th>
                            <th class="action-column">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="vehicleTableBody">
                        @forelse($vehicles as $vehicle)
                            @php
                                $history = $vehicle->gateLogs
                                    ->sortByDesc('gate_in')
                                    ->values();

                                $latestLog = $history->first();

                                $vehicleHistory = $history->map(function ($log) {
                                    return [
                                        'purpose' => $log->purpose ?? '-',
                                        'gate_in' => $log->gate_in
                                            ? \Illuminate\Support\Carbon::parse($log->gate_in)->format('d/m/Y H:i')
                                            : '-',
                                        'gate_out' => $log->gate_out
                                            ? \Illuminate\Support\Carbon::parse($log->gate_out)->format('d/m/Y H:i')
                                            : '-',
                                        'status' => $log->status ?? '-',
                                    ];
                                })->values();
                            @endphp

                            <tr class="vehicle-row"
                                data-search="{{ strtolower(($vehicle->plate_number ?? '') . ' ' . ($vehicle->vehicleType->name ?? '') . ' ' . ($vehicle->driver_name ?? '')) }}">

                                <td class="number-cell">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <span class="plate-number">
                                        {{ $vehicle->plate_number }}
                                    </span>
                                </td>

                                <td>
                                    <span class="vehicle-badge">
                                        {{ $vehicle->vehicleType->name ?? 'Tidak diketahui' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="driver-name">
                                        {{ $vehicle->driver_name }}
                                    </span>
                                </td>

                                <td>
                                    @if($latestLog && $latestLog->status === 'Di Dalam')
                                        <span class="status-inside">
                                            <span class="status-dot"></span>
                                            Di Dalam Area
                                        </span>
                                    @elseif($latestLog && $latestLog->status === 'Sudah Keluar')
                                        <span class="status-outside">
                                            <span class="status-dot"></span>
                                            Sudah Keluar
                                        </span>
                                    @else
                                        <span class="status-unknown">
                                            Belum Ada Aktivitas
                                        </span>
                                    @endif
                                </td>

                                <td class="date-cell">
                                    @if($latestLog && $latestLog->gate_in)
                                        {{ \Illuminate\Support\Carbon::parse($latestLog->gate_in)->format('d/m/Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <div class="actions">
                                        <button type="button"
                                                class="action-btn detail-btn js-vehicle-detail"
                                                title="Lihat detail"
                                                aria-label="Lihat detail kendaraan"
                                                data-plate="{{ $vehicle->plate_number }}"
                                                data-type="{{ $vehicle->vehicleType->name ?? 'Tidak diketahui' }}"
                                                data-driver="{{ $vehicle->driver_name }}"
                                                data-status="{{ $latestLog->status ?? 'Belum ada aktivitas' }}"
                                                data-gate-in="{{ $latestLog && $latestLog->gate_in ? \Illuminate\Support\Carbon::parse($latestLog->gate_in)->format('d/m/Y H:i') : '-' }}"
                                                data-gate-out="{{ $latestLog && $latestLog->gate_out ? \Illuminate\Support\Carbon::parse($latestLog->gate_out)->format('d/m/Y H:i') : '-' }}"
                                                data-history="{{ $vehicleHistory->toJson() }}">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <button type="button"
                                                class="action-btn edit-btn js-vehicle-edit"
                                                title="Edit kendaraan"
                                                aria-label="Edit kendaraan"
                                                data-id="{{ $vehicle->id }}"
                                                data-plate="{{ $vehicle->plate_number }}"
                                                data-type-id="{{ $vehicle->vehicle_type_id }}"
                                                data-driver="{{ $vehicle->driver_name }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form action="{{ route('vehicles.destroy', $vehicle->id) }}"
                                              method="POST"
                                              class="delete-form"
                                              onsubmit="return confirm('Yakin ingin menghapus kendaraan {{ $vehicle->plate_number }}?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="action-btn delete-btn"
                                                    title="Hapus kendaraan"
                                                    aria-label="Hapus kendaraan">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyVehicleRow">
                                <td colspan="7">
                                    <div class="empty-state">
                                        <strong>Belum ada data kendaraan</strong>
                                        <p>
                                            Tambahkan kendaraan untuk mulai mengelola data monitoring.
                                        </p>

                                        <button type="button"
                                                class="btn-orange empty-add-button"
                                                id="btnAddVehicleEmpty">
                                            <i class="bi bi-plus-lg"></i>
                                            Tambah Kendaraan
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                        <tr id="noSearchResults" hidden>
                            <td colspan="7">
                                <div class="empty-state">
                                    <strong>Data tidak ditemukan</strong>
                                    <p>
                                        Coba gunakan kata kunci pencarian yang berbeda.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span>
                    <i class="bi bi-shield-check"></i>
                    Monitoring kendaraan Gate Utama
                </span>

                <span>WisataGate</span>
            </div>
        </div>
    </section>

</div>

<style>
    .vehicle-page {
        --vg-orange: #ea580c;
        --vg-orange-dark: #c2410c;
        --vg-text: #1f2937;
        --vg-muted: #6b7280;
        --vg-border: #e5e7eb;

        width: 100%;
        max-width: 100%;
        padding: 2px 0 18px;
        color: var(--vg-text);
        font-size: 13px;
    }

    .vehicle-page *,
    .vehicle-page *::before,
    .vehicle-page *::after {
        box-sizing: border-box;
    }

    .vehicle-page [hidden] {
        display: none !important;
    }

    /* HEADER */
    .vehicle-page .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 15px;
    }

    .vehicle-page .page-eyebrow {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 5px;
        color: var(--vg-orange);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .vehicle-page .page-title {
        margin: 0;
        color: #1f2937;
        font-size: 23px;
        font-weight: 750;
        letter-spacing: -.5px;
        line-height: 1.35;
    }

    .vehicle-page .page-subtitle {
        max-width: 650px;
        margin: 5px 0 0;
        color: var(--vg-muted);
        font-size: 12px;
        line-height: 1.6;
    }

    /* TOMBOL */
    .vehicle-page .btn-orange,
    .vehicle-page .btn-light-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 10px 16px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.2;
        text-decoration: none;
        cursor: pointer;
        transition: background .18s ease, border-color .18s ease, color .18s ease;
    }

    .vehicle-page .btn-orange {
        border-color: var(--vg-orange);
        background: var(--vg-orange);
        color: #fff;
    }

    .vehicle-page .btn-orange:hover {
        border-color: var(--vg-orange-dark);
        background: var(--vg-orange-dark);
        color: #fff;
    }

    .vehicle-page .btn-light-outline {
        border-color: #e5e7eb;
        background: #fff;
        color: #374151;
    }

    .vehicle-page .btn-light-outline:hover {
        border-color: #fed7aa;
        background: #fff7ed;
        color: var(--vg-orange-dark);
    }

    .vehicle-page .close-panel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        background: #fff;
        color: #6b7280;
        cursor: pointer;
    }

    .vehicle-page .close-panel:hover {
        background: #fff7ed;
        color: var(--vg-orange);
    }

    /* CARD UTAMA */
    .vehicle-page .main-card {
        margin-bottom: 15px;
        overflow: hidden;
        border: 1px solid var(--vg-border);
        border-radius: 11px;
        background: #fff;
        box-shadow: 0 2px 7px rgba(31, 41, 55, .03);
    }

    .vehicle-page .card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        padding: 13px 16px;
        border-bottom: 1px solid #f0f1f3;
    }

    .vehicle-page .card-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .vehicle-page .card-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        flex-shrink: 0;
        border-radius: 8px;
        font-size: 16px;
    }

    .vehicle-page .orange-icon {
        background: #fff7ed;
        color: var(--vg-orange);
    }

    .vehicle-page .green-icon {
        background: #ecfdf5;
        color: #059669;
    }

    .vehicle-page .card-title {
        margin: 0;
        color: #1f2937;
        font-size: 14px;
        font-weight: 750;
        line-height: 1.4;
    }

    .vehicle-page .card-description {
        margin: 3px 0 0;
        color: var(--vg-muted);
        font-size: 11px;
        line-height: 1.5;
    }

    .vehicle-page .card-body {
        padding: 15px 16px;
    }

    /* ALERT */
    .vehicle-page .alert {
        margin-bottom: 12px;
        padding: 10px 13px;
        border-radius: 8px;
        font-size: 12px;
    }

    /* FORM */
    .vehicle-page .form-section-title {
        margin-bottom: 13px;
        color: #374151;
        font-size: 12px;
        font-weight: 750;
    }

    .vehicle-page .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 13px 16px;
    }

    .vehicle-page .form-field {
        min-width: 0;
    }

    .vehicle-page .form-field-full {
        grid-column: 1 / -1;
    }

    .vehicle-page .form-label {
        display: block;
        margin-bottom: 5px;
        color: #374151;
        font-size: 12px;
        font-weight: 650;
    }

    .vehicle-page .required {
        color: #dc2626;
    }

    .vehicle-page .form-control,
    .vehicle-page .form-select {
        width: 100%;
        min-height: 40px;
        padding: 9px 11px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #1f2937;
        font-family: inherit;
        font-size: 12px;
        box-shadow: none;
    }

    .vehicle-page .form-control::placeholder {
        color: #9ca3af;
    }

    .vehicle-page .form-control:focus,
    .vehicle-page .form-select:focus {
        border-color: var(--vg-orange);
        outline: none;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, .1);
    }

    .vehicle-page .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid #f0f1f3;
    }

    /* CARD TOTAL KENDARAAN - MINIMALIS */
    .vehicle-page .summary-card {
        display: flex;
        align-items: center;
        min-height: 0;
        margin-bottom: 15px;
        padding: 17px 20px;
        border: 1px solid #e5e7eb;
        border-left: 4px solid var(--vg-orange);
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 6px rgba(31, 41, 55, .03);
    }

    .vehicle-page .summary-content {
        display: block;
    }

    .vehicle-page .summary-label {
        display: block;
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
    }

    .vehicle-page .summary-number {
        margin-top: 5px;
        color: #1f2937;
        font-size: 28px;
        font-weight: 750;
        line-height: 1.2;
    }

    .vehicle-page .summary-caption {
        display: block;
        margin-top: 5px;
        color: #9ca3af;
        font-size: 11px;
    }

    /* PENCARIAN */
    .vehicle-page .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }

    .vehicle-page .search-wrapper {
        position: relative;
        width: min(100%, 330px);
    }

    .vehicle-page .search-wrapper > i {
        position: absolute;
        top: 50%;
        left: 11px;
        color: #9ca3af;
        font-size: 13px;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .vehicle-page .search-control {
        width: 100%;
        min-height: 40px;
        padding: 9px 11px 9px 33px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        color: #374151;
        font-family: inherit;
        font-size: 12px;
        outline: none;
    }

    .vehicle-page .search-control:focus {
        border-color: var(--vg-orange);
        box-shadow: 0 0 0 3px rgba(234, 88, 12, .08);
    }

    .vehicle-page .table-info {
        color: #9ca3af;
        font-size: 11px;
    }

    .vehicle-page .table-info strong {
        color: #374151;
    }

    /* TABEL */
    .vehicle-page .table-responsive {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #edf0f2;
        border-radius: 8px;
    }

    .vehicle-page .vehicle-table {
        width: 100%;
        min-width: 800px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
        color: #374151;
        vertical-align: middle;
    }

    .vehicle-page .vehicle-table thead th {
        padding: 11px 12px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
        color: #6b7280;
        font-size: 10px;
        font-weight: 750;
        letter-spacing: .35px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .vehicle-page .vehicle-table tbody td {
        padding: 11px 12px;
        border-bottom: 1px solid #f0f1f3;
        background: #fff;
        font-size: 12px;
        line-height: 1.5;
    }

    .vehicle-page .vehicle-table tbody tr:last-child td {
        border-bottom: none;
    }

    .vehicle-page .vehicle-table tbody tr:hover td {
        background: #fffaf7;
    }

    .vehicle-page .number-column,
    .vehicle-page .number-cell {
        width: 45px;
        text-align: center;
    }

    .vehicle-page .number-cell {
        color: #9ca3af;
        font-size: 11px;
    }

    .vehicle-page .plate-number {
        color: #1f2937;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .vehicle-page .driver-name {
        color: #374151;
        font-weight: 550;
    }

    .vehicle-page .date-cell {
        color: #6b7280;
        font-size: 11px !important;
        white-space: nowrap;
    }

    .vehicle-page .vehicle-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 8px;
        border: 1px solid #ffedd5;
        border-radius: 6px;
        background: #fff7ed;
        color: #c2410c;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .vehicle-page .status-inside,
    .vehicle-page .status-outside,
    .vehicle-page .status-unknown {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .vehicle-page .status-inside {
        background: #ecfdf5;
        color: #047857;
    }

    .vehicle-page .status-outside {
        background: #f3f4f6;
        color: #4b5563;
    }

    .vehicle-page .status-unknown {
        background: #f9fafb;
        color: #9ca3af;
    }

    .vehicle-page .status-dot {
        width: 6px;
        height: 6px;
        flex-shrink: 0;
        border-radius: 50%;
        background: currentColor;
    }

    /* TOMBOL AKSI */
    .vehicle-page .action-column {
        width: 115px;
    }

    .vehicle-page .actions {
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .vehicle-page .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 31px;
        height: 31px;
        flex-shrink: 0;
        padding: 0;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        background: #fff;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
        transition: .18s ease;
    }

    .vehicle-page .detail-btn {
        border-color: #bbf7d0;
        background: #f0fdf4;
        color: #16a34a;
    }

    .vehicle-page .detail-btn:hover {
        background: #dcfce7;
        color: #15803d;
    }

    .vehicle-page .edit-btn {
        border-color: #bfdbfe;
        background: #eff6ff;
        color: #2563eb;
    }

    .vehicle-page .edit-btn:hover {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .vehicle-page .delete-btn {
        border-color: #fecaca;
        background: #fef2f2;
        color: #dc2626;
    }

    .vehicle-page .delete-btn:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    .vehicle-page .delete-form {
        display: inline-flex;
        margin: 0;
    }

    /* DETAIL KENDARAAN */
    .vehicle-page .detail-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 9px;
    }

    .vehicle-page .detail-box {
        min-width: 0;
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
    }

    .vehicle-page .detail-box span {
        display: block;
        margin-bottom: 5px;
        color: #9ca3af;
        font-size: 10px;
        font-weight: 600;
    }

    .vehicle-page .detail-box strong {
        display: block;
        overflow-wrap: anywhere;
        color: #1f2937;
        font-size: 12px;
        font-weight: 750;
        line-height: 1.5;
    }

    .vehicle-page .section-label {
        display: flex;
        align-items: center;
        gap: 7px;
        margin: 17px 0 10px;
        color: #374151;
        font-size: 12px;
        font-weight: 750;
    }

    .vehicle-page .section-label i {
        color: var(--vg-orange);
    }

    .vehicle-page .history-table {
        min-width: 600px;
    }

    /* DATA KOSONG */
    .vehicle-page .empty-state {
        padding: 25px 12px;
        text-align: center;
        color: #9ca3af;
    }

    .vehicle-page .empty-state strong {
        display: block;
        margin-bottom: 5px;
        color: #374151;
        font-size: 12px;
    }

    .vehicle-page .empty-state p {
        margin: 0;
        font-size: 11px;
        line-height: 1.6;
    }

    .vehicle-page .empty-add-button {
        margin-top: 13px;
    }

    /* FOOTER TABEL */
    .vehicle-page .table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
        color: #9ca3af;
        font-size: 10px;
    }

    .vehicle-page .table-footer i {
        margin-right: 4px;
        color: #059669;
    }

    /* TABLET */
    @media (max-width: 991px) {
        .vehicle-page .page-title {
            font-size: 21px;
        }

        .vehicle-page .card-top,
        .vehicle-page .card-body {
            padding: 13px;
        }

        .vehicle-page .detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    /* MOBILE */
    @media (max-width: 767px) {
        .vehicle-page {
            padding: 0 0 12px;
        }

        .vehicle-page .page-header {
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .vehicle-page .page-title {
            font-size: 20px;
        }

        .vehicle-page .page-subtitle {
            font-size: 11px;
        }

        .vehicle-page .page-header > .btn-orange {
            min-height: 40px;
            padding: 10px 14px;
            font-size: 13px;
        }

        .vehicle-page .card-top,
        .vehicle-page .card-body {
            padding: 12px;
        }

        .vehicle-page .form-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .vehicle-page .form-field-full {
            grid-column: auto;
        }

        .vehicle-page .form-actions {
            justify-content: stretch;
        }

        .vehicle-page .form-actions > button {
            flex: 1;
        }

        .vehicle-page .summary-card {
            padding: 15px 16px;
        }

        .vehicle-page .summary-number {
            font-size: 26px;
        }

        .vehicle-page .table-toolbar {
            align-items: stretch;
        }

        .vehicle-page .search-wrapper {
            width: 100%;
        }

        .vehicle-page .table-info {
            text-align: left;
        }

        .vehicle-page .detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }
    }

    @media (max-width: 420px) {
        .vehicle-page .page-header > .btn-orange {
            width: 100%;
        }

        .vehicle-page .detail-grid {
            grid-template-columns: 1fr;
        }

        .vehicle-page .card-top > .btn-light-outline {
            width: 100%;
        }

        .vehicle-page .table-footer {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('.vehicle-page');

    if (!root) return;

    const formPanel = root.querySelector('#vehicleFormPanel');
    const detailPanel = root.querySelector('#vehicleDetailPanel');

    const vehicleForm = root.querySelector('#vehicleForm');
    const vehicleMethod = root.querySelector('#vehicleMethod');

    const formTitle = root.querySelector('#vehicleFormTitle');
    const formDescription = root.querySelector('#vehicleFormDescription');
    const submitButton = root.querySelector('#btnSubmitVehicle');

    const plateInput = root.querySelector('#plate_number');
    const typeInput = root.querySelector('#vehicle_type_id');
    const driverInput = root.querySelector('#driver_name');

    const searchInput = root.querySelector('#vehicleSearch');
    const vehicleRows = Array.from(root.querySelectorAll('.vehicle-row'));

    const visibleCount = root.querySelector('#visibleVehicleCount');
    const noSearchResults = root.querySelector('#noSearchResults');

    const originalFormAction = @json(route('vehicles.store'));
    const updateUrlTemplate = @json(url('/vehicles/__VEHICLE_ID__'));

    function openForm() {
        detailPanel.hidden = true;
        formPanel.hidden = false;

        formPanel.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function closeForm() {
        formPanel.hidden = true;
        vehicleForm.reset();

        vehicleForm.action = originalFormAction;

        vehicleMethod.disabled = true;
        vehicleMethod.value = 'PUT';

        formTitle.textContent = 'Tambah Kendaraan';

        formDescription.textContent =
            'Masukkan informasi kendaraan yang akan didaftarkan.';

        submitButton.innerHTML =
            '<i class="bi bi-check-lg"></i> Simpan Kendaraan';
    }

    function addVehicle() {
        closeForm();

        vehicleForm.reset();
        vehicleForm.action = originalFormAction;

        vehicleMethod.disabled = true;
        vehicleMethod.value = 'PUT';

        formTitle.textContent = 'Tambah Kendaraan';

        formDescription.textContent =
            'Masukkan informasi kendaraan yang akan didaftarkan.';

        submitButton.innerHTML =
            '<i class="bi bi-check-lg"></i> Simpan Kendaraan';

        openForm();
        plateInput.focus();
    }

    root.querySelector('#btnAddVehicle')
        ?.addEventListener('click', addVehicle);

    root.querySelector('#btnAddVehicleEmpty')
        ?.addEventListener('click', addVehicle);

    root.querySelector('#btnCloseForm')
        ?.addEventListener('click', closeForm);

    root.querySelector('#btnCancelForm')
        ?.addEventListener('click', closeForm);

    /* EDIT */
    root.querySelectorAll('.js-vehicle-edit').forEach(function (button) {
        button.addEventListener('click', function () {
            const vehicleId = this.dataset.id;

            if (!vehicleId) return;

            closeForm();

            vehicleForm.reset();

            vehicleForm.action = updateUrlTemplate.replace(
                '__VEHICLE_ID__',
                encodeURIComponent(vehicleId)
            );

            vehicleMethod.disabled = false;
            vehicleMethod.value = 'PUT';

            plateInput.value = this.dataset.plate || '';
            typeInput.value = this.dataset.typeId || '';
            driverInput.value = this.dataset.driver || '';

            formTitle.textContent = 'Edit Kendaraan';

            formDescription.textContent =
                'Perbarui informasi kendaraan yang telah terdaftar.';

            submitButton.innerHTML =
                '<i class="bi bi-check-lg"></i> Simpan Perubahan';

            openForm();
            plateInput.focus();
        });
    });

    /* TUTUP DETAIL */
    root.querySelector('#btnCloseDetail')
        ?.addEventListener('click', function () {
            detailPanel.hidden = true;
        });

    function createCell(value) {
        const cell = document.createElement('td');
        cell.textContent = value || '-';
        return cell;
    }

    /* RIWAYAT */
    function renderHistory(history) {
        const body = root.querySelector('#vehicleHistoryBody');

        body.replaceChildren();

        if (!Array.isArray(history) || history.length === 0) {
            const row = document.createElement('tr');
            const cell = document.createElement('td');
            const empty = document.createElement('div');

            cell.colSpan = 5;
            empty.className = 'empty-state';
            empty.textContent = 'Belum ada riwayat aktivitas.';

            cell.appendChild(empty);
            row.appendChild(cell);
            body.appendChild(row);

            return;
        }

        history.forEach(function (item, index) {
            const row = document.createElement('tr');

            row.appendChild(createCell(index + 1));
            row.appendChild(createCell(item.purpose));
            row.appendChild(createCell(item.gate_in));
            row.appendChild(createCell(item.gate_out));

            const statusCell = document.createElement('td');
            const badge = document.createElement('span');

            if (item.status === 'Di Dalam') {
                badge.className = 'status-inside';
            } else if (item.status === 'Sudah Keluar') {
                badge.className = 'status-outside';
            } else {
                badge.className = 'status-unknown';
            }

            badge.textContent = item.status || '-';

            statusCell.appendChild(badge);
            row.appendChild(statusCell);
            body.appendChild(row);
        });
    }

    /* DETAIL */
    root.querySelectorAll('.js-vehicle-detail').forEach(function (button) {
        button.addEventListener('click', function () {
            formPanel.hidden = true;

            root.querySelector('#detailPlate').textContent =
                this.dataset.plate || '-';

            root.querySelector('#detailType').textContent =
                this.dataset.type || '-';

            root.querySelector('#detailDriver').textContent =
                this.dataset.driver || '-';

            root.querySelector('#detailStatus').textContent =
                this.dataset.status || 'Belum ada aktivitas';

            root.querySelector('#detailGateIn').textContent =
                this.dataset.gateIn || '-';

            root.querySelector('#detailGateOut').textContent =
                this.dataset.gateOut || '-';

            let history = [];

            try {
                history = JSON.parse(this.dataset.history || '[]');
            } catch (error) {
                history = [];
            }

            renderHistory(history);

            detailPanel.hidden = false;

            detailPanel.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    /* PENCARIAN */
    function filterVehicles() {
        const keyword = (searchInput.value || '')
            .trim()
            .toLowerCase();

        let count = 0;

        vehicleRows.forEach(function (row) {
            const text = (row.dataset.search || '').toLowerCase();
            const matches = text.includes(keyword);

            row.hidden = !matches;

            if (matches) count++;
        });

        visibleCount.textContent = count;

        if (noSearchResults) {
            noSearchResults.hidden =
                count !== 0 || vehicleRows.length === 0;
        }
    }

    searchInput?.addEventListener('input', filterVehicles);
});
</script>
@endsection
