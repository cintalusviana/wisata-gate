
@extends('layouts.app')

@section('title', 'Laporan Kendaraan')

@section('content')
@php
    $start = $startDate ?? now()->startOfMonth()->toDateString();
    $end = $endDate ?? now()->toDateString();
    $histories = $histories ?? collect();
    $vehicleTypeSummary = $vehicleTypeSummary ?? collect();
@endphp

<div class="report-page">

    {{-- HEADER --}}
    <div class="report-heading">
        <div>
            <div class="report-eyebrow">
                <i class="bi bi-file-earmark-bar-graph"></i>
                PUSAT LAPORAN
            </div>
            <h1>Laporan Kendaraan</h1>
            <p>Pantau aktivitas kendaraan masuk dan keluar Area Wisata.</p>
        </div>

        <div class="report-actions">
            <a href="{{ route('reports.export.pdf', ['start_date' => $start, 'end_date' => $end]) }}"
               class="btn-export btn-pdf">
                <i class="bi bi-file-earmark-pdf"></i>
                Export PDF
            </a>

            <a href="{{ route('reports.export.excel', ['start_date' => $start, 'end_date' => $end]) }}"
               class="btn-export btn-excel">
                <i class="bi bi-file-earmark-excel"></i>
                Export Excel
            </a>
        </div>
    </div>

    {{-- FILTER --}}
    <section class="report-panel filter-panel">
        <div class="panel-heading">
            <div class="panel-title">
                <div class="panel-icon">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div>
                    <h2>Filter Periode</h2>
                    <p>Pilih rentang tanggal laporan.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('reports.index') }}" method="GET" class="filter-form">

            <div class="filter-field">
                <label for="start_date">Tanggal Mulai</label>
                <input type="date"
                       id="start_date"
                       name="start_date"
                       value="{{ $start }}"
                       max="{{ now()->toDateString() }}"
                       required>
            </div>

            <div class="filter-field">
                <label for="end_date">Tanggal Selesai</label>
                <input type="date"
                       id="end_date"
                       name="end_date"
                       value="{{ $end }}"
                       max="{{ now()->toDateString() }}"
                       required>
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn-apply">
                    <i class="bi bi-funnel"></i>
                    Terapkan Filter
                </button>

                <a href="{{ route('reports.index') }}" class="btn-reset">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    Reset
                </a>
            </div>
        </form>
    </section>

    {{-- STATISTIK --}}
    <div class="stats-grid">

        <article class="stat-card">
            <div class="stat-info">
                <span>Total Kendaraan Masuk</span>
                <h3>{{ number_format($totalEntry ?? 0) }}</h3>
                <small><i class="bi bi-box-arrow-in-right"></i> Catatan kendaraan masuk</small>
            </div>
            <div class="stat-icon orange">
                <i class="bi bi-box-arrow-in-right"></i>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-info">
                <span>Total Kendaraan Keluar</span>
                <h3>{{ number_format($totalExit ?? 0) }}</h3>
                <small><i class="bi bi-box-arrow-right"></i> Catatan kendaraan keluar</small>
            </div>
            <div class="stat-icon slate">
                <i class="bi bi-box-arrow-right"></i>
            </div>
        </article>

        <article class="stat-card">
            <div class="stat-info">
                <span>Kendaraan di Dalam</span>
                <h3>{{ number_format($totalInside ?? 0) }}</h3>
                <small><i class="bi bi-clock-history"></i> Belum tercatat keluar</small>
            </div>
            <div class="stat-icon dark">
                <i class="bi bi-car-front"></i>
            </div>
        </article>

    </div>

    {{-- OVERVIEW --}}
    <div class="overview-grid">

        {{-- GRAFIK --}}
        <section class="report-panel chart-panel">

            <div class="panel-heading">
                <div class="panel-title">
                    <div class="panel-icon">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div>
                        <h2>Aktivitas Kendaraan</h2>
                        <p>Tren kendaraan masuk dan keluar.</p>
                    </div>
                </div>
            </div>

            <div class="chart-content">

                <div class="chart-overview">
                    <div class="chart-metric">
                        <span class="metric-line entry-line"></span>
                        <div>
                            <span>Kendaraan Masuk</span>
                            <strong>{{ number_format($totalEntry ?? 0) }}</strong>
                        </div>
                    </div>

                    <div class="chart-metric">
                        <span class="metric-line exit-line"></span>
                        <div>
                            <span>Kendaraan Keluar</span>
                            <strong>{{ number_format($totalExit ?? 0) }}</strong>
                        </div>
                    </div>
                </div>

                {{-- DATA GRAFIK --}}
                <div class="chart-container">
                    <canvas
                        id="vehicleActivityChart"
                        data-labels="{{ json_encode($chartLabels ?? []) }}"
                        data-entries="{{ json_encode($chartEntries ?? []) }}"
                        data-exits="{{ json_encode($chartExits ?? []) }}">
                    </canvas>
                </div>

                <div class="chart-legend">
                    <span><i class="legend-entry"></i> Kendaraan Masuk</span>
                    <span><i class="legend-exit"></i> Kendaraan Keluar</span>
                </div>

                <div class="chart-note">
                    <i class="bi bi-info-circle"></i>
                    Data grafik mengikuti periode laporan yang dipilih.
                </div>

            </div>
        </section>

        {{-- KOLOM SAMPING --}}
        <div class="side-column">

            {{-- PERIODE --}}
            <section class="report-panel">

                <div class="panel-heading">
                    <div class="panel-title">
                        <div class="panel-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <h2>Informasi Periode</h2>
                            <p>Rentang waktu laporan.</p>
                        </div>
                    </div>
                </div>

                <div class="side-content">

                    <div class="period-row">
                        <span><i class="bi bi-calendar-event"></i> Tanggal Mulai</span>
                        <strong>
                            {{ \Carbon\Carbon::parse($start)->translatedFormat('d F Y') }}
                        </strong>
                    </div>

                    <div class="period-divider"></div>

                    <div class="period-row">
                        <span><i class="bi bi-calendar-check"></i> Tanggal Selesai</span>
                        <strong>
                            {{ \Carbon\Carbon::parse($end)->translatedFormat('d F Y') }}
                        </strong>
                    </div>

                    <div class="period-total">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <div>
                            <span>Total Kendaraan Masuk</span>
                            <strong>{{ number_format($totalEntry ?? 0) }} kendaraan</strong>
                        </div>
                    </div>

                </div>
            </section>

            {{-- JENIS KENDARAAN --}}
            <section class="report-panel">

                <div class="panel-heading">
                    <div class="panel-title">
                        <div class="panel-icon">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <div>
                            <h2>Ringkasan Jenis Kendaraan</h2>
                            <p>Jumlah kendaraan per jenis.</p>
                        </div>
                    </div>
                </div>

                <div class="side-content vehicle-summary">

                    @forelse($vehicleTypeSummary as $type => $count)

                        <div class="vehicle-row">
                            <div class="vehicle-info">
                                <div class="vehicle-icon">
                                    <i class="bi bi-car-front"></i>
                                </div>
                                <span>{{ $type ?: 'Lainnya' }}</span>
                            </div>

                            <div class="vehicle-count">
                                <strong>{{ number_format($count) }}</strong>
                                <small>kendaraan</small>
                            </div>
                        </div>

                    @empty

                        <div class="empty-state compact-empty">
                            <i class="bi bi-inbox"></i>
                            <h3>Belum ada data</h3>
                            <p>Belum ada kendaraan pada periode ini.</p>
                        </div>

                    @endforelse

                </div>
            </section>

        </div>
    </div>

    {{-- RIWAYAT --}}
    <section class="report-panel history-panel">

        <div class="panel-heading history-heading">
            <div class="panel-title">
                <div class="panel-icon">
                    <i class="bi bi-list-ul"></i>
                </div>
                <div>
                    <h2>Riwayat Aktivitas Kendaraan</h2>
                    <p>Daftar aktivitas kendaraan berdasarkan periode laporan.</p>
                </div>
            </div>

            <span class="record-badge">
                {{ $histories->count() }} catatan
            </span>
        </div>

        <div class="table-responsive">
            <table class="report-table" id="historyTable">

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Plat Nomor</th>
                        <th>Jenis Kendaraan</th>
                        <th>Pengemudi</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Petugas</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($histories as $history)

                        <tr class="history-row">

                            <td class="row-number"></td>

                            <td>
                                <span class="plate-number">
                                    {{ $history->plate_number ?: '—' }}
                                </span>
                            </td>

                            <td>{{ $history->vehicle_type ?: '—' }}</td>

                            <td>{{ $history->driver_name ?: '—' }}</td>

                            <td>
                                @if($history->gate_in)
                                    <div class="date-cell">
                                        <span>{{ \Carbon\Carbon::parse($history->gate_in)->format('d/m/Y') }}</span>
                                        <small>{{ \Carbon\Carbon::parse($history->gate_in)->format('H:i') }}</small>
                                    </div>
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                @if($history->gate_out)
                                    <div class="date-cell">
                                        <span>{{ \Carbon\Carbon::parse($history->gate_out)->format('d/m/Y') }}</span>
                                        <small>{{ \Carbon\Carbon::parse($history->gate_out)->format('H:i') }}</small>
                                    </div>
                                @else
                                    <span class="muted-value">Belum keluar</span>
                                @endif
                            </td>

                            <td>{{ $history->officer?->name ?? '—' }}</td>

                            <td>
                                @if($history->status === 'Di Dalam')
                                    <span class="status-pill status-inside">
                                        <span></span> Di Dalam
                                    </span>
                                @else
                                    <span class="status-pill status-exited">
                                        <span></span> Sudah Keluar
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <h3>Belum ada riwayat kendaraan</h3>
                                    <p>Tidak ada aktivitas kendaraan pada periode yang dipilih.</p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        @if($histories->count() > 4)
            <div class="table-footer">
                <span id="tableInfo"></span>

                <button type="button" id="toggleHistory" class="btn-view-all">
                    Lihat Semua
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>
        @endif

    </section>

    <div class="report-footnote">
        <i class="bi bi-info-circle"></i>
        Laporan aktivitas kendaraan di Area Wisata (Gate Utama).
    </div>

