@extends('layouts.app')
@section('title', 'Check-in — Aruanda Digital')

@push('styles')
<style>
    .checkin-wrap { padding:16px; }
    .checkin-header { background:linear-gradient(135deg,#16a34a,#166534); padding:20px 16px; color:#fff; margin:-16px -16px 16px; }
    .checkin-header h6 { font-size:16px; font-weight:800; margin:0 0 2px; }
    .checkin-header small { font-size:12px; opacity:.8; }
    .checkin-card { background:#fff; border-radius:12px; padding:20px; box-shadow:0 1px 8px rgba(0,0,0,.06); margin-bottom:16px; }
    .checkin-card h6 { font-size:14px; font-weight:700; color:#16a34a; margin-bottom:12px; }
    .checkin-info { font-size:13px; color:#374151; margin-bottom:8px; display:flex; gap:8px; align-items:flex-start; }
    .checkin-info i { color:#16a34a; margin-top:1px; flex-shrink:0; }
    .qr-box { width:160px; height:160px; background:#f3f4f6; border-radius:12px; margin:0 auto 16px; display:flex; align-items:center; justify-content:center; color:#9ca3af; font-size:12px; text-align:center; border:2px dashed #e5e7eb; }
    .search-section { background:#fff; border-radius:12px; padding:16px; box-shadow:0 1px 8px rgba(0,0,0,.06); }
    .search-section h6 { font-size:14px; font-weight:700; color:#111827; margin-bottom:12px; }
</style>
@endpush

@section('content')
<div class="checkin-wrap">
    <div class="checkin-header">
        <h6>Check-in</h6>
        <small>Confirmar presença em evento</small>
    </div>

    @if ($event)
    <div class="checkin-card">
        <h6><i class="bi bi-calendar-event me-2"></i>{{ $event->name }}</h6>
        <div class="qr-box"><span>QR Code<br>em breve</span></div>
        <div class="checkin-info"><i class="bi bi-clock"></i><span>{{ $event->starts_at->translatedFormat('d \d\e F \d\e Y \à\s H:i') }}</span></div>
        @if ($event->address)<div class="checkin-info"><i class="bi bi-geo-alt"></i><span>{{ $event->address }}</span></div>@endif
        @if ($event->house)<div class="checkin-info"><i class="bi bi-house"></i><span>{{ $event->house->name }}</span></div>@endif
    </div>
    @endif

    <div class="search-section">
        <h6><i class="bi bi-search me-2"></i>Buscar Participante</h6>
        <div class="input-group mb-3">
            <input type="text" id="searchParticipant" class="form-control" placeholder="Nome ou e-mail...">
            <button class="btn btn-success" type="button" onclick="Swal.fire({icon:'info',title:'Em breve',text:'Busca por participante em desenvolvimento.',confirmButtonColor:'#16a34a'})">
                <i class="bi bi-search"></i>
            </button>
        </div>
        <div class="text-center text-muted" style="font-size:13px;">
            <i class="bi bi-info-circle me-1"></i>Digite o nome ou e-mail do participante para confirmar presença
        </div>
    </div>
</div>
<div style="height:80px;"></div>
@endsection
