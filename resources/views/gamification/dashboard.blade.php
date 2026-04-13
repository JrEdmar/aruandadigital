@extends('layouts.app')
@section('title', 'Minha Jornada — Aruanda Digital')

@push('styles')
<style>
    /* ── Hero XP ── */
    .xp-hero {
        background:linear-gradient(135deg, var(--p) 0%, var(--p-dk) 100%);
        padding:24px 16px 32px;color:#fff;text-align:center;position:relative;overflow:hidden;
    }
    .xp-hero::before {
        content:'';position:absolute;top:-40px;right:-40px;
        width:180px;height:180px;background:rgba(255,255,255,.06);border-radius:50%;
    }
    .xp-hero::after {
        content:'';position:absolute;bottom:-50px;left:-30px;
        width:160px;height:160px;background:rgba(255,255,255,.04);border-radius:50%;
    }
    .xp-level-badge {
        display:inline-flex;align-items:center;gap:6px;
        background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);
        border-radius:20px;padding:4px 14px;font-size:12px;font-weight:700;
        margin-bottom:14px;
    }
    .xp-pts { font-size:52px;font-weight:800;line-height:1;margin-bottom:4px; }
    .xp-label { font-size:13px;opacity:.8;margin-bottom:16px; }
    .xp-bar-wrap {
        background:rgba(255,255,255,.2);border-radius:99px;height:8px;
        overflow:hidden;margin-bottom:6px;
    }
    .xp-bar-fill { height:100%;background:#fff;border-radius:99px;transition:width 1s ease; }
    .xp-progress-labels {
        display:flex;justify-content:space-between;
        font-size:10px;opacity:.75;font-weight:600;
    }

    /* ── Stats flutuando ── */
    .xp-stats {
        display:flex;background:#fff;border-radius:var(--r);
        margin:-16px 14px 0;position:relative;z-index:2;
        box-shadow:var(--shadow-md);overflow:hidden;
    }
    .xp-stat-col {
        flex:1;text-align:center;padding:14px 6px;
        border-right:1px solid var(--border-lt);
    }
    .xp-stat-col:last-child { border-right:none; }
    .xp-stat-num { font-size:20px;font-weight:800;color:var(--p);line-height:1; }
    .xp-stat-lbl { font-size:10px;color:var(--txt-3);margin-top:3px;font-weight:600; }

    /* ── Seções ── */
    .sec-label {
        font-size:11px;font-weight:700;color:var(--txt-3);
        text-transform:uppercase;letter-spacing:.6px;
        padding:16px 16px 8px;display:flex;align-items:center;justify-content:space-between;
    }
    .sec-label a { font-size:12px;font-weight:700;color:var(--p);text-decoration:none;text-transform:none;letter-spacing:0; }

    /* Achievement row */
    .ach-row {
        display:flex;align-items:center;gap:14px;
        padding:12px 16px;border-bottom:1px solid var(--border-lt);
        background:#fff;
    }
    .ach-icon {
        width:46px;height:46px;border-radius:12px;
        background:var(--p-xl);display:flex;align-items:center;justify-content:center;
        font-size:22px;color:var(--p);flex-shrink:0;
    }
    .ach-info { flex:1;min-width:0; }
    .ach-name { font-size:13px;font-weight:700;color:var(--txt);margin-bottom:2px; }
    .ach-desc { font-size:11px;color:var(--txt-3); }
    .ach-pts {
        font-size:11px;font-weight:700;color:var(--p);
        background:var(--p-xl);padding:3px 8px;border-radius:8px;flex-shrink:0;
    }

    /* Quick actions */
    .quick-grid {
        display:grid;grid-template-columns:1fr 1fr;gap:10px;
        padding:0 14px 14px;
    }
    .quick-card {
        background:#fff;border-radius:var(--r);
        box-shadow:var(--shadow-sm);padding:16px;
        display:flex;flex-direction:column;align-items:center;gap:8px;
        text-decoration:none;color:inherit;text-align:center;
        transition:background .15s;
    }
    .quick-card:active { background:var(--p-xl); }
    .quick-card i { font-size:26px;color:var(--p); }
    .quick-card span { font-size:12px;font-weight:700;color:var(--txt-2); }
</style>
@endpush

@section('content')

{{-- Hero XP --}}
<div class="xp-hero">
    <div style="font-size:11px;opacity:.7;margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">
        <i class="bi bi-building me-1"></i>{{ $house->name }}
    </div>
    <div class="xp-level-badge">
        <i class="bi bi-shield-fill-check"></i>
        Nível {{ $houseLevel }}
    </div>
    <div class="xp-pts">{{ number_format($housePoints) }}</div>
    <div class="xp-label">pontos na casa</div>
    @php
        $currentLevelPts = ($houseLevel - 1) * 100;
        $progress  = min(100, (($housePoints - $currentLevelPts) / 100) * 100);
        $remaining = ($houseLevel * 100) - $housePoints;
    @endphp
    <div class="xp-bar-wrap">
        <div class="xp-bar-fill" style="width:{{ $progress }}%;"></div>
    </div>
    <div class="xp-progress-labels">
        <span>Nível {{ $houseLevel }}</span>
        <span>{{ $remaining > 0 ? $remaining . ' pts para Nível ' . ($houseLevel + 1) : 'Nível máximo!' }}</span>
    </div>
</div>

{{-- Stats --}}
<div class="xp-stats">
    <div class="xp-stat-col">
        <div class="xp-stat-num">{{ $achievements->count() }}</div>
        <div class="xp-stat-lbl">Conquistas</div>
    </div>
    <div class="xp-stat-col">
        <div class="xp-stat-num">{{ $houseLevel }}</div>
        <div class="xp-stat-lbl">Nível</div>
    </div>
    <div class="xp-stat-col">
        <div class="xp-stat-num">#{{ $houseRank }}</div>
        <div class="xp-stat-lbl">Ranking</div>
    </div>
</div>

{{-- Ações rápidas --}}
<div class="sec-label" style="margin-top:28px;">Explorar</div>
<div class="quick-grid">
    <a href="{{ route('achievements') }}" class="quick-card">
        <i class="bi bi-trophy"></i>
        <span>Conquistas</span>
    </a>
    <a href="{{ route('ranking') }}" class="quick-card">
        <i class="bi bi-bar-chart-line"></i>
        <span>Ranking</span>
    </a>
    <a href="{{ route('studies') }}" class="quick-card">
        <i class="bi bi-book"></i>
        <span>Estudos</span>
    </a>
    <a href="{{ route('events') }}" class="quick-card">
        <i class="bi bi-calendar-event"></i>
        <span>Eventos</span>
    </a>
</div>

{{-- Conquistas recentes --}}
<div class="sec-label">
    Conquistas Recentes
    <a href="{{ route('achievements') }}">Ver todas</a>
</div>

@forelse($achievements as $achievement)
<div class="ach-row">
    <div class="ach-icon">
        <i class="{{ $achievement->icon ?? 'bi bi-award' }}"></i>
    </div>
    <div class="ach-info">
        <div class="ach-name">{{ $achievement->name }}</div>
        <div class="ach-desc">{{ $achievement->description }}</div>
    </div>
    <span class="ach-pts">+{{ $achievement->points_required }} pts</span>
</div>
@empty
<div class="empty-state" style="padding:32px 16px;">
    <i class="bi bi-trophy"></i>
    <p>Nenhuma conquista ainda. Continue explorando!</p>
    <a href="{{ route('studies') }}" class="btn btn-sm btn-primary mt-2" style="border-radius:20px;">
        Ver Estudos
    </a>
</div>
@endforelse

<div style="height:24px;"></div>
@endsection
