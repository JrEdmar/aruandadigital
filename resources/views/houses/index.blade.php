@extends('layouts.app')
@section('title', 'Casas & Templos — Aruanda Digital')

@push('styles')
<style>
    .filter-scroll {
        display:flex;gap:8px;padding:10px 16px;
        overflow-x:auto;scrollbar-width:none;
        background:#fff;border-bottom:1px solid var(--border-lt);
    }
    .filter-scroll::-webkit-scrollbar { display:none; }
    .fchip {
        flex-shrink:0;padding:6px 14px;
        border:1.5px solid var(--border);border-radius:20px;
        font-size:12px;font-weight:600;color:var(--txt-2);background:#fff;
        cursor:pointer;white-space:nowrap;transition:all .15s;
    }
    .fchip.active { border-color:var(--p);color:var(--p);background:var(--p-xl); }

    /* Card de casa */
    .house-row {
        background:#fff;border-bottom:1px solid var(--border-lt);
        text-decoration:none;color:inherit;display:block;
        transition:background .15s;
    }
    .house-row:active { background:var(--p-xl); }

    .house-cover {
        width:100%;height:130px;object-fit:cover;
        background:var(--p-lt);display:block;
    }
    .house-row-body {
        display:flex;align-items:center;gap:12px;
        padding:12px 16px;
    }
    .house-logo-sm {
        width:48px;height:48px;border-radius:12px;
        object-fit:cover;background:var(--p-lt);
        border:2px solid var(--p-lt);flex-shrink:0;
    }
    .house-row-info { flex:1;min-width:0; }
    .house-row-name { font-size:14px;font-weight:700;color:var(--txt);margin-bottom:4px; }
    .house-row-sub { font-size:12px;color:var(--txt-3);display:flex;align-items:center;gap:5px;flex-wrap:wrap; }
    .house-row-chips { display:flex;gap:5px;margin-top:5px;flex-wrap:wrap; }
    .house-row-stat { font-size:11px;font-weight:600;color:var(--txt-3);background:var(--bg);border-radius:6px;padding:2px 7px;display:inline-flex;align-items:center;gap:3px; }
</style>
@endpush

@section('content')

<div class="page-hdr">
    <div>
        <h6 style="margin:0;"><i class="bi bi-building me-2" style="color:var(--p);"></i>Casas & Templos</h6>
        <div class="t-muted" style="font-size:11px;margin-top:1px;">{{ $houses->total() }} encontradas</div>
    </div>
    <button class="btn btn-sm btn-outline-success"
            style="border-radius:20px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:5px;"
            onclick="openHouseFilter()">
        <i class="bi bi-sliders"></i>Filtros
    </button>
</div>

<div class="search-wrap">
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" id="searchInput" class="form-control" placeholder="Buscar casas, templos...">
    </div>
</div>

<div class="filter-scroll">
    <button class="fchip active" data-t="">Todos</button>
    <button class="fchip" id="btnProximas" data-t="proximas">
        <i class="bi bi-geo-alt-fill" style="color:var(--p);"></i> Perto de mim
    </button>
    <button class="fchip" data-t="umbanda">Umbanda</button>
    <button class="fchip" data-t="candomble">Candomblé</button>
    <button class="fchip" data-t="misto">Misto</button>
    <button class="fchip" data-t="espirita">Espírita</button>
</div>

