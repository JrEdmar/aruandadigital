@extends('layouts.app')
@section('title', 'Início — Aruanda Digital')

@push('styles')
<style>
    /* ── Header ── */
    .home-hdr {
        background: #fff;
        padding: 14px 16px 12px;
        border-bottom: 1px solid var(--border-lt);
    }
    .home-hdr-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .home-greeting-sub  { font-size: 11px; color: var(--txt-3); font-weight: 500; }
    .home-greeting-name { font-size: 20px; font-weight: 800; color: var(--txt); line-height: 1.1; }
    .home-avatar {
        width: 44px; height: 44px; border-radius: 50%;
        object-fit: cover; border: 2.5px solid var(--p-lt);
        background: var(--p-lt); flex-shrink: 0;
    }
    .home-hdr-right { display: flex; align-items: center; gap: 8px; }
    .home-notif-btn {
        width: 38px; height: 38px; border-radius: 50%;
        border: none; background: var(--bg);
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; color: var(--txt-2); cursor: pointer;
        text-decoration: none; position: relative;
    }
    .home-notif-dot {
        position: absolute; top: 6px; right: 6px;
        width: 8px; height: 8px; background: #ef4444;
        border-radius: 50%; border: 1.5px solid #fff;
    }

    /* ── Search ── */
    .home-search {
        display: flex; align-items: center;
        background: var(--bg); border-radius: 12px;
        padding: 10px 14px; gap: 8px; cursor: pointer;
        text-decoration: none;
    }
    .home-search i { font-size: 16px; color: var(--txt-4); }
    .home-search span { font-size: 14px; color: var(--txt-4); }

    /* ── Quick Actions ── */
    .quick-actions {
        display: flex; gap: 10px;
        padding: 14px 16px 10px;
        background: #fff;
        border-bottom: 1px solid var(--border-lt);
        overflow-x: auto; scrollbar-width: none;
    }
    .quick-actions::-webkit-scrollbar { display: none; }
    .qa-btn {
        flex-shrink: 0;
        display: flex; flex-direction: column;
        align-items: center; gap: 6px;
        text-decoration: none;
        cursor: pointer;
    }
    .qa-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
    }
    .qa-label {
        font-size: 11px; font-weight: 600;
        color: var(--txt-2); white-space: nowrap;
    }

    /* ── Section header ── */
    .sec-hdr {
        display: flex; align-items: center;
        justify-content: space-between;
        padding: 14px 16px 8px;
    }
    .sec-hdr-title { font-size: 15px; font-weight: 700; color: var(--txt); }
    .sec-hdr a { font-size: 12px; font-weight: 600; color: var(--p); text-decoration: none; }

    /* ── Card de Casa (horizontal scroll) ── */
    .houses-scroll {
        display: flex; gap: 12px;
        padding: 0 16px 16px;
        overflow-x: auto; scrollbar-width: none;
    }
    .houses-scroll::-webkit-scrollbar { display: none; }
    .house-card {
        flex-shrink: 0; width: 200px;
        background: #fff; border-radius: var(--r);
        overflow: hidden; box-shadow: var(--shadow-sm);
        text-decoration: none; color: inherit;
        display: block;
    }
    .house-card-img {
        width: 100%; height: 110px; object-fit: cover;
        background: var(--p-lt); display: block;
    }
    .house-card-body { padding: 10px 12px; }
    .house-card-name {
        font-size: 13px; font-weight: 700;
        color: var(--txt); margin-bottom: 4px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .house-card-sub {
        font-size: 11px; color: var(--txt-3);
        display: flex; align-items: center; gap: 4px;
        margin-bottom: 8px;
    }
    .house-card-stats {
        display: flex; gap: 8px;
    }
    .house-stat {
        display: inline-flex; align-items: center; gap: 3px;
        font-size: 10px; font-weight: 600; color: var(--txt-3);
        background: var(--bg); border-radius: 6px; padding: 2px 6px;
    }

    /* ── Card de Evento (lista vertical) ── */
    .ev-list-card {
        display: flex; align-items: stretch;
        background: #fff;
        border-bottom: 1px solid var(--border-lt);
        text-decoration: none; color: inherit;
        transition: background .15s;
    }
    .ev-list-card:active { background: var(--p-xl); }
    .ev-date-col {
        width: 56px; flex-shrink: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        background: var(--p-xl);
        border-right: 1.5px solid var(--p-lt);
        padding: 12px 4px;
    }
    .ev-date-col .day { font-size: 22px; font-weight: 800; color: var(--p-dk); line-height: 1; }
    .ev-date-col .mon { font-size: 10px; font-weight: 700; color: var(--p); text-transform: uppercase; margin-top: 2px; }
    .ev-list-img {
        width: 80px; flex-shrink: 0;
        object-fit: cover; background: var(--p-lt);
    }
    .ev-list-body {
        flex: 1; min-width: 0; padding: 12px;
        display: flex; flex-direction: column; gap: 4px;
    }
    .ev-list-name {
        font-size: 14px; font-weight: 700; color: var(--txt);
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;
    }
    .ev-list-meta {
        font-size: 11px; color: var(--txt-3);
        display: flex; align-items: center; gap: 4px;
    }
    .ev-list-footer {
        display: flex; align-items: center; gap: 6px; margin-top: 2px;
    }

    /* ── Banners de produtos ── */
    .promo-scroll {
        display: flex; gap: 10px;
        padding: 14px 16px 16px;
        overflow-x: auto; scrollbar-width: none;
        background: #fff;
        border-bottom: 1px solid var(--border-lt);
    }
    .promo-scroll::-webkit-scrollbar { display: none; }
    .promo-card {
        flex-shrink: 0; width: 148px;
        background: var(--bg); border-radius: var(--r);
        overflow: hidden; text-decoration: none; color: inherit;
        display: flex; flex-direction: column;
        box-shadow: var(--shadow-sm);
        position: relative;
    }
    .promo-card-img {
        width: 100%; height: 100px; object-fit: cover;
        background: var(--p-lt); display: block;
    }
    .promo-card-body { padding: 8px 10px 10px; }
    .promo-card-name {
        font-size: 12px; font-weight: 700; color: var(--txt);
        margin-bottom: 4px;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden; line-height: 1.3;
    }
    .promo-card-price {
        font-size: 14px; font-weight: 800; color: var(--p);
    }
    .promo-card-badge {
        position: absolute; top: 7px; left: 7px;
        font-size: 9px; font-weight: 800;
        padding: 2px 7px; border-radius: 6px;
        background: #fef3c7; color: #92400e;
        text-transform: uppercase; letter-spacing: .3px;
    }

    /* ── Empty ── */
    .disc-empty {
        text-align: center; padding: 40px 24px; color: var(--txt-4);
    }
    .disc-empty i { font-size: 44px; display: block; margin-bottom: 10px; opacity: .3; }
    .disc-empty p { font-size: 14px; margin: 0; }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="home-hdr">
    <div class="home-hdr-top">
        <div>
            <div class="home-greeting-sub">
                @php $h = now()->hour; echo $h < 12 ? 'Bom dia,' : ($h < 18 ? 'Boa tarde,' : 'Boa noite,'); @endphp
            </div>
            <div class="home-greeting-name">{{ explode(' ', $user->name)[0] }} 👋</div>
        </div>
        <div class="home-hdr-right">
            <a href="{{ route('notifications') }}" class="home-notif-btn">
                <i class="bi bi-bell"></i>
                {{-- @if ($unread > 0) <span class="home-notif-dot"></span> @endif --}}
            </a>
            <a href="{{ route('profile') }}">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="home-avatar"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(substr($user->name,0,1)) }}&background=dcfce7&color=166534&size=44'">
            </a>
        </div>
    </div>
    {{-- Search --}}
    <a href="{{ route('houses') }}" class="home-search">
        <i class="bi bi-search"></i>
        <span>Buscar casas, eventos, membros...</span>
    </a>
