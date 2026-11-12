@extends('layouts.petugas')

@section('title', 'Pencarian Kendaraan - WisataGate')

@section('content')

<style>
    .search-page {
        max-width: 1500px;
        margin: 0 auto;
    }

    /* HEADER */
    .search-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .search-heading-left {
        min-width: 0;
    }

    .search-title {
        display: flex;
        align-items: center;
        gap: 13px;
        margin: 0;
        color: var(--text);
        font-size: 30px;
        line-height: 1.2;
        font-weight: 800;
    }

    .search-title-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 13px;
        background: var(--primary-light);
        color: var(--primary);
        font-size: 22px;
    }

    .search-description {
        margin: 8px 0 0 61px;
        color: var(--text-secondary);
        font-size: 14px;
        line-height: 1.6;
    }

    .result-count {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 42px;
        padding: 9px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--card);
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .result-count strong {
        color: var(--primary);
        font-weight: 800;
    }

    /* SEARCH FORM */
    .search-card {
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid var(--border);
        border-radius: 18px;
        background: var(--card);
        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.04);
    }

    .search-form {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 210px auto;
        gap: 14px;
        align-items: end;
    }

    .search-field {
        min-width: 0;
    }

    .search-label {
        display: block;
        margin-bottom: 8px;
        color: var(--text);
        font-size: 13px;
        font-weight: 700;
    }

    .search-input-wrap {
        position: relative;
    }

    .search-input-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 17px;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        min-height: 48px;
        padding: 11px 15px 11px 45px;
        border: 1px solid var(--border);
        border-radius: 11px;
        outline: none;
        background: var(--input-bg);
        color: var(--text);
        font-size: 14px;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .search-input::placeholder {
        color: var(--text-muted);
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(234, 88, 12, .10);
    }

    .search-select {
        width: 100%;
        min-height: 48px;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 11px;
        outline: none;
        background: var(--input-bg);
        color: var(--text);
        font-size: 14px;
        cursor: pointer;
    }

    .search-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(234, 88, 12, .10);
    }

    .search-actions {
        display: flex;
        gap: 9px;
    }

    .btn-search,
    .btn-reset {
        min-height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 11px;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
        text-decoration: none;
        transition: background .2s ease, transform .2s ease;
    }

    .btn-search {
        border: 0;
        background: var(--primary);
        color: white;
    }

    .btn-search:hover {
        background: var(--primary-hover);
        color: white;
        transform: translateY(-1px);
    }

    .btn-reset {
        padding-right: 17px;
        padding-left: 17px;
        border: 1px solid var(--border);
        background: var(--card);
        color: var(--text-secondary);
    }

    .btn-reset:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }

    /* INFORMATION */
    .search-help {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        margin-top: 15px;
        padding: 12px 14px;
        border-radius: 10px;
        background: var(--primary-light);
        color: var(--text-secondary);
        font-size: 12px;
        line-height: 1.5;
    }

    .search-help i {
        margin-top: 1px;
        flex-shrink: 0;
        color: var(--primary);
    }

    /* RESULT TABLE */
    .result-card {
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 18px;
        background: var(--card);
        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.04);
    }

    .result-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 19px 22px;
        border-bottom: 1px solid var(--border);
    }

    .result-header-title {
        margin: 0;
        color: var(--text);
        font-size: 16px;
        font-weight: 800;
    }

    .result-header-subtitle {
        margin: 4px 0 0;
        color: var(--text-secondary);
        font-size: 12px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .search-table {
        width: 100%;
        min-width: 900px;
        margin: 0;
        border-collapse: collapse;
    }

    .search-table thead th {
        padding: 14px 18px;
        border-bottom: 1px solid var(--border);
        background: var(--bg);
        color: var(--text-secondary);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .search-table tbody td {
        padding: 16px 18px;
        border-bottom: 1px solid var(--border);
        color: var(--text);
        font-size: 13px;
        vertical-align: middle;
    }

    .search-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .search-table tbody tr:hover {
        background: rgba(234, 88, 12, .025);
    }

    .number-cell {
        width: 55px;
        color: var(--text-muted) !important;
        font-weight: 700;
    }

    .vehicle-cell {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .vehicle-icon {
        width: 39px;
        height: 39px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 10px;
        background: var(--primary-light);
        color: var(--primary);
        font-size: 17px;
    }

    .vehicle-plate {
        color: var(--text);
        font-size: 13px;
        font-weight: 800;
    }

    .vehicle-type {
        margin-top: 3px;
        color: var(--text-secondary);
        font-size: 11px;
    }

    .driver-name {
        color: var(--text);
        font-weight: 700;
    }

    .purpose-text {
        max-width: 220px;
        color: var(--text-secondary);
        line-height: 1.45;
    }

    .time-text {
        white-space: nowrap;
    }

    .time-date {
        color: var(--text);
        font-size: 12px;
        font-weight: 700;
    }

    .time-hour {
        margin-top: 3px;
        color: var(--text-secondary);
        font-size: 11px;
    }

    /* STATUS */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 28px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .status-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-inside {
        background: rgba(16, 185, 129, .10);
        color: #059669;
    }

    .status-exit {
        background: rgba(107, 114, 128, .12);
        color: #6B7280;
    }

    .status-default {
        background: rgba(234, 88, 12, .10);
        color: var(--primary);
    }

    /* EMPTY RESULT */
    .empty-result {
        padding: 65px 25px;
        text-align: center;
    }

    .empty-icon {
        width: 68px;
        height: 68px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 17px;
        border-radius: 18px;
        background: var(--primary-light);
        color: var(--primary);
        font-size: 27px;
    }

    .empty-title {
        margin: 0;
        color: var(--text);
        font-size: 18px;
        font-weight: 800;
    }

    .empty-description {
        max-width: 450px;
        margin: 8px auto 0;
        color: var(--text-secondary);
        font-size: 13px;
        line-height: 1.6;
    }

    /* RESPONSIVE */
    @media (max-width: 1100px) {
        .search-form {
            grid-template-columns: 1fr 1fr;
        }

        .search-actions {
            grid-column: span 2;
        }

        .btn-search,
        .btn-reset {
            flex: 1;
        }
    }

    @media (max-width: 768px) {
        .search-heading {
            flex-direction: column;
            gap: 15px;
        }

        .search-title {
            font-size: 25px;
        }

        .search-description {
            margin-left: 61px;
        }

        .result-count {
            width: 100%;
            justify-content: center;
        }

        .search-card {
            padding: 18px;
        }

        .search-form {
            grid-template-columns: 1fr;
        }

        .search-actions {
            grid-column: auto;
        }
    }

    @media (max-width: 576px) {
        .search-title {
            align-items: flex-start;
            font-size: 22px;
        }

        .search-title-icon {
            width: 43px;
            height: 43px;
            font-size: 19px;
        }

        .search-description {
            margin-left: 56px;
            font-size: 12px;
        }

        .search-card {
            padding: 15px;
            border-radius: 15px;
        }

        .result-card {
            border-radius: 15px;
        }

        .result-header {
            padding: 16px;
        }

        .search-actions {
            flex-direction: column;
        }

        .btn-search,
        .btn-reset {
            width: 100%;
        }
    }
</style>

<div class="search-page">

    {{-- HEADER --}}
    <div class="search-heading">
        <div class="search-heading-left">
            <h1 class="search-title">
                <span class="search-title-icon">
                    <i class="bi bi-search"></i>
                </span>
                Pencarian Kendaraan
            </h1>

            <p class="search-description">
                Cari data kendaraan berdasarkan nomor plat,
                nama pengemudi, tujuan, atau catatan.
            </p>
        </div>

        <div class="result-count">
            <i class="bi bi-database"></i>
            <span>Hasil:</span>
            <strong>{{ $totalResults ?? 0 }}</strong>
            <span>data</span>
        </div>
    </div>

    {{-- FORM PENCARIAN --}}
    <div class="search-card">
        <form
            action="{{ route('petugas.pencarian') }}"
            method="GET"
            class="search-form"
        >
            <div class="search-field">
                <label for="keyword" class="search-label">
                    Kata Kunci
                </label>

                <div class="search-input-wrap">
                    <i class="bi bi-search search-input-icon"></i>

                    <input
                        type="text"
                        name="keyword"
                        id="keyword"
                        class="search-input"
                        value="{{ $keyword ?? '' }}"
                        placeholder="Contoh: S 1567 AB atau Citra"
                        autocomplete="off"
                    >
                </div>
            </div>

            <div class="search-field">
                <label for="status" class="search-label">
                    Status Kendaraan
                </label>

                <select name="status" id="status" class="search-select">
                    <option value="">Semua Status</option>

                    <option
                        value="Di Dalam"
                        {{ ($status ?? '') === 'Di Dalam' ? 'selected' : '' }}
                    >
                        Di Dalam
                    </option>

                    <option
                        value="Sudah Keluar"
                        {{ in_array(($status ?? ''), ['Sudah Keluar', 'Keluar'], true) ? 'selected' : '' }}
                    >
                        Sudah Keluar
                    </option>
                </select>
            </div>

            <div class="search-actions">
                <button type="submit" class="btn-search">
                    <i class="bi bi-search"></i>
                    Cari
                </button>

                <a
                    href="{{ route('petugas.pencarian') }}"
                    class="btn-reset"
                >
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>
            </div>
        </form>

        <div class="search-help">
            <i class="bi bi-info-circle-fill"></i>

            <span>
                Kamu bisa mencari berdasarkan nomor plat,
                jenis kendaraan, nama pengemudi, tujuan, atau
                catatan kendaraan. Gunakan filter status untuk
                mempersempit hasil.
            </span>
        </div>
    </div>

    {{-- HASIL PENCARIAN --}}
    <div class="result-card">

        <div class="result-header">
            <div>
                <h2 class="result-header-title">
                    Hasil Pencarian
                </h2>

                <p class="result-header-subtitle">
                    Data kendaraan yang tercatat di Gate Utama.
                </p>
            </div>

            @if(($keyword ?? '') !== '' || ($status ?? '') !== '')
                <span class="result-count">
                    <i class="bi bi-funnel"></i>
                    Filter aktif
                </span>
            @endif
        </div>

        @if(isset($gateLogs) && $gateLogs->count() > 0)

            <div class="table-wrapper">
                <table class="search-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kendaraan</th>
                            <th>Pengemudi</th>
                            <th>Tujuan</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($gateLogs as $index => $log)

                            @php
                                /*
                                 * Data utama dibaca langsung dari gate_logs.
                                 * Relasi vehicle hanya menjadi data cadangan.
                                 */
                                $plateNumber = $log->plate_number
                                    ?: ($log->vehicle?->plate_number ?? null);

                                $vehicleTypeName = $log->vehicle_type
                                    ?: ($log->vehicle?->vehicleType?->name ?? null);

                                $driverName = $log->driver_name
                                    ?: ($log->vehicle?->driver_name ?? null);

                                $vehicleTypeLower = strtolower(
                                    (string) $vehicleTypeName
                                );

                                $statusClass = match ($log->status) {
                                    'Di Dalam' => 'status-inside',
                                    'Sudah Keluar', 'Keluar' => 'status-exit',
                                    default => 'status-default',
                                };
                            @endphp

                            <tr>
                                {{-- NOMOR --}}
                                <td class="number-cell">
                                    {{ $index + 1 }}
                                </td>

                                {{-- KENDARAAN --}}
                                <td>
                                    <div class="vehicle-cell">
                                        <div class="vehicle-icon">
                                            @if(str_contains($vehicleTypeLower, 'motor'))
                                                <i class="bi bi-bicycle"></i>
                                            @elseif(str_contains($vehicleTypeLower, 'bus'))
                                                <i class="bi bi-bus-front"></i>
                                            @elseif(str_contains($vehicleTypeLower, 'truk'))
                                                <i class="bi bi-truck"></i>
                                            @else
                                                <i class="bi bi-car-front-fill"></i>
                                            @endif
                                        </div>

                                        <div>
                                            <div class="vehicle-plate">
                                                {{ $plateNumber ?: '-' }}
                                            </div>

                                            <div class="vehicle-type">
                                                {{ $vehicleTypeName ?: 'Jenis tidak tersedia' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- PENGEMUDI --}}
                                <td>
                                    <span class="driver-name">
                                        {{ $driverName ?: '-' }}
                                    </span>
                                </td>

                                {{-- TUJUAN --}}
                                <td>
                                    <div class="purpose-text">
                                        {{ $log->purpose ?: '-' }}
                                    </div>
                                </td>

                                {{-- WAKTU MASUK --}}
                                <td>
                                    @if($log->gate_in)
                                        <div class="time-text">
                                            <div class="time-date">
                                                {{ \Carbon\Carbon::parse($log->gate_in)->locale('id')->translatedFormat('d M Y') }}
                                            </div>

                                            <div class="time-hour">
                                                {{ \Carbon\Carbon::parse($log->gate_in)->format('H:i') }}
                                                WIB
                                            </div>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- WAKTU KELUAR --}}
                                <td>
                                    @if($log->gate_out)
                                        <div class="time-text">
                                            <div class="time-date">
                                                {{ \Carbon\Carbon::parse($log->gate_out)->locale('id')->translatedFormat('d M Y') }}
                                            </div>

                                            <div class="time-hour">
                                                {{ \Carbon\Carbon::parse($log->gate_out)->format('H:i') }}
                                                WIB
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-secondary">
                                            Masih di area
                                        </span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $log->status ?: '-' }}
                                    </span>
                                </td>
                            </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>

        @else

            {{-- DATA KOSONG --}}
            <div class="empty-result">
                <div class="empty-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3 class="empty-title">
                    Data tidak ditemukan
                </h3>

                <p class="empty-description">
                    @if(($keyword ?? '') !== '' || ($status ?? '') !== '')
                        Tidak ada kendaraan yang sesuai dengan
                        kata kunci atau filter yang kamu gunakan.
                        Coba gunakan kata kunci lain.
                    @else
                        Belum ada data kendaraan yang tercatat
                        pada sistem.
                    @endif
                </p>
            </div>

        @endif
    </div>
</div>

@endsection
