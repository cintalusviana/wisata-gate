@extends('layouts.app')

@section('title', 'Dashboard Admin | WisataGate')

@section('content')

@php
    $vehicles = $vehicles ?? collect();
    $activity = $activity ?? collect();
    $vehicleComposition = $vehicleComposition ?? collect();

    $vehicles = $vehicles instanceof \Illuminate\Support\Collection ? $vehicles : collect($vehicles);
    $activity = $activity instanceof \Illuminate\Support\Collection ? $activity : collect($activity);
    $vehicleComposition = $vehicleComposition instanceof \Illuminate\Support\Collection
        ? $vehicleComposition
        : collect($vehicleComposition);

    $compositionTotal = (int) $vehicleComposition->sum('total');
    $colors = ['green', 'orange', 'purple', 'gray', 'cyan'];

    $maxActivity = max(5, (int) $activity->max(function ($item) {
        return max(
            (int) data_get($item, 'entry', 0),
            (int) data_get($item, 'exit', 0)
        );
    }));

    $chartCeiling = max(5, (int) (ceil($maxActivity / 5) * 5));
@endphp

<style>
.dashboard-page{width:100%;max-width:1500px;margin:0 auto;font-family:Inter,sans-serif;font-size:13px;-webkit-font-smoothing:antialiased}
.dashboard-page *,.dashboard-page *:before,.dashboard-page *:after{box-sizing:border-box;font-family:Inter,sans-serif}
.dashboard-hero{position:relative;min-height:172px;display:flex;align-items:center;justify-content:space-between;gap:24px;overflow:hidden;margin-bottom:22px;padding:29px 32px;border-radius:15px;background:linear-gradient(110deg,#ff5a00 0%,#f04400 52%,#d61f00 100%);box-shadow:0 12px 30px rgba(214,31,0,.2)}
.dashboard-hero:before{content:"";position:absolute;width:390px;height:390px;right:-150px;top:-230px;border:1px solid rgba(255,255,255,.12);border-radius:50%;pointer-events:none}
.dashboard-hero:after{content:"";position:absolute;width:230px;height:230px;right:100px;bottom:-180px;border-radius:50%;background:rgba(255,255,255,.09);pointer-events:none}
.hero-content,.hero-count{position:relative;z-index:2}
.hero-label{display:flex;align-items:center;flex-wrap:wrap;gap:8px;margin-bottom:11px;color:#ffe0cc;font-size:10px;font-weight:800;letter-spacing:.07em}
.hero-live{width:8px;height:8px;flex-shrink:0;border-radius:50%;background:#34d399;box-shadow:0 0 0 4px rgba(52,211,153,.12)}
.hero-label span:last-child{color:rgba(255,255,255,.8);font-weight:500;letter-spacing:0}
.hero-title{margin:0 0 8px;color:#fff;font-size:25px;line-height:1.35;font-weight:800;letter-spacing:-.025em}
.hero-description{max-width:650px;margin:0;color:#ffe4d6;font-size:12px;line-height:1.7}
.hero-count{min-width:175px;text-align:right}
.hero-count-number{color:#ffd0b5;font-size:52px;line-height:1.1;font-weight:800}
.hero-count-label{margin-top:7px;color:#fff0e8;font-size:11px;font-weight:600}
.statistics-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px;margin-bottom:22px}
.stat-card{min-width:0;min-height:135px;padding:19px;border:1px solid var(--border);border-radius:13px;background:var(--card);box-shadow:0 4px 15px rgba(0,0,0,.035)}
.stat-card-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:17px}
.stat-label{color:var(--text-secondary);font-size:10px;font-weight:800;letter-spacing:.02em}
.stat-icon{width:36px;height:36px;flex-shrink:0;display:flex;align-items:center;justify-content:center;border-radius:9px;background:var(--primary-light);color:var(--primary);font-size:16px}
.stat-icon.green{background:rgba(16,185,129,.1);color:#10b981}.stat-icon.orange{background:rgba(249,115,22,.12);color:#f97316}
.stat-value{color:var(--text);font-size:30px;line-height:1.1;font-weight:800}
.stat-description{margin-top:8px;color:var(--text-muted);font-size:10px;line-height:1.5}
.dashboard-grid{display:grid;grid-template-columns:minmax(0,1.5fr) minmax(300px,.8fr);gap:20px;margin-bottom:22px;align-items:start}
.dashboard-card{min-width:0;overflow:hidden;border:1px solid var(--border);border-radius:14px;background:var(--card);box-shadow:0 4px 16px rgba(0,0,0,.035)}
.card-header{display:flex;align-items:flex-start;justify-content:space-between;gap:15px;padding:20px 21px 17px;border-bottom:1px solid var(--border)}
.card-header h3{margin:0 0 6px;color:var(--text);font-size:15px;line-height:1.45;font-weight:800}
.card-header p{margin:0;color:var(--text-secondary);font-size:11px;line-height:1.65}
.card-link{color:var(--primary);font-size:10px;font-weight:700;text-decoration:none;white-space:nowrap}
.card-link:hover{color:var(--primary-hover)}
.chart-legend{display:flex;align-items:center;flex-wrap:wrap;gap:12px}
.legend{display:flex;align-items:center;gap:6px;color:var(--text-secondary);font-size:10px;font-weight:600;white-space:nowrap}
.legend-dot{width:8px;height:8px;border-radius:2px;background:var(--primary)}.legend-dot.exit{background:#f59e0b}
.chart-container{height:245px;padding:19px 20px}
.chart{position:relative;width:100%;height:100%;padding-left:34px;padding-bottom:27px}
.chart-lines{position:absolute;top:4px;left:34px;right:0;bottom:27px;display:flex;flex-direction:column;justify-content:space-between}
.chart-line{border-top:1px dashed var(--border)}
.chart-y{position:absolute;top:0;left:0;bottom:27px;display:flex;flex-direction:column;justify-content:space-between;color:var(--text-muted);font-size:9px}
.chart-bars{position:absolute;top:4px;left:34px;right:0;bottom:27px;display:flex;align-items:stretch;justify-content:space-around}
.chart-group{height:100%;min-width:0;display:flex;align-items:flex-end;justify-content:center;gap:4px}
.chart-bar{width:clamp(7px,1.5vw,20px);max-width:22px;min-height:0;border-radius:4px 4px 0 0;background:var(--primary);transition:height .25s ease}
.chart-bar.exit{background:#f59e0b}
.chart-x{position:absolute;left:34px;right:0;bottom:0;display:flex;justify-content:space-around;color:var(--text-secondary);font-size:9px;font-weight:600}
.chart-note{padding:11px 21px;border-top:1px solid var(--border);color:var(--text-muted);font-size:10px;line-height:1.6}
.type-list{padding:10px 21px 18px}
.type-item{padding:13px 0;border-bottom:1px solid var(--border)}.type-item:last-of-type{border-bottom:0}
.type-header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:8px}
.type-name{min-width:0;display:flex;align-items:center;gap:9px;color:var(--text);font-size:12px;font-weight:700}
.type-name i{width:29px;height:29px;flex-shrink:0;display:flex;align-items:center;justify-content:center;border-radius:8px;background:var(--primary-light);color:var(--primary);font-size:14px}
.type-count{color:var(--text-secondary);font-size:10px;font-weight:700;text-align:right;white-space:nowrap}
.type-progress{height:6px;overflow:hidden;border-radius:20px;background:var(--border)}
.type-progress span{display:block;height:100%;border-radius:20px;background:var(--primary);transition:width .3s ease}
.type-progress.green span{background:#10b981}.type-progress.orange span{background:#f59e0b}.type-progress.purple span{background:#8b5cf6}.type-progress.gray span{background:#64748b}.type-progress.cyan span{background:#06b6d4}
.type-note{padding-top:14px;color:var(--text-muted);font-size:10px;line-height:1.6;text-align:center}
.table-tools{display:flex;align-items:center;flex-wrap:wrap;gap:8px;padding:15px 21px}
.search{flex:1 1 220px;min-width:0;min-height:36px;display:flex;align-items:center;gap:8px;padding:0 11px;border:1px solid var(--border);border-radius:8px;background:var(--input-bg);color:var(--text-muted);font-size:11px}
.search i{flex-shrink:0}.search input{width:100%;min-width:0;padding:8px 0;border:0;outline:none;background:transparent;color:var(--text);font-size:11px}
.search input::placeholder{color:var(--text-muted)}
.filter-button{min-height:35px;padding:0 11px;border:1px solid var(--border);border-radius:7px;background:var(--card);color:var(--text-secondary);font-size:10px;font-weight:600;cursor:pointer}
.filter-button:hover,.filter-button.active{border-color:var(--primary);background:var(--primary);color:#fff}
.table-wrapper{width:100%;overflow-x:auto;scrollbar-width:thin}
.vehicle-table{width:100%;min-width:700px;border-collapse:collapse}
.vehicle-table th{padding:12px 16px;border-top:1px solid var(--border);border-bottom:1px solid var(--border);background:rgba(148,163,184,.08);color:var(--text-secondary);font-size:10px;font-weight:800;text-align:left;white-space:nowrap}
.vehicle-table td{padding:14px 16px;border-bottom:1px solid var(--border);color:var(--text-secondary);font-size:11px;vertical-align:middle}
.vehicle-table tbody tr:last-child td{border-bottom:0}.vehicle-table tbody tr:hover{background:rgba(234,88,12,.035)}
.vehicle-table strong{color:var(--text);font-weight:700}
.vehicle-status{display:inline-flex;align-items:center;gap:6px;padding:6px 9px;border-radius:20px;background:rgba(16,185,129,.1);color:#059669;font-size:10px;font-weight:700;white-space:nowrap}
.vehicle-status-dot{width:6px;height:6px;flex-shrink:0;border-radius:50%;background:#10b981}
.empty-state{padding:38px 20px!important;color:var(--text-muted)!important;text-align:center;line-height:1.7}
.empty-state i{display:block;margin-bottom:10px;font-size:29px}
.denah-header{display:flex;align-items:flex-start;justify-content:space-between;width:100%;gap:10px}
.denah-title{display:flex;align-items:center;gap:9px}.denah-title h3{margin:0}
.denah-title-icon{width:30px;height:30px;flex-shrink:0;display:flex;align-items:center;justify-content:center;border-radius:8px;background:var(--primary-light);color:var(--primary);font-size:14px}
.denah-description{padding:16px 21px 14px;color:var(--text-secondary);font-size:11px;line-height:1.7}
.denah-preview{position:relative;height:265px;margin:0 18px 18px;overflow:hidden;display:flex;align-items:center;justify-content:center;border:1px solid var(--border);border-radius:12px;background:radial-gradient(circle at 50% 45%,rgba(255,255,255,.9) 0,rgba(255,255,255,.45) 32%,rgba(148,163,184,.08) 70%,rgba(148,163,184,.13) 100%)}
[data-theme="dark"] .denah-preview{background:radial-gradient(circle at 50% 45%,rgba(55,65,81,.95) 0,rgba(31,41,55,.9) 40%,rgba(17,24,39,.95) 100%)}
.denah-line-horizontal{position:absolute;width:78%;height:2px;top:50%;left:11%;background:var(--border)}
.denah-line-vertical{position:absolute;width:2px;height:70%;top:15%;left:50%;background:var(--border)}
.denah-zone{position:absolute;border:1px dashed var(--border);border-radius:12px;background:rgba(148,163,184,.05)}
.denah-zone.zone-left,.denah-zone.zone-right,.denah-zone.zone-bottom-left,.denah-zone.zone-bottom-right{width:105px;height:62px}
.denah-zone.zone-left{left:7%;top:13%}.denah-zone.zone-right{right:7%;top:13%}.denah-zone.zone-bottom-left{left:7%;bottom:13%}.denah-zone.zone-bottom-right{right:7%;bottom:13%}
.denah-zone-label{position:absolute;color:var(--text-secondary);font-size:9px;font-weight:700;text-align:center}
.label-left,.label-right,.label-bottom-left,.label-bottom-right{width:105px}
.label-left{left:7%;top:calc(13% + 24px)}.label-right{right:7%;top:calc(13% + 24px)}.label-bottom-left{left:7%;bottom:calc(13% + 24px)}.label-bottom-right{right:7%;bottom:calc(13% + 24px)}
.denah-center{position:relative;z-index:5;width:78px;height:78px;display:flex;flex-direction:column;align-items:center;justify-content:center;border:5px solid rgba(255,255,255,.95);border-radius:50%;background:var(--primary);color:#fff;box-shadow:0 0 0 7px rgba(234,88,12,.12),0 10px 25px rgba(234,88,12,.2)}
.denah-center i{margin-bottom:3px;font-size:22px}.denah-center span{font-size:8px;font-weight:800}
.denah-point{position:absolute;z-index:3;width:12px;height:12px;border:3px solid var(--card);border-radius:50%;background:#10b981;box-shadow:0 3px 8px rgba(0,0,0,.12)}
.point-one{top:27%;left:38%}.point-two{top:68%;left:62%}.point-three{top:29%;right:27%}
.denah-overlay{position:absolute;z-index:10;left:50%;bottom:12px;transform:translateX(-50%);width:calc(100% - 24px);padding:10px;border:1px solid var(--border);border-radius:9px;background:var(--card);box-shadow:0 5px 18px rgba(0,0,0,.08);text-align:center}
.denah-overlay-title{display:flex;align-items:center;justify-content:center;gap:7px;color:var(--text);font-size:9px;font-weight:800}
.denah-overlay-title i{color:var(--primary);font-size:12px}.denah-overlay-subtitle{margin-top:4px;color:var(--text-muted);font-size:9px;line-height:1.5}
[data-theme="dark"] .dashboard-card,[data-theme="dark"] .stat-card{box-shadow:0 4px 18px rgba(0,0,0,.18)}
[data-theme="dark"] .vehicle-table th{background:rgba(255,255,255,.035)}
[data-theme="dark"] .vehicle-table tbody tr:hover{background:rgba(249,115,22,.06)}
[data-theme="dark"] .dashboard-hero{background:linear-gradient(110deg,#ff5a00 0%,#e63b00 52%,#b91c00 100%);box-shadow:0 14px 35px rgba(0,0,0,.35)}
@media(max-width:1250px){.dashboard-grid{grid-template-columns:minmax(0,1fr)}}
@media(max-width:1050px){.statistics-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:750px){.dashboard-hero{align-items:flex-start;flex-direction:column;padding:25px}.hero-count{min-width:0;text-align:left}.hero-count-number{font-size:42px}.card-header{padding:17px}.table-tools{padding:13px 16px}.chart-container{padding-left:14px;padding-right:14px}}
@media(max-width:600px){.statistics-grid{grid-template-columns:minmax(0,1fr);gap:12px}.stat-card{min-height:115px;padding:17px}.hero-title{font-size:21px}.hero-label{font-size:9px}.card-header h3{font-size:14px}.chart-legend{gap:8px}.chart-container{height:225px}.denah-preview{height:245px;margin-left:12px;margin-right:12px}.denah-zone.zone-left,.denah-zone.zone-right,.denah-zone.zone-bottom-left,.denah-zone.zone-bottom-right{width:82px;height:54px}.label-left,.label-right,.label-bottom-left,.label-bottom-right{width:82px;font-size:8px}.denah-center{width:68px;height:68px}.denah-center i{font-size:19px}}
@media(max-width:380px){.dashboard-hero{padding:20px}.hero-title{font-size:19px}.hero-description{font-size:11px}.table-tools .filter-button{padding:0 9px}.type-header{gap:6px}}
</style>

<div class="dashboard-page">
    <section class="dashboard-hero">
        <div class="hero-content">
            <div class="hero-label">
                <span class="hero-live"></span>
                KONSOL MANAJEMEN ADMIN
                <span>• Area Wisata (Gate Utama)</span>
            </div>
            <h2 class="hero-title">Dashboard Pengawasan Operasional</h2>
            <p class="hero-description">Pusat kendali monitoring kendaraan dan rekapitulasi aktivitas keluar-masuk Gate Utama.</p>
        </div>
        <div class="hero-count">
            <div class="hero-count-number">{{ $totalInside ?? 0 }}</div>
            <div class="hero-count-label">Kendaraan sedang berada di area</div>
        </div>
    </section>

    <section class="statistics-grid">
        <div class="stat-card">
            <div class="stat-card-top"><span class="stat-label">MASIH DI AREA</span><div class="stat-icon green"><i class="bi bi-car-front-fill"></i></div></div>
            <div class="stat-value">{{ $totalInside ?? 0 }}</div>
            <div class="stat-description">Unit aktif di dalam Area Wisata</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top"><span class="stat-label">MASUK HARI INI</span><div class="stat-icon"><i class="bi bi-box-arrow-in-right"></i></div></div>
            <div class="stat-value">{{ $totalEntry ?? 0 }}</div>
            <div class="stat-description">Arus kedatangan terdaftar hari ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top"><span class="stat-label">KELUAR HARI INI</span><div class="stat-icon orange"><i class="bi bi-box-arrow-right"></i></div></div>
            <div class="stat-value">{{ $totalExit ?? 0 }}</div>
            <div class="stat-description">Kendaraan keluar tercatat hari ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-top"><span class="stat-label">PETUGAS GATE</span><div class="stat-icon"><i class="bi bi-people"></i></div></div>
            <div class="stat-value">{{ $totalOfficers ?? 1 }}</div>
            <div class="stat-description">Pos pemantauan Gate Utama</div>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <div><h3>Aktivitas Kendaraan Hari Ini</h3><p>Perbandingan kendaraan masuk dan keluar berdasarkan waktu pencatatan.</p></div>
                <div class="chart-legend"><div class="legend"><span class="legend-dot"></span> Masuk</div><div class="legend"><span class="legend-dot exit"></span> Keluar</div></div>
            </div>
            <div class="chart-container">
                <div class="chart">
                    <div class="chart-y"><span>{{ $chartCeiling }}</span><span>{{ round($chartCeiling * .75) }}</span><span>{{ round($chartCeiling * .5) }}</span><span>0</span></div>
                    <div class="chart-lines"><div class="chart-line"></div><div class="chart-line"></div><div class="chart-line"></div><div class="chart-line"></div></div>
                    <div class="chart-bars">
                        @forelse($activity as $item)
                            @php
                                $entry = (int) data_get($item, 'entry', 0);
                                $exit = (int) data_get($item, 'exit', 0);
                                $entryHeight = min(100, max(0, ($entry / $chartCeiling) * 100));
                                $exitHeight = min(100, max(0, ($exit / $chartCeiling) * 100));
                            @endphp
                            <div class="chart-group">
                                <div class="chart-bar" title="Masuk: {{ $entry }} unit" @style(['height' => $entryHeight . '%'])></div>
                                <div class="chart-bar exit" title="Keluar: {{ $exit }} unit" @style(['height' => $exitHeight . '%'])></div>
                            </div>
                        @empty
                            <div style="margin:auto;color:var(--text-muted);font-size:11px;">Belum ada aktivitas hari ini.</div>
                        @endforelse
                    </div>
                    <div class="chart-x">
                        @foreach($activity as $item)
                            <span>{{ data_get($item, 'label', '') }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="chart-note"><i class="bi bi-info-circle"></i> Grafik dihitung berdasarkan waktu masuk dan keluar yang tercatat di Gate Utama.</div>
        </div>

        <div class="dashboard-card">
            <div class="card-header"><div><h3>Komposisi Jenis Kendaraan</h3><p>Distribusi kendaraan yang masih berada di dalam Area Wisata.</p></div></div>
            <div class="type-list">
                @forelse($vehicleComposition as $index => $type)
                    @php
                        $typeName = strtolower((string) data_get($type, 'name', 'lainnya'));
                        $typeTotal = (int) data_get($type, 'total', 0);
                        $percentage = $compositionTotal > 0 ? round(($typeTotal / $compositionTotal) * 100, 1) : 0;
                        if (str_contains($typeName, 'motor')) {
                            $icon = 'bi-bicycle';
                        } elseif (str_contains($typeName, 'bus')) {
                            $icon = 'bi-bus-front';
                        } elseif (str_contains($typeName, 'truk') || str_contains($typeName, 'truck')) {
                            $icon = 'bi-truck';
                        } elseif (str_contains($typeName, 'mobil') || str_contains($typeName, 'car')) {
                            $icon = 'bi-car-front-fill';
                        } else {
                            $icon = 'bi-three-dots';
                        }
                        $color = $colors[$index % count($colors)];
                    @endphp
                    <div class="type-item">
                        <div class="type-header">
                            <div class="type-name"><i class="bi {{ $icon }}"></i>{{ data_get($type, 'name', 'Lainnya') }}</div>
                            <div class="type-count">{{ $typeTotal }} unit ({{ $percentage }}%)</div>
                        </div>
                        <div class="type-progress {{ $color }}"><span @style(['width' => $percentage . '%'])></span></div>
                    </div>
                @empty
                    <div class="empty-state"><i class="bi bi-car-front"></i>Belum ada kendaraan yang sedang berada di area wisata.</div>
                @endforelse
                <div class="type-note">Total {{ $compositionTotal }} unit kendaraan aktif di area wisata.</div>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <div><h3>Kendaraan Sedang Berada di Area</h3><p>Daftar kendaraan yang belum dicatat keluar melalui Gate Utama.</p></div>
                <a href="{{ route('history.index') }}" class="card-link">Semua Kendaraan <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="table-tools">
                <label class="search"><i class="bi bi-search"></i><input type="search" id="vehicleSearch" placeholder="Cari plat, jenis, pengemudi..." aria-label="Cari kendaraan"></label>
                <button type="button" class="filter-button active" data-filter="all">Semua</button>
                <button type="button" class="filter-button" data-filter="mobil">Mobil</button>
                <button type="button" class="filter-button" data-filter="motor">Motor</button>
            </div>
            <div class="table-wrapper">
                <table class="vehicle-table" id="currentVehicleTable">
                    <thead><tr><th>Plat</th><th>Jenis</th><th>Pengemudi</th><th>Tujuan</th><th>Masuk</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($vehicles as $log)
                            @php
                                $plate = $log->plate_number ?? $log->vehicle?->plate_number ?? '-';
                                $typeName = $log->vehicle_type ?? $log->vehicle?->vehicleType?->name ?? '-';
                                $driverName = $log->driver_name ?? $log->vehicle?->driver_name ?? '-';
                                $purpose = $log->purpose ?? 'Area Wisata';
                                $gateIn = $log->gate_in;
                                $status = $log->status ?? 'Di Dalam';
                            @endphp
                            <tr data-plate="{{ strtolower((string) $plate) }}" data-type="{{ strtolower((string) $typeName) }}" data-driver="{{ strtolower((string) $driverName) }}" data-purpose="{{ strtolower((string) $purpose) }}">
                                <td><strong>{{ $plate }}</strong></td>
                                <td>{{ $typeName }}</td>
                                <td>{{ $driverName }}</td>
                                <td>{{ $purpose }}</td>
                                <td>
                                    @if($gateIn)
                                        <span title="{{ \Carbon\Carbon::parse($gateIn)->format('d/m/Y H:i:s') }}">{{ \Carbon\Carbon::parse($gateIn)->format('H:i') }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td><span class="vehicle-status"><span class="vehicle-status-dot"></span>{{ $status }}</span></td>
                            </tr>
                        @empty
                            <tr class="no-vehicles-row"><td colspan="6" class="empty-state"><i class="bi bi-car-front"></i>Belum ada kendaraan yang sedang berada di area wisata.</td></tr>
                        @endforelse
                        <tr id="noSearchResults" style="display:none;"><td colspan="6" class="empty-state"><i class="bi bi-search"></i>Kendaraan yang dicari tidak ditemukan.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="dashboard-card denah-card">
            <div class="card-header"><div class="denah-header"><div class="denah-title"><span class="denah-title-icon"><i class="bi bi-geo-alt"></i></span><h3>Denah Area Wisata Ringkas</h3></div></div></div>
            <div class="denah-description">Ilustrasi ringkas Area Wisata yang menampilkan Gate Utama, jalur kendaraan, dan area rekreasi.</div>
            <div class="denah-preview">
                <div class="denah-line-horizontal"></div><div class="denah-line-vertical"></div>
                <div class="denah-zone zone-left"></div><div class="denah-zone zone-right"></div><div class="denah-zone zone-bottom-left"></div><div class="denah-zone zone-bottom-right"></div>
                <div class="denah-zone-label label-left">Jalur Masuk</div><div class="denah-zone-label label-right">Inti Rekreasi</div><div class="denah-zone-label label-bottom-left">Area Kendaraan</div><div class="denah-zone-label label-bottom-right">Jalur Keluar</div>
                <span class="denah-point point-one"></span><span class="denah-point point-two"></span><span class="denah-point point-three"></span>
                <div class="denah-center"><i class="bi bi-geo-alt-fill"></i><span>Area Wisata</span></div>
                <div class="denah-overlay"><div class="denah-overlay-title"><i class="bi bi-signpost-2-fill"></i>Area Wisata · Gate Utama</div><div class="denah-overlay-subtitle">Ilustrasi tata letak area pemantauan</div></div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('vehicleSearch');
    const table = document.getElementById('currentVehicleTable');

    if (!searchInput || !table) return;

    const filterButtons = document.querySelectorAll('[data-filter]');
    const rows = Array.from(table.querySelectorAll('tbody tr[data-plate]'));
    const noResults = document.getElementById('noSearchResults');
    let currentFilter = 'all';

    function filterVehicles() {
        const keyword = searchInput.value.trim().toLowerCase();
        let visibleCount = 0;

        rows.forEach(function (row) {
            const plate = row.dataset.plate || '';
            const type = row.dataset.type || '';
            const driver = row.dataset.driver || '';
            const purpose = row.dataset.purpose || '';

            const matchesSearch = plate.includes(keyword) ||
                type.includes(keyword) ||
                driver.includes(keyword) ||
                purpose.includes(keyword);

            const matchesType = currentFilter === 'all' ||
                type.includes(currentFilter);

            const visible = matchesSearch && matchesType;
            row.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });

        if (noResults) {
            noResults.style.display = rows.length > 0 && visibleCount === 0 ? '' : 'none';
        }
    }

    searchInput.addEventListener('input', filterVehicles);

    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            currentFilter = button.dataset.filter || 'all';
            filterButtons.forEach(function (item) {
                item.classList.toggle('active', item === button);
            });
            filterVehicles();
        });
    });
});
</script>

@endsection