<div id="houses-wrapper">
@forelse ($houses as $house)
<div class="house-row"
     data-type="{{ $house->type }}"
     data-name="{{ strtolower($house->name) }}"
     data-lat="{{ $house->latitude ?? '' }}"
     data-lng="{{ $house->longitude ?? '' }}">
    <a href="{{ route('houses.show', $house->id) }}" style="text-decoration:none;display:block;">
        <img src="{{ $house->cover_image_url }}" alt="{{ $house->name }}" class="house-cover"
             onerror="this.src='https://placehold.co/400x130/166534/ffffff?text={{ urlencode($house->name) }}'">
    </a>
    <div class="house-row-body">
        <a href="{{ route('houses.show', $house->id) }}" style="text-decoration:none;">
            <img src="{{ $house->logo_image_url }}" alt="{{ $house->name }}" class="house-logo-sm"
                 onerror="this.src='https://placehold.co/48x48/dcfce7/166534?text={{ urlencode(substr($house->name,0,1)) }}'">
        </a>
        <div class="house-row-info">
            <div class="house-row-name">{{ $house->name }}</div>
            <div class="house-row-sub">
                <span>{{ ucfirst($house->type ?? 'Espiritualidade') }}</span>
                @if ($house->city)
                    <i class="bi bi-dot" style="font-size:14px;"></i>
                    <span><i class="bi bi-geo-alt" style="font-size:10px;"></i> {{ $house->city }}{{ $house->state ? '/'.$house->state : '' }}</span>
                @endif
            </div>
            <div class="house-row-chips">
                <span class="house-row-stat"><i class="bi bi-calendar3"></i>Eventos: {{ $house->upcomingEvents->count() }}</span>
                <span class="house-row-stat"><i class="bi bi-people"></i>{{ $house->activeMembers->count() }} membros</span>
            </div>
        </div>
        <a href="{{ route('houses.show', $house->id) }}" style="text-decoration:none;">
            <i class="bi bi-chevron-right" style="font-size:13px;color:var(--txt-4);flex-shrink:0;"></i>
        </a>
    </div>
</div>
@empty
<div class="empty-state">
    <i class="bi bi-building-x"></i>
    <p>Nenhuma casa encontrada.</p>
</div>
@endforelse
</div>

@if ($houses->hasPages())
    <div style="padding:16px;">{{ $houses->links('pagination::bootstrap-5') }}</div>
@endif

<div style="height:24px;"></div>

{{-- Modal de Filtros (visitante) --}}
<div class="modal fade" id="houseFilterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="border-bottom:1px solid var(--border-lt);padding:14px 16px;">
                <h6 class="modal-title fw-800" style="margin:0;">Filtrar Casas</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:16px;">

                <div class="mb-4">
                    <label class="t-label mb-2 d-block">Tradição</label>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;" id="hfTradGroup">
                        <button class="fchip active" data-ht="" style="padding:5px 12px;font-size:12px;">Todas</button>
                        <button class="fchip" data-ht="umbanda"   style="padding:5px 12px;font-size:12px;">Umbanda</button>
                        <button class="fchip" data-ht="candomble" style="padding:5px 12px;font-size:12px;">Candomblé</button>
                        <button class="fchip" data-ht="misto"     style="padding:5px 12px;font-size:12px;">Misto</button>
                        <button class="fchip" data-ht="espirita"  style="padding:5px 12px;font-size:12px;">Espírita</button>
                    </div>
                </div>

                <div class="mb-2">
                    <label class="t-label mb-2 d-block">Cidade</label>
                    <input type="text" id="hfCity" class="form-control" placeholder="Ex: São Paulo"
                           style="font-size:14px;border-radius:8px;">
                </div>

            </div>
            <div class="modal-footer" style="border-top:1px solid var(--border-lt);padding:12px 16px;gap:8px;">
                <button type="button" class="btn btn-sm"
                        style="border-radius:20px;border:1.5px solid var(--border);font-weight:600;color:var(--txt-2);"
                        onclick="resetHouseFilter()">Limpar</button>
                <button type="button" class="btn btn-primary btn-sm"
                        style="border-radius:20px;flex:1;font-weight:700;"
                        onclick="applyHouseFilter()">Aplicar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    var deb;
    $('#searchInput').on('input', function () {
        clearTimeout(deb);
        var q = $(this).val().toLowerCase();
        deb = setTimeout(function () {
            $('.house-row').each(function () {
                $(this).toggle($(this).data('name').includes(q));
            });
        }, 280);
    });

    $('.fchip').on('click', function () {
        var t = $(this).data('t');
        if (t === 'proximas') {
            activarProximas($(this));
            return;
        }
        $('.fchip').removeClass('active');
        $(this).addClass('active');
        $('#houses-wrapper .house-row').each(function () {
            $(this).show();
        });
        if (t) {
            $('.house-row').each(function () {
                $(this).toggle($(this).data('type') === t);
            });
        }
        // Restaura ordem original
        var wrapper = $('#houses-wrapper');
        wrapper.find('.house-row').sort(function (a, b) {
            return parseInt($(a).data('orig') || 0) - parseInt($(b).data('orig') || 0);
        }).appendTo(wrapper);
    });

    // Marca ordem original
    $('#houses-wrapper .house-row').each(function (i) {
        $(this).data('orig', i);
    });

    function activarProximas(btn) {
        if (!navigator.geolocation) {
            Swal.fire({ icon: 'warning', title: 'Geolocalização indisponível', text: 'Seu dispositivo não suporta esta função.', confirmButtonColor: '#16a34a' });
            return;
        }
        btn.html('<i class="bi bi-arrow-repeat" style="animation:spin .8s linear infinite;display:inline-block;"></i> Localizando...');
        navigator.geolocation.getCurrentPosition(
            function (pos) {
                var lat = pos.coords.latitude;
                var lng = pos.coords.longitude;
                $('.fchip').removeClass('active');
                btn.addClass('active').html('<i class="bi bi-geo-alt-fill" style="color:var(--p);"></i> Perto de mim');

                var rows = [];
                $('#houses-wrapper .house-row').each(function () {
                    var hLat = parseFloat($(this).data('lat'));
                    var hLng = parseFloat($(this).data('lng'));
                    var dist = (hLat && hLng) ? haversine(lat, lng, hLat, hLng) : 99999;
                    rows.push({ el: this, dist: dist });
                });
                rows.sort(function (a, b) { return a.dist - b.dist; });
                var wrapper = $('#houses-wrapper');
                rows.forEach(function (r) {
                    $(r.el).show().find('.house-row-sub').each(function () {
                        // Adiciona distância se não exibida
                        var existing = $(this).find('.dist-badge');
                        if (!existing.length && r.dist < 9999) {
                            $(this).append('<span class="dist-badge" style="background:var(--p-xl);color:var(--p);border-radius:6px;padding:1px 6px;font-size:10px;font-weight:700;margin-left:4px;"><i class="bi bi-geo-alt"></i> ' + r.dist.toFixed(1) + ' km</span>');
                        }
                    });
                    wrapper.append(r.el);
                });
            },
            function () {
                btn.html('<i class="bi bi-geo-alt-fill" style="color:var(--p);"></i> Perto de mim');
                Swal.fire({ icon: 'info', title: 'Permissão negada', text: 'Permita o acesso à sua localização para usar este filtro.', confirmButtonColor: '#16a34a' });
            },
            { timeout: 8000 }
        );
    }

    function haversine(lat1, lng1, lat2, lng2) {
        var R = 6371;
        var dLat = (lat2 - lat1) * Math.PI / 180;
        var dLng = (lng2 - lng1) * Math.PI / 180;
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) *
                Math.sin(dLng/2) * Math.sin(dLng/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }
});

