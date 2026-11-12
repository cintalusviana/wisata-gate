
@extends('layouts.app')

@section('title', 'Denah Area Wisata')

@section('content')
@php
    $activeGateLogs = $activeGateLogs ?? collect();
    $vehicleTypeCounts = $vehicleTypeCounts ?? collect();
    $parkingLogsBySlot = $parkingLogsBySlot ?? collect();

    $motorSlots = collect($motorSlots ?? collect(range(1, 24))->map(
        fn ($n) => 'M' . str_pad($n, 2, '0', STR_PAD_LEFT)
    ));

    $carSlots = collect($carSlots ?? collect(range(1, 16))->map(
        fn ($n) => 'R' . str_pad($n, 2, '0', STR_PAD_LEFT)
    ));

    $parkingSlots = collect($parkingSlots ?? $motorSlots->merge($carSlots));

    $totalInside = $totalInside ?? $activeGateLogs->count();
    $occupiedSlots = $parkingLogsBySlot->count();
    $totalParkingSlots = $parkingSlots->count();
    $emptySlots = max(0, $totalParkingSlots - $occupiedSlots);

    $motorCapacity = $motorSlots->count();
    $carCapacity = $carSlots->count();

    $chartColors = [
        '#E67E22', '#16A34A', '#0891B2', '#8B5CF6',
        '#64748B', '#CA8A04', '#0F766E', '#94A3B8'
    ];

    $chartTotal = max($vehicleTypeCounts->sum(), 1);
    $chartRadius = 65;
    $chartCircumference = 2 * pi() * $chartRadius;
    $chartOffset = 0;
@endphp