</div>
@endsection

@push('styles')
<style>
.report-page {
    --orange: #EA580C;
    --orange-dark: #C2410C;
    --text: #1F2937;
    --muted: #6B7280;
    --border: #E5E7EB;

    min-width: 0;
    padding-bottom: 24px;
    color: var(--text);
}

.report-page *,
.report-page *::before,
.report-page *::after {
    box-sizing: border-box;
}

/* HEADER */

.report-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
    margin-bottom: 22px;
}

.report-eyebrow {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 8px;
    color: var(--orange);
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 1.2px;
}

.report-heading h1 {
    margin: 0 0 7px;
    color: #1F2937;
    font-size: clamp(24px, 2.2vw, 30px);
    font-weight: 800;
    letter-spacing: -.7px;
}

.report-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.7;
}

.report-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}

.btn-export,
.btn-apply,
.btn-reset,
.btn-view-all {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 39px;
    padding: 9px 14px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-family: inherit;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.btn-pdf {
    color: #fff;
    background: #DC2626;
    border-color: #DC2626;
}

.btn-pdf:hover {
    color: #fff;
    background: #B91C1C;
}

.btn-excel {
    color: #fff;
    background: #15803D;
    border-color: #15803D;
}

.btn-excel:hover {
    color: #fff;
    background: #166534;
}

/* PANEL */

.report-panel,
.stat-card {
    min-width: 0;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 13px;
    box-shadow: 0 3px 12px rgba(15, 23, 42, .035);
}

.report-panel {
    overflow: hidden;
    margin-bottom: 19px;
}

.panel-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 16px 18px;
    border-bottom: 1px solid #F1F2F4;
}