</div>

{{-- Lembrete de gira hoje --}}
@foreach($todayEvents as $tevt)
<a href="{{ route('events.show', $tevt->id) }}" style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:linear-gradient(135deg,#166534,#16a34a);text-decoration:none;border-bottom:2px solid rgba(255,255,255,.15);">
    <div style="font-size:24px;line-height:1;">🪘</div>
    <div style="flex:1;min-width:0;">
        <div style="font-size:13px;font-weight:800;color:#fff;">Hoje tem {{ $tevt->name }}!</div>
        <div style="font-size:11px;color:rgba(255,255,255,.8);"><i class="bi bi-clock me-1"></i>{{ $tevt->starts_at->format('H:i') }} — Confirmar presença</div>
    </div>
    <i class="bi bi-chevron-right" style="color:rgba(255,255,255,.7);font-size:14px;flex-shrink:0;"></i>
</a>
@endforeach

{{-- Quick Actions --}}
<div class="quick-actions">
    <a href="{{ route('houses') }}" class="qa-btn">
        <div class="qa-icon" style="background:#dcfce7;">
            <i class="bi bi-building" style="color:#166534;"></i>
        </div>
        <span class="qa-label">Casas</span>
    </a>
    <a href="{{ route('events') }}" class="qa-btn">
        <div class="qa-icon" style="background:#dbeafe;">
            <i class="bi bi-calendar-event" style="color:#1e40af;"></i>
        </div>
        <span class="qa-label">Eventos</span>
    </a>
    {{-- LOJA DESABILITADA — descomentar para reativar
    <a href="{{ route('shop') }}" class="qa-btn">
        <div class="qa-icon" style="background:#fef3c7;">
            <i class="bi bi-bag" style="color:#92400e;"></i>
        </div>
        <span class="qa-label">Loja</span>
    </a>
    --}}
    <a href="{{ route('studies.public') }}" class="qa-btn">
        <div class="qa-icon" style="background:#ede9fe;">
            <i class="bi bi-book" style="color:#5b21b6;"></i>
        </div>
        <span class="qa-label">Estudos</span>
    </a>
</div>

{{-- LOJA DESABILITADA — descomentar para reativar
{{-- ─── Produtos em Destaque ─── --}}
@if ($products->isNotEmpty())
<div class="sec-hdr">
    <span class="sec-hdr-title">Produtos em Destaque</span>
    <a href="{{ route('shop') }}">Ver todos <i class="bi bi-chevron-right"></i></a>
</div>
<div class="promo-scroll">
    @foreach ($products as $product)
    <a href="{{ route('shop.products.show', $product->id) }}" class="promo-card">
        @if ($product->stock <= 3 && $product->stock > 0)
            <span class="promo-card-badge">Últimas {{ $product->stock }}</span>
        @endif
        <img src="{{ $product->first_image_url }}" alt="{{ $product->name }}" class="promo-card-img"
             onerror="this.src='https://placehold.co/148x100/dcfce7/166534?text={{ urlencode(substr($product->name,0,1)) }}'">
        <div class="promo-card-body">
            <div class="promo-card-name">{{ $product->name }}</div>
            <div class="promo-card-price">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
        </div>
    </a>
    @endforeach
</div>
<div class="section-divider"></div>
@endif
--}}

{{-- ─── Casas em Destaque ─── --}}
<div class="sec-hdr">
    <span class="sec-hdr-title">Casas em Destaque</span>
    <a href="{{ route('houses') }}">Ver todas <i class="bi bi-chevron-right"></i></a>
</div>

@if ($houses->isEmpty())
<div class="disc-empty" style="padding:24px;">
    <i class="bi bi-building-x"></i>
    <p>Nenhuma casa disponível.</p>
</div>
@else
<div class="houses-scroll">
    @foreach ($houses as $house)
    <a href="{{ route('houses.show', $house->id) }}" class="house-card">
        <img src="{{ $house->cover_image_url }}" alt="{{ $house->name }}" class="house-card-img"
             onerror="this.src='https://placehold.co/200x110/166534/ffffff?text={{ urlencode(substr($house->name,0,10)) }}'">
        <div class="house-card-body">
            <div class="house-card-name">{{ $house->name }}</div>
            <div class="house-card-sub">
                <i class="bi bi-geo-alt" style="font-size:10px;"></i>
                {{ $house->city ?: $house->type_name }}
            </div>
            <div class="house-card-stats">
                <span class="house-stat"><i class="bi bi-people"></i>{{ $house->activeMembers->count() }}</span>
                <span class="house-stat"><i class="bi bi-calendar3"></i>{{ $house->upcomingEvents->count() }}</span>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endif

<div class="section-divider"></div>

{{-- ─── Próximos Eventos ─── --}}
<div class="sec-hdr">
    <span class="sec-hdr-title">Próximos Eventos</span>
    <a href="{{ route('events') }}">Ver todos <i class="bi bi-chevron-right"></i></a>
</div>

@forelse ($events as $event)
<a href="{{ route('events.show', $event->id) }}" class="ev-list-card">
    <div class="ev-date-col">
        <span class="day">{{ $event->starts_at->format('d') }}</span>
        <span class="mon">{{ $event->starts_at->translatedFormat('M') }}</span>
    </div>
    <img src="{{ $event->banner_image_url }}" alt="{{ $event->name }}" class="ev-list-img"
         onerror="this.src='https://placehold.co/80x80/dcfce7/166534?text=E'">
    <div class="ev-list-body">
        <div class="ev-list-name">{{ $event->name }}</div>
        <div class="ev-list-meta">
            <i class="bi bi-clock" style="font-size:10px;"></i>
            {{ $event->starts_at->format('H:i') }}
            @if ($event->house)
                &nbsp;·&nbsp; {{ $event->house->name }}
            @endif
        </div>
        <div class="ev-list-footer">
            @if ($event->price > 0)
                <span class="badge-price">R$ {{ number_format($event->price, 2, ',', '.') }}</span>
            @else
                <span class="badge-price" style="background:#dcfce7;color:#166534;">Gratuito</span>
            @endif
            @php
                $sStyle = ['open'=>'background:#dcfce7;color:#166534;','full'=>'background:#fef9c3;color:#854d0e;'];
                $sLabel = ['open'=>'Aberto','full'=>'Lotado'];
            @endphp
            <span class="badge-cat" style="{{ $sStyle[$event->status] ?? '' }}">
                {{ $sLabel[$event->status] ?? $event->status }}
            </span>
        </div>
    </div>
    <div style="display:flex;align-items:center;padding:0 12px;">
        <i class="bi bi-chevron-right" style="font-size:13px;color:var(--txt-4);"></i>
    </div>
</a>
@empty
<div class="disc-empty">
    <i class="bi bi-calendar-x"></i>
    <p>Nenhum evento em breve.</p>
</div>
@endforelse

<div style="height:24px;"></div>
@endsection
