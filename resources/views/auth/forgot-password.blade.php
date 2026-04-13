@extends('layouts.guest')

@section('title', 'Recuperar Senha — Aruanda Digital')

@section('content')

<div class="text-center mb-4">
    <div style="width:56px;height:56px;background:#DCFCE7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
        <i class="bi bi-shield-lock" style="font-size:26px;color:#16A34A;"></i>
    </div>
    <h2 class="section-title">Recuperar Senha</h2>
    <p class="section-subtitle">Informe seu e-mail para receber o link de redefinição</p>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius:10px;font-size:14px;">
        <i class="bi bi-check-circle-fill text-success"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-4">
        <label class="form-label fw-semibold small" for="email">E-mail cadastrado</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="seu@email.com"
                autocomplete="email"
                required
                autofocus
            >
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
        <i class="bi bi-send me-1"></i>Enviar link de recuperação
    </button>

</form>

<div class="text-center">
    <a href="{{ route('login') }}" class="link-subtle">
        <i class="bi bi-arrow-left me-1"></i>Voltar para o login
    </a>
</div>

@endsection
