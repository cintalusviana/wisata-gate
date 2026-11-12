@extends('layouts.app')

@section('title', 'Riwayat Kendaraan')

@section('content')

@php
    $historyTotal = $histories->count();
    $showAllHistory = request()->boolean('all');
@endphp

<style>
    .history-page {
        --orange: #ea580c;
        --orange-light: #fff7ed;
        --blue: #2563eb;
        --green: #16a34a;
        --red: #dc2626;
        --border: #e5e7eb;
        --muted: #6b7280;
        --text: #111827;
        font-family: 'Inter', sans-serif;
        color: var(--text);
    }

    .history-page *,
    .history-page *::before,
    .history-page *::after {
        box-sizing: border-box;
    }

    .history-page [hidden] {
        display: none !important;
    }

    .history-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .history-header h1 {
        margin: 0 0 8px;
        color: var(--text);
        font-size: 28px;
        font-weight: 750;
        letter-spacing: -.6px;
    }

    .history-header p {
        max-width: 580px;
        margin: 0;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .history-header-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
    }

    .history-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 9px 14px;
        border: 1px solid transparent;
        border-radius: 9px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
    }

    .history-btn:disabled {
        cursor: not-allowed;
        opacity: .7;
    }

    .history-btn-orange {
        color: #fff;
        background: #ea580c;
        border-color: #ea580c;
    }

    .history-btn-orange:hover {
        color: #fff;
        background: #c2410c;
        border-color: #c2410c;
    }

    .history-btn-blue {
        color: #fff;
        background: #2563eb;
        border-color: #2563eb;
    }

    .history-btn-blue:hover {
        color: #fff;
        background: #1d4ed8;
        border-color: #1d4ed8;
    }

    .history-btn-green {
        color: #fff;
        background: #16a34a;
        border-color: #16a34a;
    }

    .history-btn-green:hover {
        color: #fff;
        background: #15803d;
        border-color: #15803d;
    }

    .history-btn-red,
    .history-btn-red:focus,
    .history-btn-red:active {
        color: #fff !important;
        background: #dc2626 !important;
        border: 1px solid #dc2626 !important;
    }

    .history-btn-red:hover {
        color: #fff !important;
        background: #b91c1c !important;
        border-color: #b91c1c !important;
    }

    .history-btn-red:focus-visible {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, .2);
    }

    .history-btn-light {
        color: #374151;
        background: #fff;
        border-color: #d1d5db;
    }

    .history-btn-light:hover {
        color: #111827;
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .history-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .history-summary-card {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
        padding: 20px;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 13px;
    }

    .history-summary-card::after {
        display: none !important;
        content: none !important;
    }

    .history-summary-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        flex-shrink: 0;
        color: var(--summary-color, var(--orange));
        background: var(--summary-bg, var(--orange-light));
        border-radius: 12px;
        font-size: 20px;
    }

    .history-summary-info small {
        display: block;
        margin-bottom: 5px;
        color: var(--muted);
        font-size: 12px;
    }

    .history-summary-info strong {
        display: block;
        color: var(--text);
        font-size: 24px;
        font-weight: 750;
        line-height: 1.2;
    }

    .history-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 13px;
    }

    .history-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 20px 22px;
        border-bottom: 1px solid var(--border);
    }

    .history-card-header h2 {
        margin: 0 0 5px;
        color: var(--text);
        font-size: 16px;
        font-weight: 750;
    }

    .history-card-header p {
        margin: 0;
        color: var(--muted);
        font-size: 12px;
        line-height: 1.6;
    }

    .history-filters {
        display: grid;
        grid-template-columns: minmax(220px, 1.6fr)
                               minmax(150px, .8fr)
                               minmax(150px, .8fr)
                               auto;
        gap: 11px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--border);
    }

    .history-filter-control {
        position: relative;
        min-width: 0;
    }

    .history-filter-control > i {
        position: absolute;
        top: 50%;
        left: 12px;
        z-index: 1;
        color: #9ca3af;
        font-size: 14px;
        pointer-events: none;
        transform: translateY(-50%);
    }

    .history-filter-control input,
    .history-filter-control select {
        width: 100%;
        height: 41px;
        padding: 0 12px;
        color: #111827;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        font-family: inherit;
        font-size: 12px;
    }

    .history-filter-control input[type="text"] {
        padding-left: 35px;
    }

    .history-filter-control input:focus,
    .history-filter-control select:focus,
    .history-form-control:focus {
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(234, 88, 12, .1);
    }

    .history-reset-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        height: 41px;
        padding: 0 14px;
        color: #374151;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        cursor: pointer;
    }

    .history-reset-btn:hover {
        background: #f9fafb;
    }

    .history-edit-panel {
        margin: 20px 22px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #bbf7d0;
        border-radius: 13px;
        box-shadow: 0 4px 15px rgba(22, 163, 74, .06);
        scroll-margin-top: 24px;
    }

    .history-edit-panel-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 17px 20px;
        background: #f0fdf4;
        border-bottom: 1px solid #bbf7d0;
    }

    .history-edit-panel-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        color: #15803d;
        background: #dcfce7;
        border-radius: 10px;
        font-size: 18px;
    }

    .history-edit-panel-header h3 {
        margin: 0 0 4px;
        color: #166534;
        font-size: 15px;
        font-weight: 750;
    }

    .history-edit-panel-header p {
        margin: 0;
        color: #4b7b5b;
        font-size: 12px;
        line-height: 1.5;
    }

    .history-edit-panel-body {
        padding: 22px;
    }

    .history-edit-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .history-form-group {
        min-width: 0;
        margin: 0;
    }

    .history-form-group label {
        display: block;
        margin-bottom: 7px;
        color: #374151;
        font-size: 12px;
        font-weight: 700;
    }

    .history-required {
        color: var(--red);
    }

    .history-form-control {
        display: block;
        width: 100%;
        min-height: 42px;
        padding: 10px 12px;
        color: #111827;
        background: #fff;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        font-family: inherit;
        font-size: 13px;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .history-form-control::placeholder {
        color: #9ca3af;
    }

    textarea.history-form-control {
        min-height: 90px;
        resize: vertical;
    }

    .history-form-full {
        grid-column: 1 / -1;
    }

    .history-form-hint {
        display: block;
        margin-top: 6px;
        color: #9ca3af;
        font-size: 11px;
        line-height: 1.5;
    }

    .history-edit-panel-footer {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 9px;
        padding: 15px 22px;
        background: #f9fafb;
        border-top: 1px solid var(--border);
    }

    .history-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        min-width: 1100px;
        border-collapse: collapse;
    }

    .history-table th {
        padding: 14px 16px;
        color: #6b7280;
        background: #f9fafb;
        border-bottom: 1px solid var(--border);
        font-size: 11px;
        font-weight: 750;
        text-align: left;
        white-space: nowrap;
    }

    .history-table td {
        padding: 15px 16px;
        color: #374151;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .history-table tbody tr {
        transition: background .15s ease;
    }

    .history-table tbody tr:hover {
        background: #fafafa;
    }

    .history-table tbody tr:last-child td {
        border-bottom: none;
    }

    .history-number {
        color: #9ca3af;
        font-weight: 700;
    }

    .history-plate {
        display: inline-flex;
        align-items: center;
        padding: 6px 9px;
        color: #1e40af;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .3px;
    }

    .history-vehicle-type {
        color: #374151;
        font-weight: 600;
    }

    .history-driver-name {
        color: #111827;
        font-weight: 700;
    }

    .history-purpose {
        color: #6b7280;
    }

    .history-time {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .history-time-date {
        color: #374151;
        font-size: 11px;
        font-weight: 700;
    }

    .history-time-hour {
        color: #9ca3af;
        font-size: 11px;
    }

    .history-officer {
        color: #4b5563;
        font-size: 11px;
        font-weight: 600;
    }

    .history-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }

    .history-status::before {
        width: 6px;
        height: 6px;
        content: '';
        background: currentColor;
        border-radius: 50%;
    }

    .history-status-inside {
        color: #c2410c;
        background: #fff7ed;
    }

    .history-status-exited {
        color: #15803d;
        background: #f0fdf4;
    }

    .history-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .history-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        padding: 0;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        transition: background .2s ease, color .2s ease;
    }

    .history-action-detail {
        color: #2563eb;
        background: #eff6ff;
        border-color: #dbeafe;
    }

    .history-action-detail:hover {
        color: #fff;
        background: #2563eb;
        border-color: #2563eb;
    }

    .history-action-edit {
        color: #15803d;
        background: #f0fdf4;
        border-color: #bbf7d0;
    }

    .history-action-edit:hover {
        color: #fff;
        background: #16a34a;
        border-color: #16a34a;
    }

    .history-action-delete {
        color: #dc2626;
        background: #fef2f2;
        border-color: #fecaca;
    }

    .history-action-delete:hover {
        color: #fff;
        background: #dc2626;
        border-color: #dc2626;
    }

    .history-empty {
        padding: 48px 20px !important;
        text-align: center;
        white-space: normal !important;
    }

    .history-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        color: #9ca3af;
        background: #f3f4f6;
        border-radius: 16px;
        font-size: 25px;
    }

    .history-empty h3 {
        margin: 0 0 7px;
        color: #374151;
        font-size: 15px;
        font-weight: 750;
    }

    .history-empty p {
        margin: 0;
        color: #9ca3af;
        font-size: 12px;
        line-height: 1.6;
    }

    .history-result-info {
        padding: 14px 22px;
        color: #9ca3af;
        border-top: 1px solid var(--border);
        font-size: 11px;
    }

    /* TOMBOL LIHAT SEMUA - SEDERHANA DAN RATA KANAN */
    .history-view-all-wrap {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 12px 22px;
        border-top: 1px solid var(--border);
    }

    .history-view-all-wrap .history-btn {
        min-height: 34px;
        padding: 7px 11px;
        color: #6b7280;
        background: transparent;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 600;
    }

    .history-view-all-wrap .history-btn:hover {
        color: #374151;
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .history-modal .modal-dialog {
        width: calc(100% - 24px);
        max-width: 800px;
    }

    .history-modal .modal-content {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 20px 55px rgba(15, 23, 42, .16);
    }

    .history-modal .modal-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 19px 22px;
        border-bottom: 1px solid #e5e7eb;
    }

    .history-modal-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .history-modal-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        color: #2563eb;
        background: #eff6ff;
        border-radius: 11px;
        font-size: 19px;
    }

    .history-modal-heading h5 {
        margin: 0 0 4px;
        color: #111827;
        font-size: 15px;
        font-weight: 750;
    }

    .history-modal-heading p {
        margin: 0;
        color: #6b7280;
        font-size: 11px;
        line-height: 1.5;
    }

    .history-modal .modal-body {
        padding: 22px;
    }

    .history-detail-profile {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 18px;
        padding: 16px;
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 11px;
    }

    .history-detail-profile-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        color: #2563eb;
        background: #dbeafe;
        border-radius: 12px;
        font-size: 22px;
    }

    .history-detail-plate {
        margin-bottom: 5px;
        color: #1e3a8a;
        font-size: 16px;
        font-weight: 800;
    }

    .history-detail-driver {
        color: #64748b;
        font-size: 12px;
    }

    .history-detail-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .history-detail-item {
        min-width: 0;
        padding: 14px;
        overflow-wrap: anywhere;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
    }

    .history-detail-label {
        display: block;
        margin-bottom: 7px;
        color: #9ca3af;
        font-size: 10px;
    }

    .history-detail-value {
        color: #111827;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .history-modal .modal-footer {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 8px;
        padding: 15px 22px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
    }

    .history-delete-warning {
        padding: 16px;
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 10px;
        font-size: 12px;
        line-height: 1.8;
    }

    .history-delete-warning > i {
        margin-right: 6px;
        font-size: 16px;
    }

    /* DARK MODE */
    body.dark-mode .history-page {
        color: #f9fafb;
    }

    body.dark-mode .history-header h1,
    body.dark-mode .history-summary-info strong,
    body.dark-mode .history-card-header h2,
    body.dark-mode .history-driver-name {
        color: #f9fafb;
    }

    body.dark-mode .history-header p,
    body.dark-mode .history-card-header p {
        color: #9ca3af;
    }

    body.dark-mode .history-summary-card,
    body.dark-mode .history-card,
    body.dark-mode .history-edit-panel {
        background: #111827;
        border-color: #374151;
    }

    body.dark-mode .history-edit-panel-header {
        background: #052e16;
        border-color: #166534;
    }

    body.dark-mode .history-edit-panel-header h3 {
        color: #bbf7d0;
    }

    body.dark-mode .history-edit-panel-header p {
        color: #86efac;
    }

    body.dark-mode .history-edit-panel-footer,
    body.dark-mode .history-card-header,
    body.dark-mode .history-filters,
    body.dark-mode .history-result-info,
    body.dark-mode .history-view-all-wrap {
        background: #111827;
        border-color: #374151;
    }

    body.dark-mode .history-filter-control input,
    body.dark-mode .history-filter-control select,
    body.dark-mode .history-form-control {
        color: #f9fafb;
        background: #1f2937;
        border-color: #4b5563;
    }

    body.dark-mode .history-form-group label {
        color: #e5e7eb;
    }

    body.dark-mode .history-table th {
        color: #d1d5db;
        background: #1f2937;
        border-color: #374151;
    }

    body.dark-mode .history-table td {
        color: #d1d5db;
        border-color: #374151;
    }

    body.dark-mode .history-table tbody tr:hover {
        background: #1f2937;
    }

    body.dark-mode .history-vehicle-type,
    body.dark-mode .history-time-date,
    body.dark-mode .history-officer {
        color: #e5e7eb;
    }

    body.dark-mode .history-purpose {
        color: #9ca3af;
    }

    body.dark-mode .history-reset-btn,
    body.dark-mode .history-btn-light {
        color: #e5e7eb;
        background: #1f2937;
        border-color: #4b5563;
    }

    body.dark-mode .history-view-all-wrap .history-btn {
        color: #d1d5db;
        background: transparent;
        border-color: #4b5563;
    }

    body.dark-mode .history-view-all-wrap .history-btn:hover {
        color: #fff;
        background: #1f2937;
        border-color: #6b7280;
    }

    body.dark-mode .history-modal .modal-content {
        background: #111827;
        border-color: #374151;
    }

    body.dark-mode .history-modal .modal-header,
    body.dark-mode .history-modal .modal-footer {
        background: #1f2937;
        border-color: #374151;
    }

    body.dark-mode .history-modal-heading h5,
    body.dark-mode .history-detail-value {
        color: #f9fafb;
    }

    body.dark-mode .history-modal .modal-body {
        background: #111827;
    }

    body.dark-mode .history-detail-item {
        background: #1f2937;
        border-color: #374151;
    }

    body.dark-mode .history-detail-profile {
        background: #172554;
        border-color: #1e40af;
    }

    body.dark-mode .history-detail-plate {
        color: #bfdbfe;
    }

    body.dark-mode .history-detail-driver {
        color: #9ca3af;
    }

    @media (max-width: 1050px) {
        .history-filters {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .history-summary {
            gap: 12px;
        }

        .history-summary-card {
            padding: 16px;
        }
    }

    @media (max-width: 760px) {
        .history-header {
            flex-direction: column;
            gap: 15px;
        }

        .history-header-actions {
            width: 100%;
        }

        .history-header-actions .history-btn {
            flex: 1;
        }

        .history-summary {
            grid-template-columns: 1fr;
        }

        .history-summary-card {
            padding: 17px;
        }

        .history-card-header {
            padding: 17px;
        }

        .history-filters {
            padding: 16px;
        }

        .history-edit-panel {
            margin: 16px;
        }

        .history-edit-panel-body {
            padding: 17px;
        }

        .history-edit-panel-footer {
            padding: 14px 17px;
        }

        .history-detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 520px) {
        .history-header h1 {
            font-size: 24px;
        }

        .history-header-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .history-header-actions .history-btn {
            width: 100%;
            flex: none;
        }

        .history-filters,
        .history-edit-grid {
            grid-template-columns: 1fr;
        }

        .history-form-full {
            grid-column: auto;
        }

        .history-edit-panel-header {
            align-items: flex-start;
            padding: 15px;
        }

        .history-edit-panel-body {
            padding: 15px;
        }

        .history-edit-panel-footer {
            flex-direction: column-reverse;
        }

        .history-edit-panel-footer .history-btn {
            width: 100%;
        }

        .history-modal .modal-dialog {
            width: calc(100% - 16px);
            margin: 8px auto;
        }

        .history-modal .modal-header,
        .history-modal .modal-body {
            padding: 16px;
        }

        .history-modal .modal-footer {
            padding: 14px 16px;
        }

        .history-detail-grid {
            grid-template-columns: 1fr;
        }

        .history-modal .modal-footer .history-btn {
            flex: 1;
        }

        .history-view-all-wrap {
            padding: 12px 16px;
        }
    }
</style>

<div class="history-page">

    {{-- HEADER --}}
    <div class="history-header">
        <div>
            <h1>Riwayat Kendaraan</h1>
            <p>
                Kelola dan pantau seluruh catatan kendaraan yang masuk dan
                keluar melalui Gate Utama area wisata.
            </p>
        </div>

        <div class="history-header-actions">
            <a href="{{ route('gate-logs.create') }}"
               class="history-btn history-btn-orange">
                <i class="bi bi-plus-lg"></i>
                Tambah Kendaraan
            </a>

            <a href="{{ route('monitoring.export') }}"
               class="history-btn history-btn-light">
                <i class="bi bi-download"></i>
                Export Data
            </a>
        </div>
    </div>

    {{-- RINGKASAN --}}
    <div class="history-summary">
        <div class="history-summary-card"
             style="--summary-color:#2563eb;--summary-bg:#eff6ff;">
            <div class="history-summary-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="history-summary-info">
                <small>Total Riwayat</small>
                <strong>{{ $historyTotal }}</strong>
            </div>
        </div>

        <div class="history-summary-card"
             style="--summary-color:#ea580c;--summary-bg:#fff7ed;">
            <div class="history-summary-icon">
                <i class="bi bi-car-front"></i>
            </div>
            <div class="history-summary-info">
                <small>Masih Di Dalam</small>
                <strong>{{ $histories->where('status', 'Di Dalam')->count() }}</strong>
            </div>
        </div>

        <div class="history-summary-card"
             style="--summary-color:#16a34a;--summary-bg:#f0fdf4;">
            <div class="history-summary-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="history-summary-info">
                <small>Sudah Keluar</small>
                <strong>{{ $histories->where('status', 'Sudah Keluar')->count() }}</strong>
            </div>
        </div>
    </div>

    {{-- DAFTAR RIWAYAT --}}
    <div class="history-card">

        <div class="history-card-header">
            <div>
                <h2>Daftar Riwayat Kendaraan</h2>
                <p>Informasi aktivitas kendaraan pada Gate Utama.</p>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="history-filters">
            <div class="history-filter-control">
                <i class="bi bi-search"></i>
                <input type="text"
                       id="historySearch"
                       placeholder="Cari plat, pengemudi, atau tujuan..."
                       aria-label="Cari riwayat kendaraan">
            </div>

            <div class="history-filter-control">
                <select id="historyStatus" aria-label="Filter status">
                    <option value="">Semua Status</option>
                    <option value="Di Dalam">Di Dalam</option>
                    <option value="Sudah Keluar">Sudah Keluar</option>
                </select>
            </div>

            <div class="history-filter-control">
                <input type="date"
                       id="historyDate"
                       aria-label="Filter tanggal masuk">
            </div>

            <button type="button"
                    class="history-reset-btn"
                    id="resetHistoryFilter">
                <i class="bi bi-arrow-counterclockwise"></i>
                Reset Filter
            </button>
        </div>

        {{-- FORM EDIT --}}
        <section class="history-edit-panel"
                 id="historyEditPanel"
                 hidden>

            <div class="history-edit-panel-header">
                <div class="history-edit-panel-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h3>Edit Riwayat Kendaraan</h3>
                    <p>
                        Perbarui informasi kendaraan melalui formulir berikut.
                    </p>
                </div>
            </div>

            <form id="historyEditForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="return_to" value="history">

                <div class="history-edit-panel-body">
                    <div class="history-edit-grid">

                        <div class="history-form-group">
                            <label for="editHistoryPlate">
                                Plat Nomor <span class="history-required">*</span>
                            </label>
                            <input type="text"
                                   name="plate_number"
                                   id="editHistoryPlate"
                                   class="history-form-control"
                                   maxlength="20"
                                   placeholder="Contoh: S 1234 AB"
                                   required>
                        </div>

                        <div class="history-form-group">
                            <label for="editHistoryType">
                                Jenis Kendaraan <span class="history-required">*</span>
                            </label>
                            <input type="text"
                                   name="vehicle_type"
                                   id="editHistoryType"
                                   class="history-form-control"
                                   maxlength="100"
                                   placeholder="Contoh: Mobil"
                                   required>
                        </div>

                        <div class="history-form-group">
                            <label for="editHistoryDriver">
                                Nama Pengemudi <span class="history-required">*</span>
                            </label>
                            <input type="text"
                                   name="driver_name"
                                   id="editHistoryDriver"
                                   class="history-form-control"
                                   maxlength="100"
                                   placeholder="Masukkan nama pengemudi"
                                   required>
                        </div>

                        <div class="history-form-group">
                            <label for="editHistoryPurpose">
                                Tujuan <span class="history-required">*</span>
                            </label>
                            <input type="text"
                                   name="purpose"
                                   id="editHistoryPurpose"
                                   class="history-form-control"
                                   maxlength="255"
                                   placeholder="Contoh: Area Wisata"
                                   required>
                        </div>

                        <div class="history-form-group history-form-full">
                            <label for="editHistoryNotes">Catatan</label>
                            <textarea name="notes"
                                      id="editHistoryNotes"
                                      class="history-form-control"
                                      maxlength="1000"
                                      placeholder="Tambahkan catatan jika diperlukan"></textarea>
                            <span class="history-form-hint">
                                Kosongkan jika tidak ada catatan tambahan.
                            </span>
                        </div>

                    </div>
                </div>

                <div class="history-edit-panel-footer">
                    <button type="button"
                            class="history-btn history-btn-light"
                            id="cancelHistoryEdit">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </button>

                    <button type="submit"
                            class="history-btn history-btn-green"
                            id="saveHistoryEdit">
                        <i class="bi bi-check-lg"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>

        {{-- TABEL --}}
        <div class="history-table-wrap">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Plat Nomor</th>
                        <th>Jenis</th>
                        <th>Pengemudi</th>
                        <th>Tujuan</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Petugas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="historyTableBody">
                    @forelse($histories as $index => $history)
                        @php
                            $plateNumber = $history->plate_number ?? '-';
                            $vehicleTypeName = $history->vehicle_type ?? '-';
                            $driverName = $history->driver_name ?? '-';
                            $purpose = $history->purpose ?? '-';
                            $officerName = $history->officer?->name ?? '-';
                            $historyStatus = $history->status ?? '-';

                            $gateInFormatted = $history->gate_in
                                ? $history->gate_in->format('d-m-Y H:i')
                                : '-';

                            $gateOutFormatted = $history->gate_out
                                ? $history->gate_out->format('d-m-Y H:i')
                                : 'Belum keluar';
                        @endphp

                        <tr class="history-row"
                            data-status="{{ $historyStatus }}"
                            data-date="{{ $history->gate_in ? $history->gate_in->format('Y-m-d') : '' }}">

                            <td>
                                <span class="history-number">{{ $index + 1 }}</span>
                            </td>

                            <td>
                                <span class="history-plate">{{ $plateNumber }}</span>
                            </td>

                            <td>
                                <span class="history-vehicle-type">{{ $vehicleTypeName }}</span>
                            </td>

                            <td>
                                <span class="history-driver-name">{{ $driverName }}</span>
                            </td>

                            <td>
                                <span class="history-purpose">{{ $purpose }}</span>
                            </td>

                            <td>
                                @if($history->gate_in)
                                    <div class="history-time">
                                        <span class="history-time-date">
                                            {{ $history->gate_in->format('d-m-Y') }}
                                        </span>
                                        <span class="history-time-hour">
                                            {{ $history->gate_in->format('H:i') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                @if($history->gate_out)
                                    <div class="history-time">
                                        <span class="history-time-date">
                                            {{ $history->gate_out->format('d-m-Y') }}
                                        </span>
                                        <span class="history-time-hour">
                                            {{ $history->gate_out->format('H:i') }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-muted">Belum keluar</span>
                                @endif
                            </td>

                            <td>
                                <span class="history-officer">{{ $officerName }}</span>
                            </td>

                            <td>
                                @if($historyStatus === 'Di Dalam')
                                    <span class="history-status history-status-inside">
                                        Di Dalam
                                    </span>
                                @elseif($historyStatus === 'Sudah Keluar')
                                    <span class="history-status history-status-exited">
                                        Sudah Keluar
                                    </span>
                                @else
                                    <span class="history-status history-status-inside">
                                        {{ $historyStatus }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="history-actions">

                                    <button type="button"
                                            class="history-action-btn history-action-detail"
                                            title="Lihat Detail"
                                            aria-label="Lihat detail kendaraan"
                                            data-history-detail
                                            data-id="{{ $history->id }}"
                                            data-plate="{{ $plateNumber }}"
                                            data-type="{{ $vehicleTypeName }}"
                                            data-driver="{{ $driverName }}"
                                            data-purpose="{{ $purpose }}"
                                            data-gate-in="{{ $gateInFormatted }}"
                                            data-gate-out="{{ $gateOutFormatted }}"
                                            data-officer="{{ $officerName }}"
                                            data-status="{{ $historyStatus }}"
                                            data-notes="{{ $history->notes ?? '-' }}">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <button type="button"
                                            class="history-action-btn history-action-edit"
                                            title="Edit Riwayat"
                                            aria-label="Edit riwayat kendaraan"
                                            data-history-edit
                                            data-id="{{ $history->id }}"
                                            data-plate="{{ $plateNumber }}"
                                            data-type="{{ $vehicleTypeName }}"
                                            data-driver="{{ $driverName }}"
                                            data-purpose="{{ $history->purpose ?? '' }}"
                                            data-notes="{{ $history->notes ?? '' }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <button type="button"
                                            class="history-action-btn history-action-delete"
                                            title="Hapus Riwayat"
                                            aria-label="Hapus riwayat kendaraan"
                                            data-history-delete
                                            data-id="{{ $history->id }}"
                                            data-plate="{{ $plateNumber }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="historyEmptyRow">
                            <td colspan="10" class="history-empty">
                                <div class="history-empty-icon">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <h3>Belum Ada Riwayat Kendaraan</h3>
                                <p>
                                    Catatan kendaraan yang masuk dan keluar
                                    akan ditampilkan di sini.
                                </p>
                            </td>
                        </tr>
                    @endforelse

                    <tr id="historyNoResults" hidden>
                        <td colspan="10" class="history-empty">
                            <div class="history-empty-icon">
                                <i class="bi bi-search"></i>
                            </div>
                            <h3>Data Tidak Ditemukan</h3>
                            <p>
                                Coba ubah kata kunci atau reset filter pencarian.
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="history-result-info" id="historyResultInfo">
            Menampilkan {{ min(10, $historyTotal) }} dari {{ $historyTotal }}
            riwayat kendaraan.
        </div>

        {{-- TOMBOL LIHAT SEMUA --}}
        @if($historyTotal > 10)
            <div class="history-view-all-wrap">
                @if($showAllHistory)
                    <a href="{{ route('history.index') }}"
                       class="history-btn">
                        <i class="bi bi-chevron-up"></i>
                        Tampilkan 10 Data
                    </a>
                @else
                    <a href="{{ route('history.index', ['all' => 1]) }}"
                       class="history-btn">
                        Lihat Semua ({{ $historyTotal }})
                        <i class="bi bi-chevron-right"></i>
                    </a>
                @endif
            </div>
        @endif

        @if(method_exists($histories, 'links'))
            <div class="px-3 py-3">
                {{ $histories->links() }}
            </div>
        @endif
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade history-modal"
     id="historyDetailModal"
     tabindex="-1"
     aria-labelledby="historyDetailModalTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <div class="history-modal-heading">
                    <div class="history-modal-icon">
                        <i class="bi bi-car-front"></i>
                    </div>
                    <div>
                        <h5 id="historyDetailModalTitle">
                            Detail Riwayat Kendaraan
                        </h5>
                        <p>Informasi lengkap aktivitas kendaraan.</p>
                    </div>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <div class="history-detail-profile">
                    <div class="history-detail-profile-icon">
                        <i class="bi bi-car-front"></i>
                    </div>
                    <div>
                        <div class="history-detail-plate" id="detailHistoryPlate">-</div>
                        <div class="history-detail-driver" id="detailHistoryDriver">-</div>
                    </div>
                </div>

                <div class="history-detail-grid">
                    <div class="history-detail-item">
                        <span class="history-detail-label">Jenis Kendaraan</span>
                        <div class="history-detail-value" id="detailHistoryType">-</div>
                    </div>

                    <div class="history-detail-item">
                        <span class="history-detail-label">Pengemudi</span>
                        <div class="history-detail-value" id="detailHistoryDriverValue">-</div>
                    </div>

                    <div class="history-detail-item">
                        <span class="history-detail-label">Tujuan</span>
                        <div class="history-detail-value" id="detailHistoryPurpose">-</div>
                    </div>

                    <div class="history-detail-item">
                        <span class="history-detail-label">Waktu Masuk</span>
                        <div class="history-detail-value" id="detailHistoryIn">-</div>
                    </div>

                    <div class="history-detail-item">
                        <span class="history-detail-label">Waktu Keluar</span>
                        <div class="history-detail-value" id="detailHistoryOut">-</div>
                    </div>

                    <div class="history-detail-item">
                        <span class="history-detail-label">Petugas</span>
                        <div class="history-detail-value" id="detailHistoryOfficer">-</div>
                    </div>

                    <div class="history-detail-item">
                        <span class="history-detail-label">Status</span>
                        <div class="history-detail-value" id="detailHistoryStatus">-</div>
                    </div>

                    <div class="history-detail-item">
                        <span class="history-detail-label">ID Riwayat</span>
                        <div class="history-detail-value" id="detailHistoryId">-</div>
                    </div>

                    <div class="history-detail-item">
                        <span class="history-detail-label">Catatan</span>
                        <div class="history-detail-value" id="detailHistoryNotes">-</div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="history-btn history-btn-light"
                        data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS --}}
<div class="modal fade history-modal"
     id="historyDeleteModal"
     tabindex="-1"
     aria-labelledby="historyDeleteModalTitle"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <form id="historyDeleteForm"
              method="POST"
              class="modal-content">

            @csrf
            @method('DELETE')

            <input type="hidden" name="return_to" value="history">

            <div class="modal-header">
                <div class="history-modal-heading">
                    <div class="history-modal-icon"
                         style="color:#dc2626;background:#fef2f2;">
                        <i class="bi bi-trash3"></i>
                    </div>

                    <div>
                        <h5 id="historyDeleteModalTitle">
                            Hapus Riwayat Kendaraan
                        </h5>
                        <p>Periksa kembali data sebelum menghapus.</p>
                    </div>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <div class="history-delete-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Apakah kamu yakin ingin menghapus riwayat kendaraan
                    <strong id="deleteHistoryPlate">-</strong>?
                    Data yang dihapus tidak dapat dikembalikan.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                        class="history-btn history-btn-light"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="submit"
                        class="history-btn history-btn-red"
                        id="deleteHistorySubmit">
                    <i class="bi bi-trash3"></i>
                    Ya, Hapus
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const baseGateLogsUrl = @json(url('/gate-logs'));

    /* HELPER */
    function setText(id, value) {
        const element = document.getElementById(id);

        if (element) {
            element.textContent = value || '-';
        }
    }

    function getModal(elementId) {
        const element = document.getElementById(elementId);

        if (!element || typeof bootstrap === 'undefined') {
            return null;
        }

        return bootstrap.Modal.getOrCreateInstance(element);
    }

    const detailModal = getModal('historyDetailModal');
    const deleteModal = getModal('historyDeleteModal');

    const editPanel = document.getElementById('historyEditPanel');
    const editForm = document.getElementById('historyEditForm');
    const deleteForm = document.getElementById('historyDeleteForm');

    const cancelEditButton = document.getElementById('cancelHistoryEdit');
    const saveButton = document.getElementById('saveHistoryEdit');
    const deleteSubmitButton = document.getElementById('deleteHistorySubmit');

    function resetSaveButton() {
        if (!saveButton) return;

        saveButton.disabled = false;
        saveButton.innerHTML =
            '<i class="bi bi-check-lg"></i> Simpan Perubahan';
    }

    function resetDeleteButton() {
        if (!deleteSubmitButton) return;

        deleteSubmitButton.disabled = false;
        deleteSubmitButton.innerHTML =
            '<i class="bi bi-trash3"></i> Ya, Hapus';
    }

    resetSaveButton();
    resetDeleteButton();

    window.addEventListener('pageshow', function () {
        resetSaveButton();
        resetDeleteButton();
    });

    /* EDIT RIWAYAT */
    document.querySelectorAll('[data-history-edit]').forEach(function (button) {
        button.addEventListener('click', function () {
            const data = this.dataset;

            if (!editPanel || !editForm) return;

            resetSaveButton();

            editForm.action =
                baseGateLogsUrl + '/' + encodeURIComponent(data.id);

            document.getElementById('editHistoryPlate').value =
                data.plate === '-' ? '' : (data.plate || '');

            document.getElementById('editHistoryType').value =
                data.type === '-' ? '' : (data.type || '');

            document.getElementById('editHistoryDriver').value =
                data.driver === '-' ? '' : (data.driver || '');

            document.getElementById('editHistoryPurpose').value =
                data.purpose === '-' ? '' : (data.purpose || '');

            document.getElementById('editHistoryNotes').value =
                data.notes === '-' ? '' : (data.notes || '');

            editPanel.hidden = false;

            editPanel.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            window.setTimeout(function () {
                document.getElementById('editHistoryPlate').focus({
                    preventScroll: true
                });
            }, 250);
        });
    });

    /* BATAL EDIT */
    if (cancelEditButton && editPanel && editForm) {
        cancelEditButton.addEventListener('click', function () {
            editForm.reset();
            editForm.action = '';
            editPanel.hidden = true;
            resetSaveButton();
        });
    }

    /* DETAIL RIWAYAT */
    document.querySelectorAll('[data-history-detail]').forEach(function (button) {
        button.addEventListener('click', function () {
            const data = this.dataset;

            setText('detailHistoryPlate', data.plate);
            setText('detailHistoryDriver', data.driver);
            setText('detailHistoryType', data.type);
            setText('detailHistoryDriverValue', data.driver);
            setText('detailHistoryPurpose', data.purpose);
            setText('detailHistoryIn', data.gateIn);
            setText('detailHistoryOut', data.gateOut);
            setText('detailHistoryOfficer', data.officer);
            setText('detailHistoryStatus', data.status);
            setText('detailHistoryId', data.id ? '#' + data.id : '-');
            setText('detailHistoryNotes', data.notes);

            if (detailModal) {
                detailModal.show();
            }
        });
    });

    /* HAPUS RIWAYAT */
    document.querySelectorAll('[data-history-delete]').forEach(function (button) {
        button.addEventListener('click', function () {
            const data = this.dataset;

            if (!deleteForm) return;

            resetDeleteButton();

            deleteForm.action =
                baseGateLogsUrl + '/' + encodeURIComponent(data.id);

            setText('deleteHistoryPlate', data.plate);

            if (deleteModal) {
                deleteModal.show();
            }
        });
    });

    /* FILTER PENCARIAN */
    const searchInput = document.getElementById('historySearch');
    const statusFilter = document.getElementById('historyStatus');
    const dateFilter = document.getElementById('historyDate');
    const resetButton = document.getElementById('resetHistoryFilter');
    const resultInfo = document.getElementById('historyResultInfo');
    const noResultsRow = document.getElementById('historyNoResults');

    const rows = Array.from(
        document.querySelectorAll('.history-row')
    );

    const initialLimit = 10;

    // Status awal mengikuti parameter URL ?all=1
    let isShowingAll = @json($showAllHistory);

    function filterHistory() {
        const keyword = (searchInput?.value || '')
            .trim()
            .toLowerCase();

        const selectedStatus = statusFilter?.value || '';
        const selectedDate = dateFilter?.value || '';

        let matchingRows = [];

        rows.forEach(function (row) {
            const rowText = row.textContent.toLowerCase();
            const rowStatus = row.dataset.status || '';
            const rowDate = row.dataset.date || '';

            const matchesKeyword = rowText.includes(keyword);
            const matchesStatus =
                !selectedStatus || rowStatus === selectedStatus;
            const matchesDate =
                !selectedDate || rowDate === selectedDate;

            if (matchesKeyword && matchesStatus && matchesDate) {
                matchingRows.push(row);
            }
        });

        const hasActiveFilter =
            keyword !== '' ||
            selectedStatus !== '' ||
            selectedDate !== '';

        // Saat filter digunakan, tampilkan semua hasil yang cocok.
        const displayAll = isShowingAll || hasActiveFilter;

        matchingRows.forEach(function (row, index) {
            row.hidden = !displayAll && index >= initialLimit;
        });

        rows.forEach(function (row) {
            if (!matchingRows.includes(row)) {
                row.hidden = true;
            }
        });

        const matchingCount = matchingRows.length;

        const displayedCount = displayAll
            ? matchingCount
            : Math.min(initialLimit, matchingCount);

        if (noResultsRow) {
            noResultsRow.hidden =
                rows.length === 0 || matchingCount > 0;
        }

        if (resultInfo) {
            resultInfo.textContent =
                'Menampilkan ' + displayedCount +
                ' dari ' + matchingCount +
                ' hasil riwayat kendaraan.';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterHistory);
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', filterHistory);
    }

    if (dateFilter) {
        dateFilter.addEventListener('change', filterHistory);
    }

    if (resetButton) {
        resetButton.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (dateFilter) dateFilter.value = '';

            filterHistory();
        });
    }

    filterHistory();

    /* SPINNER SIMPAN */
    if (editForm) {
        editForm.addEventListener('submit', function (event) {
            if (!editForm.checkValidity()) {
                event.preventDefault();
                editForm.reportValidity();
                resetSaveButton();
                return;
            }

            if (saveButton) {
                saveButton.disabled = true;
                saveButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Menyimpan...';
            }
        });
    }

    /* SPINNER HAPUS */
    if (deleteForm) {
        deleteForm.addEventListener('submit', function () {
            if (deleteSubmitButton) {
                deleteSubmitButton.disabled = true;
                deleteSubmitButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Menghapus...';
            }
        });
    }
});
</script>

@endsection