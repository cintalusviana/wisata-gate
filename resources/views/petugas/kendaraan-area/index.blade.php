@extends('layouts.petugas')

@section('title', 'Kendaraan di Area')

@section('content')

<style>
    /* =========================================================
       FONT
    ========================================================= */

    .petugas-area-page {
        font-family: 'Inter', sans-serif;
    }


    /* =========================================================
       HEADER HALAMAN
    ========================================================= */

    .area-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .area-page-title {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #111827;
    }

    .area-page-description {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 14px;
    }


    /* =========================================================
       BUTTON CATAT KENDARAAN MASUK
       ORANGE
    ========================================================= */

    .btn-tambah-kendaraan {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 17px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        background: #EA580C;
        border: 1px solid #EA580C;
        transition: .2s ease;
        white-space: nowrap;
    }

    .btn-tambah-kendaraan:hover {
        color: #ffffff;
        background: #C2410C;
        border-color: #C2410C;
    }


    /* =========================================================
       ALERT
    ========================================================= */

    .area-alert {
        border-radius: 10px;
        padding: 13px 16px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .area-alert-success {
        color: #166534;
        background: #dcfce7;
        border: 1px solid #bbf7d0;
    }

    .area-alert-error {
        color: #991b1b;
        background: #fee2e2;
        border: 1px solid #fecaca;
    }


    /* =========================================================
       FORM EDIT
       ORANGE
    ========================================================= */

    .edit-form-card {
        background: #ffffff;
        border: 1px solid #fed7aa;
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 14px rgba(234, 88, 12, 0.08);
    }

    .edit-form-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 22px;
    }

    .edit-form-title-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .edit-form-icon {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #ffedd5;
        color: #ea580c;
        font-size: 18px;
        flex-shrink: 0;
    }

    .edit-form-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .edit-form-description {
        margin: 4px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .edit-form-close {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        text-decoration: none;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: .2s ease;
    }

    .edit-form-close:hover {
        color: #ea580c;
        background: #fff7ed;
        border-color: #fed7aa;
    }

    .edit-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .edit-form-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .edit-form-group.full {
        grid-column: 1 / -1;
    }

    .edit-form-label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .edit-form-control {
        width: 100%;
        min-height: 43px;
        padding: 10px 13px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #ffffff;
        color: #111827;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        outline: none;
        transition: .2s ease;
    }

    .edit-form-control:focus {
        border-color: #ea580c;
        box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
    }

    textarea.edit-form-control {
        min-height: 95px;
        resize: vertical;
    }

    .edit-form-error {
        font-size: 12px;
        color: #dc2626;
    }

    .edit-form-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 22px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .edit-btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 16px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #475569;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: .2s ease;
    }

    .edit-btn-cancel:hover {
        color: #334155;
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .edit-btn-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 40px;
        padding: 9px 17px;
        border-radius: 8px;
        border: 1px solid #ea580c;
        background: #ea580c;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s ease;
    }

    .edit-btn-save:hover {
        background: #c2410c;
        border-color: #c2410c;
    }


    /* =========================================================
       STAT CARD
       ICON ORANGE
    ========================================================= */

    .area-stat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 20px;
    }

    .area-stat-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #EA580C;
        color: #ffffff;
        font-size: 19px;
        flex-shrink: 0;
    }

    .area-stat-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 3px;
    }

    .area-stat-value {
        font-size: 21px;
        font-weight: 700;
        color: #111827;
    }


    /* =========================================================
       TABLE CARD
       WARNA ASLI TETAP
    ========================================================= */

    .area-table-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
    }

    .area-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 19px 22px;
        border-bottom: 1px solid #e5e7eb;
    }

    .area-table-title {
        margin: 0;
        font-size: 17px;
        font-weight: 700;
        color: #111827;
    }

    .area-table-description {
        margin: 4px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .area-count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 28px;
        padding: 0 9px;
        border-radius: 999px;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
    }

    .area-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .area-table {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
    }

    .area-table thead th {
        padding: 13px 16px;
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        border-bottom: 1px solid #e5e7eb;
        white-space: nowrap;
    }

    .area-table tbody td {
        padding: 15px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #374151;
        font-size: 13px;
        vertical-align: middle;
    }

    .area-table tbody tr:last-child td {
        border-bottom: none;
    }

    .area-table tbody tr:hover {
        background: #fafafa;
    }

    .vehicle-plate {
        font-weight: 700;
        color: #111827;
        white-space: nowrap;
    }

    .vehicle-type {
        color: #475569;
    }

    .vehicle-driver {
        font-weight: 500;
        color: #334155;
    }

    .vehicle-purpose {
        color: #475569;
        max-width: 180px;
    }

    .vehicle-time {
        color: #64748b;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 999px;
        background: #dcfce7;
        color: #15803d;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-badge i {
        font-size: 8px;
    }


    /* =========================================================
       AKSI
       WARNA ASLI TETAP
    ========================================================= */

    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        border: 1px solid transparent;
        text-decoration: none;
        transition: .2s ease;
        cursor: pointer;
        font-size: 14px;
    }

    .action-edit {
        color: #2563eb;
        background: #eff6ff;
        border-color: #dbeafe;
    }

    .action-edit:hover {
        color: #1d4ed8;
        background: #dbeafe;
    }

    .action-exit {
        color: #16a34a;
        background: #f0fdf4;
        border-color: #dcfce7;
    }

    .action-exit:hover {
        color: #15803d;
        background: #dcfce7;
    }

    .action-delete {
        color: #dc2626;
        background: #fef2f2;
        border-color: #fee2e2;
    }

    .action-delete:hover {
        color: #b91c1c;
        background: #fee2e2;
    }

    .action-form {
        display: inline;
        margin: 0;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .area-empty {
        padding: 55px 25px;
        text-align: center;
    }

    .area-empty-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #f1f5f9;
        color: #94a3b8;
        font-size: 23px;
    }

    .area-empty-title {
        margin: 0 0 5px;
        font-size: 16px;
        font-weight: 700;
        color: #334155;
    }

    .area-empty-text {
        margin: 0;
        font-size: 13px;
        color: #64748b;
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 768px) {

        .area-page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-tambah-kendaraan {
            justify-content: center;
            width: 100%;
        }

        .edit-form-grid {
            grid-template-columns: 1fr;
        }

        .edit-form-group.full {
            grid-column: auto;
        }

        .edit-form-header {
            align-items: center;
        }

        .edit-form-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .edit-btn-cancel,
        .edit-btn-save {
            width: 100%;
        }

        .area-table-header {
            padding: 17px;
        }
    }


    @media (max-width: 576px) {

        .area-page-title {
            font-size: 21px;
        }

        .area-page-description {
            font-size: 13px;
        }

        .edit-form-card {
            padding: 18px;
            border-radius: 12px;
        }

        .edit-form-title {
            font-size: 16px;
        }

        .edit-form-description {
            font-size: 12px;
        }

        .edit-form-grid {
            gap: 14px;
        }

        .area-stat-card {
            padding: 15px;
        }

        .area-table-card {
            border-radius: 11px;
        }
    }
