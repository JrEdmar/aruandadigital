@extends('layouts.app')
@section('title', 'Atacado — Aruanda Digital')

@push('styles')
<style>
    .wh-hdr {
        background:linear-gradient(135deg, var(--p) 0%, var(--p-dk) 100%);
        padding:20px 16px;color:#fff;
    }
    .wh-hdr h6 { font-size:17px;font-weight:800;margin:0 0 3px; }
    .wh-hdr small { font-size:12px;opacity:.8; }

    .wh-row {
        display:flex;align-items:center;gap:12px;
        padding:12px 16px;border-bottom:1px solid var(--border-lt);
        background:#fff;
    }
    .wh-img {
        width:52px;height:52px;border-radius:var(--r-sm);
        object-fit:cover;background:var(--p-lt);flex-shrink:0;
    }
    .wh-info { flex:1;min-width:0; }
    .wh-name { font-size:13px;font-weight:700;color:var(--txt);margin-bottom:4px;
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
    .wh-prices { display:flex;align-items:center;gap:8px;flex-wrap:wrap; }
    .price-varejo {
        font-size:11px;font-weight:600;color:var(--txt-4);
        text-decoration:line-through;
    }
    .price-atacado {
        font-size:14px;font-weight:800;color:var(--p-dk);
    }
    .discount-badge {
        font-size:10px;font-weight:700;padding:2px 8px;border-radius:8px;
        background:#dcfce7;color:#166534;
    }
</style>
@endpush

@section('content')

<div class="wh-hdr">
    <h6><i class="bi bi-boxes me-2"></i>Produtos Atacado</h6>
    <small>Preços especiais para revendedores</small>
</div>

@forelse($products as $product)
@php
    $discount = $product->price > 0 && $product->wholesale_price
        ? round((1 - $product->wholesale_price / $product->price) * 100)
        : 0;
@endphp
<div class="wh-row">
    <img src="{{ $product->first_image_url }}" alt="{{ $product->name }}" class="wh-img"
         onerror="this.src='https://placehold.co/52x52/dcfce7/166534?text=P'">
    <div class="wh-info">
        <div class="wh-name">{{ $product->name }}</div>
        <div class="wh-prices">
            <span class="price-varejo">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
            @if($product->wholesale_price)
            <span class="price-atacado">R$ {{ number_format($product->wholesale_price, 2, ',', '.') }}</span>
            @if($discount > 0)
            <span class="discount-badge">-{{ $discount }}%</span>
            @endif
            @else
            <span style="font-size:12px;color:var(--txt-4);">Preço atacado não definido</span>
            @endif
        </div>
    </div>
    @if($product->stock !== null)
    <div style="text-align:right;flex-shrink:0;">
        <div style="font-size:12px;font-weight:700;color:var(--txt);">{{ $product->stock }}</div>
        <div style="font-size:10px;color:var(--txt-4);">em estoque</div>
    </div>
    @endif
</div>
@empty
<div class="empty-state" style="padding:60px 16px;">
    <i class="bi bi-boxes"></i>
    <p>Nenhum produto de atacado disponível.</p>
</div>
@endforelse

@if($products->hasPages())
<div style="padding:16px;">{{ $products->links('pagination::bootstrap-5') }}</div>
@endif

<div style="height:24px;"></div>
@endsection
