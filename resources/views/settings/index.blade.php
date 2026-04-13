@extends('layouts.app')
@section('title', 'Configurações — Aruanda Digital')

@push('styles')
<style>
    .settings-group { background:var(--surface); border-radius:var(--r); margin:12px 14px 0; overflow:hidden; box-shadow:var(--shadow-sm); }
    .settings-head { padding:12px 16px 6px; font-size:11px; font-weight:700; color:var(--txt-3); text-transform:uppercase; letter-spacing:.6px; }
    .settings-row { display:flex; align-items:center; justify-content:space-between; padding:13px 16px; border-bottom:1px solid var(--border-lt); gap:12px; }
    .settings-row:last-child { border-bottom:none; }
    .settings-row-info { flex:1; }
    .settings-row-label { font-size:14px; font-weight:600; color:var(--txt); }
    .settings-row-sub { font-size:12px; color:var(--txt-3); margin-top:1px; }
    .form-switch .form-check-input { width:42px; height:22px; cursor:pointer; }
    .form-check-input:checked { background-color:var(--p); border-color:var(--p); }
    .danger-btn { display:flex; align-items:center; gap:10px; padding:13px 16px; font-size:14px; font-weight:600; color:#dc2626; text-decoration:none; border:none; background:none; width:100%; cursor:pointer; }
    .danger-btn i { font-size:18px; }
</style>
@endpush

@section('content')

<div class="page-hdr">
    <h6><i class="bi bi-gear me-2" style="color:var(--p);"></i>Configurações</h6>
</div>

<form method="POST" action="{{ route('settings.update') }}">
@csrf
@method('PUT')

{{-- Notificações --}}
<div class="settings-group">
    <div class="settings-head">Notificações</div>

    <div class="settings-row">
        <div class="settings-row-info">
            <div class="settings-row-label">Notificações do sistema</div>
            <div class="settings-row-sub">Alertas, mensagens e atualizações</div>
        </div>
        <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" name="email_notifications" checked>
        </div>
    </div>

    <div class="settings-row">
        <div class="settings-row-info">
            <div class="settings-row-label">Novos eventos</div>
            <div class="settings-row-sub">Quando eventos forem criados pelas suas casas</div>
        </div>
        <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" checked>
        </div>
    </div>
</div>

{{-- Conta --}}
<div class="settings-group">
    <div class="settings-head">Conta</div>

    <a href="{{ route('profile.change-password') }}" class="settings-row" style="text-decoration:none;color:inherit;">
        <div class="settings-row-info">
            <div class="settings-row-label">Alterar senha</div>
        </div>
        <i class="bi bi-chevron-right" style="font-size:13px;color:var(--txt-4);"></i>
    </a>

    <a href="{{ route('card') }}" class="settings-row" style="text-decoration:none;color:inherit;">
        <div class="settings-row-info">
            <div class="settings-row-label">Carteirinha digital</div>
        </div>
        <i class="bi bi-chevron-right" style="font-size:13px;color:var(--txt-4);"></i>
    </a>
</div>

<div style="padding:14px;">
    <button type="submit" class="btn btn-primary w-100" style="border-radius:var(--r-sm);">
        <i class="bi bi-check-circle me-1"></i>Salvar Configurações
    </button>
</div>

</form>

{{-- Zona de perigo --}}
<div class="settings-group" style="margin-bottom:14px;">
    <div class="settings-head" style="color:#dc2626;">Zona de Perigo</div>
    <button class="danger-btn" onclick="confirmDelete()">
        <i class="bi bi-trash3"></i>Excluir minha conta
    </button>
</div>

<div style="height:24px;"></div>
@endsection

@push('scripts')
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Excluir conta?',
        text: 'Esta ação é irreversível. Todos os seus dados serão perdidos.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    });
}
</script>
@endpush
