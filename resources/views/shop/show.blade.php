@extends('layouts.app')
@section('title', $product->name . ' — Aruanda Digital')

@push('styles')
<style>
    .product-hero {
        position: relative;
        background: var(--p-lt);
        max-height: 280px;
        overflow: hidden;
    }
    .product-hero img {
        width: 100%;
        max-height: 280px;
        object-fit: cover;
        display: block;
    }
    .hero-back {
        position: absolute;
        top: 12px; left: 12px;
        width: 38px; height: 38px;
        background: rgba(255,255,255,.9);
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: var(--txt);
        text-decoration: none;
        box-shadow: var(--shadow);
        backdrop-filter: blur(4px);
    }

    .product-body { padding: 16px; }

    .product-cat-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--p);
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 6px;
    }
    .product-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--txt);
        margin: 0 0 12px;
        line-height: 1.2;
    }

    /* Preço destacado */
    .price-block {
        background: var(--p-xl);
        border-radius: var(--r);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        border: 1.5px solid var(--p-lt);
    }
    .price-label { font-size: 11px; color: var(--txt-3); font-weight: 600; }
    .price-value { font-size: 28px; font-weight: 800; color: var(--p-dk); line-height: 1; }
    .price-note  { font-size: 11px; color: var(--p); font-weight: 600; }

    /* Stock info */
    .stock-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: var(--txt-3);
        margin-bottom: 16px;
    }
    .stock-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .product-desc-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--txt);
        margin: 0 0 6px;
    }
    .product-desc {
        font-size: 13px;
        line-height: 1.6;
        color: var(--txt-2);
        margin: 0 0 20px;
    }

    /* Botão de compra fixo na base */
    .buy-bar {
        position: sticky;
        bottom: calc(var(--bb-h) + 8px);
        left: 0; right: 0;
        padding: 12px 16px;
        background: var(--surface);
        border-top: 1px solid var(--border-lt);
        box-shadow: 0 -4px 16px rgba(0,0,0,.06);
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .btn-wishlist {
        width: 48px; height: 48px;
        border-radius: var(--r-sm);
        border: 1.5px solid var(--border);
        background: var(--surface);
        font-size: 20px;
        color: var(--txt-3);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-wishlist:hover { border-color: var(--p); color: var(--p); }
    .btn-cart {
        flex: 1;
        height: 48px;
        background: var(--p);
        border: none;
        border-radius: var(--r-sm);
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center; gap: 8px;
        transition: background .15s, transform .1s;
    }
    .btn-cart:hover  { background: var(--p-hov); }
    .btn-cart:active { transform: scale(.98); }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="product-hero">
    <img src="{{ $product->first_image_url }}" alt="{{ $product->name }}"
         onerror="this.src='https://placehold.co/400x280/dcfce7/166534?text={{ urlencode(substr($product->name,0,1)) }}'">
    <a href="{{ route('shop') }}" class="hero-back">
        <i class="bi bi-arrow-left"></i>
    </a>
</div>

{{-- Corpo --}}
<div class="product-body">

    <div class="product-cat-label">
        <i class="bi bi-tag me-1"></i>{{ $product->category ?? 'Produto' }}
    </div>

    <h1 class="product-title">{{ $product->name }}</h1>

    {{-- Bloco de preço --}}
    <div class="price-block">
        <div>
            <div class="price-label">Preço</div>
            <div class="price-value">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
        </div>
        <div style="text-align:right;">
            @if(($product->stock ?? 0) > 0)
                <div class="price-note"><i class="bi bi-check-circle me-1"></i>Em estoque</div>
                <div class="price-label">{{ $product->stock }} disponíveis</div>
            @else
                <div style="font-size:11px;font-weight:700;color:#dc2626;"><i class="bi bi-x-circle me-1"></i>Esgotado</div>
            @endif
        </div>
    </div>

    {{-- Estoque visual --}}
    <div class="stock-row">
        <div class="stock-dot" style="background:{{ ($product->stock ?? 0) > 5 ? '#22c55e' : (($product->stock ?? 0) > 0 ? '#f59e0b' : '#ef4444') }};"></div>
        @if(($product->stock ?? 0) > 10)
            Estoque disponível
        @elseif(($product->stock ?? 0) > 0)
            Últimas {{ $product->stock }} unidades!
        @else
            Produto esgotado
        @endif
    </div>

    {{-- Descrição --}}
    @if($product->description)
    <p class="product-desc-title">Descrição</p>
    <p class="product-desc">{{ $product->description }}</p>
    @endif

    {{-- Tags extras --}}
    <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:80px;">
        <span class="badge-cat">{{ $product->category ?? 'Produto' }}</span>
        @if($product->is_wholesale ?? false)
            <span class="badge-cat badge-hot">Disponível no Atacado</span>
        @endif
    </div>

</div>

{{-- Barra de ação fixada --}}
<div class="buy-bar">
    <button class="btn-wishlist" title="Favoritar">
        <i class="bi bi-heart"></i>
    </button>
    <button class="btn-cart" onclick="addToCart({{ $product->id }})">
        <i class="bi bi-bag-plus"></i>
        Adicionar ao Carrinho
    </button>
</div>

@endsection

@push('scripts')
<script>
function addToCart(id) {
    $.post('{{ route('cart.add') }}', {
        _token: '{{ csrf_token() }}',
        product_id: id,
        quantity: 1
    })
    .done(function (r) {
        Swal.fire({
            icon: 'success',
            title: 'Adicionado!',
            text: 'Produto adicionado ao carrinho.',
            timer: 1800,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    })
    .fail(function () {
        Swal.fire({ icon: 'error', title: 'Erro', text: 'Não foi possível adicionar.', confirmButtonColor: '#16a34a' });
    });
}
</script>
@endpush
