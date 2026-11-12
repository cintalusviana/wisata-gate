<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kendaraan WisataGate</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #ea580c;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header h1 {
            font-size: 20px;
            margin: 0 0 5px;
            color: #c2410c;
        }

        .header h2 {
            font-size: 13px;
            margin: 0 0 5px;
        }

        .header p {
            margin: 0;
            color: #64748b;
        }

        .period {
            text-align: right;
            margin-bottom: 12px;
            font-size: 10px;
        }

        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary td {
            width: 33.33%;
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: center;
        }

        .summary .label {
            display: block;
            color: #64748b;
            margin-bottom: 6px;
        }

        .summary .number {
            display: block;
            font-size: 20px;
            font-weight: bold;
            color: #ea580c;
        }

        h3 {
            font-size: 13px;
            margin: 15px 0 8px;
            color: #1f2937;
        }

        .vehicle-summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .vehicle-summary th,
        .vehicle-summary td {
            border: 1px solid #d1d5db;
            padding: 7px;
            text-align: left;
        }

        .vehicle-summary th {
            background: #fff7ed;
        }

        .history {
            width: 100%;
            border-collapse: collapse;
        }

        .history th,
        .history td {
            border: 1px solid #d1d5db;
            padding: 6px;
            text-align: center;
            vertical-align: middle;
        }

        .history th {
            background: #ea580c;
            color: #ffffff;
            font-weight: bold;
        }

        .history tr:nth-child(even) {
            background: #f9fafb;
        }

        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 1px solid #d1d5db;
            text-align: right;
            color: #64748b;
            font-size: 9px;
        }

        .empty {
            text-align: center;
            padding: 15px;
            color: #64748b;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>WISATAGATE</h1>
        <h2>LAPORAN MONITORING KENDARAAN</h2>
        <p>Sistem Monitoring Kendaraan Masuk dan Keluar Area Wisata</p>
        <p>Area Wisata - Gate Utama</p>
    </div>

    <div class="period">
        <strong>Periode Laporan:</strong>
        {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}
        s.d.
        {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
    </div>

    <table class="summary">
        <tr>
            <td>
                <span class="label">Total Kendaraan Masuk</span>
                <span class="number">{{ $totalEntry }}</span>
            </td>
            <td>
                <span class="label">Total Kendaraan Keluar</span>
                <span class="number">{{ $totalExit }}</span>
            </td>
            <td>
                <span class="label">Kendaraan Masih di Area</span>
                <span class="number">{{ $totalInside }}</span>
            </td>
        </tr>
    </table>

    <h3>Rekap Jenis Kendaraan</h3>

    <table class="vehicle-summary">
        <thead>
            <tr>
                <th width="10%">No.</th>
                <th>Jenis Kendaraan</th>
                <th width="20%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($vehicleTypeSummary as $type => $count)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $type }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="empty">
                        Belum ada data kendaraan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Riwayat Kendaraan</h3>

    <table class="history">
        <thead>
            <tr>
                <th width="4%">No.</th>
                <th width="11%">Nomor Polisi</th>
                <th width="10%">Jenis</th>
                <th width="12%">Pengemudi</th>
                <th width="10%">Tujuan</th>
                <th width="13%">Waktu Masuk</th>
                <th width="13%">Waktu Keluar</th>
                <th width="9%">Status</th>
                <th width="10%">Petugas</th>
                <th width="8%">Petak</th>
            </tr>
        </thead>

        <tbody>
            @forelse($histories as $history)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $history->plate_number ?? '-' }}</td>
                    <td>{{ $history->vehicle_type ?? '-' }}</td>
                    <td>{{ $history->driver_name ?? '-' }}</td>
                    <td>{{ $history->purpose ?? '-' }}</td>
                    <td>
                        {{ $history->gate_in
                            ? \Carbon\Carbon::parse($history->gate_in)->format('d-m-Y H:i')
                            : '-' }}
                    </td>
                    <td>
                        {{ $history->gate_out
                            ? \Carbon\Carbon::parse($history->gate_out)->format('d-m-Y H:i')
                            : '-' }}
                    </td>
                    <td>{{ $history->status ?? '-' }}</td>
                    <td>{{ $history->officer->name ?? '-' }}</td>
                    <td>{{ $history->parking_slot ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="empty">
                        Tidak ada riwayat kendaraan pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak melalui Sistem WisataGate |
        {{ now()->format('d-m-Y H:i') }}
    </div>

</body>
</html>