// CSS para animação do spin
document.head.insertAdjacentHTML('beforeend', '<style>@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}</style>');

// ── Modal de Filtros ──────────────────────────────────────────────────
var activeHfTrad = '';

function openHouseFilter() {
    new bootstrap.Modal(document.getElementById('houseFilterModal')).show();
}

$('#hfTradGroup').on('click', '.fchip', function () {
    $('#hfTradGroup .fchip').removeClass('active');
    $(this).addClass('active');
    activeHfTrad = $(this).data('ht') || '';
});

function applyHouseFilter() {
    var trad = activeHfTrad;
    var city = $('#hfCity').val().toLowerCase().trim();

    bootstrap.Modal.getInstance(document.getElementById('houseFilterModal')).hide();

    // Sincroniza chip de tradição na barra
    $('.fchip[data-t]').removeClass('active');
    if (trad) {
        $('.fchip[data-t="' + trad + '"]').addClass('active');
    } else {
        $('.fchip[data-t=""]').addClass('active');
    }

    $('.house-row').each(function () {
        var rowType = $(this).data('type') || '';
        var rowName = $(this).data('name') || '';
        var rowCity = $(this).find('.house-row-sub').text().toLowerCase();

        var show = true;
        if (trad && rowType !== trad) show = false;
        if (city && !rowCity.includes(city) && !rowName.includes(city)) show = false;
        $(this).toggle(show);
    });
}

function resetHouseFilter() {
    activeHfTrad = '';
    $('#hfTradGroup .fchip').removeClass('active');
    $('#hfTradGroup .fchip[data-ht=""]').addClass('active');
    $('#hfCity').val('');
    bootstrap.Modal.getInstance(document.getElementById('houseFilterModal')).hide();
    $('.house-row').show();
    $('.fchip[data-t]').removeClass('active');
    $('.fchip[data-t=""]').addClass('active');
}
</script>
@endpush
