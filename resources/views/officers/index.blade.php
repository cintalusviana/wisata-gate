@extends('layouts.app')

@section('title', 'Data Petugas')

@section('content')
<style>
    .officer-page{font-family:'Inter',sans-serif}
    .officer-header{display:flex;align-items:flex-start;justify-content:space-between;gap:24px;margin-bottom:24px}
    .officer-header-left h1{margin:0 0 8px;font-size:30px;font-weight:700;color:#111827}
    .officer-header-left p{margin:0;color:#6b7280;font-size:14px;line-height:1.6}
    .officer-header-actions{display:flex;align-items:center;gap:10px}
    .btn-add-officer{border:0;background:#f97316;color:#fff;padding:11px 17px;border-radius:9px;font-size:14px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:8px;transition:.2s;white-space:nowrap}
    .btn-add-officer:hover{background:#ea580c;color:#fff}
    .officer-summary{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px;margin-bottom:22px}
    .summary-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;padding:20px;display:flex;align-items:center;gap:15px}
    .summary-icon{width:46px;height:46px;border-radius:11px;background:#fff7ed;color:#f97316;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
    .summary-info small{display:block;color:#6b7280;font-size:12px;margin-bottom:4px}
    .summary-info strong{display:block;color:#111827;font-size:22px;font-weight:700}
    .officer-card,.create-officer-panel{background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden}
    .officer-card-header{padding:20px 22px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;gap:15px}
    .officer-card-title h2,.create-officer-panel-heading h2{margin:0 0 4px;font-size:17px;font-weight:700;color:#111827}
    .officer-card-title p,.create-officer-panel-heading p{margin:0;font-size:13px;color:#6b7280}
    .officer-search{width:260px;position:relative}
    .officer-search i{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:15px}
    .officer-search input{width:100%;height:40px;padding:0 14px 0 38px;border:1px solid #d1d5db;border-radius:9px;outline:none;font-size:13px;color:#111827;background:#fff}
    .officer-search input:focus,.create-officer-form-group input:focus,.create-officer-form-group select:focus,.edit-form-group input:focus,.edit-form-group select:focus{border-color:#f97316;box-shadow:0 0 0 3px rgba(249,115,22,.10)}
    .table-responsive{width:100%;overflow-x:auto}
    .officer-table{width:100%;border-collapse:collapse;min-width:760px}
    .officer-table th{background:#f9fafb;color:#6b7280;font-size:12px;font-weight:700;text-align:left;padding:14px 20px;border-bottom:1px solid #e5e7eb;white-space:nowrap}
    .officer-table td{padding:15px 20px;border-bottom:1px solid #f1f5f9;color:#374151;font-size:13px;vertical-align:middle}
    .officer-table tbody tr:hover{background:#fafafa}.officer-table tbody tr:last-child td{border-bottom:none}
    .officer-number{color:#9ca3af;font-weight:600}.officer-name{font-weight:600;color:#111827}.officer-email{color:#6b7280}
    .role-badge,.status-badge{display:inline-flex;align-items:center;justify-content:center;padding:5px 10px;border-radius:999px;font-size:11px;font-weight:600}
    .role-admin{background:#fff7ed;color:#c2410c}.role-petugas{background:#eff6ff;color:#2563eb}.status-aktif{background:#ecfdf5;color:#047857}.status-nonaktif{background:#fef2f2;color:#dc2626}
    .action-buttons{display:flex;align-items:center;gap:7px}
    .action-button{width:34px;height:34px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;color:#6b7280;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:.2s}
    .action-button:hover{background:#f9fafb;color:#111827;border-color:#d1d5db}.action-button.detail:hover{color:#0891b2;border-color:#a5f3fc;background:#ecfeff}.action-button.edit:hover{color:#2563eb;border-color:#bfdbfe;background:#eff6ff}.action-button.delete:hover{color:#dc2626;border-color:#fecaca;background:#fef2f2}
    .empty-state{padding:55px 20px;text-align:center}.empty-state i{font-size:42px;color:#d1d5db;margin-bottom:12px}.empty-state h3{margin:0 0 6px;color:#374151;font-size:16px}.empty-state p{margin:0;color:#9ca3af;font-size:13px}
    .officer-alert{border-radius:10px;border:0;font-size:13px;margin-bottom:20px}
    /* Form tambah muncul di halaman yang sama */
    .create-officer-panel{display:none;margin-bottom:22px;animation:officerFormAppear .22s ease}
    .create-officer-panel.is-visible{display:block}
    .create-officer-panel-header{display:flex;align-items:center;gap:12px;padding:20px 22px;border-bottom:1px solid #e5e7eb}
    .create-officer-panel-icon,.edit-modal-icon{width:42px;height:42px;border-radius:11px;background:#fff7ed;color:#f97316;display:flex;align-items:center;justify-content:center;font-size:19px;flex-shrink:0}
    .create-officer-panel-heading{flex:1;min-width:0}
    .create-officer-panel-body{padding:24px 22px}
    .create-officer-form-grid,.edit-officer-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px 22px}
    .create-officer-form-group,.edit-form-group{display:flex;flex-direction:column;min-width:0}
    .create-officer-form-group label,.edit-form-group label{margin-bottom:7px;color:#374151;font-size:13px;font-weight:600}
    .create-officer-form-group input,.create-officer-form-group select,.edit-form-group input,.edit-form-group select{width:100%;height:43px;padding:0 13px;border:1px solid #d1d5db;border-radius:9px;background:#fff;color:#111827;font-size:13px;outline:none;transition:.2s}
    .create-officer-form-group small,.edit-form-help{margin-top:6px;color:#9ca3af;font-size:11px}
    .create-officer-panel-footer,.edit-officer-modal .modal-footer{display:flex;align-items:center;justify-content:flex-end;gap:10px;padding:17px 22px;border-top:1px solid #e5e7eb;background:#f9fafb}
    .btn-modal-cancel,.btn-detail-close{border:1px solid #d1d5db;background:#fff;color:#374151;padding:10px 17px;border-radius:9px;font-size:13px;font-weight:600}
    .btn-modal-cancel:hover,.btn-detail-close:hover{background:#f3f4f6}
    .btn-modal-save{border:0;background:#f97316;color:#fff;padding:10px 18px;border-radius:9px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:7px}
    .btn-modal-save:hover{background:#ea580c}
    @keyframes officerFormAppear{from{opacity:0;transform:translateY(-7px)}to{opacity:1;transform:translateY(0)}}
    /* Modal detail/edit/hapus tetap digunakan */
    .detail-officer-modal .modal-dialog,.edit-officer-modal .modal-dialog{max-width:850px;width:90%}
    .detail-officer-modal .modal-content,.edit-officer-modal .modal-content,.delete-modal .modal-content{border:0;border-radius:16px;overflow:hidden;box-shadow:0 24px 60px rgba(15,23,42,.24)}
    .detail-officer-modal .modal-header,.edit-officer-modal .modal-header{padding:20px 26px;border-bottom:1px solid #e5e7eb;background:#fff}
    .detail-modal-heading,.edit-modal-title{display:flex;align-items:center;gap:12px}
    .detail-modal-icon{width:40px;height:40px;border-radius:10px;background:#ecfeff;color:#0891b2;display:flex;align-items:center;justify-content:center;font-size:18px}
    .detail-modal-heading h5,.edit-modal-title h5{margin:0 0 3px;font-size:17px;font-weight:700;color:#111827}
    .detail-modal-heading span,.edit-modal-title span{font-size:12px;color:#6b7280}
    .detail-officer-modal .modal-body,.edit-officer-modal .modal-body{padding:26px 28px;background:#fff}
    .detail-profile{display:flex;align-items:center;gap:15px;padding:17px;border:1px solid #e5e7eb;border-radius:13px;margin-bottom:20px;background:#fafafa}
    .detail-avatar{width:54px;height:54px;border-radius:13px;background:#fff7ed;color:#f97316;display:flex;align-items:center;justify-content:center;font-size:23px;flex-shrink:0}
    .detail-profile-name{font-size:16px;font-weight:700;color:#111827;margin-bottom:4px}.detail-profile-email{color:#6b7280;font-size:12px}
    .detail-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
    .detail-item{border:1px solid #e5e7eb;border-radius:11px;padding:15px;background:#fff}
    .detail-item-label{display:block;color:#9ca3af;font-size:11px;margin-bottom:6px}.detail-item-value{color:#111827;font-size:13px;font-weight:600;overflow-wrap:anywhere}
    .detail-officer-modal .modal-footer{padding:17px 28px;border-top:1px solid #e5e7eb;background:#f9fafb;display:flex;justify-content:flex-end}
    .delete-modal .modal-dialog{max-width:430px}.delete-modal .modal-body{padding:28px;text-align:center}
    .delete-icon{width:54px;height:54px;border-radius:50%;background:#fef2f2;color:#dc2626;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:23px}
    .delete-modal h5{margin:0 0 8px;font-size:17px;font-weight:700;color:#111827}.delete-modal p{margin:0;color:#6b7280;font-size:13px;line-height:1.6}
    .delete-modal .modal-footer{border-top:1px solid #e5e7eb;padding:15px 20px;display:flex;justify-content:flex-end;gap:9px}
    .btn-delete-confirm{border:0;background:#dc2626;color:#fff;padding:9px 16px;border-radius:8px;font-size:13px;font-weight:600}.btn-delete-confirm:hover{background:#b91c1c}
    .modal-backdrop.show{background:rgba(15,23,42,.38)!important;opacity:1!important;backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px)}
    /* Dark mode */
    body.dark-mode .officer-header-left h1,body.dark-mode .summary-info strong,body.dark-mode .officer-card-title h2,body.dark-mode .officer-table td,body.dark-mode .officer-name,body.dark-mode .detail-modal-heading h5,body.dark-mode .detail-profile-name,body.dark-mode .detail-item-value,body.dark-mode .edit-modal-title h5,body.dark-mode .create-officer-panel-heading h2,body.dark-mode .create-officer-form-group label,body.dark-mode .edit-form-group label,body.dark-mode .delete-modal h5{color:#f9fafb}
    body.dark-mode .officer-header-left p,body.dark-mode .officer-card-title p,body.dark-mode .summary-info small,body.dark-mode .officer-email,body.dark-mode .detail-modal-heading span,body.dark-mode .detail-profile-email,body.dark-mode .detail-item-label,body.dark-mode .edit-modal-title span,body.dark-mode .create-officer-panel-heading p,body.dark-mode .create-officer-form-group small,body.dark-mode .edit-form-help,body.dark-mode .delete-modal p{color:#9ca3af}
    body.dark-mode .summary-card,body.dark-mode .officer-card,body.dark-mode .create-officer-panel,body.dark-mode .detail-officer-modal .modal-content,body.dark-mode .edit-officer-modal .modal-content,body.dark-mode .delete-modal .modal-content{background:#111827;border-color:#374151}
    body.dark-mode .officer-card-header,body.dark-mode .officer-table th,body.dark-mode .officer-table td,body.dark-mode .create-officer-panel-header,body.dark-mode .create-officer-panel-footer,body.dark-mode .detail-officer-modal .modal-header,body.dark-mode .detail-officer-modal .modal-body,body.dark-mode .detail-officer-modal .modal-footer,body.dark-mode .edit-officer-modal .modal-header,body.dark-mode .edit-officer-modal .modal-body,body.dark-mode .edit-officer-modal .modal-footer,body.dark-mode .delete-modal .modal-footer{border-color:#374151}
    body.dark-mode .officer-table th{background:#1f2937}body.dark-mode .officer-table tbody tr:hover{background:#1f2937}
    body.dark-mode .officer-search input,body.dark-mode .create-officer-form-group input,body.dark-mode .create-officer-form-group select,body.dark-mode .edit-form-group input,body.dark-mode .edit-form-group select{background:#1f2937;border-color:#4b5563;color:#f9fafb}
    body.dark-mode .action-button{background:#1f2937;border-color:#374151;color:#9ca3af}
    body.dark-mode .detail-profile,body.dark-mode .detail-item{background:#1f2937;border-color:#374151}
    body.dark-mode .create-officer-panel-footer,body.dark-mode .detail-officer-modal .modal-footer,body.dark-mode .edit-officer-modal .modal-footer{background:#1f2937}
    body.dark-mode .btn-detail-close,body.dark-mode .btn-modal-cancel{background:#1f2937;border-color:#4b5563;color:#e5e7eb}
    @media(max-width:900px){.officer-summary{grid-template-columns:1fr}.officer-header{flex-direction:column}.officer-header-actions{width:100%}.btn-add-officer{width:100%}.officer-card-header{flex-direction:column;align-items:stretch}.officer-search{width:100%}.detail-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:700px){.edit-officer-modal .modal-dialog,.detail-officer-modal .modal-dialog{width:95%;margin:12px auto}.edit-officer-form-grid,.create-officer-form-grid,.detail-grid{grid-template-columns:1fr}.edit-officer-modal .modal-header,.edit-officer-modal .modal-body,.detail-officer-modal .modal-header,.detail-officer-modal .modal-body{padding:20px}.edit-officer-modal .modal-footer,.detail-officer-modal .modal-footer{padding:15px 20px}}
    @media(max-width:430px){.officer-header-left h1{font-size:24px}.officer-card-header{padding:16px}.officer-table th,.officer-table td{padding:12px 14px}.create-officer-panel-header,.create-officer-panel-body{padding:18px 16px}.create-officer-panel-footer{padding:15px 16px;flex-wrap:wrap}}
</style>

<div class="officer-page">
    <div class="officer-header">
        <div class="officer-header-left">
            <h1>Data Petugas</h1>
            <p>Kelola data admin dan petugas yang bertugas mencatat kendaraan masuk dan keluar area wisata.</p>
        </div>
        <div class="officer-header-actions">
            <button type="button" class="btn-add-officer" id="btnToggleCreateOfficer" aria-expanded="false" aria-controls="createOfficerPanel">
                <i class="bi bi-plus-lg"></i><span>Tambah Petugas</span>
            </button>
        </div>
    </div>

    {{-- FORM TAMBAH PETUGAS: MUNCUL DI ATAS STATISTIK --}}
    <div class="create-officer-panel {{ $errors->any() && old('_form') === 'create_officer' ? 'is-visible' : '' }}" id="createOfficerPanel" aria-hidden="{{ $errors->any() && old('_form') === 'create_officer' ? 'false' : 'true' }}">
        <div class="create-officer-panel-header">
            <div class="create-officer-panel-icon"><i class="bi bi-person-plus"></i></div>
            <div class="create-officer-panel-heading">
                <h2>Tambah Petugas</h2>
                <p>Buat akun admin atau petugas untuk mengakses sistem WisataGate.</p>
            </div>
        </div>
        <form id="createOfficerForm" action="{{ route('officers.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_form" value="create_officer">
            <div class="create-officer-panel-body">
                <div class="create-officer-form-grid">
                    <div class="create-officer-form-group">
                        <label for="createOfficerName">Nama Petugas</label>
                        <input type="text" id="createOfficerName" name="name" value="{{ old('_form') === 'create_officer' ? old('name') : '' }}" autocomplete="name" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="create-officer-form-group">
                        <label for="createOfficerEmail">Email</label>
                        <input type="email" id="createOfficerEmail" name="email" value="{{ old('_form') === 'create_officer' ? old('email') : '' }}" autocomplete="email" placeholder="contoh@email.com" required>
                    </div>
                    <div class="create-officer-form-group">
                        <label for="createOfficerRole">Role</label>
                        <select id="createOfficerRole" name="role" required>
                            <option value="petugas" @selected(old('_form') === 'create_officer' ? old('role', 'petugas') === 'petugas' : true)>Petugas</option>
                            <option value="admin" @selected(old('_form') === 'create_officer' && old('role') === 'admin')>Admin</option>
                        </select>
                    </div>
                    <div class="create-officer-form-group">
                        <label for="createOfficerStatus">Status</label>
                        <select id="createOfficerStatus" name="status" required>
                            <option value="aktif" @selected(old('_form') === 'create_officer' ? old('status', 'aktif') === 'aktif' : true)>Aktif</option>
                            <option value="nonaktif" @selected(old('_form') === 'create_officer' && old('status') === 'nonaktif')>Nonaktif</option>
                        </select>
                    </div>
                    <div class="create-officer-form-group">
                        <label for="createOfficerPassword">Password</label>
                        <input type="password" id="createOfficerPassword" name="password" autocomplete="new-password" placeholder="Minimal 8 karakter" minlength="8" required>
                        <small>Gunakan password minimal 8 karakter.</small>
                    </div>
                    <div class="create-officer-form-group">
                        <label for="createOfficerPasswordConfirmation">Konfirmasi Password</label>
                        <input type="password" id="createOfficerPasswordConfirmation" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi password" minlength="8" required>
                    </div>
                </div>
            </div>
            <div class="create-officer-panel-footer">
                <button type="button" class="btn-modal-cancel" id="btnCancelCreateOfficer"><i class="bi bi-x-lg me-1"></i>Batal</button>
                <button type="submit" class="btn-modal-save"><i class="bi bi-check-lg"></i>Simpan Petugas</button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success officer-alert"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger officer-alert"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger officer-alert">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle me-2"></i>Terdapat kesalahan:</div>
            <ul class="mb-0 ps-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="officer-summary">
        <div class="summary-card"><div class="summary-icon"><i class="bi bi-people"></i></div><div class="summary-info"><small>Total Pengguna</small><strong>{{ $officers->count() }}</strong></div></div>
        <div class="summary-card"><div class="summary-icon"><i class="bi bi-person-check"></i></div><div class="summary-info"><small>Pengguna Aktif</small><strong>{{ $officers->where('status', 'aktif')->count() }}</strong></div></div>
        <div class="summary-card"><div class="summary-icon"><i class="bi bi-shield-check"></i></div><div class="summary-info"><small>Admin</small><strong>{{ $officers->where('role', 'admin')->count() }}</strong></div></div>
    </div>

    <div class="officer-card">
        <div class="officer-card-header">
            <div class="officer-card-title"><h2>Daftar Petugas</h2><p>Data akun yang dapat mengakses sistem WisataGate.</p></div>
            <div class="officer-search"><i class="bi bi-search"></i><input type="text" id="officerSearch" placeholder="Cari nama atau email..."></div>
        </div>
        <div class="table-responsive">
            <table class="officer-table">
                <thead><tr><th width="60">No</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th width="150">Aksi</th></tr></thead>
                <tbody id="officerTableBody">
                @forelse($officers as $index => $officer)
                    <tr class="officer-row">
                        <td><span class="officer-number">{{ $index + 1 }}</span></td>
                        <td><div class="officer-name">{{ $officer->name }}</div></td>
                        <td><div class="officer-email">{{ $officer->email }}</div></td>
                        <td>@if($officer->role === 'admin')<span class="role-badge role-admin">Admin</span>@else<span class="role-badge role-petugas">Petugas</span>@endif</td>
                        <td>@if($officer->status === 'aktif')<span class="status-badge status-aktif">Aktif</span>@else<span class="status-badge status-nonaktif">Nonaktif</span>@endif</td>
                        <td>
                            <div class="action-buttons">
                                <button type="button" class="action-button detail" title="Detail" onclick="openDetailOfficerModal({{ $officer->id }}, @js($officer->name), @js($officer->email), @js($officer->role), @js($officer->status), @js($officer->created_at ? $officer->created_at->format('d F Y, H:i') : '-'))"><i class="bi bi-eye"></i></button>
                                <button type="button" class="action-button edit" title="Edit" onclick="openEditOfficerModal({{ $officer->id }}, @js($officer->name), @js($officer->email), @js($officer->role), @js($officer->status))"><i class="bi bi-pencil"></i></button>
                                @if($officer->id !== auth()->id())
                                    <button type="button" class="action-button delete" title="Hapus" onclick="openDeleteOfficerModal({{ $officer->id }}, @js($officer->name))"><i class="bi bi-trash3"></i></button>
                                @else
                                    <button type="button" class="action-button delete" disabled title="Akun yang sedang digunakan" style="opacity:.45;cursor:not-allowed"><i class="bi bi-trash3"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="bi bi-people"></i><h3>Belum ada data petugas</h3><p>Tambahkan data admin atau petugas untuk mulai menggunakan sistem.</p></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- DETAIL MODAL --}}
<div class="modal fade detail-officer-modal" id="detailOfficerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header">
            <div class="detail-modal-heading"><div class="detail-modal-icon"><i class="bi bi-person-vcard"></i></div><div><h5>Detail Petugas</h5><span>Informasi lengkap akun petugas.</span></div></div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
            <div class="detail-profile"><div class="detail-avatar"><i class="bi bi-person"></i></div><div><div class="detail-profile-name" id="detailOfficerName">-</div><div class="detail-profile-email" id="detailOfficerEmail">-</div></div></div>
            <div class="detail-grid">
                <div class="detail-item"><span class="detail-item-label">Nama Petugas</span><div class="detail-item-value" id="detailOfficerNameValue">-</div></div>
                <div class="detail-item"><span class="detail-item-label">Email</span><div class="detail-item-value" id="detailOfficerEmailValue">-</div></div>
                <div class="detail-item"><span class="detail-item-label">Role</span><div class="detail-item-value" id="detailOfficerRoleValue">-</div></div>
                <div class="detail-item"><span class="detail-item-label">Status</span><div class="detail-item-value" id="detailOfficerStatusValue">-</div></div>
                <div class="detail-item"><span class="detail-item-label">ID Pengguna</span><div class="detail-item-value" id="detailOfficerIdValue">-</div></div>
                <div class="detail-item"><span class="detail-item-label">Terdaftar</span><div class="detail-item-value" id="detailOfficerCreatedValue">-</div></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-detail-close" data-bs-dismiss="modal">Tutup</button></div>
    </div></div>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade edit-officer-modal" id="editOfficerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-header">
            <div class="edit-modal-title"><div class="edit-modal-icon"><i class="bi bi-pencil-square"></i></div><div><h5>Edit Data Petugas</h5><span>Perbarui informasi akun petugas.</span></div></div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <form id="editOfficerForm" method="POST">
            @csrf @method('PUT')
            <div class="modal-body"><div class="edit-officer-form-grid">
                <div class="edit-form-group"><label for="editOfficerName">Nama Petugas</label><input type="text" id="editOfficerName" name="name" required></div>
                <div class="edit-form-group"><label for="editOfficerEmail">Email</label><input type="email" id="editOfficerEmail" name="email" required></div>
                <div class="edit-form-group"><label for="editOfficerRole">Role</label><select id="editOfficerRole" name="role" required><option value="admin">Admin</option><option value="petugas">Petugas</option></select></div>
                <div class="edit-form-group"><label for="editOfficerStatus">Status</label><select id="editOfficerStatus" name="status" required><option value="aktif">Aktif</option><option value="nonaktif">Nonaktif</option></select></div>
                <div class="edit-form-group"><label for="editOfficerPassword">Password Baru</label><input type="password" id="editOfficerPassword" name="password" autocomplete="new-password" placeholder="Kosongkan jika tidak diubah"><div class="edit-form-help">Kosongkan jika password tidak ingin diubah.</div></div>
                <div class="edit-form-group"><label for="editOfficerPasswordConfirmation">Konfirmasi Password</label><input type="password" id="editOfficerPasswordConfirmation" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi password baru"></div>
            </div></div>
            <div class="modal-footer"><button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn-modal-save"><i class="bi bi-check-lg"></i>Simpan Perubahan</button></div>
        </form>
    </div></div>
</div>

{{-- DELETE MODAL --}}
<div class="modal fade delete-modal" id="deleteOfficerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
        <div class="modal-body"><div class="delete-icon"><i class="bi bi-trash3"></i></div><h5>Hapus Data Petugas?</h5><p>Data petugas <strong id="deleteOfficerName"></strong> akan dihapus dari sistem. Tindakan ini tidak dapat dibatalkan.</p></div>
        <div class="modal-footer"><button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button><form id="deleteOfficerForm" method="POST" style="margin:0">@csrf @method('DELETE')<button type="submit" class="btn-delete-confirm">Hapus Data</button></form></div>
    </div></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleButton = document.getElementById('btnToggleCreateOfficer');
    const cancelButton = document.getElementById('btnCancelCreateOfficer');
    const formPanel = document.getElementById('createOfficerPanel');
    const nameInput = document.getElementById('createOfficerName');

    function openCreateOfficerForm() {
        if (!formPanel || !toggleButton) return;
        formPanel.classList.add('is-visible');
        formPanel.setAttribute('aria-hidden', 'false');
        toggleButton.setAttribute('aria-expanded', 'true');
        toggleButton.innerHTML = '<i class="bi bi-dash-lg"></i><span>Tutup Form</span>';
        setTimeout(function () {
            if (nameInput) nameInput.focus();
            formPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 50);
    }

    function closeCreateOfficerForm() {
        if (!formPanel || !toggleButton) return;
        formPanel.classList.remove('is-visible');
        formPanel.setAttribute('aria-hidden', 'true');
        toggleButton.setAttribute('aria-expanded', 'false');
        toggleButton.innerHTML = '<i class="bi bi-plus-lg"></i><span>Tambah Petugas</span>';
    }

    if (toggleButton && formPanel) {
        toggleButton.addEventListener('click', function () {
            formPanel.classList.contains('is-visible') ? closeCreateOfficerForm() : openCreateOfficerForm();
        });
    }
    if (cancelButton) cancelButton.addEventListener('click', closeCreateOfficerForm);

    @if ($errors->any() && old('_form') === 'create_officer')
        openCreateOfficerForm();
    @endif

    const searchInput = document.getElementById('officerSearch');
    const rows = document.querySelectorAll('.officer-row');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();
            rows.forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
            });
        });
    }
});

window.openDetailOfficerModal = function (id, name, email, role, status, createdAt) {
    document.getElementById('detailOfficerName').textContent = name;
    document.getElementById('detailOfficerEmail').textContent = email;
    document.getElementById('detailOfficerNameValue').textContent = name;
    document.getElementById('detailOfficerEmailValue').textContent = email;
    document.getElementById('detailOfficerRoleValue').textContent = role === 'admin' ? 'Admin' : 'Petugas';
    document.getElementById('detailOfficerStatusValue').textContent = status === 'aktif' ? 'Aktif' : 'Nonaktif';
    document.getElementById('detailOfficerIdValue').textContent = '#' + id;
    document.getElementById('detailOfficerCreatedValue').textContent = createdAt;
    bootstrap.Modal.getOrCreateInstance(document.getElementById('detailOfficerModal')).show();
};

window.openEditOfficerModal = function (id, name, email, role, status) {
    document.getElementById('editOfficerName').value = name;
    document.getElementById('editOfficerEmail').value = email;
    document.getElementById('editOfficerRole').value = role;
    document.getElementById('editOfficerStatus').value = status;
    document.getElementById('editOfficerPassword').value = '';
    document.getElementById('editOfficerPasswordConfirmation').value = '';
    document.getElementById('editOfficerForm').action = "{{ url('/officers') }}/" + id;
    bootstrap.Modal.getOrCreateInstance(document.getElementById('editOfficerModal')).show();
};

window.openDeleteOfficerModal = function (id, name) {
    document.getElementById('deleteOfficerName').textContent = name;
    document.getElementById('deleteOfficerForm').action = "{{ url('/officers') }}/" + id;
    bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteOfficerModal')).show();
};
</script>
@endsection
