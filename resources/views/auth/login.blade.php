@extends('layouts.guest')

@section('title', 'Login — Aruanda Digital')

@section('content')

<h2 class="section-title">Entrar</h2>
<p class="section-subtitle">Acesse sua conta Aruanda Digital</p>

{{-- Erros de validação --}}
@if($errors->any())
    @push('scripts')
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Dados inválidos',
            html: '<ul style="text-align:left;margin:0;padding-left:20px">@foreach($errors->all() as $error)<li>{{ addslashes($error) }}</li>@endforeach</ul>',
            confirmButtonColor: '#16A34A',
            confirmButtonText: 'Corrigir',
        });
    </script>
    @endpush
@endif

<form method="POST" action="{{ route('login') }}" id="loginForm">
    @csrf

    {{-- E-mail --}}
    <div class="mb-3">
        <label class="form-label fw-semibold small" for="email">E-mail</label>
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
            >
        </div>
    </div>

    {{-- Senha --}}
    <div class="mb-3">
        <label class="form-label fw-semibold small" for="password">Senha</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input
                type="password"
                id="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="••••••••"
                autocomplete="current-password"
                required
            >
            <button type="button" class="input-group-text border-start-0" id="togglePassword" style="cursor:pointer;border-radius:0 8px 8px 0 !important;">
                <i class="bi bi-eye" id="toggleIcon"></i>
            </button>
        </div>
    </div>

    {{-- Lembrar-me + Entrar --}}
    <div class="d-flex align-items-center justify-content-between mb-3 gap-2">
        <div class="form-check mb-0">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label small text-muted" for="remember">Lembrar-me</label>
        </div>
        <button type="submit" class="btn btn-primary px-4 py-2">
            <i class="bi bi-box-arrow-in-right me-1"></i>Entrar
        </button>
    </div>

    {{-- Link esqueci senha --}}
    <div class="text-center mb-3">
        <a href="{{ route('password.request') }}" class="link-subtle">
            Esqueci minha senha
        </a>
    </div>
</form>

{{-- Separador --}}
<div class="divider-text my-3">ou continue com</div>

{{-- Botão Google --}}
<a href="{{ route('auth.google') }}" class="btn btn-google w-100 mb-2 d-flex align-items-center justify-content-center gap-2">
    <svg width="18" height="18" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M47.532 24.552c0-1.636-.132-3.272-.412-4.872H24.48v9.218h13.002c-.558 2.994-2.232 5.556-4.752 7.254v5.994h7.686c4.5-4.146 7.116-10.26 7.116-17.594z" fill="#4285F4"/>
        <path d="M24.48 48c6.498 0 11.97-2.142 15.96-5.844l-7.686-5.994c-2.16 1.458-4.932 2.304-8.274 2.304-6.354 0-11.742-4.29-13.68-10.062H2.832v6.192C6.804 42.714 15.132 48 24.48 48z" fill="#34A853"/>
        <path d="M10.8 28.404A14.432 14.432 0 0 1 9.9 24c0-1.524.264-3.006.9-4.404v-6.192H2.832A24.016 24.016 0 0 0 0 24c0 3.876.93 7.548 2.832 10.596l7.968-6.192z" fill="#FBBC05"/>
        <path d="M24.48 9.534c3.564 0 6.756 1.224 9.276 3.636l6.948-6.948C36.45 2.394 30.978 0 24.48 0 15.132 0 6.804 5.286 2.832 13.404l7.968 6.192C12.738 13.824 18.126 9.534 24.48 9.534z" fill="#EA4335"/>
    </svg>
    Continuar com Google
</a>

{{-- Botão Facebook --}}
<a href="{{ route('auth.facebook') }}" class="btn btn-facebook w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
    <i class="bi bi-facebook" style="font-size:18px"></i>
    Continuar com Facebook
</a>

{{-- Cadastre-se --}}
<div class="divider-text my-3">não tem conta?</div>

<a href="{{ route('register') }}" class="btn btn-outline-secondary w-100" style="border-radius:8px;font-weight:600;">
    <i class="bi bi-person-plus me-1"></i>Cadastre-se gratuitamente
</a>

@endsection

@push('scripts')
<script>
$(function () {
    // Toggle visibilidade da senha
    $('#togglePassword').on('click', function () {
        const input = $('#password');
        const icon  = $('#toggleIcon');
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