</style>


<div class="petugas-area-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="area-page-header">

        <div>

            <h1 class="area-page-title">
                Kendaraan di Area Wisata
            </h1>

            <p class="area-page-description">
                Daftar kendaraan yang saat ini masih berada di Area Wisata.
            </p>

        </div>


        <a
            href="{{ route('gate-logs.create') }}"
            class="btn-tambah-kendaraan"
        >
            <i class="bi bi-plus-lg"></i>
            Catat Kendaraan Masuk
        </a>

    </div>


    {{-- =====================================================
         ALERT SUCCESS
    ====================================================== --}}

    @if (session('success'))

        <div class="area-alert area-alert-success">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =====================================================
         ALERT ERROR
    ====================================================== --}}

    @if (session('error'))

        <div class="area-alert area-alert-error">

            <i class="bi bi-exclamation-circle me-1"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- =====================================================
         FORM EDIT
    ====================================================== --}}

    @if (isset($editingGateLog) && $editingGateLog)

        <div class="edit-form-card">

            <div class="edit-form-header">

                <div class="edit-form-title-wrapper">

                    <div class="edit-form-icon">
                        <i class="bi bi-pencil-square"></i>
                    </div>

                    <div>

                        <h2 class="edit-form-title">
                            Edit Data Kendaraan
                        </h2>

                        <p class="edit-form-description">
                            Perbarui data kendaraan yang tercatat di Gate Utama.
                        </p>

                    </div>

                </div>


                <a
                    href="{{ route('petugas.kendaraan-area') }}"
                    class="edit-form-close"
                    title="Tutup form edit"
                >
                    <i class="bi bi-x-lg"></i>
                </a>

            </div>


            <form
                action="{{ route('gate-logs.update', $editingGateLog->id) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="edit-form-grid">

                    {{-- NOMOR PLAT --}}

                    <div class="edit-form-group">

                        <label
                            for="plate_number"
                            class="edit-form-label"
                        >
                            Nomor Plat
                        </label>

                        <input
                            type="text"
                            id="plate_number"
                            name="plate_number"
                            class="edit-form-control"
                            value="{{ old('plate_number', $editingGateLog->plate_number) }}"
                            placeholder="Contoh: S 1234 AB"
                            required
                        >

                        @error('plate_number')

                            <span class="edit-form-error">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- JENIS KENDARAAN --}}

                    <div class="edit-form-group">

                        <label
                            for="vehicle_type"
                            class="edit-form-label"
                        >
                            Jenis Kendaraan
                        </label>

                        <input
                            type="text"
                            id="vehicle_type"
                            name="vehicle_type"
                            class="edit-form-control"
                            value="{{ old('vehicle_type', $editingGateLog->vehicle_type) }}"
                            placeholder="Contoh: Mobil"
                            required
                        >

                        @error('vehicle_type')

                            <span class="edit-form-error">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- NAMA PENGEMUDI --}}

                    <div class="edit-form-group">

                        <label
                            for="driver_name"
                            class="edit-form-label"
                        >
                            Nama Pengemudi
                        </label>

                        <input
                            type="text"
                            id="driver_name"
                            name="driver_name"
                            class="edit-form-control"
                            value="{{ old('driver_name', $editingGateLog->driver_name) }}"
                            placeholder="Masukkan nama pengemudi"
                            required
                        >

                        @error('driver_name')

                            <span class="edit-form-error">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- TUJUAN --}}

                    <div class="edit-form-group">

                        <label
                            for="purpose"
                            class="edit-form-label"
                        >
                            Tujuan
                        </label>

                        <input
                            type="text"
                            id="purpose"
                            name="purpose"
                            class="edit-form-control"
                            value="{{ old('purpose', $editingGateLog->purpose) }}"
                            placeholder="Contoh: Area Wisata"
                            required
                        >

                        @error('purpose')

                            <span class="edit-form-error">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>


                    {{-- CATATAN --}}

                    <div class="edit-form-group full">

                        <label
                            for="notes"
                            class="edit-form-label"
                        >
                            Catatan
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            class="edit-form-control"
                            placeholder="Tambahkan catatan jika diperlukan"
                        >{{ old('notes', $editingGateLog->notes) }}</textarea>

                        @error('notes')

                            <span class="edit-form-error">
                                {{ $message }}
                            </span>

                        @enderror

                    </div>

                </div>


                {{-- FOOTER FORM EDIT --}}

                <div class="edit-form-footer">

                    <a
                        href="{{ route('petugas.kendaraan-area') }}"
                        class="edit-btn-cancel"
                    >
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="edit-btn-save"
                    >
                        <i class="bi bi-check-lg"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    @endif


    {{-- =====================================================
         STATISTIK
    ====================================================== --}}

    <div class="area-stat-card">

        <div class="area-stat-icon">
            <i class="bi bi-car-front-fill"></i>
        </div>

        <div>

            <div class="area-stat-label">
                Kendaraan Saat Ini di Area
            </div>

            <div class="area-stat-value">
                {{ $totalInside ?? 0 }}
            </div>

        </div>

    </div>


    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="area-table-card">

        <div class="area-table-header">

            <div>

                <h2 class="area-table-title">
                    Daftar Kendaraan
                </h2>

                <p class="area-table-description">
                    Kendaraan yang belum tercatat keluar dari Area Wisata.
                </p>

            </div>


            <span class="area-count-badge">
                {{ $vehicles->count() }}
            </span>

        </div>


        <div class="area-table-wrapper">

            <table class="area-table">

                <thead>

                    <tr>

                        <th width="55">
                            No
                        </th>

                        <th>
                            Nomor Plat
                        </th>

                        <th>
                            Jenis Kendaraan
                        </th>

                        <th>
                            Pengemudi
                        </th>

                        <th>
                            Tujuan
                        </th>

                        <th>
                            Waktu Masuk
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Petugas
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($vehicles as $index => $gateLog)

                        <tr>

                            {{-- NO --}}

                            <td>
                                {{ $index + 1 }}
                            </td>


                            {{-- NOMOR PLAT --}}

                            <td>

                                <div class="vehicle-plate">
                                    {{ $gateLog->plate_number ?? '-' }}
                                </div>

                            </td>


                            {{-- JENIS --}}

                            <td>

                                <div class="vehicle-type">
                                    {{ $gateLog->vehicle_type ?? '-' }}
                                </div>

                            </td>


                            {{-- PENGEMUDI --}}

                            <td>

                                <div class="vehicle-driver">
                                    {{ $gateLog->driver_name ?? '-' }}
                                </div>

                            </td>


                            {{-- TUJUAN --}}

                            <td>

                                <div class="vehicle-purpose">
                                    {{ $gateLog->purpose ?? '-' }}
                                </div>

                            </td>


                            {{-- WAKTU MASUK --}}

                            <td>

                                <div class="vehicle-time">

                                    {{ $gateLog->gate_in
                                        ? $gateLog->gate_in->format('d/m/Y H:i')
                                        : '-' }}

                                </div>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                <span class="status-badge">

                                    <i class="bi bi-circle-fill"></i>

                                    {{ $gateLog->status }}

                                </span>

                            </td>


                            {{-- PETUGAS --}}

                            <td>
                                {{ $gateLog->officer->name ?? '-' }}
                            </td>


                            {{-- AKSI --}}

                            <td>

                                <div class="action-group">

                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route('petugas.kendaraan-area', ['edit' => $gateLog->id]) }}"
                                        class="action-btn action-edit"
                                        title="Edit"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    {{-- KELUAR --}}

                                    <form
                                        action="{{ route('gate-logs.exit', $gateLog->id) }}"
                                        method="POST"
                                        class="action-form"
                                        onsubmit="return confirm('Catat kendaraan ini keluar dari Area Wisata?')"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="action-btn action-exit"
                                            title="Catat Keluar"
                                        >
                                            <i class="bi bi-box-arrow-right"></i>
                                        </button>

                                    </form>


                                    {{-- HAPUS --}}

                                    <form
                                        action="{{ route('gate-logs.destroy', $gateLog->id) }}"
                                        method="POST"
                                        class="action-form"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kendaraan ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn action-delete"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash3"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9">

                                <div class="area-empty">

                                    <div class="area-empty-icon">
                                        <i class="bi bi-car-front"></i>
                                    </div>

                                    <h3 class="area-empty-title">
                                        Belum Ada Kendaraan
                                    </h3>

                                    <p class="area-empty-text">
                                        Belum ada kendaraan yang sedang berada di Area Wisata.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection