@extends('layouts.app')
@section('title', 'Meus Eventos — Aruanda Digital')

@push('styles')
<style>
    .ml-hdr {
        display:flex;align-items:center;justify-content:space-between;
        padding:14px 16px 12px;background:#fff;
        border-bottom:1px solid var(--border-lt);
    }
    .ml-hdr h6 { margin:0;font-size:17px;font-weight:800;color:var(--txt); }

    /* Tabs */
    .ml-tabs {
        display:flex;background:#fff;
        border-bottom:1px solid var(--border-lt);
        position:sticky;top:0;z-index:50;
    }
    .ml-tab {
        flex:1;padding:13px 8px;text-align:center;
        font-size:14px;font-weight:700;color:var(--txt-3);
        border:none;background:none;
        border-bottom:3px solid transparent;cursor:pointer;
        transition:color .2s,border-color .2s;
    }
    .ml-tab.active { color:var(--p);border-bottom-color:var(--p); }

    /* Card Evento */
    .ev-list-card {
        display:flex;align-items:stretch;
        background:#fff;border-bottom:1px solid var(--border-lt);
        text-decoration:none;color:inherit;
        transition:background .15s;
    }
    .ev-list-card:active { background:var(--p-xl); }

    .ev-list-img {
        width:90px;flex-shrink:0;object-fit:cover;
        background:var(--p-lt);display:block;
    }
    .ev-list-body {
        flex:1;min-width:0;padding:12px;
        display:flex;flex-direction:column;gap:5px;
    }
    .ev-list-name {
        font-size:14px;font-weight:700;color:var(--txt);
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
        line-height:1.3;
    }
    .ev-list-meta {
        font-size:11px;color:var(--txt-3);
        display:flex;align-items:center;gap:4px;
    }
    .ev-list-footer { display:flex;align-items:center;gap:6px;flex-wrap:wrap; }

    .ev-list-date {
        width:52px;flex-shrink:0;
        display:flex;flex-direction:column;
        align-items:center;justify-content:center;
        background:var(--p-xl);
        border-left:1.5px solid var(--p-lt);
        padding:10px 4px;
        text-align:center;
    }
    .ev-list-date .d { font-size:22px;font-weight:800;color:var(--p-dk);line-height:1; }
    .ev-list-date .m { font-size:9px;font-weight:700;color:var(--p);text-transform:uppercase;margin-top:2px; }

    /* Status badges */
    .badge-inscrito    { background:#dcfce7;color:#166534; }
    .badge-confirmado  { background:#fef9c3;color:#854d0e; }
    .badge-checkin     { background:#ede9fe;color:#5b21b6; }
    .badge-passado     { background:#f3f4f6;color:#6b7280; }
</style>
@endpush

@section('content')

<div class="ml-hdr">
    <h6><i class="bi bi-calendar-heart me-2" style="color:var(--p);"></i>Meus Eventos</h6>
    <a href="{{ route('events') }}" class="btn btn-sm btn-outline-success"
       style="border-radius:20px;font-size:12px;font-weight:700;">
        <i class="bi bi-plus me-1"></i>Explorar
    </a>
</div>

<div class="ml-tabs">
    <button class="ml-tab active" data-panel="upcoming">
        <i class="bi bi-calendar-check me-1"></i>Inscritos ({{ $upcoming->count() }})
    </button>
    <button class="ml-tab" data-panel="past">
        <i class="bi bi-patch-check me-1"></i>Participei ({{ $past->count() }})
    </button>
</div>

{{-- Futuros --}}
<div id="panel-upcoming">
    @forelse ($upcoming as $event)
    <a href="{{ route('events.show', $event->id) }}" class="ev-list-card">
        <img src="{{ $event->banner_image_url }}" alt="{{ $event->name }}" class="ev-list-img"
             onerror="this.src='https://placehold.co/90x90/dcfce7/166534?text=E'">
        <div class="ev-list-body">
            <div class="ev-list-name">{{ $event->name }}</div>
            <div class="ev-list-meta">
                <i class="bi bi-clock" style="font-size:10px;"></i>
                {{ $event->starts_at->format('d/m/Y • H:i') }}
            </div>
            @if ($event->house)
            <div class="ev-list-meta">
                <i class="bi bi-building" style="font-size:10px;"></i>
                {{ $event->house->name }}
            </div>
            @endif
            <div class="ev-list-footer">
                @php $pstatus = $event->pivot->status ?? 'registered'; @endphp
                @if ($pstatus === 'checked_in')
                    <span class="badge-cat badge-checkin">✅ Check-in feito</span>
                @else
                    <span class="badge-cat badge-inscrito">✅ Inscrito</span>
                @endif
                @if ($event->price > 0)
                    <span class="badge-price">R$ {{ number_format($event->price,2,',','.') }}</span>
                @else
                    <span class="badge-price" style="background:#dcfce7;color:#166534;">Gratuito</span>
                @endif
            </div>
        </div>
        <div class="ev-list-date">
            <span class="d">{{ $event->starts_at->format('d') }}</span>
            <span class="m">{{ $event->starts_at->translatedFormat('M') }}</span>
        </div>
    </a>
    @empty
    <div class="empty-state">
        <i class="bi bi-calendar-x"></i>
        <p>Você ainda não se inscreveu em nenhum evento.</p>
        <a href="{{ route('events') }}" class="btn btn-sm btn-primary mt-2" style="border-radius:20px;">Explorar Eventos</a>
    </div>
    @endforelse
</div>

{{-- Anteriores --}}
<div id="panel-past" style="display:none;">
    @forelse ($past as $event)
    <a href="{{ route('events.show', $event->id) }}" class="ev-list-card" style="opacity:.75;">
        <img src="{{ $event->banner_image_url }}" alt="{{ $event->name }}" class="ev-list-img"
             onerror="this.src='https://placehold.co/90x90/dcfce7/166534?text=E'"
             style="filter:grayscale(.4);">
        <div class="ev-list-body">
            <div class="ev-list-name" style="color:var(--txt-2);">{{ $event->name }}</div>
            <div class="ev-list-meta">
                <i class="bi bi-calendar-check" style="font-size:10px;"></i>
                {{ $event->starts_at->format('d/m/Y') }}
            </div>
            @if ($event->house)
            <div class="ev-list-meta"><i class="bi bi-building" style="font-size:10px;"></i>{{ $event->house->name }}</div>
            @endif
            <div class="ev-list-footer">
                <span class="badge-cat badge-checkin">✅ Participei</span>
            </div>
        </div>
        <div class="ev-list-date" style="background:var(--bg);border-left-color:var(--border);">
            <span class="d" style="color:var(--txt-3);">{{ $event->starts_at->format('d') }}</span>
            <span class="m" style="color:var(--txt-4);">{{ $event->starts_at->translatedFormat('M') }}</span>
        </div>
    </a>
    @empty
    <div class="empty-state">
        <i class="bi bi-calendar2-x"></i>
        <p>Você ainda não participou de nenhum evento.</p>
    </div>
    @endforelse
</div>

<div style="height:24px;"></div>
@endsection

@push('scripts')
<script>
$(function () {
    $('.ml-tab').on('click', function () {
        $('.ml-tab').removeClass('active');
        $(this).addClass('active');
        var p = $(this).data('panel');
        $('#panel-upcoming,#panel-past').hide();
        $('#panel-' + p).show();
    });
});
</script>
@endpush
