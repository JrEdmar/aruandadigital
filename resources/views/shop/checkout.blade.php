@extends('layouts.app')
@section('title', 'Finalizar Pedido — Aruanda Digital')

@push('styles')
<style>
    .checkout-header { background: linear-gradient(135deg,var(--p),var(--p-dk)); padding:16px; color:#fff; }
    .checkout-header h6 { margin:0; font-size:17px; font-weight:800; }
    .section-card { background:var(--surface); border-radius:var(--r); margin:12px 14px 0; overflow:hidden; box-shadow:var(--shadow-sm); }
    .section-card-head { padding:12px 16px 8px; font-size:11px; font-weight:700; color:var(--txt-3); text-transform:uppercase; letter-spacing:.6px; border-bottom:1px solid var(--border-lt); }
    .pay-opt { display:flex; align-items:center; gap:12px; padding:13px 16px; border-bottom:1px solid var(--border-lt); cursor:pointer; }
    .pay-opt:last-child { border-bottom:none; }
    .pay-opt input[type=radio] { width:18px; height:18px; accent-color:var(--p); }
    .pay-opt label { font-size:14px; font-weight:600; color:var(--txt); cursor:pointer; flex:1; }
    .pay-opt .pay-icon { font-size:20px; color:var(--p); width:24px; text-align:center; }
    .order-item { display:flex; align-items:center; gap:10px; padding:10px 16px; border-bottom:1px solid var(--border-lt); }
    .order-item:last-child { border-bottom:none; }
    .order-item img { width:44px; height:44px; border-radius:var(--r-sm); object-fit:cover; background:var(--p-lt); }
    .order-item-name { font-size:13px; font-weight:600; color:var(--txt); flex:1; }
    .order-item-price { font-size:13px; font-weight:700; color:var(--p-dk); }
    .total-row { display:flex; justify-content:space-between; align-items:center; padding:14px 16px; background:var(--surface); border-top:1px solid var(--border); }
    .btn-finish { width:100%; height:50px; background:var(--p); border:none; border-radius:var(--r-sm); font-size:16px; font-weight:700; color:#fff; cursor:pointer; transition:background .15s; }
    .btn-finish:hover { background:var(--p-hov); }
</style>
@endpush

@section('content')

<div class="checkout-header">
    <h6><i class="bi bi-bag-check me-2"></i>Finalizar Pedido</h6>
</div>

<form method="POST" action="{{ route('checkout.store') }}">
@csrf

{{-- Resumo dos itens --}}
<div class="section-card">
    <div class="section-card-head">Itens do Pedido</div>
    @foreach($cart as $item)
    <div class="order-item">
        <img src="{{ $item['image'] ?? '' }}" alt="{{ $item['name'] }}"
             onerror="this.src='https://placehold.co/44x44/dcfce7/166534?text=P'">
        <span class="order-item-name">{{ $item['name'] }} <span class="text-muted">×{{ $item['qty'] }}</span></span>
        <span class="order-item-price">R$ {{ number_format(($item['price'] ?? 0) * ($item['qty'] ?? 1), 2, ',', '.') }}</span>
    </div>
    @endforeach
</div>

{{-- Forma de pagamento --}}
<div class="section-card">
    <div class="section-card-head">Forma de Pagamento</div>
    <div class="pay-opt">
        <span class="pay-icon"><i class="bi bi-qr-code"></i></span>
        <input type="radio" name="payment_method" id="pix" value="pix" checked>
        <label for="pix">Pix <small class="text-success fw-600">(aprovação imediata)</small></label>
    </div>
    <div class="pay-opt">
        <span class="pay-icon"><i class="bi bi-credit-card"></i></span>
        <input type="radio" name="payment_method" id="card" value="card">
        <label for="card">Cartão de Crédito</label>
    </div>
    <div class="pay-opt">
        <span class="pay-icon"><i class="bi bi-receipt"></i></span>
        <input type="radio" name="payment_method" id="boleto" value="boleto">
        <label for="boleto">Boleto Bancário</label>
    </div>
</div>

{{-- Total --}}
<div class="section-card" style="margin-bottom:100px;">
    <div class="total-row">
        <span style="font-size:15px;font-weight:600;color:var(--txt-2);">Total</span>
        <span style="font-size:22px;font-weight:800;color:var(--p-dk);">R$ {{ number_format($total, 2, ',', '.') }}</span>
    </div>
</div>

{{-- Barra de ação --}}
<div style="position:sticky;bottom:var(--bb-h);left:0;right:0;padding:12px 14px;background:var(--surface);border-top:1px solid var(--border);box-shadow:0 -4px 16px rgba(0,0,0,.06);">
    <button type="submit" class="btn-finish">
        <i class="bi bi-check-circle me-2"></i>Confirmar Pedido
    </button>
</div>

</form>

<div style="height:24px;"></div>
@endsection