.panel-title {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
}

.panel-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 37px;
    height: 37px;
    color: var(--orange);
    background: #FFF7ED;
    border: 1px solid #FFEDD5;
    border-radius: 9px;
    font-size: 15px;
}

.panel-heading h2 {
    margin: 0 0 5px;
    color: var(--text);
    font-size: 13px;
    font-weight: 750;
}

.panel-heading p {
    margin: 0;
    color: var(--muted);
    font-size: 10px;
    line-height: 1.6;
}

/* FILTER */

.filter-panel {
    padding-bottom: 18px;
}

.filter-panel .panel-heading {
    border-bottom: 0;
}

.filter-form {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
    align-items: end;
    gap: 13px;
    padding: 0 18px;
}

.filter-field {
    min-width: 0;
}

.filter-field label {
    display: block;
    margin-bottom: 7px;
    color: #374151;
    font-size: 11px;
    font-weight: 700;
}

.filter-field input {
    width: 100%;
    min-height: 41px;
    padding: 9px 11px;
    color: #1F2937;
    background: #fff;
    border: 1px solid #DDE2E8;
    border-radius: 8px;
    outline: none;
    font-family: inherit;
    font-size: 12px;
}

.filter-field input:focus {
    border-color: var(--orange);
    box-shadow: 0 0 0 3px rgba(234, 88, 12, .09);
}

