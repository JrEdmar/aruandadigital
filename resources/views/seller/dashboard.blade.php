@extends('layouts.app')
@section('title', 'Minha Loja — Aruanda Digital')

@push('styles')
<style>
    /* Hero vendedor */
    .seller-hero {
        background:linear-gradient(135deg, var(--p) 0%, var(--p-dk) 100%);
        padding:20px 16px 40px;color:#fff;
    }
    .seller-hero h6 { font-size:17px;font-weight:800;margin:0 0 3px; }
    .seller-hero small { font-size:12px;opacity:.8; }

    /* Stats flutuando */
    .seller-stats {
        display:flex;background:#fff;border-radius:var(--r);
        margin:-24px 14px 0;position:relative;z-index:2;
        box-shadow:var(--shadow-md);overflow:hidden;
    }
    .seller-stat {
        flex:1;text-align:center;padding:14px 6px;
        border-right:1px solid var(--border-lt);
    }
    .seller-stat:last-child { border-right:none; }
    .seller-stat .num { font-size:20px;font-weight:800;color:var(--p);line-height:1; }
    .seller-stat .lbl { font-size:10px;color:var(--txt-3);margin-top:3px;font-weight:600; }

    /* Toolbar */
    .seller-toolbar {
        display:flex;align-items:center;justify-content:space-between;
        padding:14px 16px 10px;margin-top:16px;
    }
    .sec-title { font-size:13px;font-weight:700;color:var(--txt); }

    /* Produto row */
    .prod-row {
        display:flex;align-items:center;gap:12px;
        padding:12px 16px;border-bottom:1px solid var(--border-lt);
        background:#fff;transition:background .15s;
    }
    .prod-row:active { background:var(--p-xl); }
    .prod-img {
        width:52px;height:52px;border-radius:var(--r-sm);
        object-fit:cover;background:var(--p-lt);flex-shrink:0;
    }
    .prod-info { flex:1;min-width:0; }
    .prod-name { font-size:13px;font-weight:700;color:var(--txt);margin-bottom:3px;
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
    .prod-meta { display:flex;align-items:center;gap:6px;flex-wrap:wrap; }
    .prod-price { font-size:14px;font-weight:800;color:var(--p-dk); }
    .prod-stock { font-size:11px;color:var(--txt-3);font-weight:600; }

    .status-active   { background:#dcfce7;color:#166534; }
    .status-inactive { background:#f3f4f6;color:#6b7280; }
    .status-pending  { background:#fef9c3;color:#854d0e; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="seller-hero">
    <h6><i class="bi bi-bag-heart me-2"></i>Minha Loja</h6>
    <small>{{ Auth::user()->name }}</small>
</div>

{{-- Stats --}}
<div class="seller-stats">
    <div class="seller-stat">
        <div class="num">{{ $stats['total_products'] ?? $products->total() }}</div>
        <div class="lbl">Produtos</div>
    </div>
    <div class="seller-stat">
        <div class="num">{{ $stats['total_sales'] ?? 0 }}</div>
        <div class="lbl">Vendas</div>
    </div>
    <div class="seller-stat">
        <div class="num">R$ {{ number_format($stats['revenue'] ?? 0, 0, ',', '.') }}</div>
        <div class="lbl">Receita</div>
    </div>
</div>

{{-- Toolbar --}}
<div class="seller-toolbar">
    <span class="sec-title">Produtos</span>
    <a href="{{ route('seller.products.create') }}"
       class="btn btn-sm btn-primary"
       style="border-radius:20px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:4px;">
        <i class="bi bi-plus"></i>Novo Produto
    </a>
</div>

{{-- Lista de produtos --}}
@forelse($products as $product)
<div class="prod-row">
    <img src="{{ $product->first_image_url }}" alt="{{ $product->name }}" class="prod-img"
         onerror="this.src='https://placehold.co/52x52/dcfce7/166534?text=P'">
    <div class="prod-info">
        <div class="prod-name">{{ $product->name }}</div>
        <div class="prod-meta">
            <span class="prod-price">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
            <span class="prod-stock">
                <i class="bi bi-box" style="font-size:9px;"></i>
                {{ $product->stock ?? 0 }} em estoque
            </span>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0;">
        <span class="badge-cat status-{{ $product->status === 'active' ? 'active' : ($product->status === 'pending' ? 'pending' : 'inactive') }}">
            {{ $product->status === 'active' ? 'Ativo' : ($product->status === 'pending' ? 'Pendente' : 'Inativo') }}
        </span>
        @if($product->is_wholesale)
        <span class="badge-cat badge-hot" style="font-size:9px;">Atacado</span>
        @endif
    </div>
</div>
@empty
<div class="empty-state" style="padding:60px 16px;">
    <i class="bi bi-bag-x"></i>
    <p>Nenhum produto cadastrado ainda.</p>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary mt-2" style="border-radius:20px;">
        Cadastrar Primeiro Produto
    </a>
</div>
@endforelse

@if($products->hasPages())
<div style="padding:16px;">{{ $products->links('pagination::bootstrap-5') }}</div>
@endif

@if($user->role === 'loja_master')
<div style="padding:12px 14px;">
    <a href="{{ route('wholesale') }}"
       style="display:flex;align-items:center;justify-content:center;gap:8px;
              padding:14px;background:#fff;border-radius:var(--r);
              border:1.5px solid var(--border);text-decoration:none;
              font-size:14px;font-weight:700;color:var(--p);box-shadow:var(--shadow-sm);">
        <i class="bi bi-boxes"></i>Gerenciar Atacado
    </a>
</div>
@endif

<div style="height:24px;"></div>
@endsection
