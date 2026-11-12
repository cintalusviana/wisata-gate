@extends('layouts.petugas')

@section('title', 'Kendaraan Keluar')

@section('content')

<style>
    .page-header {
        margin-bottom: 28px;
    }

    .page-header h2 {
        margin: 0;
        color: var(--text);
        font-size: 30px;
        font-weight: 800;
    }

    .page-header p {
        margin: 7px 0 0;
        color: var(--text-secondary);
        font-size: 14px;
    }

    .summary-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 22px;
        margin-bottom: 24px;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, .04);
    }

    .summary-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 14px;
        background: #EFF6FF;
        color: #2563EB;
        font-size: 24px;
    }

    [data-theme="dark"] .summary-icon {
        background: #172554;
        color: #93C5FD;
    }

    .summary-label {
        margin-bottom: 4px;
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 600;
    }

    .summary-number {
        color: var(--text);
        font-size: 25px;
        font-weight: 800;
        line-height: 1;
    }

    .data-card {
        overflow: hidden;
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, .04);
    }

    .data-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 22px 24px;
        border-bottom: 1px solid var(--border);
    }

    .data-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text);
        font-size: 17px;
        font-weight: 800;
    }

    .data-card-title i {
        color: var(--primary);
        font-size: 20px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .vehicle-table {
        width: 100%;
        min-width: 950px;
        margin: 0;
        border-collapse: collapse;
    }

    .vehicle-table th {
        padding: 15px 20px;
        background: var(--bg);
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        white-space: nowrap;
        text-align: left;
    }

    .vehicle-table td {
        padding: 17px 20px;
        color: var(--text);
        border-bottom: 1px solid var(--border);
        font-size: 14px;
        vertical-align: middle;
    }

    .vehicle-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .vehicle-table tbody tr:hover {
        background: rgba(37, 99, 235, .035);
    }

    .number-cell {
        color: var(--text-muted) !important;
        font-weight: 700;
    }

    .vehicle-info {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 180px;
    }

    .vehicle-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 11px;
        background: var(--primary-light);
        color: var(--primary);
        font-size: 18px;
    }

    .vehicle-plate {
        color: var(--text);
        font-weight: 800;
        white-space: nowrap;
    }

    .vehicle-type {
        margin-top: 3px;
        color: var(--text-secondary);
        font-size: 12px;
    }

    .driver-name {
        color: var(--text);
        font-weight: 600;
        white-space: nowrap;
    }

    .purpose-text {
        color: var(--text-secondary);
        min-width: 110px;
    }

    .time-cell {
        white-space: nowrap;
    }

    .time-main {
        color: var(--text);
        font-weight: 700;
    }

    .time-date {
        margin-top: 3px;
        color: var(--text-secondary);
        font-size: 12px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        background: rgba(16, 185, 129, .10);
        color: #059669;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10B981;
    }

    .empty-state {
        padding: 70px 25px;
        text-align: center;
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        border-radius: 20px;
        background: var(--bg);
        color: var(--text-muted);
        font-size: 30px;
    }

    .empty-state h4 {
        margin: 0 0 7px;
        color: var(--text);
        font-size: 17px;
        font-weight: 800;
    }

    .empty-state p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 13px;
    }

    @media (max-width: 576px) {
        .page-header {
            margin-bottom: 20px;
        }

        .page-header h2 {
            font-size: 24px;
        }

        .page-header p {
            font-size: 12px;
            line-height: 1.6;
        }

        .summary-card {
            padding: 18px;
        }

        .summary-icon {
            width: 46px;
            height: 46px;
            font-size: 20px;
        }

        .summary-number {
            font-size: 22px;
        }

        .data-card-header {
            padding: 18px;
        }

        .data-card-title {
            font-size: 15px;
        }

        .vehicle-table {
            min-width: 950px;
        }
    }
</style>

{{-- HEADER --}}
<div class="page-header">
    <h2>Kendaraan Keluar</h2>

    <p>
        Daftar kendaraan yang telah keluar melalui Gate Utama.
    </p>
</div>

{{-- SUMMARY --}}
<div class="summary-card">
    <div class="summary-icon">
        <i class="bi bi-box-arrow-right"></i>
    </div>

    <div>
        <div class="summary-label">
            Total Kendaraan Keluar
        </div>

        <div class="summary-number">
            {{ $totalExit ?? 0 }}
        </div>
    </div>
</div>

{{-- DATA KENDARAAN --}}
<div class="data-card">
    <div class="data-card-header">
        <div class="data-card-title">
            <i class="bi bi-list-check"></i>
            <span>Daftar Kendaraan</span>
        </div>
    </div>

    @if(isset($vehicles) && $vehicles->count() > 0)

        <div class="table-wrapper">
            <table class="vehicle-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Kendaraan</th>
                        <th>Pengemudi</th>
                        <th>Tujuan</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($vehicles as $index => $gateLog)
                        <tr>
                            {{-- NOMOR --}}
                            <td class="number-cell">
                                {{ $index + 1 }}
                            </td>

                            {{-- KENDARAAN --}}
                            <td>
                                <div class="vehicle-info">
                                    <div class="vehicle-icon">
                                        <i class="bi bi-car-front-fill"></i>
                                    </div>

                                    <div>
                                        <div class="vehicle-plate">
                                            {{ $gateLog->plate_number ?: '-' }}
                                        </div>

                                        <div class="vehicle-type">
                                            {{ $gateLog->vehicle_type ?: 'Jenis kendaraan -' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- PENGEMUDI --}}
                            <td>
                                <div class="driver-name">
                                    {{ $gateLog->driver_name ?: '-' }}
                                </div>
                            </td>

                            {{-- TUJUAN --}}
                            <td>
                                <div class="purpose-text">
                                    {{ $gateLog->purpose ?: '-' }}
                                </div>
                            </td>

                            {{-- WAKTU MASUK --}}
                            <td class="time-cell">
                                @if($gateLog->gate_in)
                                    <div class="time-main">
                                        {{ \Illuminate\Support\Carbon::parse($gateLog->gate_in)->format('H:i') }}
                                    </div>

                                    <div class="time-date">
                                        {{ \Illuminate\Support\Carbon::parse($gateLog->gate_in)->format('d-m-Y') }}
                                    </div>
                                @else
                                    <span>-</span>
                                @endif
                            </td>

                            {{-- WAKTU KELUAR --}}
                            <td class="time-cell">
                                @if($gateLog->gate_out)
                                    <div class="time-main">
                                        {{ \Illuminate\Support\Carbon::parse($gateLog->gate_out)->format('H:i') }}
                                    </div>

                                    <div class="time-date">
                                        {{ \Illuminate\Support\Carbon::parse($gateLog->gate_out)->format('d-m-Y') }}
                                    </div>
                                @else
                                    <span>-</span>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="status-badge">
                                    <span class="status-dot"></span>
                                    {{ $gateLog->status ?: 'Sudah Keluar' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @else
        {{-- DATA KOSONG --}}
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-car-front"></i>
            </div>

            <h4>Belum ada kendaraan keluar</h4>

            <p>
                Data kendaraan yang sudah keluar akan muncul di sini.
            </p>
        </div>
    @endif
</div>

@endsection