@extends('layouts.petugas')

@section('content')

<style>
    /* =========================================================
       HALAMAN
    ========================================================= */

    .gate-entry-page {
        width: 100%;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .entry-header {
        background: #ffffff;
        border: 1px solid #eadfd6;
        border-radius: 16px;
        padding: 26px 30px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        box-shadow: 0 3px 12px rgba(80, 50, 30, 0.04);
    }

    .entry-header-left {
        min-width: 0;
    }

    .entry-title-row {
        display: flex;
        align-items: center;
        gap: 13px;
        flex-wrap: wrap;
    }

    .entry-header-left h1 {
        margin: 0;
        font-size: 32px;
        line-height: 1.2;
        font-weight: 800;
        color: #1f2933;
        letter-spacing: -0.7px;
    }

    .entry-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 13px;
        border-radius: 8px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        color: #c2410c;
        font-size: 12px;
        font-weight: 800;
    }

    .entry-header-left p {
        margin: 9px 0 0;
        color: #718096;
        font-size: 14px;
        line-height: 1.5;
    }

    .officer-info {
        flex-shrink: 0;
        text-align: right;
        font-size: 13px;
        color: #718096;
        line-height: 1.6;
    }

    .officer-info strong {
        color: #374151;
        font-weight: 800;
    }

    .officer-status {
        margin-top: 5px;
        color: #15803d;
        font-size: 12px;
        font-weight: 700;
    }

    .officer-status i {
        font-size: 7px;
        vertical-align: middle;
        margin-right: 4px;
    }

    /* =========================================================
       ALERT
    ========================================================= */

    .entry-alert {
        padding: 13px 16px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .entry-alert-danger {
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca;
    }

    .entry-alert-success {
        color: #166534;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }

    /* =========================================================
       LAYOUT
    ========================================================= */

    .entry-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(330px, 0.9fr);
        gap: 24px;
        align-items: start;
    }

    .entry-card {
        background: #ffffff;
        border: 1px solid #eadfd6;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(80, 50, 30, 0.04);
    }

    /* =========================================================
       FORM
    ========================================================= */

    .entry-form {
        padding: 32px;
    }

    .form-section {
        margin-bottom: 23px;
    }

    .form-label-custom {
        display: block;
        margin-bottom: 9px;
        color: #374151;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .required {
        color: #dc2626;
    }

    .form-control-custom {
        width: 100%;
        height: 46px;
        padding: 0 14px;
        border: 1px solid #d6d3d1;
        border-radius: 9px;
        outline: none;
        background: #fafaf9;
        color: #1f2933;
        font-size: 14px;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .form-control-custom::placeholder {
        color: #a8a29e;
    }

    .form-control-custom:focus {
        border-color: #ea580c;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.10);
    }

    /* =========================================================
       NOMOR PLAT
    ========================================================= */

    .plate-input {
        height: 64px;
        border: 2px solid #ea580c;
        background: #fffaf5;
        font-size: 21px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #1f2933;
    }

    .plate-input:focus {
        border-color: #c2410c;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.10);
    }

    .plate-help {
        margin-top: 7px;
        color: #9a8f87;
        font-size: 11px;
        line-height: 1.5;
    }

    /* =========================================================
       JENIS KENDARAAN
    ========================================================= */

    .vehicle-types {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 9px;
    }

    .vehicle-type-option {
        position: relative;
    }

    .vehicle-type-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .vehicle-type-option label {
        min-height: 43px;
        padding: 0 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: 1px solid #ded8d3;
        border-radius: 8px;
        background: #fafaf9;
        color: #57534e;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .vehicle-type-option label:hover {
        border-color: #fdba74;
        background: #fff7ed;
        color: #c2410c;
    }

    .vehicle-type-option input:checked + label {
        border-color: #ea580c;
        background: #ea580c;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(234, 88, 12, 0.16);
    }

    /* =========================================================
       TWO COLUMN
    ========================================================= */

    .form-two-column {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 17px;
    }

    .readonly-field {
        background: #f5f5f4;
        color: #57534e;
        cursor: not-allowed;
    }

    textarea.form-control-custom {
        height: 82px;
        padding-top: 12px;
        padding-bottom: 12px;
        resize: vertical;
        line-height: 1.5;
    }

    /* =========================================================
       BUTTON
    ========================================================= */

    .save-button {
        width: 100%;
        min-height: 51px;
        margin-top: 3px;
        border: 0;
        border-radius: 10px;
        background: #ea580c;
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 6px 15px rgba(234, 88, 12, 0.20);
        transition: all 0.2s ease;
    }

    .save-button:hover {
        background: #c2410c;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(194, 65, 12, 0.23);
    }

    .save-button:disabled {
        background: #cbd5e1;
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }

    .save-note {
        margin-top: 10px;
        text-align: center;
        color: #a8a29e;
        font-size: 11px;
        line-height: 1.5;
    }

    .save-note strong {
        color: #15803d;
    }

    /* =========================================================
       SIDEBAR - ALUR
    ========================================================= */

    .flow-card {
        padding: 24px;
        background: #fffaf5;
    }

    .flow-title {
        margin: 0 0 7px;
        color: #1f2933;
        font-size: 17px;
        font-weight: 800;
    }

    .flow-subtitle {
        margin: 0 0 16px;
        color: #8a817b;
        font-size: 11px;
        line-height: 1.5;
    }

    .flow-list {
        margin: 0;
        padding-left: 21px;
    }

    .flow-list li {
        margin-bottom: 12px;
        padding-left: 3px;
        color: #57534e;
        font-size: 12px;
        line-height: 1.55;
    }

    .flow-list li:last-child {
        margin-bottom: 0;
    }

    .flow-list li::marker {
        color: #ea580c;
        font-weight: 800;
    }

    .flow-list strong {
        color: #c2410c;
    }

    /* =========================================================
       RECENT
    ========================================================= */

    .recent-card {
        margin-top: 20px;
    }

    .recent-header {
        padding: 19px 21px 15px;
        border-bottom: 1px solid #eee7e1;
    }

    .recent-title {
        margin: 0;
        color: #1f2933;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .recent-subtitle {
        margin: 5px 0 0;
        color: #9a8f87;
        font-size: 11px;
    }

    .recent-list {
        padding: 0 21px;
    }

    .recent-item {
        min-height: 64px;
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid #f0ece8;
    }

    .recent-item:last-child {
        border-bottom: 0;
    }

    .recent-plate {
        color: #1f2933;
        font-family: monospace;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .recent-detail {
        margin-top: 4px;
        color: #8a817b;
        font-size: 11px;
    }

    .recent-right {
        text-align: right;
    }

    .recent-time {
        color: #1f2933;
        font-family: monospace;
        font-size: 13px;
        font-weight: 800;
    }

    .recent-status {
        display: inline-flex;
        margin-top: 4px;
        padding: 3px 8px;
        border-radius: 5px;
        background: #f0fdf4;
        color: #15803d;
        font-size: 9px;
        font-weight: 800;
    }

    .empty-recent {
        padding: 30px 10px;
        text-align: center;
        color: #a8a29e;
        font-size: 11px;
    }

    .empty-recent i {
        display: block;
        margin-bottom: 7px;
        font-size: 22px;
        color: #d6d3d1;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1100px) {
        .entry-layout {
            grid-template-columns: 1fr;
        }

        .entry-header-left h1 {
            font-size: 28px;
        }
    }

    @media (max-width: 760px) {
        .entry-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 21px;
        }

        .officer-info {
            width: 100%;
            text-align: left;
        }

        .entry-header-left h1 {
            font-size: 25px;
        }

        .vehicle-types {
            grid-template-columns: repeat(3, 1fr);
        }

        .form-two-column {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .entry-form {
            padding: 23px;
        }
    }

    @media (max-width: 480px) {
        .entry-header {
            padding: 18px;
            border-radius: 12px;
        }

        .entry-header-left h1 {
            font-size: 21px;
        }

        .entry-header-left p {
            font-size: 11px;
        }

        .entry-title-row {
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }

        .entry-form {
            padding: 18px;
        }

        .entry-card {
            border-radius: 12px;
        }

        .vehicle-types {
            grid-template-columns: repeat(2, 1fr);
        }

        .plate-input {
            height: 57px;
            font-size: 18px;
        }

        .flow-card {
            padding: 18px;
        }

        .recent-header {
            padding-left: 16px;
            padding-right: 16px;
        }

        .recent-list {
            padding-left: 16px;
            padding-right: 16px;
        }
    }
</style>


<div class="gate-entry-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="entry-header">

        <div class="entry-header-left">

            <div class="entry-title-row">

                <h1>
                    Catat Kendaraan Masuk
                </h1>

                <span class="entry-badge">
                    <i class="bi bi-geo-alt-fill"></i>
                    Pos Gate Utama
                </span>

            </div>

            <p>
                Input kendaraan yang tiba di palang pintu Gate Utama
                untuk masuk ke Area Wisata.
            </p>

        </div>


        <div class="officer-info">

            <div>
                Petugas Bertugas:
                <strong>{{ auth()->user()->name }}</strong>
            </div>

            <div class="officer-status">
                <i class="bi bi-circle-fill"></i>
                Jalur Masuk Gate Utama Normal
            </div>

        </div>

    </div>


    {{-- =====================================================
         ALERT
    ====================================================== --}}

    @if ($errors->any())

        <div class="entry-alert entry-alert-danger">

            <i class="bi bi-exclamation-circle me-1"></i>

            {{ $errors->first() }}

        </div>

    @endif


    @if (session('success'))

        <div class="entry-alert entry-alert-success">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}

    <div class="entry-layout">

        {{-- =================================================
             FORM KENDARAAN MASUK
        ================================================== --}}

        <div class="entry-card">

            <form
                action="{{ route('gate-logs.store') }}"
                method="POST"
                class="entry-form"
                id="gateEntryForm"
            >

                @csrf


                {{-- =================================================
                     NOMOR PLAT
                ================================================== --}}

                <div class="form-section">

                    <label
                        for="plate_number"
                        class="form-label-custom"
                    >
                        Nomor Plat Kendaraan
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="plate_number"
                        id="plate_number"
                        class="form-control-custom plate-input"
                        value="{{ old('plate_number') }}"
                        placeholder="CONTOH: S 1234 AB"
                        autocomplete="off"
                        required
                        autofocus
                    >

                    <div class="plate-help">
                        Ketik nomor plat kendaraan secara manual.
                    </div>

                </div>


                {{-- =================================================
                     JENIS KENDARAAN
                ================================================== --}}

                <div class="form-section">

                    <label class="form-label-custom">
                        Jenis Kendaraan
                        <span class="required">*</span>
                    </label>

                    <div class="vehicle-types">

                        @php
                            $types = [
                                'Mobil',
                                'Motor',
                                'Bus',
                                'Minibus',
                                'Truk',
                                'Lainnya',
                            ];
                        @endphp

                        @foreach ($types as $type)

                            @php
                                $typeId = 'type_' . \Illuminate\Support\Str::slug($type);
                            @endphp

                            <div class="vehicle-type-option">

                                <input
                                    type="radio"
                                    name="vehicle_type"
                                    id="{{ $typeId }}"
                                    value="{{ $type }}"
                                    {{ old('vehicle_type') === $type ? 'checked' : '' }}
                                    required
                                >

                                <label for="{{ $typeId }}">
                                    {{ $type }}
                                </label>

                            </div>

                        @endforeach

                    </div>

                    <div class="plate-help">
                        Pilih jenis kendaraan sesuai kendaraan yang masuk.
                    </div>

                </div>


                {{-- =================================================
                     PENGEMUDI + TUJUAN
                ================================================== --}}

                <div class="form-two-column">

                    <div class="form-section">

                        <label
                            for="driver_name"
                            class="form-label-custom"
                        >
                            Nama Pengemudi
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="driver_name"
                            id="driver_name"
                            class="form-control-custom"
                            value="{{ old('driver_name') }}"
                            placeholder="Masukkan nama pengemudi"
                            required
                        >

                    </div>


                    <div class="form-section">

                        <label
                            for="purpose"
                            class="form-label-custom"
                        >
                            Tujuan di Area
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="purpose"
                            id="purpose"
                            class="form-control-custom"
                            value="{{ old('purpose', 'Area Wisata') }}"
                            placeholder="Contoh: Area Wisata"
                            required
                        >

                    </div>

                </div>


                {{-- =================================================
                     WAKTU + PETUGAS
                ================================================== --}}

                <div class="form-two-column">

                    <div class="form-section">

                        <label
                            for="gate_in_display"
                            class="form-label-custom"
                        >
                            Waktu Masuk
                        </label>

                        <input
                            type="text"
                            id="gate_in_display"
                            class="form-control-custom readonly-field"
                            value="{{ now()->format('d/m/Y H:i') }}"
                            readonly
                        >

                        <div class="plate-help">
                            Waktu masuk dicatat otomatis saat data disimpan.
                        </div>

                    </div>


                    <div class="form-section">

                        <label
                            for="officer_display"
                            class="form-label-custom"
                        >
                            Petugas Gate
                        </label>

                        <input
                            type="text"
                            id="officer_display"
                            class="form-control-custom readonly-field"
                            value="{{ auth()->user()->name }}"
                            readonly
                        >

                    </div>

                </div>


                {{-- =================================================
                     CATATAN
                ================================================== --}}

                <div class="form-section">

                    <label
                        for="notes"
                        class="form-label-custom"
                    >
                        Catatan (Opsional)
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        class="form-control-custom"
                        placeholder="Misal: Kunjungan keluarga, rombongan pariwisata, kirim bahan resto..."
                    >{{ old('notes') }}</textarea>

                </div>


                {{-- =================================================
                     BUTTON
                ================================================== --}}

                <button
                    type="submit"
                    class="save-button"
                    id="saveButton"
                >

                    <i class="bi bi-box-arrow-in-right me-1"></i>

                    Simpan Kendaraan Masuk

                </button>

                <div class="save-note">

                    Setelah disimpan, status kendaraan otomatis menjadi

                    <strong>Di Dalam</strong>

                    Area Wisata.

                </div>

            </form>

        </div>


        {{-- =================================================
             SIDEBAR KANAN
        ================================================== --}}

        <div>

            {{-- =================================================
                 ALUR MASUK
            ================================================== --}}

            <div class="entry-card flow-card">

                <h3 class="flow-title">
                    Alur Masuk Gate Utama
                </h3>

                <p class="flow-subtitle">
                    Proses pencatatan kendaraan masuk ke area wisata.
                </p>

                <ol class="flow-list">

                    <li>
                        Kendaraan berhenti di depan gardu Gate Utama.
                    </li>

                    <li>
                        Petugas memeriksa fisik dan plat nomor kendaraan.
                    </li>

                    <li>
                        Petugas mengisi data kendaraan pada formulir.
                    </li>

                    <li>
                        Petugas memilih jenis kendaraan dan mengisi nama pengemudi.
                    </li>

                    <li>
                        Sistem mencatat waktu masuk dan status menjadi
                        <strong>Di Dalam</strong>.
                    </li>

                    <li>
                        Kendaraan diperbolehkan masuk ke Area Wisata.
                    </li>

                </ol>

            </div>


            {{-- =================================================
                 5 KENDARAAN TERAKHIR
            ================================================== --}}

            <div class="entry-card recent-card">

                <div class="recent-header">

                    <h3 class="recent-title">
                        5 Kendaraan Terakhir Masuk
                    </h3>

                    <p class="recent-subtitle">
                        Riwayat kendaraan yang baru dicatat masuk.
                    </p>

                </div>

                <div class="recent-list">

                    @forelse (($recentGateLogs ?? collect())->take(5) as $log)

                        <div class="recent-item">

                            <div>

                                <div class="recent-plate">
                                    {{ $log->plate_number ?? '-' }}
                                </div>

                                <div class="recent-detail">

                                    {{ $log->vehicle_type ?? 'Kendaraan' }}

                                    @if ($log->driver_name)
                                        · {{ $log->driver_name }}
                                    @endif

                                </div>

                            </div>

                            <div class="recent-right">

                                <div class="recent-time">

                                    {{ $log->gate_in
                                        ? $log->gate_in->format('H:i')
                                        : '--:--'
                                    }}

                                </div>

                                <div class="recent-status">

                                    {{ $log->status }}

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="empty-recent">

                            <i class="bi bi-car-front"></i>

                            Belum ada kendaraan masuk.

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const plateInput =
        document.getElementById('plate_number');

    const saveButton =
        document.getElementById('saveButton');

    const gateEntryForm =
        document.getElementById('gateEntryForm');


    /*
    |--------------------------------------------------------------------------
    | CEK FORM
    |--------------------------------------------------------------------------
    */

    if (
        !plateInput ||
        !saveButton ||
        !gateEntryForm
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | NOMOR PLAT OTOMATIS HURUF BESAR
    |--------------------------------------------------------------------------
    */

    plateInput.addEventListener(
        'input',
        function () {

            this.value = this.value
                .toUpperCase()
                .replace(/\s+/g, ' ');

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SUBMIT FORM
    |--------------------------------------------------------------------------
    */

    gateEntryForm.addEventListener(
        'submit',
        function (event) {

            const plate =
                plateInput.value.trim();

            const vehicleType =
                document.querySelector(
                    'input[name="vehicle_type"]:checked'
                );

            const driverName =
                document.getElementById('driver_name').value.trim();


            /*
            | Nomor plat wajib diisi
            */

            if (!plate) {

                event.preventDefault();

                alert(
                    'Silakan masukkan nomor plat kendaraan.'
                );

                plateInput.focus();

                return;

            }


            /*
            | Jenis kendaraan wajib dipilih
            */

            if (!vehicleType) {

                event.preventDefault();

                alert(
                    'Silakan pilih jenis kendaraan.'
                );

                return;

            }


            /*
            | Nama pengemudi wajib diisi
            */

            if (!driverName) {

                event.preventDefault();

                alert(
                    'Silakan masukkan nama pengemudi.'
                );

                document
                    .getElementById('driver_name')
                    .focus();

                return;

            }


            /*
            | Cegah klik dua kali
            */

            saveButton.disabled = true;

            saveButton.innerHTML =
                '<i class="bi bi-arrow-repeat me-1"></i>' +
                ' Menyimpan...';

        }
    );

});

</script>

@endsection