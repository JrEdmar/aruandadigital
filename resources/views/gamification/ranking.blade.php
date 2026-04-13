@extends('layouts.app')
@section('title', 'Ranking — Aruanda Digital')

@push('styles')
<style>
    .rank-hdr {
        background:linear-gradient(135deg, var(--p) 0%, var(--p-dk) 100%);
        padding:20px 16px 48px;text-align:center;color:#fff;
    }
    .rank-hdr h6 { font-size:17px;font-weight:800;margin:0 0 4px; }
    .rank-hdr small { font-size:12px;opacity:.8; }

    /* Pódio */
    .podium-wrap {
        display:flex;align-items:flex-end;justify-content:center;
        gap:8px;padding:0 16px;
        margin:-32px auto 0;position:relative;z-index:2;
    }
    .podium-col {
        flex:1;max-width:100px;display:flex;flex-direction:column;align-items:center;gap:6px;
        text-align:center;
    }
    .podium-avatar {
        width:52px;height:52px;border-radius:50%;
        object-fit:cover;background:var(--p-lt);
        border:3px solid #fff;box-shadow:0 4px 12px rgba(0,0,0,.15);
    }
    .podium-name { font-size:11px;font-weight:700;color:var(--txt);max-width:80px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }
    .podium-pts  { font-size:11px;font-weight:800;color:var(--p); }
    .podium-block {
        width:100%;border-radius:var(--r-sm) var(--r-sm) 0 0;
        display:flex;align-items:center;justify-content:center;
        font-size:18px;font-weight:800;color:#fff;
    }
    .podium-1 { height:72px;background:linear-gradient(to bottom, #f59e0b, #d97706); }
    .podium-2 { height:52px;background:linear-gradient(to bottom, #9ca3af, #6b7280); }
    .podium-3 { height:40px;background:linear-gradient(to bottom, #b45309, #92400e); }
    .podium-col.first .podium-avatar { width:64px;height:64px; }

    /* Lista */
    .rank-row {
        display:flex;align-items:center;gap:12px;
        padding:12px 16px;border-bottom:1px solid var(--border-lt);
        background:#fff;transition:background .15s;
    }
    .rank-row.me { background:var(--p-xl); }
    .rank-row:active { background:var(--bg); }

    .rank-pos {
        width:28px;text-align:center;
        font-size:13px;font-weight:800;
        color:var(--txt-3);flex-shrink:0;
    }
    .rank-pos.top3 { color:#f59e0b; }

    .rank-avatar {
        width:40px;height:40px;border-radius:50%;
        object-fit:cover;background:var(--p-lt);flex-shrink:0;
        border:2px solid var(--p-lt);
    }
    .rank-row.me .rank-avatar { border-color:var(--p); }

    .rank-info { flex:1;min-width:0; }
    .rank-name { font-size:13px;font-weight:700;color:var(--txt); }
    .rank-sub { font-size:11px;color:var(--txt-3); }

    .rank-pts { font-size:14px;font-weight:800;color:var(--p-dk); }

    /* Minha posição sticky */
    .my-rank-bar {
        position:sticky;bottom:var(--bb-h);left:0;right:0;
        background:var(--p);padding:12px 16px;
        display:flex;align-items:center;gap:12px;
        box-shadow:0 -4px 16px rgba(22,101,52,.3);
    }
    .my-rank-bar .rank-avatar { border-color:rgba(255,255,255,.5); }
    .my-rank-bar .rank-name { color:#fff;font-size:13px; }
    .my-rank-bar .rank-sub { color:rgba(255,255,255,.8); }
    .my-rank-bar .rank-pos { color:rgba(255,255,255,.9);font-size:15px; }
    .my-rank-bar .rank-pts { color:#fff; }
</style>
@endpush

@section('content')

<div class="rank-hdr">
    <h6><i class="bi bi-trophy-fill me-2"></i>Ranking</h6>
    <small><i class="bi bi-building me-1"></i>{{ $house->name }}</small>
</div>

@php
    $top3    = $ranking->take(3);
    $rest    = $ranking->skip(3);
    $authId  = auth()->id();
    $myRank  = $ranking->search(fn($u) => $u->id === $authId);
    $myRank  = $myRank !== false ? $myRank + 1 : null;
    $myUser  = $ranking->firstWhere('id', $authId);
    $order1  = $top3->get(0);
    $order2  = $top3->get(1);
    $order3  = $top3->get(2);
@endphp

{{-- Pódio --}}
@if($top3->count() >= 2)
<div class="podium-wrap">
    {{-- 2º --}}
    @if($order2)
    <div class="podium-col">
        <img src="{{ $order2->avatar_url }}" alt="{{ $order2->name }}" class="podium-avatar"
             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($order2->name) }}&background=dcfce7&color=166534&size=52'">
        <div class="podium-name">{{ $order2->name }}</div>
        <div class="podium-pts">{{ number_format($order2->pivot->house_points ?? 0) }} pts</div>
        <div class="podium-block podium-2">2</div>
    </div>
    @endif
    {{-- 1º --}}
    @if($order1)
    <div class="podium-col first">
        <img src="{{ $order1->avatar_url }}" alt="{{ $order1->name }}" class="podium-avatar"
             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($order1->name) }}&background=dcfce7&color=166534&size=64'">
        <div class="podium-name" style="font-weight:800;">{{ $order1->name }}</div>
        <div class="podium-pts" style="font-size:13px;">{{ number_format($order1->pivot->house_points ?? 0) }} pts</div>
        <div class="podium-block podium-1">👑</div>
    </div>
    @endif
    {{-- 3º --}}
    @if($order3)
    <div class="podium-col">
        <img src="{{ $order3->avatar_url }}" alt="{{ $order3->name }}" class="podium-avatar"
             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($order3->name) }}&background=dcfce7&color=166534&size=52'">
        <div class="podium-name">{{ $order3->name }}</div>
        <div class="podium-pts">{{ number_format($order3->pivot->house_points ?? 0) }} pts</div>
        <div class="podium-block podium-3">3</div>
    </div>
    @endif
</div>
@endif

{{-- Lista restante (4º em diante) --}}
@foreach($rest as $index => $rankedUser)
@php $pos = $index + 4; $isMe = $rankedUser->id === $authId; @endphp
<div class="rank-row {{ $isMe ? 'me' : '' }}">
    <span class="rank-pos">#{{ $pos }}</span>
    <img src="{{ $rankedUser->avatar_url }}" alt="{{ $rankedUser->name }}" class="rank-avatar"
         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($rankedUser->name) }}&background=dcfce7&color=166534&size=40'">
    <div class="rank-info">
        <div class="rank-name">{{ $rankedUser->name }} @if($isMe)<span class="badge-cat" style="font-size:9px;">Você</span>@endif</div>
        <div class="rank-sub">Nível {{ $rankedUser->pivot->house_level ?? 1 }}</div>
    </div>
    <span class="rank-pts">{{ number_format($rankedUser->pivot->house_points ?? 0) }}</span>
</div>
@endforeach

@if($ranking->isEmpty())
<div class="empty-state" style="padding:60px 16px;">
    <i class="bi bi-bar-chart-line"></i>
    <p>Nenhum membro no ranking ainda.</p>
</div>
@endif

{{-- Barra "minha posição" --}}
@if($myUser && $myRank > 3)
<div style="height:80px;"></div>
<div class="my-rank-bar">
    <span class="rank-pos">#{{ $myRank }}</span>
    <img src="{{ $myUser->avatar_url }}" alt="{{ $myUser->name }}" class="rank-avatar"
         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($myUser->name) }}&background=dcfce7&color=166534&size=40'">
    <div class="rank-info">
        <div class="rank-name">{{ $myUser->name }}</div>
        <div class="rank-sub">Nível {{ $myUser->pivot->house_level ?? 1 }} · Sua posição</div>
    </div>
    <span class="rank-pts">{{ number_format($myUser->pivot->house_points ?? 0) }}</span>
</div>
@else
<div style="height:24px;"></div>
@endif

@endsection
