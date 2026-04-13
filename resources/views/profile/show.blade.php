@extends('layouts.app')
@section('title', 'Meu Perfil — Aruanda Digital')

@push('styles')
<style>
    /* ── Header ── */
    .profile-hero {
        background: linear-gradient(135deg, var(--p) 0%, var(--p-dk) 100%);
        padding: 28px 16px 64px;
        text-align: center;
        position: relative;
    }
    .profile-hero-settings {
        position: absolute;
        top: 14px; right: 14px;
        width: 36px; height: 36px;
        background: rgba(255,255,255,.15);
        border: 1.5px solid rgba(255,255,255,.25);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 17px;
        text-decoration: none;
        transition: background .15s;
    }
    .profile-hero-settings:hover { background: rgba(255,255,255,.25); color: #fff; }

    .profile-avatar {
        width: 92px; height: 92px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,.55);
        background: var(--p-lt);
        box-shadow: 0 4px 16px rgba(0,0,0,.2);
    }
    .profile-name { font-size: 19px; font-weight: 800; color: #fff; margin: 10px 0 5px; }
    .profile-role-badge {
        display: inline-block;
        font-size: 11px; font-weight: 700;
        color: rgba(255,255,255,.95);
        background: rgba(255,255,255,.18);
        border: 1px solid rgba(255,255,255,.3);
        padding: 3px 12px;
        border-radius: 20px;
    }

    /* ── Stats flutuando ── */
    .stats-float {
        display: flex;
        background: var(--surface);
        border-radius: var(--r);
        margin: -36px 14px 0;
        position: relative;
        z-index: 2;
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    .stat-col {
        flex: 1;
        text-align: center;
        padding: 16px 6px;
        border-right: 1px solid var(--border-lt);
    }
    .stat-col:last-child { border-right: none; }
    .stat-num   { font-size: 22px; font-weight: 800; color: var(--p); line-height: 1; }
    .stat-lbl   { font-size: 10px; color: var(--txt-3); margin-top: 3px; font-weight: 600; }

    /* ── Seções ── */
    .profile-section {
        background: var(--surface);
        border-radius: var(--r);
        margin: 12px 14px 0;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    .section-head {
        font-size: 11px;
        font-weight: 700;
        color: var(--txt-3);
        text-transform: uppercase;
        letter-spacing: .6px;
        padding: 12px 16px 6px;
    }
    .profile-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        border-bottom: 1px solid var(--border-lt);
        font-size: 14px;
        color: var(--txt-2);
    }
    .profile-row:last-child { border-bottom: none; }
    .profile-row i { font-size: 16px; color: var(--p); width: 20px; text-align: center; flex-shrink: 0; }
    .profile-row span { flex: 1; }

    /* ── Casa chip ── */
    .house-chip {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        text-decoration: none;
        color: inherit;
        border-bottom: 1px solid var(--border-lt);
        transition: background .15s;
    }
    .house-chip:hover { background: var(--bg); }
    .house-chip:last-child { border-bottom: none; }
    .house-chip img {
        width: 42px; height: 42px;
        border-radius: 50%;
        object-fit: cover;
        background: var(--p-lt);
        border: 2px solid var(--p-lt);
    }
    .hc-name { font-size: 14px; font-weight: 700; color: var(--txt); margin-bottom: 2px; }
    .hc-role { font-size: 11px; color: var(--txt-3); }

    /* ── Botões de ação ── */
    .action-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 13px 16px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        color: var(--txt-2);
        border-bottom: 1px solid var(--border-lt);
        background: var(--surface);
        cursor: pointer;
        border: none;
        width: 100%;
        text-align: left;
        transition: background .15s;
    }
    .action-btn:hover { background: var(--bg); }
    .action-btn:last-child { border-bottom: none; }
    .action-btn i { font-size: 17px; width: 22px; text-align: center; color: var(--p); flex-shrink: 0; }
    .action-btn.danger { color: #dc2626; }
    .action-btn.danger i { color: #dc2626; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="profile-hero">
    <a href="{{ route('profile.edit') }}" class="profile-hero-settings">
        <i class="bi bi-gear"></i>
    </a>
    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="profile-avatar"
         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=dcfce7&color=166534&size=92'">
    <div class="profile-name">{{ $user->name }}</div>
    <div class="profile-role-badge">{{ ucfirst($user->role) }}</div>
</div>

{{-- Stats --}}
<div class="stats-float">
    <div class="stat-col">
        <div class="stat-num">{{ number_format($user->points) }}</div>
        <div class="stat-lbl">Pontos</div>
    </div>
    <div class="stat-col">
        <div class="stat-num">{{ $user->level }}</div>
        <div class="stat-lbl">Nível</div>
    </div>
    <div class="stat-col">
        <div class="stat-num">{{ $user->houses->count() }}</div>
        <div class="stat-lbl">Casas</div>
    </div>
</div>

{{-- Informações --}}
<div class="profile-section" style="margin-top:28px;">
    <div class="section-head">Informações</div>
    <div class="profile-row">
        <i class="bi bi-envelope"></i>
        <span>{{ $user->email }}</span>
    </div>
    @if ($user->phone)
    <div class="profile-row">
        <i class="bi bi-telephone"></i>
        <span>{{ $user->phone }}</span>
    </div>
    @endif
    @if ($user->birth_date)
    <div class="profile-row">
        <i class="bi bi-cake"></i>
        <span>{{ \Carbon\Carbon::parse($user->birth_date)->translatedFormat('d \d\e F \d\e Y') }}</span>
    </div>
    @endif
</div>

{{-- Casas --}}
@if ($user->houses->isNotEmpty())
<div class="profile-section">
    <div class="section-head">Minhas Casas</div>
    @foreach ($user->houses as $house)
    <a href="{{ route('houses.show', $house->id) }}" class="house-chip">
        <img src="{{ $house->logo_image_url }}" alt="{{ $house->name }}"
             onerror="this.src='https://placehold.co/42x42/dcfce7/166534?text=A'">
        <div style="flex:1;min-width:0;">
            <div class="hc-name">{{ $house->name }}</div>
            <div class="hc-role">
                    {{ ucfirst($house->pivot->role ?? 'Membro') }}
                    @if($house->pivot->role_membro) · {{ ucfirst($house->pivot->role_membro) }}@endif
                </div>
                @if($house->pivot->entities)
                <div style="font-size:11px;color:#d97706;margin-top:2px;font-weight:600;">
                    <i class="bi bi-stars" style="font-size:10px;"></i> {{ $house->pivot->entities }}
                </div>
                @endif
        </div>
        <i class="bi bi-chevron-right" style="font-size:13px;color:var(--txt-4);"></i>
    </a>
    @endforeach
</div>
@endif

{{-- Ações --}}
<div class="profile-section">
    <div class="section-head">Conta</div>
    <a href="{{ route('profile.edit') }}" class="action-btn">
        <i class="bi bi-pencil-square"></i>Editar Perfil
        <i class="bi bi-chevron-right" style="font-size:12px;color:var(--txt-4);margin-left:auto;"></i>
    </a>
    <a href="{{ route('card') }}" class="action-btn">
        <i class="bi bi-person-badge"></i>Carteirinha Digital
        <i class="bi bi-chevron-right" style="font-size:12px;color:var(--txt-4);margin-left:auto;"></i>
    </a>
    <a href="{{ route('achievements') }}" class="action-btn">
        <i class="bi bi-award"></i>Minhas Conquistas
        <i class="bi bi-chevron-right" style="font-size:12px;color:var(--txt-4);margin-left:auto;"></i>
    </a>
    <a href="{{ route('ranking') }}" class="action-btn">
        <i class="bi bi-trophy"></i>Ranking
        <i class="bi bi-chevron-right" style="font-size:12px;color:var(--txt-4);margin-left:auto;"></i>
    </a>
    <a href="{{ route('settings') }}" class="action-btn">
        <i class="bi bi-gear"></i>Configurações
        <i class="bi bi-chevron-right" style="font-size:12px;color:var(--txt-4);margin-left:auto;"></i>
    </a>
</div>

{{-- Sair --}}
<div class="profile-section" style="margin-bottom:14px;">
    <form method="POST" action="{{ route('logout') }}" id="logout-form">
        @csrf
        <button type="button" class="action-btn danger" onclick="confirmLogout()">
            <i class="bi bi-box-arrow-right"></i>Sair da Conta
        </button>
    </form>
</div>

<div style="height:24px;"></div>
@endsection

@push('scripts')
<script>
function confirmLogout() {
    Swal.fire({
        title: 'Sair da conta?',
        text: 'Você precisará fazer login novamente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sair',
        cancelButtonText: 'Cancelar'
    }).then(function (r) {
        if (r.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}
</script>
@endpush
