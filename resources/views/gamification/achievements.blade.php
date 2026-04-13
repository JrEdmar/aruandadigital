@extends('layouts.app')
@section('title', 'Conquistas — Aruanda Digital')

@push('styles')
<style>
    .ach-hero {
        background:linear-gradient(135deg, var(--p) 0%, var(--p-dk) 100%);
        padding:20px 16px;color:#fff;
        display:flex;align-items:center;gap:14px;
    }
    .ach-hero i { font-size:36px;opacity:.9; }
    .ach-hero-info h6 { font-size:17px;font-weight:800;margin:0 0 3px; }
    .ach-hero-info small { font-size:12px;opacity:.8; }

    /* Progresso geral */
    .ach-progress-bar {
        background:rgba(255,255,255,.25);border-radius:99px;height:6px;
        overflow:hidden;margin-top:8px;
    }
    .ach-progress-fill { height:100%;background:#fff;border-radius:99px; }

    /* Tabs filtro */
    .ach-tabs {
        display:flex;background:#fff;border-bottom:1px solid var(--border-lt);
        position:sticky;top:0;z-index:50;
    }
    .ach-tab {
        flex:1;padding:11px 8px;text-align:center;
        font-size:13px;font-weight:700;color:var(--txt-3);
        border:none;background:none;
        border-bottom:3px solid transparent;cursor:pointer;
        transition:color .2s,border-color .2s;
    }
    .ach-tab.active { color:var(--p);border-bottom-color:var(--p); }

    /* Card conquista */
    .ach-card {
        display:flex;align-items:center;gap:14px;
        padding:14px 16px;border-bottom:1px solid var(--border-lt);
        background:#fff;transition:background .15s;
    }
    .ach-card.locked { opacity:.55; }
    .ach-card:active { background:var(--bg); }

    .ach-icon-wrap {
        width:52px;height:52px;border-radius:14px;
        display:flex;align-items:center;justify-content:center;
        font-size:24px;flex-shrink:0;
    }
    .ach-icon-wrap.earned { background:var(--p-xl);color:var(--p); }
    .ach-icon-wrap.locked-icon { background:var(--bg);color:var(--txt-4); }

    .ach-info { flex:1;min-width:0; }
    .ach-name { font-size:14px;font-weight:700;color:var(--txt);margin-bottom:3px; }
    .ach-desc { font-size:12px;color:var(--txt-3);line-height:1.4; }

    .ach-badge {
        flex-shrink:0;text-align:center;
    }
    .ach-badge .pts {
        font-size:12px;font-weight:800;color:var(--p);
        background:var(--p-xl);padding:4px 10px;border-radius:10px;
        display:block;
    }
    .ach-badge .check {
        font-size:20px;color:var(--p);
    }
    .ach-badge .lock {
        font-size:16px;color:var(--txt-4);
    }
</style>
@endpush

@section('content')

{{-- Hero --}}
@php
    $earnedCount = $earned->count();
    $totalCount  = $all->count();
    $pct = $totalCount > 0 ? round(($earnedCount / $totalCount) * 100) : 0;
@endphp

<div class="ach-hero">
    <i class="bi bi-trophy-fill"></i>
    <div class="ach-hero-info" style="flex:1;">
        <h6>Conquistas</h6>
        <small>{{ $earnedCount }} de {{ $totalCount }} desbloqueadas</small>
        <div class="ach-progress-bar">
            <div class="ach-progress-fill" style="width:{{ $pct }}%;"></div>
        </div>
    </div>
    <div style="font-size:22px;font-weight:800;opacity:.95;">{{ $pct }}%</div>
</div>

{{-- Tabs --}}
<div class="ach-tabs">
    <button class="ach-tab active" onclick="filterAch('all', this)">Todas ({{ $totalCount }})</button>
    <button class="ach-tab" onclick="filterAch('earned', this)">Conquistadas ({{ $earnedCount }})</button>
    <button class="ach-tab" onclick="filterAch('locked', this)">Bloqueadas ({{ $totalCount - $earnedCount }})</button>
</div>

<div id="ach-list">
@forelse($all as $achievement)
@php $isEarned = $earned->contains($achievement->id); @endphp
<div class="ach-card {{ $isEarned ? '' : 'locked' }}" data-state="{{ $isEarned ? 'earned' : 'locked' }}">
    <div class="ach-icon-wrap {{ $isEarned ? 'earned' : 'locked-icon' }}">
        <i class="{{ $achievement->icon ?? 'bi bi-award' }}"></i>
    </div>
    <div class="ach-info">
        <div class="ach-name">{{ $achievement->name }}</div>
        <div class="ach-desc">{{ $achievement->description }}</div>
    </div>
    <div class="ach-badge">
        @if($isEarned)
            <i class="bi bi-check-circle-fill check"></i>
        @else
            <span class="pts">{{ $achievement->points_required }} pts</span>
            <i class="bi bi-lock-fill lock" style="display:block;margin-top:4px;text-align:center;"></i>
        @endif
    </div>
</div>
@empty
<div class="empty-state" style="padding:60px 16px;">
    <i class="bi bi-trophy"></i>
    <p>Nenhuma conquista cadastrada ainda.</p>
</div>
@endforelse
</div>

<div style="height:24px;"></div>
@endsection

@push('scripts')
<script>
function filterAch(state, el) {
    document.querySelectorAll('.ach-tab').forEach(function (t) { t.classList.remove('active'); });
    el.classList.add('active');
    document.querySelectorAll('.ach-card').forEach(function (card) {
        if (state === 'all') { card.style.display = ''; return; }
        card.style.display = card.dataset.state === state ? '' : 'none';
    });
}
</script>
@endpush
