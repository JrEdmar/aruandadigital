@extends('layouts.app')
@section('title', 'Meus Pedidos — Aruanda Digital')

@push('styles')
<style>
    .order-card {
        background:#fff;border-bottom:1px solid var(--border-lt);
        padding:14px 16px;display:flex;align-items:flex-start;gap:12px;
        transition:background .15s;
    }
    .order-card:active { background:var(--p-xl); }

    .order-icon {
        width:44px;height:44px;border-radius:12px;
        background:var(--p-xl);display:flex;align-items:center;justify-content:center;
        font-size:20px;color:var(--p);flex-shrink:0;
    }
    .order-icon.status-delivered  { background:#dcfce7;color:#166534; }
    .order-icon.status-pending    { background:#fef9c3;color:#854d0e; }
    .order-icon.status-shipped    { background:#dbeafe;color:#1d4ed8; }
    .order-icon.status-cancelled  { background:#fee2e2;color:#991b1b; }

    .order-info { flex:1;min-width:0; }
    .order-id {
        font-size:13px;font-weight:800;color:var(--txt);margin-bottom:3px;
        display:flex;align-items:center;gap:6px;
    }
    .order-meta { font-size:11px;color:var(--txt-3);margin-bottom:6px; }
    .order-footer { display:flex;align-items:center;gap:6px;flex-wrap:wrap; }

    .order-total {
        font-size:15px;font-weight:800;color:var(--p-dk);flex-shrink:0;
        text-align:right;
    }
    .order-total small { display:block;font-size:10px;font-weight:600;color:var(--txt-4);margin-bottom:1px; }

    /* Status badges */
    .st-pending   { background:#fef9c3;color:#854d0e; }
    .st-paid      { background:#dbeafe;color:#1d4ed8; }
    .st-shipped   { background:#e0e7ff;color:#3730a3; }
    .st-delivered { background:#dcfce7;color:#166534; }
    .st-cancelled { background:#fee2e2;color:#991b1b; }
</style>
@endpush

@section('content')

<div class="page-hdr">
    <div>
        <h6 style="margin:0;"><i class="bi bi-receipt me-2" style="color:var(--p);"></i>Meus Pedidos</h6>
        <div class="t-muted" style="font-size:11px;margin-top:1px;">{{ $orders->total() }} pedido(s)</div>
    </div>
    <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-success"
       style="border-radius:20px;font-size:12px;font-weight:700;">
        <i class="bi bi-bag me-1"></i>Loja
    </a>
</div>

@php
    $statusIcons = [
        'pending'   => 'bi-hourglass-split',
        'paid'      => 'bi-credit-card-2-front',
        'shipped'   => 'bi-truck',
        'delivered' => 'bi-bag-check-fill',
        'cancelled' => 'bi-x-circle',
    ];
    $statusLabels = [
        'pending'   => 'Aguardando',
        'paid'      => 'Pago',
        'shipped'   => 'Enviado',
        'delivered' => 'Entregue',
        'cancelled' => 'Cancelado',
    ];
    $payLabels = [
        'pix'    => 'Pix',
        'card'   => 'Cartão',
        'boleto' => 'Boleto',
    ];
@endphp

@forelse($orders as $order)
@php
    $st = $order->status ?? 'pending';
    $icon = $statusIcons[$st] ?? 'bi-receipt';
@endphp
<div class="order-card">
    <div class="order-icon status-{{ $st }}">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div class="order-info">
        <div class="order-id">
            Pedido #{{ $order->id }}
        </div>
        <div class="order-meta">
            <i class="bi bi-calendar3" style="font-size:10px;"></i>
            {{ $order->created_at->format('d/m/Y \à\s H:i') }}
            @if($order->payment_method)
            · <i class="bi bi-credit-card" style="font-size:10px;"></i>
            {{ $payLabels[$order->payment_method] ?? ucfirst($order->payment_method) }}
            @endif
        </div>
        <div class="order-footer">
            <span class="badge-cat st-{{ $st }}">
                {{ $statusLabels[$st] ?? ucfirst($st) }}
            </span>
            @if($order->orderItems->count())
            <span class="badge-cat">
                <i class="bi bi-box" style="font-size:9px;"></i>
                {{ $order->orderItems->count() }} iten(s)
            </span>
            @endif
        </div>
    </div>
    <div class="order-total">
        <small>Total</small>
        R$ {{ number_format($order->total, 2, ',', '.') }}
    </div>
</div>
@empty
<div class="empty-state" style="padding-top:60px;">
    <i class="bi bi-receipt"></i>
    <p>Você ainda não fez nenhum pedido.</p>
    <a href="{{ route('shop') }}" class="btn btn-primary mt-2" style="border-radius:20px;padding:8px 24px;">
        Explorar a Loja
    </a>
</div>
@endforelse

@if($orders->hasPages())
<div style="padding:16px;">{{ $orders->links('pagination::bootstrap-5') }}</div>
@endif

<div style="height:24px;"></div>
@endsection