<div class="wg-page">

    {{-- HEADER --}}
    <div class="wg-page-header">
        <div>
            <div class="wg-breadcrumb">
                <a href="{{ route('dashboard') }}">Monitoring</a>
                <i class="bi bi-chevron-right"></i>
                <span>Denah Area</span>
            </div>

            <div class="wg-heading-row">
                <div>
                    <h1>Denah Area Wisata</h1>
                    <p>Informasi kawasan wisata dan pemantauan aktivitas kendaraan.</p>
                </div>

                <span class="wg-system-status">
                    <span class="wg-status-dot"></span>
                    Sistem Aktif
                </span>
            </div>
        </div>

        <div class="wg-header-actions">
            <button type="button" class="wg-view-button active" id="btnTouristMap">
                <i class="bi bi-map"></i>
                Denah Wisata
            </button>

            <button type="button" class="wg-view-button" id="btnParkingMap">
                <i class="bi bi-p-square"></i>
                Denah Parkir
            </button>
        </div>
    </div>

    {{-- RINGKASAN --}}
    <div class="wg-summary-grid">
        <div class="wg-summary-card">
            <div class="wg-summary-icon orange">
                <i class="bi bi-car-front-fill"></i>
            </div>
            <div class="wg-summary-content">
                <span>Total Kendaraan di Area</span>
                <div class="wg-summary-value">
                    {{ number_format($totalInside) }}
                    <small>kendaraan</small>
                </div>
                <p>Kendaraan yang masih berada di kawasan wisata</p>
            </div>
        </div>

        <div class="wg-summary-card">
            <div class="wg-summary-icon green">
                <i class="bi bi-signpost-2"></i>
            </div>
            <div class="wg-summary-content">
                <span>Gate Terpantau</span>
                <div class="wg-summary-value">1 <small>gate</small></div>
                <p>Gerbang utama kawasan wisata</p>
            </div>
        </div>

        <div class="wg-summary-card">
            <div class="wg-summary-icon teal">
                <i class="bi bi-activity"></i>
            </div>
            <div class="wg-summary-content">
                <span>Status Pemantauan</span>
                <div class="wg-summary-value wg-running">Berjalan</div>
                <p>Pemantauan aktivitas gate sedang aktif</p>
            </div>
        </div>
    </div>

    {{-- DENAH WISATA --}}
    <div id="touristMapView">

        <div class="wg-card wg-map-card">
            <div class="wg-card-header">
                <div>
                    <h5>Denah Kawasan Wisata</h5>
                    <p>Gambaran lokasi fasilitas dan area wisata.</p>
                </div>
                <span class="wg-map-caption">
                    <i class="bi bi-geo-alt"></i>
                    Peta Kawasan
                </span>
            </div>

            <div class="wg-map-container">
                <div class="wg-tourist-map">

                    <div class="map-terrain"></div>

                    {{-- VEGETASI --}}
                    <div class="map-forest forest-a"></div>
                    <div class="map-forest forest-b"></div>
                    <div class="map-forest forest-c"></div>
                    <div class="map-forest forest-d"></div>
                    <div class="map-forest forest-e"></div>

                    <div class="map-trees trees-a">
                        <i class="bi bi-tree-fill"></i>
                        <i class="bi bi-tree-fill"></i>
                        <i class="bi bi-tree-fill"></i>
                        <i class="bi bi-tree-fill"></i>
                    </div>

                    <div class="map-trees trees-b">
                        <i class="bi bi-tree-fill"></i>
                        <i class="bi bi-tree-fill"></i>
                        <i class="bi bi-tree-fill"></i>
                        <i class="bi bi-tree-fill"></i>
                    </div>

                    <div class="map-trees trees-c">
                        <i class="bi bi-tree-fill"></i>
                        <i class="bi bi-tree-fill"></i>
                        <i class="bi bi-tree-fill"></i>
                        <i class="bi bi-tree-fill"></i>
                    </div>

                    {{-- DANAU --}}
                    <div class="map-lake">
                        <div class="lake-shape"></div>
                        <span class="lake-label">DANAU</span>
                    </div>

                    {{-- JALAN --}}
                    <div class="map-roads">
                        <svg viewBox="0 0 1000 520" preserveAspectRatio="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M 0 425 C 145 425,165 345,295 345 S 445 400,540 310 S 675 185,795 205 S 900 270,1000 240"
                                  fill="none" stroke="#b9b9ac" stroke-width="43" stroke-linecap="round"/>
                            <path d="M 0 425 C 145 425,165 345,295 345 S 445 400,540 310 S 675 185,795 205 S 900 270,1000 240"
                                  fill="none" stroke="#f7f5ed" stroke-width="36" stroke-linecap="round"/>

                            <path d="M 510 520 C 510 430,510 380,540 310 S 535 175,490 0"
                                  fill="none" stroke="#b9b9ac" stroke-width="35" stroke-linecap="round"/>
                            <path d="M 510 520 C 510 430,510 380,540 310 S 535 175,490 0"
                                  fill="none" stroke="#f7f5ed" stroke-width="28" stroke-linecap="round"/>

                            <path d="M 295 345 C 275 260,300 190,365 140 S 430 90,455 75"
                                  fill="none" stroke="#b9b9ac" stroke-width="23" stroke-linecap="round"/>
                            <path d="M 295 345 C 275 260,300 190,365 140 S 430 90,455 75"
                                  fill="none" stroke="#f7f5ed" stroke-width="17" stroke-linecap="round"/>

                            <path d="M 795 205 C 825 140,850 100,930 90"
                                  fill="none" stroke="#b9b9ac" stroke-width="21" stroke-linecap="round"/>
                            <path d="M 795 205 C 825 140,850 100,930 90"
                                  fill="none" stroke="#f7f5ed" stroke-width="15" stroke-linecap="round"/>

                            <path d="M 295 345 C 210 310,130 285,70 300"
                                  fill="none" stroke="#c7c5b7" stroke-width="18" stroke-linecap="round"/>
                            <path d="M 295 345 C 210 310,130 285,70 300"
                                  fill="none" stroke="#f7f5ed" stroke-width="12" stroke-linecap="round"/>
                        </svg>
                    </div>

                    {{-- BUKIT --}}
                    <div class="map-hill">
                        <div class="hill-contour contour-one"></div>
                        <div class="hill-contour contour-two"></div>
                        <div class="hill-title">
                            <i class="bi bi-mountains"></i>
                            <span>Bukit Pandang</span>
                        </div>
                    </div>

                    {{-- LOKASI WISATA --}}
                    <div class="map-location forest-location">
                        <div class="map-location-symbol nature-symbol">
                            <i class="bi bi-tree-fill"></i>
                        </div>
                        <span>Hutan Pinus</span>
                    </div>

                    <div class="map-location camping-location">
                        <div class="map-location-symbol camping-symbol">
                            <i class="bi bi-tent"></i>
                        </div>
                        <span>Area Camping</span>
                    </div>

                    <div class="map-location picnic-location">
                        <div class="map-location-symbol picnic-symbol">
                            <i class="bi bi-flower1"></i>
                        </div>
                        <span>Area Piknik</span>
                    </div>

                    <div class="map-location photo-location">
                        <div class="map-location-symbol photo-symbol">
                            <i class="bi bi-camera"></i>
                        </div>
                        <span>Spot Foto</span>
                    </div>

                    {{-- FASILITAS --}}
                    <div class="map-facility toilet-location">
                        <i class="bi bi-restroom"></i>
                        <span>Toilet</span>
                    </div>

                    <div class="map-facility food-location">
                        <i class="bi bi-shop"></i>
                        <span>Kuliner</span>
                    </div>

                    <div class="map-facility gazebo-location">
                        <i class="bi bi-house"></i>
                        <span>Gazebo</span>
                    </div>

                    <div class="map-facility information-location">
                        <i class="bi bi-info-circle"></i>
                        <span>Pos Informasi</span>
                    </div>

                    {{-- PAPAN PETUNJUK PARKIR --}}
                    <div class="map-parking-location">
                        <div class="parking-post"></div>
                        <div class="parking-board">
                            <div class="parking-board-symbol">P</div>
                            <div class="parking-board-text">
                                <strong>PARKIR</strong>
                                <small>MOTOR &amp; MOBIL</small>
                            </div>
                            <span class="parking-arrow">↓</span>
                        </div>
                    </div>

                    {{-- GERBANG UTAMA --}}
                    <div class="map-gate-location">
                        <div class="gate-road-mark"></div>

                        <div class="gate-structure">
                            <div class="gate-pillar gate-pillar-left">
                                <span></span>
                            </div>

                            <div class="gate-main-sign">
                                <small>SELAMAT DATANG DI</small>
                                <strong>WISATA ALAM</strong>
                                <span>GERBANG UTAMA</span>
                            </div>

                            <div class="gate-pillar gate-pillar-right">
                                <span></span>
                            </div>

                            <div class="gate-barrier">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>

                            <div class="gate-barrier-base"></div>
                        </div>

                        <div class="gate-caption">
                            <strong>GATE UTAMA</strong>
                            <small>Pintu Masuk dan Keluar</small>
                        </div>
                    </div>

                    {{-- KOMPAS --}}
                    <div class="map-compass">
                        <span>U</span>
                        <i class="bi bi-compass-fill"></i>
                    </div>

                    <div class="map-bottom-note">
                        <span class="map-scale-line"></span>
                        <span>Ilustrasi kawasan wisata</span>
                    </div>

                </div>
            </div>

            {{-- LEGENDA --}}
            <div class="wg-map-legend">
                <div class="wg-legend-item">
                    <span class="legend-symbol legend-gate"></span>
                    Gate Utama
                </div>
                <div class="wg-legend-item">
                    <span class="legend-symbol legend-facility"></span>
                    Fasilitas
                </div>
                <div class="wg-legend-item">
                    <span class="legend-symbol legend-nature"></span>
                    Area Wisata
                </div>
                <div class="wg-legend-item">
                    <span class="legend-symbol legend-parking"></span>
                    Area Parkir
                </div>
            </div>
        </div>

        {{-- INFORMASI GATE --}}
        <aside class="wg-gate-info-card">
            <div class="wg-side-heading">
                <div>
                    <span class="wg-side-eyebrow">MONITORING GATE</span>
                    <h5>Informasi Gate</h5>
                    <p>Informasi kondisi gerbang utama.</p>
                </div>
                <div class="wg-side-heading-icon">
                    <i class="bi bi-signpost-2"></i>
                </div>
            </div>

            <div class="wg-gate-status-panel">
                <div class="wg-gate-status-top">
                    <span class="wg-muted-label">GERBANG KAWASAN</span>
                    <span class="wg-active-badge"><span></span>Aktif</span>
                </div>
                <h6>Gate Utama</h6>
                <p>Pintu akses kendaraan kawasan wisata</p>
            </div>

            <div class="wg-side-stat">
                <div class="wg-side-stat-icon">
                    <i class="bi bi-car-front"></i>
                </div>
                <div class="wg-side-stat-content">
                    <span>Kendaraan di Dalam</span>
                    <strong>{{ number_format($totalInside) }}</strong>
                    <small>kendaraan saat ini</small>
                </div>
            </div>

            <div class="wg-side-stat">
                <div class="wg-side-stat-icon monitoring-icon">
                    <i class="bi bi-camera-video"></i>
                </div>
                <div class="wg-side-stat-content">
                    <span>Status Pemantauan</span>
                    <strong class="wg-text-green">Berjalan</strong>
                    <small>Aktivitas gate terpantau</small>
                </div>
            </div>

            <div class="wg-side-divider"></div>

            <div class="wg-side-information">
                <div class="wg-information-icon">
                    <i class="bi bi-info-lg"></i>
                </div>
                <p>Data kendaraan diperbarui berdasarkan pencatatan kendaraan masuk dan keluar melalui gate.</p>
            </div>
        </aside>
    </div>

    {{-- DENAH PARKIR --}}
    <div id="parkingMapView" class="d-none">
        <div class="wg-card wg-parking-card">

            <div class="wg-card-header wg-parking-header">
                <div>
                    <h5>Denah Area Parkir</h5>
                    <p>Informasi kapasitas dan ketersediaan petak parkir.</p>
                </div>

                <button type="button" class="wg-add-slot-button" id="btnToggleAddSlots">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Petak
                </button>
            </div>

            {{-- FORM TAMBAH PETAK --}}
            <div id="addSlotsFormWrap" class="wg-add-slots-wrap d-none">
                <div class="wg-add-slots-heading">
                    <div>
                        <h6>Tambah Petak Parkir</h6>
                        <p>Tambahkan kapasitas berdasarkan jenis kendaraan.</p>
                    </div>
                    <button type="button" class="wg-close-form" id="btnCloseAddSlots">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form action="{{ route('denah.slots.store') }}" method="POST" id="addSlotsForm">
                    @csrf

                    <div class="wg-form-grid">
                        <div class="wg-form-group">
                            <label for="vehicle_type">Jenis Kendaraan</label>
                            <select name="vehicle_type" id="vehicle_type" class="form-select" required>
                                <option value="">Pilih jenis kendaraan</option>
                                <option value="motor" {{ old('vehicle_type') === 'motor' ? 'selected' : '' }}>Motor</option>
                                <option value="mobil" {{ old('vehicle_type') === 'mobil' ? 'selected' : '' }}>Mobil</option>
                            </select>
                            @error('vehicle_type')
                                <small class="wg-error-message">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="wg-form-group">
                            <label for="additional_slots">Jumlah Petak</label>
                            <input type="number" name="additional_slots" id="additional_slots"
                                   class="form-control" min="1"
                                   value="{{ old('additional_slots', 1) }}"
                                   placeholder="Masukkan jumlah petak" required>
                            @error('additional_slots')
                                <small class="wg-error-message">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="wg-form-actions">
                        <button type="button" class="wg-cancel-button" id="btnCancelAddSlots">Batal</button>
                        <button type="submit" class="wg-save-button">
                            <i class="bi bi-check2"></i>
                            Simpan Petak
                        </button>
                    </div>
                </form>
            </div>

            {{-- ALERT --}}
            @if (session('success'))
                <div class="wg-alert wg-alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="wg-alert wg-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="wg-alert wg-alert-danger">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        <strong>Periksa kembali data yang dimasukkan.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- RINGKASAN PARKIR --}}
            <div class="wg-parking-summary">
                <div class="wg-parking-summary-item">
                    <div class="wg-parking-summary-icon motor"><i class="bi bi-bicycle"></i></div>
                    <div><span>Kapasitas Motor</span><strong>{{ $motorCapacity }} <small>petak</small></strong></div>
                </div>
                <div class="wg-parking-summary-item">
                    <div class="wg-parking-summary-icon car"><i class="bi bi-car-front"></i></div>
                    <div><span>Kapasitas Mobil</span><strong>{{ $carCapacity }} <small>petak</small></strong></div>
                </div>
                <div class="wg-parking-summary-item">
                    <div class="wg-parking-summary-icon occupied"><i class="bi bi-p-square"></i></div>
                    <div><span>Petak Terisi</span><strong>{{ $occupiedSlots }} <small>petak</small></strong></div>
                </div>
                <div class="wg-parking-summary-item">
                    <div class="wg-parking-summary-icon available"><i class="bi bi-check-circle"></i></div>
                    <div><span>Petak Kosong</span><strong>{{ $emptySlots }} <small>petak</small></strong></div>
                </div>
            </div>

            {{-- LEGENDA PARKIR --}}
            <div class="wg-parking-legend">
                <span class="wg-legend-heading">Keterangan:</span>
                <div><span class="parking-legend-box is-empty"></span>Tersedia</div>
                <div><span class="parking-legend-box is-occupied"></span>Terisi</div>
            </div>

            {{-- AREA MOTOR --}}
            <div class="wg-parking-section">
                <div class="wg-parking-section-heading">
                    <div class="wg-section-title">
                        <div class="wg-section-icon motor"><i class="bi bi-bicycle"></i></div>
                        <div>
                            <h6>Area Parkir Motor</h6>
                            <p>{{ $motorCapacity }} petak parkir</p>
                        </div>
                    </div>
                    <span class="wg-section-code">MOTOR</span>
                </div>

                @if ($motorSlots->isNotEmpty())
                    <div class="wg-parking-grid motor-grid">
                        @foreach ($motorSlots as $slot)
                            @php
                                $vehicle = $parkingLogsBySlot->get($slot);
                                $isOccupied = !empty($vehicle);
                            @endphp
                            <div class="wg-parking-slot {{ $isOccupied ? 'is-occupied' : 'is-empty' }}"
                                 title="{{ $isOccupied ? 'Petak ' . $slot . ' terisi' : 'Petak ' . $slot . ' tersedia' }}">
                                @if ($isOccupied)
                                    <div class="mini-motor">
                                        <span class="motor-wheel front"></span>
                                        <span class="motor-frame"></span>
                                        <span class="motor-wheel rear"></span>
                                    </div>
                                @else
                                    <i class="bi bi-check2"></i>
                                @endif
                                <span>{{ $slot }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="wg-empty-slots">
                        <i class="bi bi-inbox"></i>
                        <span>Belum ada petak parkir motor.</span>
                    </div>
                @endif
            </div>

            {{-- AREA MOBIL --}}
            <div class="wg-parking-section">
                <div class="wg-parking-section-heading">
                    <div class="wg-section-title">
                        <div class="wg-section-icon car"><i class="bi bi-car-front"></i></div>
                        <div>
                            <h6>Area Parkir Mobil</h6>
                            <p>{{ $carCapacity }} petak parkir</p>
                        </div>
                    </div>
                    <span class="wg-section-code">MOBIL</span>
                </div>

                @if ($carSlots->isNotEmpty())
                    <div class="wg-parking-grid car-grid">
                        @foreach ($carSlots as $slot)
                            @php
                                $vehicle = $parkingLogsBySlot->get($slot);
                                $isOccupied = !empty($vehicle);
                            @endphp
                            <div class="wg-parking-slot {{ $isOccupied ? 'is-occupied' : 'is-empty' }}"
                                 title="{{ $isOccupied ? 'Petak ' . $slot . ' terisi' : 'Petak ' . $slot . ' tersedia' }}">
                                @if ($isOccupied)
                                    <div class="mini-car">
                                        <span class="mini-car-glass"></span>
                                        <span class="mini-car-light front-left"></span>
                                        <span class="mini-car-light front-right"></span>
                                        <span class="mini-car-light rear-left"></span>
                                        <span class="mini-car-light rear-right"></span>
                                    </div>
                                @else
                                    <i class="bi bi-check2"></i>
                                @endif
                                <span>{{ $slot }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="wg-empty-slots">
                        <i class="bi bi-inbox"></i>
                        <span>Belum ada petak parkir mobil.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ANALISIS DAN STATUS --}}
    <div class="wg-bottom-grid">

        {{-- KOMPOSISI KENDARAAN --}}
        <div class="wg-card wg-chart-card">
            <div class="wg-card-header">
                <div>
                    <h5>Komposisi Kendaraan</h5>
                    <p>Distribusi jenis kendaraan di kawasan wisata.</p>
                </div>
                <span class="wg-card-label">Statistik</span>
            </div>

            <div class="wg-chart-content">
                <div class="wg-donut-wrapper">
                    <svg viewBox="0 0 180 180" class="wg-donut-chart">
                        <circle cx="90" cy="90" r="{{ $chartRadius }}" fill="none"
                                stroke="#edf0f3" stroke-width="17"/>

                        @foreach ($vehicleTypeCounts as $type => $count)
                            @php
                                $percentage = $chartTotal > 0 ? ($count / $chartTotal) : 0;
                                $dashLength = $percentage * $chartCircumference;
                                $color = $chartColors[$loop->index % count($chartColors)];
                            @endphp

                            @if ($count > 0)
                                <circle cx="90" cy="90" r="{{ $chartRadius }}"
                                        fill="none" stroke="{{ $color }}"
                                        stroke-width="17"
                                        stroke-dasharray="{{ $dashLength }} {{ $chartCircumference }}"
                                        stroke-dashoffset="{{ -$chartOffset }}"
                                        transform="rotate(-90 90 90)"/>
                                @php $chartOffset += $dashLength; @endphp
                            @endif
                        @endforeach
                    </svg>

                    <div class="wg-donut-center">
                        <strong>{{ number_format($totalInside) }}</strong>
                        <span>Kendaraan</span>
                    </div>
                </div>

                <div class="wg-chart-legend">
                    @forelse ($vehicleTypeCounts as $type => $count)
                        @php
                            $percentage = $chartTotal > 0
                                ? round(($count / $chartTotal) * 100, 1)
                                : 0;
                            $color = $chartColors[$loop->index % count($chartColors)];
                        @endphp
                        <div class="wg-chart-legend-item">
                            <div class="wg-chart-legend-name">
                                <span style="background: {{ $color }}"></span>
                                <strong>{{ ucfirst($type) }}</strong>
                            </div>
                            <div class="wg-chart-legend-value">
                                <strong>{{ number_format($count) }}</strong>
                                <small>{{ $percentage }}%</small>
                            </div>
                        </div>
                    @empty
                        <div class="wg-chart-empty">Belum ada data kendaraan.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- STATUS PARKIR --}}
        <div class="wg-card wg-status-card">
            <div class="wg-card-header">
                <div>
                    <h5>Status Area Parkir</h5>
                    <p>Ringkasan kondisi petak parkir saat ini.</p>
                </div>
                <div class="wg-status-header-icon"><i class="bi bi-p-square"></i></div>
            </div>

            <div class="wg-status-total">
                <span>Total Kendaraan di Dalam Area</span>
                <strong>{{ number_format($totalInside) }}</strong>
                <small>Kendaraan sedang berada di kawasan wisata</small>
            </div>

            <div class="wg-status-progress">
                <div class="wg-progress-heading">
                    <span>Penggunaan Petak Parkir</span>
                    <strong>{{ $totalParkingSlots > 0 ? round(($occupiedSlots / $totalParkingSlots) * 100) : 0 }}%</strong>
                </div>
                <div class="wg-progress-track">
                    <div class="wg-progress-fill"
                         style="width: {{ $totalParkingSlots > 0 ? min(100, ($occupiedSlots / $totalParkingSlots) * 100) : 0 }}%"></div>
                </div>
            </div>

            <div class="wg-status-list">
                <div class="wg-status-list-item">
                    <div class="wg-status-list-label">
                        <span class="status-indicator occupied"></span>
                        <span>Petak Terisi</span>
                    </div>
                    <strong>{{ number_format($occupiedSlots) }}</strong>
                </div>
                <div class="wg-status-list-item">
                    <div class="wg-status-list-label">
                        <span class="status-indicator available"></span>
                        <span>Petak Tersedia</span>
                    </div>
                    <strong>{{ number_format($emptySlots) }}</strong>
                </div>
                <div class="wg-status-list-item">
                    <div class="wg-status-list-label">
                        <span class="status-indicator neutral"></span>
                        <span>Total Petak</span>
                    </div>
                    <strong>{{ number_format($totalParkingSlots) }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* =====================================================
   TEMA UTAMA
===================================================== */
.wg-page {
    --wg-primary: #e98227;
    --wg-primary-dark: #c96b17;
    --wg-primary-soft: #fff4e9;
    --wg-green: #15803d;
    --wg-text: #252525;
    --wg-muted: #737373;
    --wg-border: #e5e5e5;
    --wg-card: #ffffff;
    width: 100%;
    padding-bottom: 28px;
    color: var(--wg-text);
}

.wg-page * { box-sizing: border-box; }
.wg-page button { font-family: inherit; }
.wg-page button:focus-visible {
    outline: 3px solid rgba(233,130,39,.2);
    outline-offset: 2px;
}

/* HEADER */
.wg-page-header {
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:18px;
    margin-bottom:22px;
}
.wg-breadcrumb {
    display:flex;
    align-items:center;
    gap:9px;
    margin-bottom:12px;
    font-size:12px;
    color:var(--wg-muted);
}
.wg-breadcrumb a { color:inherit; text-decoration:none; }
.wg-breadcrumb a:hover { color:var(--wg-primary-dark); }
.wg-breadcrumb i { font-size:9px; color:#a3a3a3; }
.wg-heading-row { display:flex; align-items:center; flex-wrap:wrap; gap:14px; }
.wg-heading-row h1 {
    margin:0 0 6px;
    font-size:25px;
    font-weight:750;
    letter-spacing:-.6px;
    color:var(--wg-text);
}
.wg-heading-row p { margin:0; color:var(--wg-muted); font-size:13px; }
.wg-system-status {
    display:inline-flex;
    align-items:center;
    gap:7px;
    padding:7px 11px;
    border:1px solid #bbf7d0;
    border-radius:5px;
    background:#f0fdf4;
    color:#15803d;
    font-size:11px;
    font-weight:650;
}
.wg-status-dot { width:7px; height:7px; border-radius:50%; background:#16a34a; }
.wg-header-actions {
    display:flex;
    gap:5px;
    padding:4px;
    border:1px solid var(--wg-border);
    border-radius:7px;
    background:#f8f8f8;
}
.wg-view-button {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:10px 14px;
    border:0;
    border-radius:5px;
    background:transparent;
    color:#737373;
    font-size:12px;
    font-weight:650;
    cursor:pointer;
    transition:.2s;
}
.wg-view-button:hover { background:#fff; color:var(--wg-primary-dark); }
.wg-view-button.active {
    background:#fff;
    color:var(--wg-primary-dark);
    box-shadow:0 1px 4px rgba(0,0,0,.08);
}

/* RINGKASAN */
.wg-summary-grid {
    display:grid;
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:15px;
    margin-bottom:20px;
}
.wg-summary-card {
    display:flex;
    gap:14px;
    padding:18px;
    border:1px solid var(--wg-border);
    border-radius:8px;
    background:#fff;
    box-shadow:0 2px 7px rgba(0,0,0,.025);
}
.wg-summary-icon {
    display:flex;
    align-items:center;
    justify-content:center;
    width:43px;
    height:43px;
    flex:0 0 43px;
    border-radius:7px;
    font-size:19px;
}
.wg-summary-icon.orange { background:#fff1e4; color:#d97706; }
.wg-summary-icon.green { background:#f0fdf4; color:#15803d; }
.wg-summary-icon.teal { background:#ecfeff; color:#0e7490; }
.wg-summary-content { min-width:0; }
.wg-summary-content>span { display:block; margin-bottom:6px; color:var(--wg-muted); font-size:12px; }
.wg-summary-value { font-size:24px; font-weight:750; line-height:1.2; }
.wg-summary-value small { color:var(--wg-muted); font-size:11px; font-weight:500; }
.wg-summary-value.wg-running { color:var(--wg-green); font-size:20px; }
.wg-summary-content p { margin:6px 0 0; color:#999; font-size:11px; line-height:1.5; }

/* CARD */
.wg-card,.wg-gate-info-card {
    min-width:0;
    border:1px solid var(--wg-border);
    border-radius:8px;
    background:#fff;
    box-shadow:0 2px 7px rgba(0,0,0,.025);
}
.wg-card-header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:15px;
    padding:18px 20px;
    border-bottom:1px solid #eee;
}
.wg-card-header h5 { margin:0 0 5px; font-size:14px; font-weight:750; color:var(--wg-text); }
.wg-card-header p { margin:0; color:var(--wg-muted); font-size:11px; line-height:1.5; }
.wg-map-caption,.wg-card-label {
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:7px 10px;
    border:1px solid #e5e5e5;
    border-radius:5px;
    background:#fafafa;
    color:#737373;
    font-size:10px;
    font-weight:650;
    white-space:nowrap;
}

/* DENAH */
#touristMapView {
    display:grid;
    grid-template-columns:minmax(0,1fr) 275px;
    gap:16px;
    align-items:stretch;
    margin-bottom:20px;
}
.wg-map-card { overflow:hidden; }
.wg-map-container { padding:16px 18px 10px; }
.wg-tourist-map {
    position:relative;
    width:100%;
    min-height:440px;
    overflow:hidden;
    isolation:isolate;
    border:1px solid #d5dfd2;
    border-radius:7px;
    background:#e9eee3;
}
.map-terrain {
    position:absolute;
    inset:0;
    z-index:-5;
    background-color:#e9eee3;
    background-image:
        radial-gradient(ellipse at 12% 15%,rgba(190,211,176,.65),transparent 28%),
        radial-gradient(ellipse at 80% 15%,rgba(207,220,185,.65),transparent 25%),
        radial-gradient(ellipse at 75% 80%,rgba(194,213,180,.55),transparent 27%),
        radial-gradient(ellipse at 18% 75%,rgba(202,218,187,.6),transparent 30%);
}
.map-forest { position:absolute; z-index:-3; background:#cbdcc2; opacity:.9; }
.forest-a { top:-12%; left:-7%; width:34%; height:39%; border-radius:36% 54% 48% 35%; transform:rotate(-12deg); }
.forest-b { top:4%; right:-7%; width:27%; height:34%; border-radius:45% 35% 48% 40%; transform:rotate(15deg); }
.forest-c { bottom:-15%; left:-5%; width:35%; height:37%; border-radius:44% 37% 52% 32%; }
.forest-d { bottom:-10%; right:0; width:34%; height:34%; border-radius:50% 40% 35% 45%; transform:rotate(-10deg); }
.forest-e { top:39%; left:65%; width:16%; height:25%; border-radius:48%; background:#d1dfc7; }
.map-trees {
    position:absolute;
    z-index:-1;
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    width:125px;
    color:#799a70;
    opacity:.55;
}
.map-trees i { font-size:20px; }
.trees-a { top:10%; left:5%; transform:rotate(-12deg); }
.trees-b { top:8%; right:8%; width:90px; }
.trees-c { bottom:12%; right:6%; width:120px; }

/* DANAU */
.map-lake { position:absolute; z-index:0; top:7%; left:33%; width:19%; height:24%; }
.lake-shape {
    position:absolute;
    inset:0;
    border:4px solid rgba(255,255,255,.55);
    border-radius:48% 42% 56% 39% / 42% 57% 40% 55%;
    transform:rotate(-18deg);
    background:radial-gradient(ellipse at 35% 35%,#a7d9e1 0%,#78bacd 55%,#559eb6 100%);
    box-shadow:0 2px 6px rgba(40,100,120,.12);
}
.lake-label {
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    color:#17637b;
    font-size:9px;
    font-weight:800;
    letter-spacing:1px;
}

/* JALAN */
.map-roads { position:absolute; inset:0; z-index:1; pointer-events:none; }
.map-roads svg { width:100%; height:100%; overflow:visible; }

/* BUKIT */
.map-hill {
    position:absolute;
    z-index:1;
    right:8%;
    bottom:22%;
    width:19%;
    height:19%;
    overflow:hidden;
    border:1px solid rgba(105,143,99,.2);
    border-radius:48% 52% 38% 62%;
    background:#c4d5b6;
}
.hill-contour { position:absolute; border:1px solid rgba(108,142,91,.25); border-radius:50%; }
.contour-one { inset:13% 15%; }
.contour-two { inset:25% 27%; }
.hill-title {
    position:absolute;
    inset:0;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-direction:column;
    gap:5px;
    color:#526e49;
    font-size:9px;
    font-weight:700;
    text-align:center;
}
.hill-title i { font-size:21px; }

/* LOKASI */
.map-location {
    position:absolute;
    z-index:4;
    display:flex;
    align-items:center;
    flex-direction:column;
    gap:5px;
    min-width:78px;
    color:#344536;
    font-size:10px;
    font-weight:700;
    text-align:center;
    white-space:nowrap;
}
.map-location-symbol {
    display:flex;
    align-items:center;
    justify-content:center;
    width:35px;
    height:35px;
    border:2px solid #fff;
    border-radius:50%;
    box-shadow:0 2px 7px rgba(15,23,42,.14);
    font-size:16px;
}
.nature-symbol { color:#286a3a; background:#e5f1dc; }
.camping-symbol { color:#80623d; background:#f4ead9; }
.picnic-symbol { color:#3c7952; background:#e4f0e4; }
.photo-symbol { color:#386b8b; background:#e3eff6; }
.forest-location { top:29%; left:8%; }
.camping-location { top:29%; right:8%; }
.picnic-location { bottom:13%; left:17%; }
.photo-location { top:62%; left:41%; }

/* FASILITAS */
.map-facility {
    position:absolute;
    z-index:5;
    display:flex;
    align-items:center;
    gap:6px;
    padding:6px 9px;
    border:1px solid rgba(255,255,255,.9);
    border-radius:4px;
    background:rgba(255,255,255,.96);
    box-shadow:0 2px 5px rgba(15,23,42,.08);
    color:#475569;
    font-size:9px;
    font-weight:650;
    white-space:nowrap;
}
.map-facility i { color:#64748b; font-size:13px; }
.toilet-location { top:44%; left:56%; }
.food-location { bottom:22%; right:34%; }
.gazebo-location { top:12%; right:28%; }
.information-location { top:63%; left:8%; }

/* PAPAN PARKIR */
.map-parking-location {
    position:absolute;
    z-index:6;
    right:4%;
    bottom:4%;
    width:118px;
    height:92px;
    display:flex;
    justify-content:center;
    align-items:flex-start;
}
.parking-post {
    position:absolute;
    bottom:0;
    left:50%;
    width:7px;
    height:47px;
    transform:translateX(-50%);
    border-radius:2px;
    background:linear-gradient(90deg,#737373,#b5b5b5,#777);
    box-shadow:1px 1px 2px rgba(0,0,0,.18);
}
.parking-board {
    position:relative;
    z-index:2;
    display:flex;
    align-items:center;
    gap:7px;
    width:112px;
    padding:8px 7px;
    border:3px solid #fff;
    border-radius:3px;
    background:#315d42;
    box-shadow:0 2px 5px rgba(0,0,0,.2);
    color:#fff;
}
.parking-board-symbol {
    display:flex;
    align-items:center;
    justify-content:center;
    width:28px;
    height:28px;
    border:2px solid #fff;
    border-radius:2px;
    font-size:19px;
    font-weight:800;
}
.parking-board-text { display:flex; flex-direction:column; gap:2px; }
.parking-board-text strong { font-size:9px; letter-spacing:.5px; }
.parking-board-text small { font-size:6px; white-space:nowrap; }
.parking-arrow { margin-left:auto; font-size:15px; font-weight:800; }

/* GERBANG */
.map-gate-location {
    position:absolute;
    z-index:7;
    left:2%;
    bottom:3%;
    width:180px;
    height:112px;
}
.gate-road-mark {
    position:absolute;
    left:0;
    right:0;
    bottom:0;
    height:25px;
    border-top:2px dashed rgba(255,255,255,.8);
    background:#aaa99e;
}
.gate-structure {
    position:absolute;
    top:0;
    left:9px;
    right:9px;
    height:76px;
}
.gate-pillar {
    position:absolute;
    bottom:0;
    width:13px;
    height:56px;
    border:1px solid #a8a8a8;
    background:linear-gradient(90deg,#b7b7b7,#f5f5f5 45%,#c5c5c5);
    box-shadow:1px 2px 3px rgba(0,0,0,.18);
}
.gate-pillar::before {
    content:"";
    position:absolute;
    top:-5px;
    left:-4px;
    width:19px;
    height:7px;
    border:1px solid #aaa;
    background:#e8e8e8;
}
.gate-pillar-left { left:0; }
.gate-pillar-right { right:0; }
.gate-pillar span {
    position:absolute;
    top:14px;
    left:3px;
    width:5px;
    height:32px;
    background:rgba(255,255,255,.55);
}
.gate-main-sign {
    position:absolute;
    top:2px;
    left:11px;
    right:11px;
    height:35px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex-direction:column;
    gap:1px;
    border:2px solid #fff;
    outline:1px solid #9c581e;
    background:linear-gradient(180deg,#e9903e,#c96d20);
    color:#fff;
    box-shadow:0 2px 4px rgba(0,0,0,.2);
}
.gate-main-sign small { font-size:5px; letter-spacing:1px; }
.gate-main-sign strong { font-size:10px; letter-spacing:1px; }
.gate-main-sign span { font-size:5px; letter-spacing:1.2px; }
.gate-barrier {
    position:absolute;
    left:24px;
    bottom:8px;
    width:91px;
    height:6px;
    display:flex;
    overflow:hidden;
    border:1px solid #777;
    border-radius:2px;
    background:#f4f4f4;
    transform:rotate(-3deg);
    transform-origin:left center;
}
.gate-barrier span { flex:1; background:#fff; }
.gate-barrier span:nth-child(odd) { background:#d97706; }
.gate-barrier-base {
    position:absolute;
    bottom:0;
    left:20px;
    width:12px;
    height:11px;
    border-radius:2px;
    background:#555;
}
.gate-caption {
    position:absolute;
    bottom:0;
    left:50%;
    display:flex;
    align-items:center;
    flex-direction:column;
    gap:2px;
    width:125px;
    padding:4px 6px;
    transform:translateX(-50%);
    border:1px solid #e8d5c2;
    border-radius:3px;
    background:rgba(255,255,255,.96);
    box-shadow:0 1px 4px rgba(0,0,0,.1);
}
.gate-caption strong { color:#a85a19; font-size:8px; letter-spacing:.6px; }
.gate-caption small { color:#737373; font-size:7px; }

/* KOMPAS */
.map-compass {
    position:absolute;
    top:14px;
    right:17px;
    z-index:6;
    display:flex;
    align-items:center;
    flex-direction:column;
    gap:2px;
    color:#475569;
    font-size:10px;
    font-weight:800;
}
.map-compass i { font-size:22px; }
.map-bottom-note {
    position:absolute;
    right:16px;
    bottom:13px;
    z-index:5;
    display:flex;
    align-items:center;
    gap:7px;
    color:#64748b;
    font-size:8px;
}
.map-scale-line {
    width:30px;
    height:3px;
    border-top:2px solid #64748b;
    border-left:1px solid #64748b;
    border-right:1px solid #64748b;
}

/* LEGENDA */
.wg-map-legend { display:flex; flex-wrap:wrap; gap:20px; padding:5px 20px 18px; }
.wg-legend-item { display:inline-flex; align-items:center; gap:8px; color:var(--wg-muted); font-size:10px; }
.legend-symbol { width:10px; height:10px; border-radius:3px; }
.legend-gate { background:#d97706; }
.legend-facility { background:#94a3b8; }
.legend-nature { background:#82a876; }
.legend-parking { background:#315d42; }

/* PANEL GATE */
.wg-gate-info-card { display:flex; flex-direction:column; padding:19px; overflow:hidden; }
.wg-side-heading { display:flex; justify-content:space-between; gap:10px; padding-bottom:18px; border-bottom:1px solid #eee; }
.wg-side-eyebrow { display:block; margin-bottom:7px; color:#999; font-size:9px; font-weight:750; letter-spacing:1px; }
.wg-side-heading h5 { margin:0 0 5px; font-size:15px; font-weight:750; }
.wg-side-heading p { margin:0; color:var(--wg-muted); font-size:10px; line-height:1.5; }
.wg-side-heading-icon,.wg-status-header-icon {
    display:flex; align-items:center; justify-content:center;
    width:39px; height:39px; flex:0 0 39px;
    border-radius:6px; background:#fff1e4; color:#d97706; font-size:17px;
}
.wg-gate-status-panel { margin:17px 0; padding:14px; border:1px solid #e5e5e5; border-radius:6px; background:#fafafa; }
.wg-gate-status-top { display:flex; justify-content:space-between; gap:7px; margin-bottom:10px; }
.wg-muted-label { color:#999; font-size:9px; font-weight:700; }
.wg-active-badge { display:inline-flex; align-items:center; gap:5px; padding:5px 8px; border-radius:4px; background:#dcfce7; color:#15803d; font-size:9px; font-weight:700; }
.wg-active-badge span { width:6px; height:6px; border-radius:50%; background:#16a34a; }
.wg-gate-status-panel h6 { margin:0 0 5px; font-size:14px; font-weight:750; }
.wg-gate-status-panel p { margin:0; color:var(--wg-muted); font-size:10px; }
.wg-side-stat { display:flex; align-items:center; gap:11px; padding:14px 0; border-bottom:1px solid #eee; }
.wg-side-stat-icon { display:flex; align-items:center; justify-content:center; width:37px; height:37px; flex:0 0 37px; border-radius:6px; background:#fff1e4; color:#d97706; font-size:16px; }
.wg-side-stat-icon.monitoring-icon { background:#f0fdf4; color:#15803d; }
.wg-side-stat-content { display:flex; flex-direction:column; gap:3px; }
.wg-side-stat-content>span { color:var(--wg-muted); font-size:10px; }
.wg-side-stat-content strong { font-size:17px; font-weight:750; }
.wg-side-stat-content small { color:#999; font-size:9px; }
.wg-side-stat-content strong.wg-text-green { color:#15803d; font-size:13px; }
.wg-side-divider { height:1px; margin:5px 0 15px; background:#eee; }
.wg-side-information { display:flex; gap:9px; margin-top:auto; padding:12px; border:1px solid #e5e5e5; border-radius:5px; background:#fafafa; }
.wg-information-icon { display:flex; align-items:center; justify-content:center; width:21px; height:21px; flex:0 0 21px; border-radius:50%; background:#eee; color:#555; font-size:11px; }
.wg-side-information p { margin:0; color:#737373; font-size:10px; line-height:1.65; }

/* PARKIR */
.wg-parking-card { margin-bottom:20px; padding-bottom:12px; overflow:hidden; }
.wg-add-slot-button,.wg-save-button {
    display:inline-flex; align-items:center; justify-content:center; gap:7px;
    padding:9px 13px; border:0; border-radius:5px;
    background:#e98227; color:#fff; font-size:11px; font-weight:650; cursor:pointer;
    transition:.2s;
}
.wg-add-slot-button:hover,.wg-save-button:hover { background:#c96b17; color:#fff; }
.wg-add-slots-wrap { margin:18px 20px; padding:17px; border:1px solid #f2d5ba; border-radius:7px; background:#fffaf5; }
.wg-add-slots-heading { display:flex; justify-content:space-between; gap:12px; margin-bottom:15px; }
.wg-add-slots-heading h6 { margin:0 0 5px; font-size:13px; font-weight:750; }
.wg-add-slots-heading p { margin:0; color:var(--wg-muted); font-size:10px; }
.wg-close-form { width:29px; height:29px; border:1px solid #e5e5e5; border-radius:5px; background:#fff; color:#737373; cursor:pointer; }
.wg-form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
.wg-form-group label { display:block; margin-bottom:7px; color:#475569; font-size:11px; font-weight:650; }
.wg-form-group .form-control,.wg-form-group .form-select { min-height:39px; border:1px solid #dedede; border-radius:5px; color:var(--wg-text); font-size:11px; box-shadow:none; }
.wg-form-group .form-control:focus,.wg-form-group .form-select:focus { border-color:#e98227; box-shadow:0 0 0 3px rgba(233,130,39,.12); }
.wg-error-message { display:block; margin-top:5px; color:#dc2626; font-size:10px; }
.wg-form-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:16px; }
.wg-cancel-button { padding:8px 13px; border:1px solid #dedede; border-radius:5px; background:#fff; color:#555; font-size:11px; font-weight:600; cursor:pointer; }
.wg-save-button { padding:8px 13px; }
.wg-alert { display:flex; align-items:flex-start; gap:9px; margin:15px 20px; padding:11px 13px; border-radius:5px; font-size:11px; }
.wg-alert-success { border:1px solid #bbf7d0; background:#f0fdf4; color:#15803d; }
.wg-alert-danger { border:1px solid #fecaca; background:#fef2f2; color:#b91c1c; }
.wg-alert ul { margin:5px 0 0; padding-left:17px; }

/* RINGKASAN PARKIR */
.wg-parking-summary { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; margin:20px; }
.wg-parking-summary-item { display:flex; align-items:center; gap:10px; min-width:0; padding:13px; border:1px solid #eee; border-radius:6px; background:#fff; }
.wg-parking-summary-icon { display:flex; align-items:center; justify-content:center; width:36px; height:36px; flex:0 0 36px; border-radius:5px; font-size:15px; }
.wg-parking-summary-icon.motor { background:#fff1e4; color:#d97706; }
.wg-parking-summary-icon.car { background:#f1f5f9; color:#475569; }
.wg-parking-summary-icon.occupied { background:#fef2f2; color:#dc2626; }
.wg-parking-summary-icon.available { background:#f0fdf4; color:#15803d; }
.wg-parking-summary-item>div:last-child { display:flex; flex-direction:column; gap:4px; min-width:0; }
.wg-parking-summary-item span { color:var(--wg-muted); font-size:10px; }
.wg-parking-summary-item strong { font-size:17px; font-weight:750; }
.wg-parking-summary-item strong small { color:var(--wg-muted); font-size:9px; font-weight:500; }
.wg-parking-legend { display:flex; align-items:center; flex-wrap:wrap; gap:17px; margin:0 20px 18px; padding:11px 13px; border:1px solid #eee; border-radius:5px; background:#fafafa; }
.wg-legend-heading { color:#475569; font-size:10px; font-weight:700; }
.wg-parking-legend>div { display:flex; align-items:center; gap:7px; color:#737373; font-size:10px; }
.parking-legend-box { width:12px; height:12px; border:1px solid; border-radius:3px; }
.parking-legend-box.is-empty { border-color:#bbf7d0; background:#f0fdf4; }
.parking-legend-box.is-occupied { border-color:#fecaca; background:#fef2f2; }

/* PETAK PARKIR */
.wg-parking-section { margin:0 20px 20px; padding:17px; border:1px solid #e8edf2; border-radius:7px; background:#fff; }
.wg-parking-section-heading { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; }
.wg-section-title { display:flex; align-items:center; gap:10px; }
.wg-section-icon { display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:5px; font-size:16px; }
.wg-section-icon.motor { background:#fff1e4; color:#d97706; }
.wg-section-icon.car { background:#f1f5f9; color:#475569; }
.wg-section-title h6 { margin:0 0 4px; font-size:12px; font-weight:750; }
.wg-section-title p { margin:0; color:var(--wg-muted); font-size:10px; }
.wg-section-code { padding:5px 8px; border:1px solid #e5e5e5; border-radius:4px; background:#fafafa; color:#737373; font-size:9px; font-weight:700; letter-spacing:.5px; }
.wg-parking-grid { display:grid; gap:9px; }
.motor-grid,.car-grid { grid-template-columns:repeat(8,minmax(0,1fr)); }
.wg-parking-slot {
    display:flex; align-items:center; justify-content:center; flex-direction:column; gap:7px;
    min-height:75px; border:1px solid; border-radius:5px; transition:.15s;
}
.wg-parking-slot>i { font-size:17px; }
.wg-parking-slot>span { font-size:10px; font-weight:750; letter-spacing:.2px; }
.wg-parking-slot.is-empty { border-color:#ccebd4; background:#f3faf4; color:#15803d; }
.wg-parking-slot.is-occupied { border-color:#f0c7c2; background:#fff7f5; color:#a94438; }
.wg-parking-slot:hover { transform:translateY(-2px); box-shadow:0 3px 8px rgba(15,23,42,.08); }
.wg-empty-slots { display:flex; align-items:center; justify-content:center; gap:9px; min-height:75px; border:1px dashed #cbd5e1; border-radius:6px; color:#999; font-size:11px; }

/* GAMBAR MOBIL DAN MOTOR */
.mini-car {
    position:relative;
    width:27px;
    height:43px;
    border:1px solid #773c2e;
    border-radius:9px 9px 6px 6px;
    background:linear-gradient(90deg,#9c493c,#c86b59 45%,#a64c40);
    box-shadow:inset 2px 0 2px rgba(255,255,255,.22),1px 2px 3px rgba(0,0,0,.15);
}
.mini-car::before,.mini-car::after {
    content:"";
    position:absolute;
    left:-3px;
    width:4px;
    height:9px;
    border-radius:2px;
    background:#343434;
    box-shadow:29px 0 #343434;
}
.mini-car::before { top:7px; }
.mini-car::after { bottom:7px; }
.mini-car-glass {
    position:absolute;
    top:9px;
    left:4px;
    width:17px;
    height:23px;
    border:1px solid rgba(255,255,255,.55);
    border-radius:5px;
    background:linear-gradient(90deg,#9cb5bd,#d6e0df 50%,#8da8b1);
}
.mini-car-light { position:absolute; z-index:2; width:5px; height:3px; border-radius:2px; }
.mini-car-light.front-left,.mini-car-light.front-right { top:1px; background:#fff0b5; }
.mini-car-light.front-left { left:3px; }
.mini-car-light.front-right { right:3px; }
.mini-car-light.rear-left,.mini-car-light.rear-right { bottom:1px; background:#f5a09a; }
.mini-car-light.rear-left { left:3px; }
.mini-car-light.rear-right { right:3px; }

.mini-motor {
    position:relative;
    width:28px;
    height:39px;
}
.motor-wheel {
    position:absolute;
    left:9px;
    width:10px;
    height:13px;
    border:2px solid #333;
    border-radius:50%;
    background:#777;
}
.motor-wheel.front { top:0; }
.motor-wheel.rear { bottom:0; }
.motor-frame {
    position:absolute;
    top:10px;
    left:6px;
    width:16px;
    height:20px;
    border:3px solid #c16a37;
    border-radius:7px 7px 5px 5px;
    background:linear-gradient(90deg,#a94e26,#e58a49);
}

/* ANALISIS */
.wg-bottom-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
.wg-chart-card,.wg-status-card { min-width:0; overflow:hidden; }
.wg-chart-content { display:flex; align-items:center; justify-content:center; gap:30px; min-height:230px; padding:22px; }
.wg-donut-wrapper { position:relative; width:165px; height:165px; flex:0 0 165px; }
.wg-donut-chart { width:100%; height:100%; }
.wg-donut-center { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; flex-direction:column; pointer-events:none; }
.wg-donut-center strong { font-size:27px; font-weight:800; }
.wg-donut-center span { color:var(--wg-muted); font-size:10px; }
.wg-chart-legend { display:flex; flex-direction:column; gap:13px; min-width:120px; flex:1; }
.wg-chart-legend-item { display:flex; align-items:center; justify-content:space-between; gap:12px; }
.wg-chart-legend-name { display:flex; align-items:center; gap:8px; min-width:0; }
.wg-chart-legend-name span { width:9px; height:9px; flex:0 0 9px; border-radius:3px; }
.wg-chart-legend-name strong { color:#475569; font-size:10px; font-weight:600; }
.wg-chart-legend-value { display:flex; align-items:center; gap:8px; }
.wg-chart-legend-value strong { font-size:11px; }
.wg-chart-legend-value small { color:var(--wg-muted); font-size:9px; }
.wg-chart-empty { color:#999; font-size:11px; }
.wg-status-header-icon { width:35px; height:35px; flex:0 0 35px; }
.wg-status-total { display:flex; flex-direction:column; padding:19px 20px 14px; }
.wg-status-total span { color:var(--wg-muted); font-size:11px; }
.wg-status-total strong { margin:4px 0; font-size:30px; font-weight:800; }
.wg-status-total small { color:#999; font-size:10px; }
.wg-status-progress { padding:0 20px 18px; }
.wg-progress-heading { display:flex; justify-content:space-between; margin-bottom:8px; }
.wg-progress-heading span { color:var(--wg-muted); font-size:10px; }
.wg-progress-heading strong { color:var(--wg-primary-dark); font-size:11px; }
.wg-progress-track { width:100%; height:7px; overflow:hidden; border-radius:10px; background:#f1f5f9; }
.wg-progress-fill { height:100%; border-radius:inherit; background:linear-gradient(90deg,#f3a45e,#d97706); transition:width .3s; }
.wg-status-list { padding:0 20px 17px; }
.wg-status-list-item { display:flex; align-items:center; justify-content:space-between; padding:11px 0; border-top:1px solid #eee; }
.wg-status-list-label { display:flex; align-items:center; gap:9px; color:#737373; font-size:10px; }
.wg-status-list-item>strong { font-size:12px; }
.status-indicator { width:8px; height:8px; border-radius:3px; }
.status-indicator.occupied { background:#dc2626; }
.status-indicator.available { background:#16a34a; }
.status-indicator.neutral { background:#94a3b8; }

/* RESPONSIVE */
@media(max-width:1100px) {
    #touristMapView { grid-template-columns:minmax(0,1fr) 245px; }
    .wg-chart-content { gap:17px; }
    .wg-donut-wrapper { width:140px; height:140px; flex-basis:140px; }
}
@media(max-width:900px) {
    #touristMapView { grid-template-columns:1fr; }
    .wg-gate-info-card { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; }
    .wg-side-heading,.wg-side-information { grid-column:1/-1; }
    .wg-gate-status-panel { margin:0; }
    .wg-side-stat { border:1px solid #eee; border-radius:5px; padding:12px; }
    .wg-side-divider { display:none; }
    .wg-side-information { margin-top:0; }
    .wg-parking-summary { grid-template-columns:repeat(2,minmax(0,1fr)); }
}
@media(max-width:700px) {
    .wg-page-header { align-items:flex-start; }
    .wg-heading-row h1 { font-size:21px; }
    .wg-summary-grid { grid-template-columns:1fr; gap:10px; }
    .wg-summary-card { padding:15px; }
    .wg-header-actions { width:100%; }
    .wg-view-button { flex:1; padding:10px 8px; font-size:11px; }
    .wg-tourist-map { min-height:370px; }
    .wg-map-container { padding:12px; }
    .wg-bottom-grid { grid-template-columns:1fr; }
    .wg-chart-content { justify-content:flex-start; }
    .motor-grid,.car-grid { grid-template-columns:repeat(4,minmax(0,1fr)); }
}
@media(max-width:480px) {
    .wg-heading-row { align-items:flex-start; flex-direction:column; }
    .wg-heading-row h1 { font-size:19px; }
    .wg-heading-row p { font-size:11px; line-height:1.6; }
    .wg-card-header { padding:14px; }
    .wg-map-caption,.wg-card-label { display:none; }
    .wg-map-container { padding:9px; }
    .wg-tourist-map { min-height:315px; }
    .map-location { min-width:58px; font-size:8px; }
    .map-location-symbol { width:27px; height:27px; font-size:12px; }
    .map-facility { gap:4px; padding:4px 5px; font-size:7px; }
    .map-facility i { font-size:10px; }
    .map-gate-location { left:1%; bottom:2%; transform:scale(.82); transform-origin:bottom left; }
    .map-parking-location { right:1%; bottom:2%; transform:scale(.82); transform-origin:bottom right; }
    .map-compass { top:8px; right:9px; }
    .map-compass i { font-size:17px; }
    .map-bottom-note { display:none; }
    .map-hill { right:5%; bottom:25%; }
    .hill-title { font-size:7px; }
    .hill-title i { font-size:15px; }
    .wg-map-legend { gap:11px; padding:5px 13px 14px; }
    .wg-legend-item { font-size:9px; }
    .wg-gate-info-card { grid-template-columns:1fr; padding:15px; }
    .wg-side-heading,.wg-side-information { grid-column:auto; }
    .wg-parking-summary { gap:8px; margin:13px; }
    .wg-parking-summary-item { gap:7px; padding:10px 8px; }
    .wg-parking-summary-icon { width:29px; height:29px; flex-basis:29px; font-size:13px; }
    .wg-parking-summary-item span { font-size:9px; }
    .wg-parking-summary-item strong { font-size:15px; }
    .wg-parking-legend { gap:12px; margin:0 13px 14px; }
    .wg-parking-section { margin:0 13px 15px; padding:12px; }
    .wg-parking-slot { min-height:65px; gap:5px; }
    .wg-parking-slot>span { font-size:9px; }
    .wg-form-grid { grid-template-columns:1fr; }
    .wg-add-slots-wrap { margin:13px; padding:13px; }
    .wg-parking-header { align-items:flex-start; }
    .wg-add-slot-button { padding:8px 9px; font-size:10px; white-space:nowrap; }
    .wg-chart-content { flex-direction:column; gap:20px; }
    .wg-chart-legend { width:100%; }
    .wg-donut-wrapper { width:150px; height:150px; flex-basis:150px; }
}

/* DARK MODE */
body.dark-mode .wg-page,[data-theme="dark"] .wg-page {
    --wg-text:#e5e7eb;
    --wg-muted:#9ca3af;
    --wg-border:#374151;
}
body.dark-mode .wg-summary-card,
body.dark-mode .wg-card,
body.dark-mode .wg-gate-info-card,
[data-theme="dark"] .wg-summary-card,
[data-theme="dark"] .wg-card,
[data-theme="dark"] .wg-gate-info-card {
    background:#1f2937;
    border-color:#374151;
}
body.dark-mode .wg-heading-row h1,
body.dark-mode .wg-summary-value,
body.dark-mode .wg-card-header h5,
body.dark-mode .wg-side-heading h5,
body.dark-mode .wg-side-stat-content strong,
body.dark-mode .wg-parking-summary-item strong,
body.dark-mode .wg-section-title h6,
body.dark-mode .wg-donut-center strong,
body.dark-mode .wg-status-total strong,
body.dark-mode .wg-status-list-item>strong,
[data-theme="dark"] .wg-heading-row h1,
[data-theme="dark"] .wg-summary-value,
[data-theme="dark"] .wg-card-header h5,
[data-theme="dark"] .wg-side-heading h5,
[data-theme="dark"] .wg-side-stat-content strong,
[data-theme="dark"] .wg-parking-summary-item strong,
[data-theme="dark"] .wg-section-title h6,
[data-theme="dark"] .wg-donut-center strong,
[data-theme="dark"] .wg-status-total strong,
[data-theme="dark"] .wg-status-list-item>strong {
    color:#e5e7eb;
}
body.dark-mode .wg-card-header,
body.dark-mode .wg-side-heading,
body.dark-mode .wg-side-stat,
body.dark-mode .wg-status-list-item,
[data-theme="dark"] .wg-card-header,
[data-theme="dark"] .wg-side-heading,
[data-theme="dark"] .wg-side-stat,
[data-theme="dark"] .wg-status-list-item {
    border-color:#374151;
}
body.dark-mode .wg-header-actions,
body.dark-mode .wg-gate-status-panel,
body.dark-mode .wg-side-information,
body.dark-mode .wg-parking-section,
body.dark-mode .wg-parking-summary-item,
body.dark-mode .wg-parking-legend,
body.dark-mode .wg-section-code,
[data-theme="dark"] .wg-header-actions,
[data-theme="dark"] .wg-gate-status-panel,
[data-theme="dark"] .wg-side-information,
[data-theme="dark"] .wg-parking-section,
[data-theme="dark"] .wg-parking-summary-item,
[data-theme="dark"] .wg-parking-legend,
[data-theme="dark"] .wg-section-code {
    background:#111827;
    border-color:#374151;
}
body.dark-mode .wg-view-button.active,
[data-theme="dark"] .wg-view-button.active {
    background:#374151;
    color:#fdba74;
}
body.dark-mode .wg-parking-summary-item span,
body.dark-mode .wg-side-stat-content>span,
body.dark-mode .wg-side-stat-content small,
body.dark-mode .wg-status-list-label,
body.dark-mode .wg-chart-legend-name strong,
[data-theme="dark"] .wg-parking-summary-item span,
[data-theme="dark"] .wg-side-stat-content>span,
[data-theme="dark"] .wg-side-stat-content small,
[data-theme="dark"] .wg-status-list-label,
[data-theme="dark"] .wg-chart-legend-name strong {
    color:#cbd5e1;
}
body.dark-mode .wg-add-slots-wrap,
[data-theme="dark"] .wg-add-slots-wrap {
    background:#29231e;
    border-color:#59402c;
}
body.dark-mode .wg-form-group .form-control,
body.dark-mode .wg-form-group .form-select,
[data-theme="dark"] .wg-form-group .form-control,
[data-theme="dark"] .wg-form-group .form-select {
    background:#111827;
    border-color:#4b5563;
    color:#e5e7eb;
}
body.dark-mode .wg-cancel-button,
body.dark-mode .wg-close-form,
[data-theme="dark"] .wg-cancel-button,
[data-theme="dark"] .wg-close-form {
    background:#374151;
    border-color:#4b5563;
    color:#e5e7eb;
}
body.dark-mode .wg-progress-track,
[data-theme="dark"] .wg-progress-track { background:#374151; }
body.dark-mode .wg-side-information p,
body.dark-mode .wg-status-total span,
body.dark-mode .wg-progress-heading span,
[data-theme="dark"] .wg-side-information p,
[data-theme="dark"] .wg-status-total span,
[data-theme="dark"] .wg-progress-heading span { color:#9ca3af; }
body.dark-mode .wg-donut-chart circle:first-child,
[data-theme="dark"] .wg-donut-chart circle:first-child { stroke:#374151; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const touristMapView = document.getElementById('touristMapView');
    const parkingMapView = document.getElementById('parkingMapView');

    const btnTouristMap = document.getElementById('btnTouristMap');
    const btnParkingMap = document.getElementById('btnParkingMap');

    const btnToggleAddSlots = document.getElementById('btnToggleAddSlots');
    const addSlotsFormWrap = document.getElementById('addSlotsFormWrap');

    const btnCloseAddSlots = document.getElementById('btnCloseAddSlots');
    const btnCancelAddSlots = document.getElementById('btnCancelAddSlots');

    const vehicleTypeInput = document.getElementById('vehicle_type');

    function showTouristView() {
        touristMapView.classList.remove('d-none');
        parkingMapView.classList.add('d-none');

        btnTouristMap.classList.add('active');
        btnParkingMap.classList.remove('active');
    }

    function showParkingView() {
        touristMapView.classList.add('d-none');
        parkingMapView.classList.remove('d-none');

        btnTouristMap.classList.remove('active');
        btnParkingMap.classList.add('active');
    }

    function openAddSlotsForm() {
        addSlotsFormWrap.classList.remove('d-none');

        if (vehicleTypeInput) {
            vehicleTypeInput.focus();
        }
    }

    function closeAddSlotsForm() {
        addSlotsFormWrap.classList.add('d-none');
    }

    if (btnTouristMap && btnParkingMap) {
        btnTouristMap.addEventListener('click', showTouristView);
        btnParkingMap.addEventListener('click', showParkingView);
    }

    if (btnToggleAddSlots && addSlotsFormWrap) {
        btnToggleAddSlots.addEventListener('click', function () {
            if (addSlotsFormWrap.classList.contains('d-none')) {
                openAddSlotsForm();
            } else {
                closeAddSlotsForm();
            }
        });
    }

    if (btnCloseAddSlots) {
        btnCloseAddSlots.addEventListener('click', closeAddSlotsForm);
    }

    if (btnCancelAddSlots) {
        btnCancelAddSlots.addEventListener('click', closeAddSlotsForm);
    }

    @if ($errors->any())
        showParkingView();
        openAddSlotsForm();
    @endif
});
</script>
@endsection