.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.btn-apply {
    color: #fff;
    background: var(--orange);
    border-color: var(--orange);
    white-space: nowrap;
}

.btn-apply:hover {
    color: #fff;
    background: var(--orange-dark);
}

.btn-reset,
.btn-view-all {
    color: #374151;
    background: #fff;
}

.btn-reset:hover,
.btn-view-all:hover {
    background: #F9FAFB;
    border-color: #CBD5E1;
}

/* STATISTIK */

.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 20px;
}

.stat-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 18px;
}

.stat-info {
    min-width: 0;
}

.stat-info > span {
    display: block;
    margin-bottom: 9px;
    color: var(--muted);
    font-size: 10px;
    font-weight: 650;
}

.stat-info h3 {
    margin: 0 0 8px;
    color: #111827;
    font-size: 28px;
    font-weight: 800;
    letter-spacing: -.8px;
}

.stat-info small {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #9CA3AF;
    font-size: 9px;
}

.stat-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    width: 43px;
    height: 43px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-size: 19px;
}

.stat-icon.orange {
    color: #EA580C;
    background: #FFF7ED;
    border-color: #FFEDD5;
}

.stat-icon.slate {
    color: #64748B;
    background: #F1F5F9;
    border-color: #E2E8F0;
}

.stat-icon.dark {
    color: #475569;
    background: #F8FAFC;
    border-color: #E2E8F0;
}

/* OVERVIEW */

.overview-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.4fr) minmax(280px, .9fr);
    align-items: stretch;
    gap: 17px;
    margin-bottom: 20px;
}

.overview-grid > .report-panel {
    margin-bottom: 0;
}

.side-column {
    display: flex;
    flex-direction: column;
    gap: 17px;
    min-width: 0;
}

.side-column .report-panel {
    margin-bottom: 0;
}

.chart-content {
    padding: 17px 18px 15px;
}

.chart-overview {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 9px;
    margin-bottom: 15px;
}

.chart-metric {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 11px 10px;
    background: #FAFAFA;
    border: 1px solid #F0F0F0;
    border-radius: 9px;
}

.metric-line {
    width: 4px;
    height: 34px;
    flex-shrink: 0;
    border-radius: 5px;
}

.entry-line {
    background: #EA580C;
}

.exit-line {
    background: #94A3B8;
}

.chart-metric > div {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.chart-metric span:not(.metric-line) {
    color: var(--muted);
    font-size: 9px;
}

.chart-metric strong {
    color: var(--text);
    font-size: 20px;
    font-weight: 800;
}

.chart-container {
    position: relative;
    width: 100%;
    height: 265px;
}

.chart-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 12px;
}

.chart-legend span {
    display: flex;
    align-items: center;
    gap: 7px;
    color: var(--muted);
    font-size: 9px;
}

.legend-entry,
.legend-exit {
    width: 8px;
    height: 8px;
    border-radius: 50%;
}

.legend-entry {
    background: #EA580C;
}

.legend-exit {
    background: #94A3B8;
}

