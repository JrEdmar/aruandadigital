@extends('layouts.guest')

@section('title', 'Redefinir Senha — Aruanda Digital')

@section('content')

<div class="text-center mb-4">
    <div style="width:56px;height:56px;background:#DCFCE7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
        <i class="bi bi-key" style="font-size:26px;color:#16A34A;"></i>
    </div>
    <h2 class="section-title">Nova Senha</h2>
    <p class="section-subtitle">Crie uma nova senha segura para sua conta</p>
</div>

@if(session('error'))
    @push('scripts')
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Link inválido',
            text: '{{ addslashes(session("error")) }}',
            confirmButtonColor: '#16A34A',
        });
    </script>
    @endpush
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    {{-- Token e e-mail (hidden) --}}
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">

    {{-- Nova Senha --}}
    <div class="mb-3">
        <label class="form-label fw-semibold small" for="password">Nova Senha</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Mínimo 8 caracteres"
                autocomplete="new-password"
                required
                autofocus
            >
            <button type="button" class="input-group-text border-start-0" id="toggleNew" style="cursor:pointer;border-radius:0 8px 8px 0 !important;">
                <i class="bi bi-eye" id="iconNew"></i>
            </button>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    {{-- Confirmar Nova Senha --}}
    <div class="mb-4">
        <label class="form-label fw-semibold small" for="password_confirmation">Confirmar Nova Senha</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="form-control"
                placeholder="Repita a nova senha"
                autocomplete="new-password"
                required
            >
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
        <i class="bi bi-check-circle me-1"></i>Redefinir Senha
    </button>

</form>

<div class="text-center">
    <a href="{{ route('login') }}" class="link-subtle">
        <i class="bi bi-arrow-left me-1"></i>Voltar para o login
    </a>
</div>

@endsection

@push('scripts')
<script>
$(function () {
    $('#toggleNew').on('click', function () {
        const input = $('#password');
        const icon  = $('#iconNew');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });
});
</script>
@endpush
