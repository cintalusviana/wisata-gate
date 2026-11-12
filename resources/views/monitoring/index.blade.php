
@extends('layouts.app')

@section('title', 'Monitoring Kendaraan')

@section('content')
<style>
    .monitoring-page {
        width: 100%;
        color: var(--text);
    }

    .monitoring-header {
        margin-bottom: 24px;
    }

    .monitoring-header-top,
    .monitoring-table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .monitoring-breadcrumb {
        margin-bottom: 7px;
        color: var(--text-secondary);
        font-size: 11px;
        font-weight: 700;
    }

    .monitoring-title {
        margin: 0 0 7px;
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -.5px;
    }

    .monitoring-description {
        margin: 0;
        color: var(--text-secondary);
        font-size: 13px;
    }

    .monitoring-header-actions {
        display: flex;
        gap: 9px;
        flex-wrap: wrap;
    }

    .btn-monitoring {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        min-height: 40px;
        padding: 0 15px;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: var(--card);
        color: var(--text-secondary);
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-monitoring:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-monitoring-primary {
        border-color: var(--primary);
        background: var(--primary);
        color: #fff;
    }

    .btn-monitoring-primary:hover {
        border-color: var(--primary-dark);
        background: var(--primary-dark);
        color: #fff;
    }

    .monitoring-panel {
        margin-bottom: 22px;
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: var(--card);
        box-shadow: var(--shadow);
    }

    .entry-form-panel {
        display: none;
    }

    .entry-form-panel.show {
        display: block;
    }

    .panel-heading {
        padding: 17px 20px;
        border-bottom: 1px solid var(--border);
    }

    .panel-heading h3 {
        margin: 0 0 5px;
        font-size: 15px;
        font-weight: 800;
    }

    .panel-heading p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 11px;
    }

    .panel-body {
        padding: 20px;
    }

    .monitoring-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 17px;
    }

    .monitoring-form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 0;
    }

    .monitoring-form-group.full {
        grid-column: 1 / -1;
    }

    .monitoring-form-group label {
        font-size: 11px;
        font-weight: 800;
    }

    .monitoring-control {
        width: 100%;
        min-height: 41px;
        padding: 9px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        outline: none;
        background: var(--bg);
        color: var(--text);
        font-family: inherit;
        font-size: 12px;
    }

    .monitoring-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    textarea.monitoring-control {
        min-height: 85px;
        resize: vertical;
    }

    .monitoring-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        margin-top: 20px;
        padding-top: 17px;
        border-top: 1px solid var(--border);
    }

    .monitoring-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 18px;
        padding: 12px 14px;
        border-radius: 9px;
        font-size: 12px;
    }

    .monitoring-alert-success {
        border: 1px solid rgba(22,163,74,.2);
        background: rgba(22,163,74,.08);
        color: #16a34a;
    }

    .monitoring-alert-error {
        border: 1px solid rgba(220,38,38,.2);
        background: rgba(220,38,38,.07);
        color: #dc2626;
    }

    .monitoring-alert ul {
        margin: 5px 0 0;
        padding-left: 18px;
    }

    .monitoring-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 22px;
    }

    .monitoring-stat-card {
        padding: 19px 20px;
        border: 1px solid var(--border);
        border-radius: 13px;
        background: var(--card);
        box-shadow: var(--shadow);
    }

    .monitoring-stat-label {
        margin-bottom: 9px;
        color: var(--text-secondary);
        font-size: 11px;
        font-weight: 700;
    }

    .monitoring-stat-value {
        margin-bottom: 7px;
        font-size: 27px;
        font-weight: 800;
        line-height: 1;
    }

    .monitoring-stat-description {
        color: var(--text-secondary);
        font-size: 10px;
    }

    .monitoring-table-header {
        padding: 18px 20px;
        border-bottom: 1px solid var(--border);
    }

    .monitoring-table-title h3 {
        margin: 0 0 5px;
        font-size: 15px;
        font-weight: 800;
    }

    .monitoring-table-title p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 11px;
    }

    .monitoring-filter {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .monitoring-search {
        position: relative;
        width: 220px;
    }

    .monitoring-search i {
        position: absolute;
        top: 50%;
        left: 11px;
        transform: translateY(-50%);
        color: var(--text-secondary);
    }

    .monitoring-search input {
        padding-left: 33px;
    }

    .monitoring-filter .monitoring-control {
        min-height: 37px;
        padding-top: 7px;
        padding-bottom: 7px;
        font-size: 11px;
    }

    .monitoring-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .monitoring-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .monitoring-table th {
        padding: 13px 16px;
        background: var(--bg);
        border-bottom: 1px solid var(--border);
        color: var(--text-secondary);
        text-align: left;
        white-space: nowrap;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .monitoring-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
        font-size: 11px;
    }

    .monitoring-table tbody tr:hover {
        background: var(--bg);
    }

    .monitoring-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .monitoring-plate {
        display: inline-flex;
        padding: 5px 9px;
        border: 1px solid var(--border);
        border-radius: 6px;
        background: var(--bg);
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .monitoring-muted {
        color: var(--text-secondary);
    }

    .monitoring-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border: 1px solid rgba(22,163,74,.15);
        border-radius: 999px;
        background: rgba(22,163,74,.08);
        color: #16a34a;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .monitoring-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .monitoring-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border: 1px solid var(--border);
        border-radius: 7px;
        background: var(--card);
        color: var(--text-secondary);
        cursor: pointer;
    }

    .monitoring-action:hover {
        border-color: #16a34a;
        background: rgba(22,163,74,.07);
        color: #16a34a;
    }

    .monitoring-empty {
        padding: 42px 20px !important;
        text-align: center;
        color: var(--text-secondary);
    }

    .monitoring-empty i {
        display: block;
        margin-bottom: 9px;
        font-size: 28px;
    }

    .exit-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(15,23,42,.58);
        backdrop-filter: blur(4px);
    }

    .exit-modal-overlay.show {
        display: flex;
    }

    .exit-modal {
        width: min(420px, 100%);
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: var(--card);
        box-shadow: 0 25px 70px rgba(15,23,42,.2);
    }

    .exit-modal-header,
    .exit-modal-footer {
        padding: 17px 20px;
        border-bottom: 1px solid var(--border);
    }

    .exit-modal-header h3 {
        margin: 0 0 5px;
        font-size: 15px;
        font-weight: 800;
    }

    .exit-modal-header p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 11px;
    }

    .exit-modal-body {
        padding: 20px;
    }

    .exit-modal-info {
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: 9px;
        background: var(--bg);
    }

    .exit-modal-info-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 8px 0;
        font-size: 11px;
    }

    .exit-modal-info-row + .exit-modal-info-row {
        border-top: 1px solid var(--border);
    }

    .exit-modal-info-value {
        text-align: right;
        font-weight: 800;
    }

    .exit-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        border-top: 1px solid var(--border);
        border-bottom: 0;
    }

    @media (max-width: 900px) {
        .monitoring-header-top,
        .monitoring-table-header {
            align-items: stretch;
            flex-direction: column;
        }

        .monitoring-filter {
            width: 100%;
        }

        .monitoring-search {
            flex: 1;
            width: auto;
        }
    }

    @media (max-width: 650px) {
        .monitoring-title {
            font-size: 23px;
        }

        .monitoring-header-actions {
            display: grid;
            grid-template-columns: 1fr;
        }

        .monitoring-stats {
            grid-template-columns: 1fr;
        }

        .monitoring-form-grid {
            grid-template-columns: 1fr;
        }

        .monitoring-form-group.full {
            grid-column: auto;
        }

        .monitoring-filter {
            align-items: stretch;
            flex-direction: column;
        }

        .monitoring-search {
            width: 100%;
        }

        .panel-body {
            padding: 15px;
        }

        .monitoring-form-actions {
            flex-direction: column-reverse;
        }

        .monitoring-form-actions .btn-monitoring {
            width: 100%;
        }
    }
