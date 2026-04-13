@extends('layouts.app')
@section('title', 'Mapa — Aruanda Digital')

@push('styles')
<style>
    #map { width:100%; height:calc(100vh - 180px); min-height:400px; }
    .map-search { padding:10px 16px; background:var(--surface); border-bottom:1px solid var(--border-lt); }
    .map-legend { display:flex; gap:8px; padding:8px 16px; background:var(--surface); border-bottom:1px solid var(--border-lt); overflow-x:auto; scrollbar-width:none; }
    .map-legend::-webkit-scrollbar { display:none; }
    .legend-chip { flex-shrink:0; font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; background:var(--p-xl); color:var(--p-dk); display:flex; align-items:center; gap:4px; cursor:pointer; border:1.5px solid transparent; }
    .legend-chip.active { border-color:var(--p); background:var(--p); color:#fff; }
</style>
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@section('content')

<div class="page-hdr">
    <h6><i class="bi bi-map me-2" style="color:var(--p);"></i>Casas & Templos</h6>
</div>

<div class="map-search">
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" id="mapSearch" class="form-control" placeholder="Buscar cidade ou casa...">
    </div>
</div>

<div class="map-legend">
    <div class="legend-chip" data-type-filter=""><i class="bi bi-building"></i>Todos ({{ $houses->count() }})</div>
    <div class="legend-chip" data-type-filter="umbanda"><i class="bi bi-geo-alt"></i>Umbanda</div>
    <div class="legend-chip" data-type-filter="candomble"><i class="bi bi-geo-alt"></i>Candomblé</div>
    <div class="legend-chip" data-type-filter="misto"><i class="bi bi-geo-alt"></i>Misto</div>
</div>

<div id="map"></div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var houses = @json($houses);

var map = L.map('map').setView([-15.7801, -47.9292], 5);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

var greenIcon = L.divIcon({
    className: '',
    html: '<div style="width:32px;height:32px;background:#16a34a;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;"><i class="bi bi-house-fill" style="color:#fff;font-size:14px;"></i></div>',
    iconSize: [32, 32],
    iconAnchor: [16, 16],
});

var markers = [];
var activeTypeFilter = '';

houses.forEach(function(h) {
    if (!h.latitude || !h.longitude) return;
    var marker = L.marker([h.latitude, h.longitude], {icon: greenIcon}).addTo(map);
    marker.bindPopup(
        '<div style="min-width:160px;">' +
        '<div style="font-weight:700;font-size:14px;margin-bottom:4px;">' + h.name + '</div>' +
        '<div style="font-size:12px;color:#6b7280;margin-bottom:8px;">' + (h.city || '') + (h.state ? '/' + h.state : '') + '</div>' +
        '<a href="/houses/' + h.id + '" style="display:inline-block;background:#16a34a;color:#fff;font-size:12px;font-weight:700;padding:5px 12px;border-radius:6px;text-decoration:none;">Ver Casa</a>' +
        '</div>'
    );
    marker._houseData = h;
    markers.push(marker);
});

function applyFilters() {
    var q = document.getElementById('mapSearch').value.toLowerCase().trim();
    var found = [];
    markers.forEach(function(m) {
        var h = m._houseData;
        var matchSearch = !q ||
            (h.name  && h.name.toLowerCase().includes(q)) ||
            (h.city  && h.city.toLowerCase().includes(q)) ||
            (h.state && h.state.toLowerCase().includes(q));
        var matchType = !activeTypeFilter || h.type === activeTypeFilter;
        if (matchSearch && matchType) {
            if (!map.hasLayer(m)) m.addTo(map);
            found.push(m);
        } else {
            if (map.hasLayer(m)) map.removeLayer(m);
        }
    });
    if (found.length === 1) {
        map.setView(found[0].getLatLng(), 13);
        found[0].openPopup();
    }
}

// Busca por texto
document.getElementById('mapSearch').addEventListener('input', applyFilters);

// Filtro por tipo nos chips
document.querySelectorAll('[data-type-filter]').forEach(function(chip) {
    chip.addEventListener('click', function() {
        document.querySelectorAll('[data-type-filter]').forEach(function(c){ c.classList.remove('active'); });
        this.classList.add('active');
        activeTypeFilter = this.dataset.typeFilter;
        applyFilters();
    });
});

// Tenta geolocalização
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos) {
        map.setView([pos.coords.latitude, pos.coords.longitude], 12);
    }, function(){});
}
</script>
@endpush
