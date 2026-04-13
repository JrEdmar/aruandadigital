@extends('layouts.app')
@section('title', 'Novo Produto — Aruanda Digital')

@section('content')

<div class="page-hdr">
    <h6><i class="bi bi-bag-plus me-2" style="color:var(--p);"></i>Novo Produto</h6>
</div>

<div style="padding:14px;">
<div style="background:var(--surface);border-radius:var(--r);padding:20px;box-shadow:var(--shadow-sm);">

@if($errors->any())
    <div class="alert alert-danger" style="border-radius:var(--r-sm);font-size:13px;">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('seller.products.store') }}">
@csrf

<div class="mb-3">
    <label class="form-label fw-semibold small">Nome do produto *</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name') }}" required placeholder="Ex: Vela 7 dias branca">
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small">Descrição</label>
    <textarea name="description" class="form-control" rows="3"
              placeholder="Descreva o produto...">{{ old('description') }}</textarea>
</div>

<div class="row g-2 mb-3">
    <div class="col-6">
        <label class="form-label fw-semibold small">Preço (R$) *</label>
        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
               step="0.01" min="0" value="{{ old('price') }}" required placeholder="0,00">
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold small">Estoque</label>
        <input type="number" name="stock" class="form-control" min="0"
               value="{{ old('stock', 0) }}">
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold small">Categoria</label>
    <input type="text" name="category" class="form-control"
           value="{{ old('category') }}" placeholder="Ex: Velas, Ervas, Indumentária">
</div>

<button type="submit" class="btn btn-primary w-100" style="border-radius:var(--r-sm);padding:12px;">
    <i class="bi bi-check-circle me-1"></i>Cadastrar Produto
</button>

<a href="{{ route('seller') }}" class="btn btn-outline-secondary w-100 mt-2" style="border-radius:var(--r-sm);padding:11px;">
    Cancelar
</a>

</form>
</div>
</div>

<div style="height:24px;"></div>
@endsection
