@extends('layouts.app')
@section('title', 'Carrinho — Aruanda Digital')

@push('styles')
<style>
    .cart-item {
        display:flex;align-items:stretch;
        background:#fff;border-bottom:1px solid var(--border-lt);
        gap:0;
    }
    .cart-item-img {
        width:80px;flex-shrink:0;object-fit:cover;
        background:var(--p-lt);display:block;
    }
    .cart-item-body {
        flex:1;min-width:0;padding:12px;
        display:flex;flex-direction:column;gap:6px;
    }
    .cart-item-name {
        font-size:13px;font-weight:700;color:var(--txt);
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
    }
    .cart-item-price { font-size:15px;font-weight:800;color:var(--p-dk); }
    .cart-item-actions { display:flex;align-items:center;gap:10px; }

    .qty-btn {
        width:28px;height:28px;border-radius:8px;
        border:1.5px solid var(--border);background:#fff;
        font-size:16px;font-weight:700;color:var(--txt-2);
        display:flex;align-items:center;justify-content:center;
        cursor:pointer;transition:all .15s;padding:0;
    }
    .qty-btn:hover { border-color:var(--p);color:var(--p); }
    .qty-val { font-size:14px;font-weight:700;color:var(--txt);min-width:20px;text-align:center; }

    .cart-remove {
        margin-left:auto;padding:4px 8px;
        font-size:12px;color:var(--txt-4);
        border:none;background:none;cursor:pointer;
    }
    .cart-remove:hover { color:#dc2626; }

    /* Resumo */
    .cart-summary {
        background:#fff;border-radius:var(--r);
        margin:12px 14px;box-shadow:var(--shadow-sm);
        overflow:hidden;
    }
    .sum-row {
        display:flex;justify-content:space-between;align-items:center;
        padding:12px 16px;border-bottom:1px solid var(--border-lt);
        font-size:13px;color:var(--txt-2);
    }
    .sum-row:last-child { border-bottom:none; }
    .sum-row.total {
        font-size:16px;font-weight:800;
        color:var(--txt);background:var(--p-xl);
    }
    .sum-row.total span:last-child { color:var(--p-dk); }

    /* Barra de ação */
    .cart-bar {
        position:sticky;bottom:var(--bb-h);left:0;right:0;
        padding:12px 14px;background:var(--surface);
        border-top:1px solid var(--border);
        box-shadow:0 -4px 16px rgba(0,0,0,.06);
    }
    .btn-checkout {
        width:100%;height:50px;background:var(--p);
        border:none;border-radius:var(--r-sm);
        font-size:16px;font-weight:700;color:#fff;
        cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;
        transition:background .15s;text-decoration:none;
    }
    .btn-checkout:hover { background:var(--p-hov);color:#fff; }
</style>
@endpush

@section('content')

<div class="page-hdr">
    <div>
        <h6 style="margin:0;"><i class="bi bi-bag me-2" style="color:var(--p);"></i>Carrinho</h6>
        @if(!empty($cartItems))
        <div class="t-muted" style="font-size:11px;margin-top:1px;">
            {{ array_sum(array_column($cartItems, 'qty')) }} iten(s)
        </div>
        @endif
    </div>
    <a href="{{ route('shop') }}" class="btn btn-sm btn-outline-success"
       style="border-radius:20px;font-size:12px;font-weight:700;">
        <i class="bi bi-plus me-1"></i>Continuar
    </a>
</div>

@if(empty($cartItems))
<div class="empty-state" style="padding-top:60px;">
    <i class="bi bi-bag-x"></i>
    <p>Seu carrinho está vazio.</p>
    <a href="{{ route('shop') }}" class="btn btn-primary mt-2" style="border-radius:20px;padding:8px 24px;">
        Explorar a Loja
    </a>
</div>
@else

{{-- Itens --}}
<div id="cart-list">
@foreach($cartItems as $item)
<div class="cart-item" id="ci-{{ $item['id'] }}">
    <img src="{{ $item['image'] ?? '' }}" alt="{{ $item['name'] }}" class="cart-item-img"
         onerror="this.src='https://placehold.co/80x80/dcfce7/166534?text=P'">
    <div class="cart-item-body">
        <div class="cart-item-name">{{ $item['name'] }}</div>
        <div class="cart-item-price">R$ {{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 2, ',', '.') }}</div>
        <div class="cart-item-actions">
            <button class="qty-btn" onclick="changeQty({{ $item['id'] }}, -1)">−</button>
            <span class="qty-val" id="qty-{{ $item['id'] }}">{{ $item['qty'] ?? 1 }}</span>
            <button class="qty-btn" onclick="changeQty({{ $item['id'] }}, 1)">+</button>
            <form method="POST" action="{{ route('cart.remove', $item['id']) }}" style="margin-left:auto;">
                @csrf
                @method('DELETE')
                <button type="submit" class="cart-remove" title="Remover">
                    <i class="bi bi-trash3"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endforeach
</div>

{{-- Resumo --}}
<div class="cart-summary">
    <div class="sum-row">
        <span>Subtotal</span>
        <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
    </div>
    <div class="sum-row">
        <span>Frete</span>
        <span style="color:var(--p);font-weight:700;">Grátis</span>
    </div>
    <div class="sum-row total">
        <span>Total</span>
        <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
    </div>
</div>

<div style="height:90px;"></div>

<div class="cart-bar">
    <a href="{{ route('checkout') }}" class="btn-checkout">
        <i class="bi bi-bag-check"></i>Finalizar Pedido
    </a>
</div>
@endif

@endsection

@push('scripts')
<script>
function changeQty(productId, delta) {
    $.post('{{ route("cart.add") }}', {
        _token: '{{ csrf_token() }}',
        product_id: productId,
        quantity: delta
    })
    .done(function () { location.reload(); })
    .fail(function () {
        Swal.fire({ icon:'error', title:'Erro', text:'Não foi possível atualizar.', confirmButtonColor:'#16a34a' });
    });
}
</script>
@endpush