.chart-note {
    display: flex;
    gap: 7px;
    margin-top: 13px;
    padding-top: 11px;
    color: #9CA3AF;
    border-top: 1px solid #F1F2F4;
    font-size: 9px;
    line-height: 1.7;
}

/* PERIODE */

.side-content {
    padding: 12px 16px 15px;
}

.period-row {
    display: flex;
    flex-direction: column;
    gap: 7px;
    padding: 4px 0 12px;
}

.period-row span {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #9CA3AF;
    font-size: 10px;
}

.period-row span i {
    color: var(--orange);
}

.period-row strong {
    color: #1F2937;
    font-size: 12px;
    font-weight: 750;
}

.period-divider {
    height: 1px;
    margin-bottom: 12px;
    background: #F0F1F3;
}

.period-total {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 2px;
    padding: 11px;
    color: var(--orange);
    background: #FFF7ED;
    border: 1px solid #FFEDD5;
    border-radius: 9px;
}

.period-total > i {
    font-size: 19px;
}

.period-total div {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.period-total span {
    color: #9A3412;
    font-size: 9px;
}

.period-total strong {
    color: #7C2D12;
    font-size: 12px;
}

/* JENIS KENDARAAN */

.vehicle-summary {
    padding-top: 5px;
    padding-bottom: 7px;
}

.vehicle-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    min-height: 53px;
    padding: 8px 0;
    border-bottom: 1px solid #F0F1F3;
}

.vehicle-row:last-child {
    border-bottom: 0;
}

.vehicle-info {
    display: flex;
    align-items: center;
    gap: 9px;
    min-width: 0;
    color: #374151;
    font-size: 11px;
    font-weight: 650;
}

.vehicle-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    flex-shrink: 0;
    color: #64748B;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
}

.vehicle-count {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 3px;
}

.vehicle-count strong {
    color: #1F2937;
    font-size: 13px;
    font-weight: 800;
}

.vehicle-count small {
    color: #9CA3AF;
    font-size: 9px;
}

/* TABEL */

.history-panel {
    margin-bottom: 17px;
}

.history-heading {
    flex-wrap: wrap;
}

.record-badge {
    padding: 7px 10px;
    color: #9A3412;
    background: #FFF7ED;
    border: 1px solid #FFEDD5;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 700;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.report-table {
    width: 100%;
    min-width: 900px;
    margin: 0;
    border-collapse: collapse;
    font-size: 11px;
}

.report-table th {
    padding: 13px 12px;
    color: #6B7280;
    background: #FAFAFA;
    border-bottom: 1px solid var(--border);
    font-size: 10px;
    font-weight: 750;
    text-align: left;
    white-space: nowrap;
}

.report-table td {
    padding: 13px 12px;
    color: #4B5563;
    border-bottom: 1px solid #F0F1F3;
    vertical-align: middle;
}

.report-table tbody tr:last-child td {
    border-bottom: 0;
}

.history-row:hover {
    background: #FFFCFA;
}

.plate-number {
    display: inline-block;
    padding: 6px 8px;
    color: #374151;
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 800;
    white-space: nowrap;
}

.date-cell {
    display: flex;
    flex-direction: column;
    gap: 4px;
    white-space: nowrap;
}

.date-cell span {
    color: #374151;
    font-weight: 650;
}

.date-cell small {
    color: #9CA3AF;
    font-size: 10px;
}

.muted-value {
    color: #9CA3AF;
    font-size: 10px;
    font-style: italic;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 9px;
    border: 1px solid transparent;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 700;
    white-space: nowrap;
}

.status-pill > span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.status-inside {
    color: #166534;
    background: #F0FDF4;
    border-color: #DCFCE7;
}

.status-inside > span {
    background: #16A34A;
}

.status-exited {
    color: #4B5563;
    background: #F9FAFB;
    border-color: #E5E7EB;
}

.status-exited > span {
    background: #6B7280;
}

/* FOOTER TABEL */

.table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 13px 18px;
    border-top: 1px solid #F0F1F3;
}

.table-footer > span {
    color: #9CA3AF;
    font-size: 10px;
}

.btn-view-all {
    min-height: 33px;
    padding: 7px 11px;
    font-size: 10px;
}

