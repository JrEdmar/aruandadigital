@extends('layouts.app')
@section('title', 'Alterar Senha — Aruanda Digital')

@section('content')

<div class="page-hdr">
    <h6><i class="bi bi-lock me-2" style="color:var(--p);"></i>Alterar Senha</h6>
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

<form method="POST" action="{{ route('profile.change-password.update') }}">
@csrf
@method('PUT')

<div class="mb-3">
    <label class="form-label fw-semibold small" for="current_password">Senha atual</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-lock"></i></span>
        <input type="password" id="current_password" name="current_password"
               class="form-control @error('current_password') is-invalid @enderror"
               placeholder="Senha atual" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small" for="password">Nova senha</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
        <input type="password" id="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="Mínimo 8 caracteres" required>
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold small" for="password_confirmation">Confirmar nova senha</label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
        <input type="password" id="password_confirmation" name="password_confirmation"
               class="form-control" placeholder="Repita a nova senha" required>
    </div>
</div>

<button type="submit" class="btn btn-primary w-100" style="border-radius:var(--r-sm);padding:12px;">
    <i class="bi bi-check-circle me-1"></i>Salvar Nova Senha
</button>

<a href="{{ route('profile') }}" class="btn btn-outline-secondary w-100 mt-2" style="border-radius:var(--r-sm);padding:11px;">
    Cancelar
</a>

</form>
</div>
</div>

<div style="height:24px;"></div>
@endsection
