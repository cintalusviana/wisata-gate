@extends('layouts.petugas')

@section('title', 'Riwayat Kendaraan - WisataGate')

@section('content')

<style>
    .history-page { width: 100%; }

    /* HEADER */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .page-title-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(249, 115, 22, .12);
        color: #f97316;
        font-size: 22px;
        flex-shrink: 0;
    }

    .page-title h1 {
        margin: 0;
        font-size: 25px;
        font-weight: 800;
        color: var(--text);
    }

    .page-title p {
        margin: 4px 0 0;
        font-size: 14px;
        color: var(--muted);
    }

    .history-count {
        padding: 9px 14px;
        border-radius: 10px;
        background: var(--card);
        border: 1px solid var(--border);
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .history-count i { color: #f97316; }

    /* FILTER */
    .filter-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 22px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
    }

    .filter-form {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 190px auto auto;
        gap: 12px;
        align-items: end;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
    }

    .input-wrapper { position: relative; }

    .input-wrapper > i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }

    .search-input,
    .date-input {
        width: 100%;
        height: 46px;
        border: 1px solid var(--border);
        border-radius: 11px;
        background: var(--card);
        color: var(--text);
        outline: none;
        font-size: 14px;
        transition: .2s ease;
    }

    .search-input { padding: 0 40px 0 42px; }
    .date-input { padding: 0 14px; }

    .search-input::placeholder {
        color: var(--muted);
        opacity: .8;
    }

    .search-input:focus,
    .date-input:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, .12);
    }

    .search-clear {
        position: absolute;
        right: 9px;
        top: 50%;
        transform: translateY(-50%);
        width: 28px;
        height: 28px;
        display: none;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 8px;
        background: var(--hover);
        color: var(--muted);
        cursor: pointer;
    }

    .search-clear.visible { display: flex; }

    .search-clear:hover { color: #ea580c; }

    /* BUTTON */
    .btn-filter,
    .btn-reset {
        height: 46px;
        border-radius: 11px;
        padding: 0 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s ease;
        white-space: nowrap;
        cursor: pointer;
    }

    .btn-filter {
        border: none;
        background: #f97316;
        color: #fff;
    }

    .btn-filter:hover {
        background: #ea580c;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(249, 115, 22, .20);
    }

    .btn-reset {
        background: var(--hover);
        color: var(--text);
        border: 1px solid var(--border);
    }

    .btn-reset:hover {
        background: var(--border);
        color: var(--text);
    }

    /* HISTORY CARD */
    .history-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .history-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 20px 22px;
        border-bottom: 1px solid var(--border);
    }

    .history-header h2 {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        color: var(--text);
    }

    .history-header span {
        display: block;
        margin-top: 4px;
        font-size: 13px;
        color: var(--muted);
    }

    /* TABLE */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
    }

    .history-table th {
        padding: 14px 18px;
        background: var(--hover);
        color: var(--muted);
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .03em;
        white-space: nowrap;
        border-bottom: 1px solid var(--border);
    }

    .history-table td {
        padding: 15px 18px;
        color: var(--text);
        font-size: 13px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    .history-table tbody tr:last-child td { border-bottom: none; }

    .history-table tbody tr { transition: .15s ease; }

    .history-table tbody tr:hover {
        background: rgba(249, 115, 22, .035);
    }

    .number-cell {
        width: 50px;
        color: var(--muted) !important;
        font-weight: 700;
    }

    /* VEHICLE */
    .vehicle-info {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 180px;
    }

    .vehicle-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: rgba(249, 115, 22, .10);
        color: #f97316;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 19px;
    }

    .motor-icon {
        width: 31px;
        height: 31px;
        display: block;
        color: #f97316;
        overflow: visible;
    }

    .motor-icon .wheel {
        fill: none;
        stroke: currentColor;
        stroke-width: 3.5;
    }

    .motor-icon .body { fill: currentColor; }

    .motor-icon .line {
        fill: none;
        stroke: currentColor;
        stroke-width: 3;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .motor-icon .thin-line {
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .vehicle-name {
        font-weight: 800;
        color: var(--text);
        line-height: 1.2;
    }

    .vehicle-type {
        margin-top: 4px;
        font-size: 11px;
        color: var(--muted);
    }

    .driver-name {
        font-weight: 600;
        color: var(--text);
    }

    .purpose-text {
        max-width: 220px;
        color: var(--text);
        line-height: 1.5;
    }

    .date-info {
        white-space: nowrap;
        color: var(--text);
    }

    .date-info small {
        display: block;
        margin-top: 3px;
        color: var(--muted);
        font-size: 11px;
    }

    /* STATUS */
    .exit-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 999px;
        background: rgba(249, 115, 22, .11);
        color: #ea580c;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* TOMBOL LIHAT SEMUA */
    .history-footer {
        display: flex;
        justify-content: center;
        padding: 16px;
        border-top: 1px solid var(--border);
    }

    /* EMPTY STATE */
    .empty-state {
        padding: 70px 25px;
        text-align: center;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        margin: 0 auto 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(249, 115, 22, .10);
        color: #f97316;
        font-size: 28px;
    }

    .empty-state h3 {
        margin: 0 0 7px;
        color: var(--text);
        font-size: 17px;
        font-weight: 800;
    }

    .empty-state p {
        margin: 0;
        color: var(--muted);
        font-size: 13px;
    }

    /* RESPONSIVE */
    @media (max-width: 1100px) {
        .filter-form { grid-template-columns: 1fr 180px; }

        .btn-filter,
        .btn-reset { width: 100%; }
    }

    @media (max-width: 767px) {
        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .page-title { width: 100%; }

        .page-title h1 { font-size: 21px; }
        .page-title p { font-size: 13px; }

        .history-count {
            align-self: stretch;
            text-align: center;
        }

        .filter-card {
            padding: 16px;
            border-radius: 15px;
        }

        .filter-form { grid-template-columns: 1fr; }

        .history-card { border-radius: 15px; }
        .history-header { padding: 16px; }
    }
</style>

<div class="history-page">

    {{-- HEADER --}}
    <div class="page-header">
        <div class="page-title">
            <div class="page-title-icon">
                <i class="bi bi-clock-history"></i>
            </div>

            <div>
                <h1>Riwayat Kendaraan</h1>
                <p>Riwayat kendaraan yang telah keluar dari area wisata.</p>
            </div>
        </div>

        <div class="history-count">
            <i class="bi bi-check2-circle me-1"></i>
            {{ $totalHistory }} riwayat
        </div>
    </div>

    {{-- PENCARIAN DAN FILTER --}}
    <div class="filter-card">
        <form
            action="{{ route('petugas.riwayat') }}"
            method="GET"
            class="filter-form"
            id="historySearchForm"
        >
            <div class="form-group">
                <label for="keyword">Cari Kendaraan</label>

                <div class="input-wrapper">
                    <i class="bi bi-search"></i>

                    <input
                        type="search"
                        id="keyword"
                        name="keyword"
                        class="search-input"
                        value="{{ $keyword }}"
                        placeholder="Plat nomor, pengemudi, atau tujuan"
                        autocomplete="off"
                    >

                    <button
                        type="button"
                        id="clearSearch"
                        class="search-clear"
                        aria-label="Hapus pencarian"
                        title="Hapus pencarian"
                    >
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label for="date">Tanggal Keluar</label>
                <input
                    type="date"
                    id="date"
                    name="date"
                    class="date-input"
                    value="{{ $date }}"
                >
            </div>

            <button type="submit" class="btn-filter">
                <i class="bi bi-search"></i>
                Cari
            </button>

            <a href="{{ route('petugas.riwayat') }}" class="btn-reset">
                <i class="bi bi-arrow-counterclockwise"></i>
                Reset
            </a>
        </form>
    </div>

    {{-- TABEL RIWAYAT --}}
    @php
        $showAll = request()->boolean('all');

        $displayGateLogs = $showAll
            ? $gateLogs
            : $gateLogs->take(10);
    @endphp

    <div class="history-card">
        <div class="history-header">
            <div>
                <h2>Riwayat Kendaraan Keluar</h2>
                <span>Daftar perjalanan kendaraan yang sudah selesai.</span>
            </div>
        </div>

        @if($displayGateLogs->count() > 0)
            <div class="table-responsive">
                <table class="history-table">
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
                        @foreach($displayGateLogs as $index => $gateLog)
                            @php
                                $plateNumber = trim((string) ($gateLog->plate_number ?? ''));
                                $driverName = trim((string) ($gateLog->driver_name ?? ''));
                                $vehicleTypeName = trim((string) ($gateLog->vehicle_type ?? ''));

                                $vehicleType = strtolower(
                                    preg_replace('/\s+/', ' ', $vehicleTypeName)
                                );

                                if (in_array($vehicleType, [
                                    'motor',
                                    'sepeda motor',
                                    'sepeda motor roda dua'
                                ])) {
                                    $vehicleCategory = 'motor';
                                    $vehicleIcon = null;
                                } elseif (in_array($vehicleType, [
                                    'mobil',
                                    'mobil pribadi',
                                    'mobil penumpang'
                                ])) {
                                    $vehicleCategory = 'mobil';
                                    $vehicleIcon = 'bi-car-front-fill';
                                } elseif (in_array($vehicleType, [
                                    'truk',
                                    'truck',
                                    'truk barang',
                                    'truck barang'
                                ])) {
                                    $vehicleCategory = 'truk';
                                    $vehicleIcon = 'bi-truck';
                                } elseif (in_array($vehicleType, [
                                    'bus',
                                    'bus wisata',
                                    'bus pariwisata'
                                ])) {
                                    $vehicleCategory = 'bus';
                                    $vehicleIcon = 'bi-bus-front-fill';
                                } elseif (in_array($vehicleType, [
                                    'minibus',
                                    'mini bus'
                                ])) {
                                    $vehicleCategory = 'minibus';
                                    $vehicleIcon = 'bi-bus-front';
                                } else {
                                    $vehicleCategory = 'lainnya';
                                    $vehicleIcon = 'bi-car-front';
                                }
                            @endphp

                            <tr>
                                <td class="number-cell">
                                    {{ $index + 1 }}
                                </td>

                                {{-- KENDARAAN --}}
                                <td>
                                    <div class="vehicle-info">
                                        <div
                                            class="vehicle-icon"
                                            title="{{ $vehicleTypeName !== '' ? $vehicleTypeName : 'Jenis kendaraan tidak diketahui' }}"
                                        >
                                            @if($vehicleCategory === 'motor')
                                                <svg
                                                    class="motor-icon"
                                                    viewBox="0 0 64 64"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    role="img"
                                                    aria-label="Motor"
                                                >
                                                    <circle class="wheel" cx="14" cy="47" r="8"/>
                                                    <circle class="wheel" cx="50" cy="47" r="8"/>

                                                    <path class="body"
                                                        d="M22 31 C25 27 30 25 36 27 L40 31 L36 35 L28 35 L24 39 L20 37 Z"/>

                                                    <path class="body"
                                                        d="M25 28 C27 24 32 23 36 25 L39 29 L35 31 L27 31 Z"/>

                                                    <path class="body"
                                                        d="M20 25 L30 25 L32 28 L21 28 Z"/>

                                                    <path class="line" d="M24 35 L34 44 L47 44"/>
                                                    <path class="line" d="M34 44 L14 47"/>
                                                    <path class="line" d="M40 31 L50 47"/>
                                                    <path class="line" d="M40 31 L44 22 L51 22"/>
                                                    <path class="thin-line" d="M47 22 L50 26"/>
                                                    <path class="thin-line" d="M44 22 L42 18"/>
                                                    <path class="thin-line" d="M49 22 L52 18"/>

                                                    <path class="body"
                                                        d="M48 25 C51 24 54 26 54 29 L51 30 Z"/>

                                                    <path class="line" d="M29 40 L23 45 L19 45"/>
                                                    <path class="thin-line" d="M31 38 L36 40"/>
                                                </svg>
                                            @else
                                                <i class="bi {{ $vehicleIcon }}"></i>
                                            @endif
                                        </div>

                                        <div>
                                            <div class="vehicle-name">
                                                {{ $plateNumber !== '' ? $plateNumber : '-' }}
                                            </div>

                                            <div class="vehicle-type">
                                                {{ $vehicleTypeName !== '' ? $vehicleTypeName : 'Jenis kendaraan tidak diketahui' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- PENGEMUDI --}}
                                <td>
                                    <span class="driver-name">
                                        {{ $driverName !== '' ? $driverName : '-' }}
                                    </span>
                                </td>

                                {{-- TUJUAN --}}
                                <td>
                                    <div class="purpose-text">
                                        {{ $gateLog->purpose ?: '-' }}
                                    </div>
                                </td>

                                {{-- WAKTU MASUK --}}
                                <td>
                                    @if($gateLog->gate_in)
                                        <div class="date-info">
                                            {{ \Carbon\Carbon::parse($gateLog->gate_in)->locale('id')->translatedFormat('d M Y') }}
                                            <small>
                                                {{ \Carbon\Carbon::parse($gateLog->gate_in)->format('H:i') }} WIB
                                            </small>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- WAKTU KELUAR --}}
                                <td>
                                    @if($gateLog->gate_out)
                                        <div class="date-info">
                                            {{ \Carbon\Carbon::parse($gateLog->gate_out)->locale('id')->translatedFormat('d M Y') }}
                                            <small>
                                                {{ \Carbon\Carbon::parse($gateLog->gate_out)->format('H:i') }} WIB
                                            </small>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="exit-badge">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Sudah Keluar
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- TOMBOL LIHAT SEMUA --}}
            @if($totalHistory > 10)
                <div class="history-footer">
                    @if($showAll)
                        <a
                            href="{{ route('petugas.riwayat', array_filter([
                                'keyword' => $keyword,
                                'date' => $date,
                            ])) }}"
                            class="btn-reset"
                        >
                            <i class="bi bi-chevron-up"></i>
                            Tampilkan 10 Data
                        </a>
                    @else
                        <a
                            href="{{ route('petugas.riwayat', array_filter([
                                'keyword' => $keyword,
                                'date' => $date,
                                'all' => 1,
                            ])) }}"
                            class="btn-filter"
                        >
                            <i class="bi bi-list-ul"></i>
                            Lihat Semua ({{ $totalHistory }})
                        </a>
                    @endif
                </div>
            @endif

        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-search"></i>
                </div>

                <h3>
                    @if($keyword !== '' || $date !== '')
                        Riwayat tidak ditemukan
                    @else
                        Belum ada riwayat
                    @endif
                </h3>

                <p>
                    @if($keyword !== '' || $date !== '')
                        Coba kata kunci lain atau ubah tanggal pencarian.
                    @else
                        Kendaraan yang sudah keluar akan muncul di sini.
                    @endif
                </p>
            </div>
        @endif
    </div>

</div>

{{-- PENCARIAN OTOMATIS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('keyword');
    const dateInput = document.getElementById('date');
    const searchForm = document.getElementById('historySearchForm');
    const clearButton = document.getElementById('clearSearch');

    if (!searchInput || !dateInput || !searchForm || !clearButton) {
        return;
    }

    let searchTimer = null;

    function updateClearButton() {
        clearButton.classList.toggle(
            'visible',
            searchInput.value.trim().length > 0
        );
    }

    function submitSearch() {
        searchForm.requestSubmit();
    }

    searchInput.addEventListener('input', function () {
        updateClearButton();
        clearTimeout(searchTimer);

        searchTimer = setTimeout(submitSearch, 500);
    });

    dateInput.addEventListener('change', function () {
        clearTimeout(searchTimer);
        submitSearch();
    });

    clearButton.addEventListener('click', function () {
        clearTimeout(searchTimer);
        searchInput.value = '';
        updateClearButton();
        submitSearch();
    });

    searchInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            clearTimeout(searchTimer);
        }
    });

    updateClearButton();
});
</script>

@endsection