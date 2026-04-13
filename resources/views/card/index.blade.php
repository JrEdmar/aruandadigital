@extends('layouts.app')
@section('title', 'Carteirinha — Aruanda Digital')

@push('styles')
<style>
    .card-wrap { padding:20px 16px; }
    .id-card {
        background: linear-gradient(135deg, var(--p-dk) 0%, var(--p) 60%, #22c55e 100%);
        border-radius: 20px;
        padding: 24px;
        color: #fff;
        box-shadow: 0 8px 32px rgba(22,101,52,.35);
        position: relative;
        overflow: hidden;
    }
    .id-card::before {
        content:'';
        position:absolute;
        top:-40px; right:-40px;
        width:180px; height:180px;
        background:rgba(255,255,255,.06);
        border-radius:50%;
    }
    .id-card::after {
        content:'';
        position:absolute;
        bottom:-60px; left:-30px;
        width:200px; height:200px;
        background:rgba(255,255,255,.04);
        border-radius:50%;
    }
    .card-logo { font-size:13px; font-weight:800; opacity:.85; letter-spacing:.5px; margin-bottom:20px; }
    .card-avatar { width:72px; height:72px; border-radius:50%; object-fit:cover; border:3px solid rgba(255,255,255,.5); background:var(--p-lt); margin-bottom:14px; }
    .card-name { font-size:20px; font-weight:800; margin-bottom:4px; }
    .card-role { font-size:12px; opacity:.8; font-weight:600; margin-bottom:16px; }
    .card-row { display:flex; gap:16px; margin-bottom:4px; }
    .card-field { flex:1; }
    .card-field-label { font-size:9px; opacity:.7; text-transform:uppercase; letter-spacing:.6px; font-weight:700; }
    .card-field-value { font-size:13px; font-weight:700; }
    .card-house { margin-top:16px; padding-top:14px; border-top:1px solid rgba(255,255,255,.2); display:flex; align-items:center; gap:10px; }
    .card-house img { width:36px; height:36px; border-radius:50%; border:2px solid rgba(255,255,255,.4); background:var(--p-lt); }
    .card-house-name { font-size:13px; font-weight:700; }
    .card-house-sub { font-size:11px; opacity:.75; }
    .share-btn { display:flex; align-items:center; justify-content:center; gap:8px; padding:14px; background:var(--surface); border:1.5px solid var(--border); border-radius:var(--r); font-size:14px; font-weight:700; color:var(--p); text-decoration:none; margin-top:16px; cursor:pointer; transition:background .15s; }
    .share-btn:hover { background:var(--p-xl); }
</style>
@endpush

@section('content')

<div class="page-hdr">
    <h6><i class="bi bi-person-badge me-2" style="color:var(--p);"></i>Carteirinha Digital</h6>
</div>

<div class="card-wrap">
    <div class="id-card">
        <div class="card-logo">✦ ARUANDA DIGITAL</div>

        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="card-avatar"
             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=dcfce7&color=166534&size=72'">

        <div class="card-name">{{ $user->name }}</div>
        <div class="card-role">{{ ucfirst($user->role) }}</div>

        <div class="card-row">
            <div class="card-field">
                <div class="card-field-label">Nível</div>
                <div class="card-field-value">{{ $user->level }}</div>
            </div>
            <div class="card-field">
                <div class="card-field-label">Pontos</div>
                <div class="card-field-value">{{ number_format($user->points) }}</div>
            </div>
            <div class="card-field">
                <div class="card-field-label">Membro desde</div>
                <div class="card-field-value">{{ $user->created_at->format('m/Y') }}</div>
            </div>
        </div>

        @if($house)
        <div class="card-house">
            <img src="{{ $house->logo_image_url }}" alt="{{ $house->name }}"
                 onerror="this.src='https://placehold.co/36x36/dcfce7/166534?text=A'">
            <div style="flex:1;min-width:0;">
                <div class="card-house-name">{{ $house->name }}</div>
                <div class="card-house-sub">
                    {{ ucfirst($house->pivot->role ?? 'Membro') }}
                    @if($house->pivot->role_membro)
                        · {{ ucfirst($house->pivot->role_membro) }}
                    @endif
                </div>
                @if($house->pivot->entities)
                <div style="margin-top:6px;font-size:11px;opacity:.9;">
                    <span style="opacity:.7;font-size:9px;text-transform:uppercase;letter-spacing:.5px;font-weight:700;">Entidades</span><br>
                    ✦ {{ $house->pivot->entities }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <button class="share-btn w-100" onclick="shareCard()">
        <i class="bi bi-share"></i>Compartilhar Carteirinha
    </button>

    <a href="{{ route('profile') }}" class="share-btn w-100" style="margin-top:8px;color:var(--txt-2);">
        <i class="bi bi-arrow-left"></i>Voltar ao Perfil
    </a>
</div>

<div style="height:24px;"></div>
@endsection

@push('scripts')
<script>
function shareCard() {
    if (navigator.share) {
        navigator.share({ title: 'Minha Carteirinha — Aruanda Digital', url: window.location.href });
    } else {
        navigator.clipboard.writeText(window.location.href);
        Swal.fire({ icon:'success', title:'Link copiado!', timer:1500, showConfirmButton:false, toast:true, position:'top-end' });
    }
}
</script>
@endpush
