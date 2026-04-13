@extends('layouts.app')
@section('title', 'Eventos — Aruanda Digital')

@push('styles')
<style>
    /* ── Filtros ── */
    .filter-scroll {
        display: flex;
        gap: 8px;
        padding: 10px 16px;
        overflow-x: auto;
        scrollbar-width: none;
        background: #fff;
        border-bottom: 1px solid var(--border-lt);
    }
    .filter-scroll::-webkit-scrollbar { display: none; }
    .fchip {
        flex-shrink: 0;
        padding: 6px 14px;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        font-size: 12px; font-weight: 600;
        color: var(--txt-2);
        background: #fff;
        cursor: pointer;
        white-space: nowrap;
        transition: all .15s;
    }
    .fchip.active { border-color: var(--p); color: var(--p); background: var(--p-xl); }

    /* ── Card Evento ── */
    .ev-card {
        display: flex;
        align-items: stretch;
        background: #fff;
        border-bottom: 1px solid var(--border-lt);
        text-decoration: none;
        color: inherit;
        transition: background .15s;
        position: relative;
    }
    .ev-card:active { background: var(--p-xl); }

    .ev-date-col {
        width: 56px; flex-shrink: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        background: var(--p-xl);
        border-right: 1.5px solid var(--p-lt);
        padding: 14px 4px;
    }
    .ev-date-col .d { font-size: 26px; font-weight: 800; color: var(--p-dk); line-height: 1; }
    .ev-date-col .m { font-size: 10px; font-weight: 700; color: var(--p); text-transform: uppercase; margin-top: 3px; }

    .ev-thumb {
        width: 88px; flex-shrink: 0;
        object-fit: cover;
        background: var(--p-lt);
        display: block;
    }
    .ev-body {
        flex: 1; min-width: 0;
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .ev-name {
        font-size: 14px; font-weight: 700;
        color: var(--txt); line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .ev-meta {
        font-size: 11px; color: var(--txt-3);
        display: flex; align-items: center; gap: 4px;
        flex-wrap: wrap;
    }
    .ev-footer {
        display: flex; align-items: center;
        gap: 6px; flex-wrap: wrap;
    }
    .ev-arrow {
        display: flex; align-items: center;
        padding: 0 12px; flex-shrink: 0;
    }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="page-hdr">
    <div>
        <h6 style="margin:0;"><i class="bi bi-calendar-event me-2" style="color:var(--p);"></i>Eventos</h6>
        <div class="t-muted" style="font-size:11px;margin-top:1px;">{{ $events->total() }} eventos disponíveis</div>
    </div>
    <div style="display:flex;align-items:center;gap:6px;">
        <button class="btn btn-sm btn-outline-success"
                style="border-radius:20px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:4px;"
                id="btnEvProximas">
            <i class="bi bi-geo-alt-fill"></i>Perto
        </button>
        <button class="btn btn-sm btn-outline-success"
                style="border-radius:20px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:4px;"
                onclick="openEvFilter()">
            <i class="bi bi-sliders"></i>Filtro
        </button>
        <a href="{{ route('events.my-list') }}" class="btn btn-sm btn-outline-success"
           style="border-radius:20px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:4px;">
            <i class="bi bi-calendar-heart"></i>Meus Eventos
        </a>
    </div>
</div>

{{-- Busca --}}
<div class="search-wrap">
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" id="searchInput" class="form-control" placeholder="Buscar eventos...">
    </div>
</div>

{{-- Filtros --}}
<div class="filter-scroll">
    <button class="fchip active" data-f="">Todos</button>
    <button class="fchip" data-f="free">Gratuitos</button>
    <button class="fchip" data-f="paid">Pagos</button>
    <button class="fchip" data-f="open">Abertos</button>
    <button class="fchip" data-f="full">Lotados</button>
</div>

{{-- Lista --}}
<div id="events-wrapper">
@forelse ($events as $event)
    <a href="{{ route('events.show', $event->id) }}" class="ev-card"
       data-name="{{ strtolower($event->name) }}"
       data-f="{{ $event->price > 0 ? 'paid' : 'free' }}"
       data-status="{{ $event->status }}"
       data-trad="{{ $event->house?->type ?? '' }}"
       data-lat="{{ $event->house?->latitude ?? '' }}"
       data-lng="{{ $event->house?->longitude ?? '' }}"
       data-orig="{{ $loop->index }}">

        <div class="ev-date-col">
            <span class="d">{{ $event->starts_at->format('d') }}</span>
            <span class="m">{{ $event->starts_at->translatedFormat('M') }}</span>
        </div>

        <img src="{{ $event->banner_image_url }}" alt="{{ $event->name }}" class="ev-thumb"
             onerror="this.src='https://placehold.co/88x88/dcfce7/166534?text=E'">

        <div class="ev-body">
            <div class="ev-name">{{ $event->name }}</div>
            <div class="ev-meta">
                <i class="bi bi-clock" style="font-size:10px;"></i>
                {{ $event->starts_at->format('H:i') }}
                @if ($event->house)
                    <i class="bi bi-dot" style="font-size:14px;"></i>
                    <span>{{ $event->house->name }}</span>
                @endif
            </div>
            <div class="ev-footer">
                @php
                    $sStyle = ['open'=>'background:#dcfce7;color:#166534;','full'=>'background:#fef9c3;color:#854d0e;','cancelled'=>'background:#fee2e2;color:#991b1b;','finished'=>'background:#f3f4f6;color:#6b7280;'];
                    $sLabel = ['open'=>'Aberto','full'=>'Lotado','cancelled'=>'Cancelado','finished'=>'Encerrado'];
                @endphp
                <span class="badge-cat" style="{{ $sStyle[$event->status] ?? '' }}">
                    {{ $sLabel[$event->status] ?? $event->status }}
                </span>
                @if ($event->price > 0)
                    <span class="badge-price">R$ {{ number_format($event->price, 2, ',', '.') }}</span>
                @else
                    <span class="badge-price" style="background:#dcfce7;color:#166534;">Gratuito</span>
                @endif
                @if ($event->house)
                    <span class="badge-cat">{{ strtoupper($event->house->type ?? '') }}</span>
                @endif
            </div>
        </div>

        <div class="ev-arrow">
            <i class="bi bi-chevron-right" style="font-size:13px;color:var(--txt-4);"></i>
        </div>
    </a>
@empty
    <div class="empty-state">
        <i class="bi bi-calendar-x"></i>
        <p>Nenhum evento disponível no momento.</p>
    </div>
@endforelse
</div>

@if ($events->hasPages())
    <div style="padding:16px;">{{ $events->links('pagination::bootstrap-5') }}</div>
@endif

<div style="height:24px;"></div>

{{-- Modal de Filtro --}}
<div class="modal fade" id="evFilterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid var(--border-lt);padding:14px 16px;">
                <h6 class="modal-title fw-800" style="margin:0;">Filtrar Eventos</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:16px;">

                <div class="mb-4">
                    <label class="t-label mb-2 d-block">Entrada</label>
                    <div style="display:flex;gap:8px;" id="evfPrecoGroup">
                        <button class="fchip active" data-evf-preco=""    style="padding:5px 12px;font-size:12px;">Todos</button>
                        <button class="fchip" data-evf-preco="free" style="padding:5px 12px;font-size:12px;">Gratuito</button>
                        <button class="fchip" data-evf-preco="paid" style="padding:5px 12px;font-size:12px;">Pago</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="t-label mb-2 d-block">Status</label>
                    <div style="display:flex;gap:8px;" id="evfStatusGroup">
                        <button class="fchip active" data-evf-status=""     style="padding:5px 12px;font-size:12px;">Todos</button>
                        <button class="fchip" data-evf-status="open" style="padding:5px 12px;font-size:12px;">Aberto</button>
                        <button class="fchip" data-evf-status="full" style="padding:5px 12px;font-size:12px;">Lotado</button>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="t-label mb-2 d-block">Tradição da Casa</label>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;" id="evfTradGroup">
                        <button class="fchip active" data-evf-trad=""           style="padding:5px 12px;font-size:12px;">Todas</button>
                        <button class="fchip" data-evf-trad="umbanda"   style="padding:5px 12px;font-size:12px;">Umbanda</button>
                        <button class="fchip" data-evf-trad="candomble" style="padding:5px 12px;font-size:12px;">Candomblé</button>
                        <button class="fchip" data-evf-trad="misto"     style="padding:5px 12px;font-size:12px;">Misto</button>
                    </div>
                </div>

            </div>
            <div class="modal-footer" style="border-top:1px solid var(--border-lt);padding:12px 16px;gap:8px;">
                <button type="button" class="btn btn-sm"
                        style="border-radius:20px;border:1.5px solid var(--border);font-weight:600;color:var(--txt-2);"
                        onclick="resetEvFilter()">Limpar</button>
                <button type="button" class="btn btn-primary btn-sm"
                        style="border-radius:20px;flex:1;font-weight:700;"
                        onclick="applyEvFilter()">Aplicar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {

    // ── Busca ──────────────────────────────────────────────────────────
    var deb;
    $('#searchInput').on('input', function () {
        clearTimeout(deb);
        var q = $(this).val().toLowerCase();
        deb = setTimeout(function () {
            $('.ev-card').each(function () {
                $(this).toggle($(this).data('name').includes(q));
            });
        }, 280);
    });

    // ── Chips de filtro rápido ──────────────────────────────────────────
    $('.fchip[data-f]').on('click', function () {
        $('.fchip[data-f]').removeClass('active');
        $(this).addClass('active');
        var f = $(this).data('f');
        restoreOrder();
        $('.ev-card').each(function () {
            if (!f) { $(this).show(); return; }
            var match = $(this).data('f') === f || $(this).data('status') === f;
            $(this).toggle(match);
        });
    });

    // ── Perto de mim ───────────────────────────────────────────────────
    $('#btnEvProximas').on('click', function () {
        var btn = $(this);
        if (!navigator.geolocation) {
            Swal.fire({ icon: 'warning', title: 'Indisponível', text: 'Geolocalização não suportada.', confirmButtonColor: '#16a34a' });
            return;
        }
        btn.html('<i class="bi bi-arrow-repeat" style="animation:spin .8s linear infinite;display:inline-block;"></i>').prop('disabled', true);
        navigator.geolocation.getCurrentPosition(
            function (pos) {
                var lat = pos.coords.latitude, lng = pos.coords.longitude;
                btn.html('<i class="bi bi-geo-alt-fill"></i>Perto').prop('disabled', false).addClass('active-prox');

                var cards = [];
                $('.ev-card').each(function () {
                    var eLat = parseFloat($(this).data('lat'));
                    var eLng = parseFloat($(this).data('lng'));
                    var dist = (eLat && eLng) ? haversine(lat, lng, eLat, eLng) : 99999;
                    cards.push({ el: this, dist: dist });
                });
                cards.sort(function (a, b) { return a.dist - b.dist; });

                var wrapper = $('#events-wrapper');
                cards.forEach(function (c) {
                    $(c.el).show();
                    // Exibe badge de distância se ainda não tiver
                    if (!$(c.el).find('.ev-dist').length && c.dist < 9999) {
                        $(c.el).find('.ev-meta').append(
                            '<span class="ev-dist" style="background:var(--p-xl);color:var(--p);border-radius:6px;padding:1px 6px;font-size:10px;font-weight:700;margin-left:2px;">'
                            + '<i class="bi bi-geo-alt"></i> ' + c.dist.toFixed(1) + ' km</span>'
                        );
                    }
                    wrapper.append(c.el);
                });
            },
            function () {
                btn.html('<i class="bi bi-geo-alt-fill"></i>Perto').prop('disabled', false);
                Swal.fire({ icon: 'info', title: 'Permissão negada', text: 'Permita o acesso à sua localização.', confirmButtonColor: '#16a34a' });
            },
            { timeout: 8000 }
        );
    });

    function restoreOrder() {
        var wrapper = $('#events-wrapper');
        wrapper.find('.ev-card').sort(function (a, b) {
            return parseInt($(a).data('orig') || 0) - parseInt($(b).data('orig') || 0);
        }).appendTo(wrapper);
        $('#btnEvProximas').removeClass('active-prox')
            .html('<i class="bi bi-geo-alt-fill"></i>Perto');
        $('.ev-dist').remove();
    }
});

// ── Haversine ──────────────────────────────────────────────────────────
function haversine(lat1, lng1, lat2, lng2) {
    var R = 6371, dL = (lat2-lat1)*Math.PI/180, dG = (lng2-lng1)*Math.PI/180;
    var a = Math.sin(dL/2)*Math.sin(dL/2) +
            Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*
            Math.sin(dG/2)*Math.sin(dG/2);
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

document.head.insertAdjacentHTML('beforeend',
    '<style>@keyframes spin{from{transform:rotate(0)}to{transform:rotate(360deg)}}'
    + '.active-prox{background:var(--p)!important;color:#fff!important;border-color:var(--p)!important;}</style>'
);

// ── Modal Filtro ────────────────────────────────────────────────────────
var evfPreco = '', evfStatus = '', evfTrad = '';

function openEvFilter() {
    new bootstrap.Modal(document.getElementById('evFilterModal')).show();
}

$('#evfPrecoGroup').on('click',  '.fchip', function () {
    $('#evfPrecoGroup .fchip').removeClass('active');  $(this).addClass('active');
    evfPreco = $(this).data('evf-preco') ?? '';
});
$('#evfStatusGroup').on('click', '.fchip', function () {
    $('#evfStatusGroup .fchip').removeClass('active'); $(this).addClass('active');
    evfStatus = $(this).data('evf-status') ?? '';
});
$('#evfTradGroup').on('click',   '.fchip', function () {
    $('#evfTradGroup .fchip').removeClass('active');   $(this).addClass('active');
    evfTrad = $(this).data('evf-trad') ?? '';
});

function applyEvFilter() {
    bootstrap.Modal.getInstance(document.getElementById('evFilterModal')).hide();
    $('.ev-card').each(function () {
        var show = true;
        if (evfPreco  && $(this).data('f')      !== evfPreco)  show = false;
        if (evfStatus && $(this).data('status') !== evfStatus) show = false;
        if (evfTrad   && $(this).data('trad')   !== evfTrad)   show = false;
        $(this).toggle(show);
    });
}

function resetEvFilter() {
    evfPreco = ''; evfStatus = ''; evfTrad = '';
    $('#evfPrecoGroup .fchip, #evfStatusGroup .fchip, #evfTradGroup .fchip').removeClass('active');
    $('#evfPrecoGroup  .fchip[data-evf-preco=""]').addClass('active');
    $('#evfStatusGroup .fchip[data-evf-status=""]').addClass('active');
    $('#evfTradGroup   .fchip[data-evf-trad=""]').addClass('active');
    bootstrap.Modal.getInstance(document.getElementById('evFilterModal')).hide();
    $('.ev-card').show();
}
</script>
@endpush