/* EMPTY STATE */

.empty-state {
    padding: 30px 12px;
    text-align: center;
}

.empty-state > i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    margin: 0 auto 11px;
    color: #64748B;
    background: #F8FAFC;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    font-size: 18px;
}

.empty-state h3 {
    margin: 0 0 7px;
    color: #374151;
    font-size: 12px;
    font-weight: 750;
}

.empty-state p {
    margin: 0;
    color: #9CA3AF;
    font-size: 10px;
    line-height: 1.7;
}

.compact-empty {
    padding: 17px 8px;
}

.report-footnote {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    color: #9CA3AF;
    font-size: 10px;
    line-height: 1.7;
}

/* RESPONSIVE */

@media (max-width: 1100px) {
    .filter-form {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .filter-buttons {
        grid-column: 1 / -1;
    }

    .overview-grid {
        grid-template-columns: minmax(0, 1.2fr) minmax(250px, .9fr);
    }

    .stat-card {
        padding: 15px;
    }
}

@media (max-width: 760px) {
    .report-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .report-actions {
        width: 100%;
    }

    .report-actions .btn-export {
        flex: 1;
    }

    .filter-form {
        grid-template-columns: 1fr;
        padding: 0 16px;
    }

    .filter-buttons {
        grid-column: auto;
        display: grid;
        grid-template-columns: 1fr auto;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .stat-card {
        padding: 16px;
    }

    .overview-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .side-column {
        gap: 15px;
    }

    .chart-content {
        padding: 15px;
    }

    .chart-container {
        height: 250px;
    }

    .report-table {
        min-width: 850px;
    }
}

@media (max-width: 400px) {
    .report-heading h1 {
        font-size: 23px;
    }

    .report-actions {
        flex-direction: column;
    }

    .report-actions .btn-export {
        width: 100%;
        flex: auto;
    }

    .filter-buttons {
        grid-template-columns: 1fr;
    }

    .chart-overview {
        gap: 7px;
    }

    .chart-metric {
        gap: 7px;
        padding: 9px 7px;
    }

    .chart-metric span:not(.metric-line) {
        font-size: 8px;
    }

    .chart-metric strong {
        font-size: 17px;
    }
}

/* MODE GELAP */

html[data-theme="dark"] .report-page,
body.dark-mode .report-page,
body.dark .report-page {
    color: #E5E7EB;
}

html[data-theme="dark"] .report-page .report-heading h1,
body.dark-mode .report-page .report-heading h1,
body.dark .report-page .report-heading h1 {
    color: #F3F4F6;
}

html[data-theme="dark"] .report-page .report-panel,
html[data-theme="dark"] .report-page .stat-card,
body.dark-mode .report-page .report-panel,
body.dark-mode .report-page .stat-card,
body.dark .report-page .report-panel,
body.dark .report-page .stat-card {
    background: #1F2937;
    border-color: #374151;
}

html[data-theme="dark"] .report-page .panel-heading,
body.dark-mode .report-page .panel-heading,
body.dark .report-page .panel-heading {
    border-color: #374151;
}

html[data-theme="dark"] .report-page .panel-heading h2,
html[data-theme="dark"] .report-page .stat-info h3,
html[data-theme="dark"] .report-page .chart-metric strong,
html[data-theme="dark"] .report-page .period-row strong,
html[data-theme="dark"] .report-page .vehicle-count strong,
body.dark-mode .report-page .panel-heading h2,
body.dark-mode .report-page .stat-info h3,
body.dark-mode .report-page .chart-metric strong,
body.dark-mode .report-page .period-row strong,
body.dark-mode .report-page .vehicle-count strong,
body.dark .report-page .panel-heading h2,
body.dark .report-page .stat-info h3,
body.dark .report-page .chart-metric strong,
body.dark .report-page .period-row strong,
body.dark .report-page .vehicle-count strong {
    color: #F3F4F6;
}

html[data-theme="dark"] .report-page .panel-icon,
html[data-theme="dark"] .report-page .stat-icon,
html[data-theme="dark"] .report-page .vehicle-icon,
body.dark-mode .report-page .panel-icon,
body.dark-mode .report-page .stat-icon,
body.dark-mode .report-page .vehicle-icon,
body.dark .report-page .panel-icon,
body.dark .report-page .stat-icon,
body.dark .report-page .vehicle-icon {
    background: #292524;
    border-color: #44403C;
}

html[data-theme="dark"] .report-page .filter-field label,
body.dark-mode .report-page .filter-field label,
body.dark .report-page .filter-field label {
    color: #D1D5DB;
}

html[data-theme="dark"] .report-page .filter-field input,
body.dark-mode .report-page .filter-field input,
body.dark .report-page .filter-field input {
    color: #F3F4F6;
    background: #111827;
    border-color: #374151;
    color-scheme: dark;
}

html[data-theme="dark"] .report-page .chart-metric,
body.dark-mode .report-page .chart-metric,
body.dark .report-page .chart-metric {
    background: #111827;
    border-color: #374151;
}

html[data-theme="dark"] .report-page .period-total,
body.dark-mode .report-page .period-total,
body.dark .report-page .period-total {
    background: #292524;
    border-color: #44403C;
}

html[data-theme="dark"] .report-page .period-total span,
html[data-theme="dark"] .report-page .period-total strong,
body.dark-mode .report-page .period-total span,
body.dark-mode .report-page .period-total strong,
body.dark .report-page .period-total span,
body.dark .report-page .period-total strong {
    color: #FED7AA;
}

html[data-theme="dark"] .report-page .period-total > i,
body.dark-mode .report-page .period-total > i,
body.dark .report-page .period-total > i {
    color: #FB923C;
}

html[data-theme="dark"] .report-page .period-divider,
html[data-theme="dark"] .report-page .vehicle-row,
html[data-theme="dark"] .report-page .chart-note,
body.dark-mode .report-page .period-divider,
body.dark-mode .report-page .vehicle-row,
body.dark-mode .report-page .chart-note,
body.dark .report-page .period-divider,
body.dark .report-page .vehicle-row,
body.dark .report-page .chart-note {
    border-color: #374151;
}

html[data-theme="dark"] .report-page .vehicle-info,
body.dark-mode .report-page .vehicle-info,
body.dark .report-page .vehicle-info {
    color: #E5E7EB;
}

html[data-theme="dark"] .report-page .record-badge,
body.dark-mode .report-page .record-badge,
body.dark .report-page .record-badge {
    color: #FED7AA;
    background: #292524;
    border-color: #44403C;
}

html[data-theme="dark"] .report-page .report-table th,
body.dark-mode .report-page .report-table th,
body.dark .report-page .report-table th {
    color: #D1D5DB;
    background: #111827;
    border-color: #374151;
}

html[data-theme="dark"] .report-page .report-table td,
body.dark-mode .report-page .report-table td,
body.dark .report-page .report-table td {
    color: #D1D5DB;
    border-color: #374151;
}

html[data-theme="dark"] .report-page .history-row:hover,
body.dark-mode .report-page .history-row:hover,
body.dark .report-page .history-row:hover {
    background: #273449;
}

html[data-theme="dark"] .report-page .plate-number,
body.dark-mode .report-page .plate-number,
body.dark .report-page .plate-number {
    color: #F3F4F6;
    background: #111827;
    border-color: #374151;
}

html[data-theme="dark"] .report-page .date-cell span,
body.dark-mode .report-page .date-cell span,
body.dark .report-page .date-cell span {
    color: #E5E7EB;
}

html[data-theme="dark"] .report-page .btn-reset,
html[data-theme="dark"] .report-page .btn-view-all,
body.dark-mode .report-page .btn-reset,
body.dark-mode .report-page .btn-view-all,
body.dark .report-page .btn-reset,
body.dark .report-page .btn-view-all {
    color: #E5E7EB;
    background: #111827;
    border-color: #374151;
}

html[data-theme="dark"] .report-page .empty-state h3,
body.dark-mode .report-page .empty-state h3,
body.dark .report-page .empty-state h3 {
    color: #E5E7EB;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* GRAFIK AKTIVITAS KENDARAAN */

    const canvas = document.getElementById('vehicleActivityChart');

    if (canvas && typeof Chart !== 'undefined') {

        let labels = [];
        let entries = [];
        let exits = [];

        try {
            labels = JSON.parse(canvas.dataset.labels || '[]');
            entries = JSON.parse(canvas.dataset.entries || '[]');
            exits = JSON.parse(canvas.dataset.exits || '[]');
        } catch (error) {
            console.error('Data grafik tidak valid:', error);
        }

        const ctx = canvas.getContext('2d');

        const entryGradient = ctx.createLinearGradient(0, 0, 0, 265);
        entryGradient.addColorStop(0, 'rgba(234, 88, 12, 0.25)');
        entryGradient.addColorStop(1, 'rgba(234, 88, 12, 0.01)');

        const exitGradient = ctx.createLinearGradient(0, 0, 0, 265);
        exitGradient.addColorStop(0, 'rgba(148, 163, 184, 0.22)');
        exitGradient.addColorStop(1, 'rgba(148, 163, 184, 0.01)');

        new Chart(ctx, {
            type: 'line',

            data: {
                labels: labels,

                datasets: [
                    {
                        label: 'Kendaraan Masuk',
                        data: entries,
                        borderColor: '#EA580C',
                        backgroundColor: entryGradient,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointRadius: 2.5,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#EA580C',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        spanGaps: true
                    },
                    {
                        label: 'Kendaraan Keluar',
                        data: exits,
                        borderColor: '#94A3B8',
                        backgroundColor: exitGradient,
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                        pointRadius: 2.5,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#94A3B8',
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2,
                        spanGaps: true
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                interaction: {
                    mode: 'index',
                    intersect: false
                },

                animation: {
                    duration: 700,
                    easing: 'easeOutQuart'
                },

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: '#FFFFFF',
                        titleColor: '#1F2937',
                        bodyColor: '#4B5563',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 9,
                        displayColors: true,
                        usePointStyle: true,

                        callbacks: {
                            label: function (context) {
                                const value = Number(context.parsed.y || 0);

                                return ' ' + context.dataset.label + ': ' +
                                    value.toLocaleString('id-ID') +
                                    ' kendaraan';
                            }
                        }
                    }
                },

                scales: {
                    x: {
                        grid: {
                            display: false
                        },

                        border: {
                            display: false
                        },

                        ticks: {
                            color: '#94A3B8',
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 8,

                            font: {
                                size: 9
                            }
                        }
                    },

                    y: {
                        beginAtZero: true,

                        ticks: {
                            precision: 0,
                            color: '#94A3B8',
                            padding: 7,

                            font: {
                                size: 9
                            }
                        },

                        grid: {
                            color: 'rgba(148, 163, 184, 0.15)',
                            drawTicks: false
                        },

                        border: {
                            display: false,
                            dash: [4, 4]
                        }
                    }
                }
            }
        });
    }

    /* TABEL RIWAYAT */

    const rows = Array.from(
        document.querySelectorAll('#historyTable tbody tr.history-row')
    );

    const toggleButton = document.getElementById('toggleHistory');
    const tableInfo = document.getElementById('tableInfo');

    let showAll = false;

    function updateHistoryTable() {

        rows.forEach(function (row, index) {

            row.style.display = (showAll || index < 4) ? '' : 'none';

            const numberCell = row.querySelector('.row-number');

            if (numberCell) {
                numberCell.textContent = index + 1;
            }
        });

        if (tableInfo) {

            const visibleCount = showAll
                ? rows.length
                : Math.min(4, rows.length);

            tableInfo.textContent =
                'Menampilkan ' + visibleCount +
                ' dari ' + rows.length + ' catatan';
        }

        if (toggleButton) {

            toggleButton.innerHTML = showAll
                ? 'Tampilkan Lebih Sedikit <i class="bi bi-chevron-up"></i>'
                : 'Lihat Semua <i class="bi bi-chevron-down"></i>';
        }
    }

    if (toggleButton) {
        toggleButton.addEventListener('click', function () {
            showAll = !showAll;
            updateHistoryTable();
        });
    }

    updateHistoryTable();

});
</script>
@endpush