</style>

<div class="monitoring-page">

    <div class="monitoring-header">
        <div class="monitoring-header-top">
            <div>
                <div class="monitoring-breadcrumb">Monitoring / Kendaraan</div>
                <h1 class="monitoring-title">Monitoring Kendaraan</h1>
                <p class="monitoring-description">
                    Pantau kendaraan yang berada di dalam area wisata melalui Gate Utama.
                </p>
            </div>

            <div class="monitoring-header-actions">
                <a href="{{ route('monitoring.export') }}" class="btn-monitoring">
                    <i class="bi bi-download"></i> Export Data
                </a>

                <button type="button" id="btnShowEntryForm"
                        class="btn-monitoring btn-monitoring-primary">
                    <i class="bi bi-plus-lg"></i> Catat Kendaraan Masuk
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="monitoring-alert monitoring-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="monitoring-alert monitoring-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="monitoring-alert monitoring-alert-error">
            <div>
                <strong>Data belum dapat disimpan.</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div id="entryFormPanel"
         class="monitoring-panel entry-form-panel {{ $errors->any() ? 'show' : '' }}">

        <div class="panel-heading">
            <h3>Catat Kendaraan Masuk</h3>
            <p>Masukkan informasi kendaraan yang masuk melalui Gate Utama.</p>
        </div>

        <div class="panel-body">
            <form action="{{ route('gate-logs.store') }}" method="POST">
                @csrf

                <div class="monitoring-form-grid">
                    <div class="monitoring-form-group">
                        <label for="plate_number">Nomor Plat Kendaraan *</label>
                        <input type="text" id="plate_number" name="plate_number"
                               class="monitoring-control"
                               value="{{ old('plate_number') }}"
                               placeholder="Contoh: S 1234 AB"
                               maxlength="20" required>
                    </div>

                    <div class="monitoring-form-group">
                        <label for="vehicle_type">Jenis Kendaraan *</label>
                        <select id="vehicle_type" name="vehicle_type"
                                class="monitoring-control" required>
                            <option value="">Pilih jenis kendaraan</option>
                            @foreach(['Motor', 'Mobil', 'Bus', 'Minibus', 'Truk', 'Lainnya'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('vehicle_type') === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="monitoring-form-group">
                        <label for="driver_name">Nama Pengemudi *</label>
                        <input type="text" id="driver_name" name="driver_name"
                               class="monitoring-control"
                               value="{{ old('driver_name') }}"
                               placeholder="Masukkan nama pengemudi"
                               maxlength="100" required>
                    </div>

                    <div class="monitoring-form-group">
                        <label for="purpose">Tujuan *</label>
                        <input type="text" id="purpose" name="purpose"
                               class="monitoring-control"
                               value="{{ old('purpose', 'Area Wisata') }}"
                               placeholder="Contoh: Area Wisata"
                               maxlength="150" required>
                    </div>

                    <div class="monitoring-form-group full">
                        <label for="notes">Catatan</label>
                        <textarea id="notes" name="notes"
                                  class="monitoring-control"
                                  placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="monitoring-form-actions">
                    <button type="button" id="btnCancelEntry" class="btn-monitoring">
                        Batal
                    </button>
                    <button type="submit"
                            class="btn-monitoring btn-monitoring-primary">
                        <i class="bi bi-check-lg"></i> Simpan Kendaraan Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="monitoring-stats">
        <div class="monitoring-stat-card">
            <div class="monitoring-stat-label">Sedang di Dalam</div>
            <div class="monitoring-stat-value">{{ $totalInside }}</div>
            <div class="monitoring-stat-description">
                Kendaraan yang masih berada di area wisata
            </div>
        </div>

        <div class="monitoring-stat-card">
            <div class="monitoring-stat-label">Kendaraan Masuk</div>
            <div class="monitoring-stat-value">{{ $totalEntry }}</div>
            <div class="monitoring-stat-description">Total kendaraan masuk hari ini</div>
        </div>

        <div class="monitoring-stat-card">
            <div class="monitoring-stat-label">Kendaraan Keluar</div>
            <div class="monitoring-stat-value">{{ $totalExit }}</div>
            <div class="monitoring-stat-description">Total kendaraan keluar hari ini</div>
        </div>
    </div>

    <div class="monitoring-panel">
        <div class="monitoring-table-header">
            <div class="monitoring-table-title">
                <h3>Kendaraan Sedang Berada di Area Wisata</h3>
                <p>Daftar kendaraan yang masih berada di dalam area wisata.</p>
            </div>

            <div class="monitoring-filter">
                <div class="monitoring-search">
                    <i class="bi bi-search"></i>
                    <input type="text" id="monitoringSearch"
                           class="monitoring-control"
                           placeholder="Cari plat atau pengemudi..."
                           autocomplete="off">
                </div>

                <select id="monitoringTypeFilter" class="monitoring-control">
                    <option value="">Semua Jenis</option>
                    @foreach(['Motor', 'Mobil', 'Bus', 'Minibus', 'Truk', 'Lainnya'] as $type)
                        <option value="{{ strtolower($type) }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="monitoring-table-wrapper">
            <table class="monitoring-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Plat</th>
                        <th>Jenis Kendaraan</th>
                        <th>Pengemudi</th>
                        <th>Tujuan</th>
                        <th>Waktu Masuk</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="monitoringTableBody">
                    @forelse($vehicles as $index => $log)
                        @php
                            $typeName = $log->vehicle_type ?? '-';
                            $typeLower = strtolower($typeName);

                            $vehicleIcon = match (true) {
                                str_contains($typeLower, 'motor') => 'bi-bicycle',
                                str_contains($typeLower, 'bus') => 'bi-bus-front',
                                str_contains($typeLower, 'truk') => 'bi-truck',
                                default => 'bi-car-front',
                            };
                        @endphp

                        <tr class="monitoring-row"
                            data-plate="{{ strtolower($log->plate_number ?? '') }}"
                            data-driver="{{ strtolower($log->driver_name ?? '') }}"
                            data-type="{{ strtolower($typeName) }}">

                            <td>{{ $index + 1 }}</td>

                            <td>
                                <span class="monitoring-plate">
                                    {{ $log->plate_number ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <span>
                                    <i class="bi {{ $vehicleIcon }}"></i>
                                    {{ $typeName }}
                                </span>
                            </td>

                            <td>{{ $log->driver_name ?? '-' }}</td>
                            <td>{{ $log->purpose ?? '-' }}</td>

                            <td>
                                {{ $log->gate_in?->format('d M Y, H:i') ?? '-' }}
                            </td>

                            <td>{{ $log->officer->name ?? '-' }}</td>

                            <td>
                                <span class="monitoring-status">
                                    <span class="monitoring-status-dot"></span>
                                    Di Dalam
                                </span>
                            </td>

                            <td>
                                <button type="button"
                                        class="monitoring-action exit"
                                        title="Catat Kendaraan Keluar"
                                        aria-label="Catat Kendaraan Keluar"
                                        data-id="{{ $log->id }}"
                                        data-plate="{{ $log->plate_number ?? '-' }}"
                                        data-driver="{{ $log->driver_name ?? '-' }}">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="monitoringEmptyRow">
                            <td colspan="9" class="monitoring-empty">
                                <i class="bi bi-car-front"></i>
                                Tidak ada kendaraan yang sedang berada di area wisata.
                            </td>
                        </tr>
                    @endforelse

                    <tr id="monitoringSearchEmpty" style="display:none">
                        <td colspan="9" class="monitoring-empty">
                            <i class="bi bi-search"></i>
                            Data kendaraan tidak ditemukan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="exitModalOverlay" class="exit-modal-overlay">
    <div class="exit-modal">
        <div class="exit-modal-header">
            <h3>Catat Kendaraan Keluar</h3>
            <p>Pastikan kendaraan benar-benar keluar dari area wisata.</p>
        </div>

        <div class="exit-modal-body">
            <div class="exit-modal-info">
                <div class="exit-modal-info-row">
                    <span class="monitoring-muted">Nomor Plat</span>
                    <span id="exitModalPlate" class="exit-modal-info-value">-</span>
                </div>
                <div class="exit-modal-info-row">
                    <span class="monitoring-muted">Pengemudi</span>
                    <span id="exitModalDriver" class="exit-modal-info-value">-</span>
                </div>
            </div>
        </div>

        <div class="exit-modal-footer">
            <button type="button" id="btnCancelExit" class="btn-monitoring">
                Batal
            </button>
            <button type="button" id="btnConfirmExit"
                    class="btn-monitoring btn-monitoring-primary">
                <i class="bi bi-check-lg"></i> Ya, Catat Keluar
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const entryPanel = document.getElementById('entryFormPanel');
    const showEntryButton = document.getElementById('btnShowEntryForm');
    const cancelEntryButton = document.getElementById('btnCancelEntry');

    if (showEntryButton && entryPanel) {
        showEntryButton.addEventListener('click', function () {
            entryPanel.classList.add('show');
            entryPanel.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }

    if (cancelEntryButton && entryPanel) {
        cancelEntryButton.addEventListener('click', function () {
            entryPanel.classList.remove('show');
        });
    }

    const searchInput = document.getElementById('monitoringSearch');
    const typeFilter = document.getElementById('monitoringTypeFilter');
    const rows = Array.from(document.querySelectorAll('.monitoring-row'));
    const searchEmpty = document.getElementById('monitoringSearchEmpty');

    function filterMonitoring() {
        const keyword = (searchInput?.value || '').toLowerCase().trim();
        const selectedType = (typeFilter?.value || '').toLowerCase();
        let visibleCount = 0;

        rows.forEach(function (row) {
            const plate = row.dataset.plate || '';
            const driver = row.dataset.driver || '';
            const type = row.dataset.type || '';

            const matchesKeyword =
                plate.includes(keyword) || driver.includes(keyword);

            const matchesType =
                !selectedType || type === selectedType;

            const visible = matchesKeyword && matchesType;
            row.style.display = visible ? '' : 'none';

            if (visible) visibleCount++;
        });

        if (searchEmpty) {
            searchEmpty.style.display =
                rows.length > 0 && visibleCount === 0 ? '' : 'none';
        }
    }

    searchInput?.addEventListener('input', filterMonitoring);
    typeFilter?.addEventListener('change', filterMonitoring);

    const exitOverlay = document.getElementById('exitModalOverlay');
    const exitPlate = document.getElementById('exitModalPlate');
    const exitDriver = document.getElementById('exitModalDriver');
    const cancelExitButton = document.getElementById('btnCancelExit');
    const confirmExitButton = document.getElementById('btnConfirmExit');

    let selectedExitId = null;

    document.querySelectorAll('.monitoring-action.exit').forEach(function (button) {
        button.addEventListener('click', function () {
            selectedExitId = button.dataset.id || null;

            if (exitPlate) {
                exitPlate.textContent = button.dataset.plate || '-';
            }

            if (exitDriver) {
                exitDriver.textContent = button.dataset.driver || '-';
            }

            exitOverlay?.classList.add('show');
        });
    });

    function closeExitModal() {
        exitOverlay?.classList.remove('show');
        selectedExitId = null;
    }

    cancelExitButton?.addEventListener('click', closeExitModal);

    exitOverlay?.addEventListener('click', function (event) {
        if (event.target === exitOverlay) {
            closeExitModal();
        }
    });

    confirmExitButton?.addEventListener('click', function () {
        if (!selectedExitId) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action =
            "{{ url('/gate-logs') }}/" +
            encodeURIComponent(selectedExitId) +
            "/exit";

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = "{{ csrf_token() }}";

        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    });

    document.addEventListener('keydown', function (event) {
        if (
            event.key === 'Escape' &&
            exitOverlay?.classList.contains('show')
        ) {
            closeExitModal();
        }
    });
});
</script>
@endsection
