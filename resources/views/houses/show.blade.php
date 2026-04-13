@extends('layouts.app')

@section('title', $house->name . ' — Aruanda Digital')

@push('styles')
<style>
    /* =========================================================
       HOUSE DETAIL — Estrutura fiel ao GameList (pp. 12-15)
    ========================================================= */

    /* --- Header / Banner ----------------------------------- */
    .house-header {
        position: relative;
        width: 100%;
    }

    .house-cover {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
    }

    .house-identity {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        padding: 0 16px 12px;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
    }

    .house-logo-wrap {
        margin-top: -32px;
        flex-shrink: 0;
        z-index: 2;
    }

    .house-logo {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 8px;
        border: 3px solid #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,.18);
        background: #f5f5f5;
    }

    .house-info {
        flex: 1;
        min-width: 0;
        padding-top: 4px;
    }

    .house-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--txt);
        margin: 0 0 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .house-address {
        font-size: 12px;
        color: var(--txt-3);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* --- Botões de ação ------------------------------------ */
    .house-actions {
        display: flex;
        gap: 8px;
        padding: 10px 16px;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
    }

    .btn-action {
        flex: 1;
        padding: 8px 6px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        border: none;
        cursor: pointer;
        transition: opacity .2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        white-space: nowrap;
    }

    .btn-action:active { opacity: .8; }

    .btn-join {
        background: var(--p);
        color: #fff;
    }
    .btn-join:hover { background: var(--p-hov); color: #fff; }

    .btn-events {
        background: #f59e0b;
        color: #fff;
    }
    .btn-events:hover { background: #d97706; color: #fff; }

    .btn-directions {
        background: #3b82f6;
        color: #fff;
    }
    .btn-directions:hover { background: #2563eb; color: #fff; }

    /* --- Abas ---------------------------------------------- */
    .house-tabs {
        display: flex;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .house-tab {
        flex: 1;
        padding: 12px 4px;
        text-align: center;
        font-size: 12px;
        font-weight: 600;
        color: var(--txt-3);
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: color .2s, border-color .2s;
        white-space: nowrap;
    }

    .house-tab.active {
        color: var(--p);
        border-bottom-color: var(--p);
    }

    /* --- Conteúdo das abas --------------------------------- */
    .tab-content-area {
        padding: 16px;
    }

    /* Aba: Sobre */
    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--txt);
        margin: 0 0 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .house-description {
        font-size: 13px;
        color: var(--txt-2);
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .info-grid {
        background: #fff;
        border-radius: var(--r);
        overflow: hidden;
        margin-bottom: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 13px;
    }

    .info-row:last-child { border-bottom: none; }

    .info-row i {
        color: var(--p);
        font-size: 15px;
        width: 18px;
        flex-shrink: 0;
    }

    .info-label {
        color: var(--txt-3);
        font-size: 11px;
        min-width: 80px;
    }

    .info-value {
        color: var(--txt);
        font-weight: 500;
        flex: 1;
    }

    /* Diferenciais */
    .differentials-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }

    .differential-tag {
        background: var(--p-lt);
        color: var(--p-dk);
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 500;
    }

    /* Fotos */
    .photos-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 4px;
        margin-bottom: 20px;
    }

    .photos-grid img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
    }

    /* Mapa */
    .map-placeholder {
        width: 100%;
        height: 160px;
        border-radius: var(--r);
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .map-placeholder iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* Aba: Lista */
    .list-header {
        background: #fff;
        border-radius: var(--r);
        padding: 12px 14px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }

    .list-header .vacancies {
        font-size: 13px;
        color: var(--txt-3);
    }

    .list-header .vacancies strong {
        color: var(--p);
    }

    .member-list {
        background: #fff;
        border-radius: var(--r);
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
    }

    .member-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-bottom: 1px solid #f3f4f6;
    }

    .member-item:last-child { border-bottom: none; }

    .member-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        background: var(--p-lt);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: var(--p);
        font-weight: 700;
    }

    .member-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--txt);
        flex: 1;
    }

    .member-role {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 600;
        background: var(--p-lt);
        color: var(--p-dk);
    }

    .member-role.dirigente {
        background: #fef3c7;
        color: #92400e;
    }

    .member-role.assistente {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Aba: Contato */
    .contact-share {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .btn-share {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: opacity .2s;
    }
    .btn-share:active { opacity: .8; }
    .btn-share.facebook  { background: #1877f2; color: #fff; }
    .btn-share.instagram { background: #e1306c; color: #fff; }
    .btn-share.whatsapp  { background: #25d366; color: #fff; }
    .btn-share.link      { background: #6b7280; color: #fff; }

    /* Aba: Últimos Eventos */
    .event-card {
        background: #fff;
        border-radius: var(--r);
        overflow: hidden;
        margin-bottom: 10px;
        display: flex;
        align-items: stretch;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        text-decoration: none;
        color: inherit;
    }

    .event-card:hover { opacity: .9; }

    .event-banner {
        width: 80px;
        flex-shrink: 0;
        object-fit: cover;
    }

    .event-body {
        padding: 10px 12px;
        flex: 1;
        min-width: 0;
    }

    .event-title {
        font-size: 13px;
        font-weight: 700;
        margin: 0 0 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .event-meta {
        font-size: 11px;
        color: var(--txt-3);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .event-date {
        width: 52px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: var(--p-lt);
        color: var(--p-dk);
        font-weight: 700;
        text-align: center;
        padding: 8px 4px;
    }

    .event-date .day   { font-size: 22px; line-height: 1; }
    .event-date .month { font-size: 10px; text-transform: uppercase; letter-spacing: .5px; }

    .badge-status {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-open     { background: #dcfce7; color: #166534; }
    .badge-full     { background: #fee2e2; color: #991b1b; }
    .badge-finished { background: #f3f4f6; color: #6b7280; }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: var(--txt-3);
    }

    .empty-state i {
        font-size: 48px;
        opacity: .25;
        display: block;
        margin-bottom: 12px;
    }

    .empty-state p {
        font-size: 14px;
        margin: 0;
    }
</style>
@endpush

@section('content')

{{-- ============================================================
     HEADER: Banner + Identidade (GameList pp. 12-13)
============================================================ --}}
<div class="house-header">
    <img
        src="{{ $house->cover_image_url }}"
        alt="Capa de {{ $house->name }}"
        class="house-cover"
        onerror="this.src='https://placehold.co/800x200/166534/ffffff?text=Aruanda+Digital'"
    >
</div>

<div class="house-identity">
    <div class="house-logo-wrap">
        <img
            src="{{ $house->logo_image_url }}"
            alt="Logo {{ $house->name }}"
            class="house-logo"
            onerror="this.src='https://placehold.co/64x64/dcfce7/166534?text=AD'"
        >
    </div>
    <div class="house-info">
        <h1 class="house-name">{{ $house->name }}</h1>
        <p class="house-address">
            <i class="bi bi-geo-alt-fill" style="color:var(--p);font-size:11px;"></i>
            {{ $house->full_address ?: 'Endereço não informado' }}
        </p>
    </div>
</div>

{{-- ============================================================
     BOTÕES DE AÇÃO (GameList: Jogar | Reservar | Chegar)
============================================================ --}}
@php
    $myMembership = null;
    $myStatus     = null;
    if (Auth::check()) {
        $myMembership = $house->members()->where('user_id', Auth::id())->first();
        $myStatus     = $myMembership?->pivot->status;
    }
@endphp

<div class="house-actions">
    @auth
        @if ($myStatus === 'active')
            <span class="btn-action" style="background:#dcfce7;color:#166534;cursor:default;">
                <i class="bi bi-patch-check-fill"></i>
                Você é membro
            </span>
        @elseif ($myStatus === 'pending')
            <span class="btn-action" style="background:#fef9c3;color:#92400e;cursor:default;">
                <i class="bi bi-clock-history"></i>
                Aguardando aprovação
            </span>
            <button class="btn-action" style="background:#fee2e2;color:#991b1b;"
                    onclick="cancelRequest({{ $house->id }})">
                <i class="bi bi-x-circle-fill"></i>
                Cancelar
            </button>
        @else
            <button class="btn-action btn-join" id="btn-join" onclick="openJoinModal({{ $house->id }})">
                <i class="bi bi-person-plus-fill"></i>
                @if ($myStatus === 'rejected' || $myStatus === 'cancelled')
                    Solicitar novamente
                @else
                    Participar
                @endif
            </button>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn-action btn-join">
            <i class="bi bi-person-plus-fill"></i>
            Participar
        </a>
    @endauth

    <a href="{{ route('houses.show', $house->id) }}?tab=ultimos-eventos" class="btn-action btn-events">
        <i class="bi bi-calendar-event-fill"></i>
        Eventos
    </a>

    <a href="{{ $house->maps_url }}" target="_blank" rel="noopener" class="btn-action btn-directions">
        <i class="bi bi-map-fill"></i>
        Chegar
    </a>
</div>

{{-- ============================================================
     ABAS (GameList p. 13: Sobre | Lista | Contato | Últimos Eventos)
============================================================ --}}
<nav class="house-tabs">
    <a href="?tab=sobre"
       class="house-tab {{ $tab === 'sobre' ? 'active' : '' }}">
        Sobre
    </a>
    <a href="?tab=contato"
       class="house-tab {{ $tab === 'contato' ? 'active' : '' }}">
        Contato
    </a>
    <a href="?tab=ultimos-eventos"
       class="house-tab {{ $tab === 'ultimos-eventos' ? 'active' : '' }}">
        Eventos
    </a>
    <a href="?tab=lista"
       class="house-tab {{ $tab === 'lista' ? 'active' : '' }}">
        Lista
    </a>
</nav>

{{-- ============================================================
     CONTEÚDO DAS ABAS
============================================================ --}}
<div class="tab-content-area">

    {{-- ======================================================
         ABA: SOBRE (GameList p. 12-13)
    ====================================================== --}}
    @if ($tab === 'sobre')

        {{-- Sobre a casa --}}
        @if ($house->description)
            <h2 class="section-title">
                Sobre a casa
                <i class="bi bi-info-circle" style="color:var(--txt-3);font-size:14px;"></i>
            </h2>
            <p class="house-description">{{ $house->description }}</p>
        @endif

        {{-- Linha espiritual --}}
        @if ($house->spiritual_line)
            <h2 class="section-title">Linha Espiritual</h2>
            <p class="house-description">{{ $house->spiritual_line }}</p>
        @endif

        {{-- História --}}
        @if ($house->history)
            <h2 class="section-title">História</h2>
            <p class="house-description">{{ $house->history }}</p>
        @endif

        {{-- Diferenciais --}}
        @if ($house->differentials)
            <h2 class="section-title">
                Diferenciais
                <i class="bi bi-hand-thumbs-up" style="color:var(--txt-3);font-size:14px;"></i>
            </h2>
            <div class="differentials-list">
                @foreach (explode(',', $house->differentials) as $item)
                    @if (trim($item))
                        <span class="differential-tag">{{ trim($item) }}</span>
                    @endif
                @endforeach
            </div>
        @endif

        {{-- Informações --}}
        <div class="info-grid">
            @if ($house->schedule)
                <div class="info-row">
                    <i class="bi bi-clock-fill"></i>
                    <span class="info-label">Horário</span>
                    <span class="info-value">{{ $house->schedule }}</span>
                </div>
            @endif

            @if ($house->phone)
                <div class="info-row">
                    <i class="bi bi-telephone-fill"></i>
                    <span class="info-label">Telefone</span>
                    <span class="info-value">
                        <a href="tel:{{ preg_replace('/\D/', '', $house->phone) }}"
                           style="color:var(--p);text-decoration:none;">
                            {{ $house->phone }}
                        </a>
                    </span>
                </div>
            @endif

            @if ($house->foundation_date)
                <div class="info-row">
                    <i class="bi bi-calendar3"></i>
                    <span class="info-label">Fundação</span>
                    <span class="info-value">{{ $house->foundation_date->format('d/m/Y') }}</span>
                </div>
            @endif

            @if ($house->capacity)
                <div class="info-row">
                    <i class="bi bi-people-fill"></i>
                    <span class="info-label">Capacidade</span>
                    <span class="info-value">{{ number_format($house->capacity) }} pessoas</span>
                </div>
            @endif

            <div class="info-row">
                <i class="bi bi-bookmark-fill"></i>
                <span class="info-label">Tipo</span>
                <span class="info-value">{{ $house->type_name }}</span>
            </div>
        </div>

        {{-- Localização --}}
        @if ($house->latitude && $house->longitude)
            <h2 class="section-title">Localização</h2>
            <div class="map-placeholder">
                <iframe
                    src="https://www.openstreetmap.org/export/embed.html?bbox={{ $house->longitude - 0.005 }},{{ $house->latitude - 0.005 }},{{ $house->longitude + 0.005 }},{{ $house->latitude + 0.005 }}&layer=mapnik&marker={{ $house->latitude }},{{ $house->longitude }}"
                    loading="lazy"
                    title="Localização de {{ $house->name }}"
                ></iframe>
            </div>
            <a href="{{ $house->maps_url }}" target="_blank" rel="noopener"
               class="btn btn-primary w-100 mb-4" style="border-radius:8px;">
                <i class="bi bi-map-fill me-2"></i>Ver no mapa
            </a>
        @endif

    @endif

    {{-- ======================================================
         ABA: LISTA (GameList p. 14)
    ====================================================== --}}
    @if ($tab === 'lista')

        <div class="list-header">
            <span style="font-weight:700;font-size:13px;">Membros</span>
            <span class="vacancies">
                <strong>{{ $house->activeMembers->count() }}</strong> membros ativos
            </span>
        </div>

        @if ($house->activeMembers->isEmpty())
            <div class="empty-state">
                <i class="bi bi-people"></i>
                <p>Nenhum membro ainda.<br>Seja o primeiro!</p>
            </div>
        @else
            <div class="member-list">
                @foreach ($house->activeMembers as $member)
                    <div class="member-item">
                        <div class="member-avatar">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                        <span class="member-name">{{ $member->name }}</span>
                        <span class="member-role {{ $member->pivot->role }}">
                            {{ ucfirst($member->pivot->role) }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Botão participar — condicional ao status do usuário --}}
        <div class="mt-4">
            @auth
                @if($myStatus === 'active')
                    <span class="btn btn-success w-100 disabled" style="cursor:default;">
                        <i class="bi bi-patch-check-fill me-2"></i>Você já é membro desta casa
                    </span>
                @elseif($myStatus === 'pending' || $myStatus === 'pending_transfer')
                    <span class="btn btn-warning w-100 disabled" style="cursor:default;color:#92400e;">
                        <i class="bi bi-clock-history me-2"></i>Solicitação pendente
                    </span>
                @else
                    <button class="btn btn-primary w-100" onclick="joinHouse({{ $house->id }})">
                        <i class="bi bi-person-plus-fill me-2"></i>
                        {{ ($myStatus === 'rejected' || $myStatus === 'cancelled') ? 'Solicitar novamente' : 'Solicitar Filiação' }}
                    </button>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary w-100">
                    <i class="bi bi-person-plus-fill me-2"></i>Entrar para se filiar
                </a>
            @endauth
        </div>

    @endif

    {{-- ======================================================
         ABA: CONTATO (GameList p. 15)
    ====================================================== --}}
    @if ($tab === 'contato')

        <div class="info-grid mb-4">
            @if ($house->phone)
                <div class="info-row">
                    <i class="bi bi-telephone-fill"></i>
                    <span class="info-label">Telefone</span>
                    <span class="info-value">
                        <a href="tel:{{ preg_replace('/\D/', '', $house->phone) }}"
                           style="color:var(--p);text-decoration:none;">
                            {{ $house->phone }}
                        </a>
                    </span>
                </div>
            @endif

            @if ($house->website)
                <div class="info-row">
                    <i class="bi bi-globe2"></i>
                    <span class="info-label">Site</span>
                    <span class="info-value">
                        <a href="{{ $house->website }}" target="_blank" rel="noopener"
                           style="color:var(--p);text-decoration:none;word-break:break-all;">
                            {{ $house->website }}
                        </a>
                    </span>
                </div>
            @endif

            @if ($house->email)
                <div class="info-row">
                    <i class="bi bi-envelope-fill"></i>
                    <span class="info-label">E-mail</span>
                    <span class="info-value">
                        <a href="mailto:{{ $house->email }}"
                           style="color:var(--p);text-decoration:none;">
                            {{ $house->email }}
                        </a>
                    </span>
                </div>
            @endif

            @if ($house->full_address)
                <div class="info-row">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span class="info-label">Endereço</span>
                    <span class="info-value">{{ $house->full_address }}</span>
                </div>
            @endif
        </div>

        {{-- Redes sociais --}}
        @if ($house->facebook || $house->instagram || $house->whatsapp)
            <h2 class="section-title mb-3">Redes Sociais</h2>
            <div class="contact-share">
                @if ($house->facebook)
                    <a href="{{ $house->facebook }}" target="_blank" rel="noopener"
                       class="btn-share facebook">
                        <i class="bi bi-facebook"></i> Facebook
                    </a>
                @endif

                @if ($house->instagram)
                    <a href="{{ $house->instagram }}" target="_blank" rel="noopener"
                       class="btn-share instagram">
                        <i class="bi bi-instagram"></i> Instagram
                    </a>
                @endif

                @if ($house->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $house->whatsapp) }}"
                       target="_blank" rel="noopener"
                       class="btn-share whatsapp">
                        <i class="bi bi-whatsapp"></i> WhatsApp
                    </a>
                @endif
            </div>
        @endif

        {{-- Compartilhar --}}
        <h2 class="section-title mb-3">Compartilhar</h2>
        <div class="contact-share">
            <button class="btn-share link" onclick="shareHouse()">
                <i class="bi bi-share-fill"></i> Compartilhar
            </button>
        </div>

    @endif

    {{-- ======================================================
         ABA: ÚLTIMOS EVENTOS (GameList p. 16)
    ====================================================== --}}
    @if ($tab === 'ultimos-eventos')

        @php
            $allEvents = $house->upcomingEvents->merge($house->pastEvents);
        @endphp

        @if ($allEvents->isEmpty())
            <div class="empty-state">
                <i class="bi bi-calendar-x"></i>
                <p>Nenhum evento encontrado.</p>
            </div>
        @else
            @foreach ($allEvents as $event)
                <a href="/events/{{ $event->id }}" class="event-card">
                    <div class="event-date">
                        <span class="day">{{ $event->starts_at->format('d') }}</span>
                        <span class="month">{{ $event->starts_at->translatedFormat('M') }}</span>
                    </div>
                    <img
                        src="{{ $event->banner_image_url }}"
                        alt="{{ $event->name }}"
                        class="event-banner"
                        onerror="this.src='https://placehold.co/80x80/dcfce7/166534?text=Evento'"
                    >
                    <div class="event-body">
                        <p class="event-title">{{ $event->name }}</p>
                        <div class="event-meta">
                            <span>{{ $house->name }}</span>
                            <span>·</span>
                            @php
                                $sBadge = ['open'=>'badge-status-open','full'=>'badge-status-full','cancelled'=>'badge-status-cancel'];
                            @endphp
                            <span class="badge-cat {{ $sBadge[$event->status] ?? '' }}">
                                {{ $event->status_name }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        @endif

    @endif

</div>
{{-- /tab-content-area --}}

@endsection

@push('scripts')
<script>
    function openJoinModal(houseId) {
        Swal.fire({
            title: 'Solicitar Filiação',
            html: `
                <p style="font-size:13px;color:#6b7280;margin-bottom:12px;">
                    Preencha as informações abaixo. O dirigente será notificado.
                </p>
                <div style="margin-bottom:10px;text-align:left;">
                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px;">
                        Função desejada <span style="color:#dc2626;">*</span>
                    </label>
                    <select id="swal-role-membro"
                            style="width:100%;padding:8px 10px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;color:#374151;">
                        <option value="">Selecione...</option>
                        <option value="médium">Médium</option>
                        <option value="cambone">Cambone</option>
                        <option value="dirigente auxiliar">Dirigente Auxiliar</option>
                    </select>
                </div>
                <div style="text-align:left;">
                    <label style="font-size:12px;font-weight:600;color:#374151;display:block;margin-bottom:4px;">
                        Mensagem para o dirigente (opcional)
                    </label>
                    <textarea id="swal-message" rows="3"
                              placeholder="Conte um pouco sobre você e por que quer se filiar..."
                              style="width:100%;padding:8px 10px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:none;color:#374151;"></textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Enviar solicitação',
            cancelButtonText: 'Cancelar',
            preConfirm: function() {
                var role = document.getElementById('swal-role-membro').value;
                if (!role) {
                    Swal.showValidationMessage('Selecione a função desejada');
                    return false;
                }
                return {
                    role_membro: role,
                    message: document.getElementById('swal-message').value,
                };
            },
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/houses/' + houseId + '/join',
                    method: 'POST',
                    data: {
                        _token:      $('meta[name="csrf-token"]').attr('content'),
                        message:     result.value.message,
                        role_membro: result.value.role_membro,
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Solicitação enviada!',
                            text: 'O dirigente da casa será notificado.',
                            icon: 'success',
                            confirmButtonColor: '#16a34a',
                        }).then(function() { location.reload(); });
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON?.message ?? 'Tente novamente mais tarde.';
                        Swal.fire({ title: 'Ops!', text: msg, icon: 'error', confirmButtonColor: '#16a34a' });
                    }
                });
            }
        });
    }

    function cancelRequest(houseId) {
        Swal.fire({
            title: 'Cancelar solicitação?',
            text: 'Sua solicitação de filiação será cancelada.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sim, cancelar',
            cancelButtonText: 'Manter',
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/houses/' + houseId + '/cancel-request',
                    method: 'POST',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function() {
                        Swal.fire({
                            title: 'Solicitação cancelada.',
                            icon: 'success',
                            confirmButtonColor: '#16a34a',
                        }).then(function() { location.reload(); });
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON?.message ?? 'Erro ao cancelar.';
                        Swal.fire({ title: 'Ops!', text: msg, icon: 'error', confirmButtonColor: '#16a34a' });
                    }
                });
            }
        });
    }

    function shareHouse() {
        var url  = window.location.href;
        var title = document.title;

        if (navigator.share) {
            navigator.share({ title: title, url: url });
        } else {
            navigator.clipboard.writeText(url).then(function() {
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'success',
                    title: 'Link copiado!',
                    showConfirmButton: false,
                    timer: 2000,
                });
            });
        }
    }
</script>
@endpush
