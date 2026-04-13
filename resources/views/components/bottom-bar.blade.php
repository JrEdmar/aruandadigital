{{-- Bottom Bar — navegação principal --}}
@php $u = Auth::user(); @endphp
<nav class="bottom-bar">

{{-- ── LOJA / LOJA MASTER — barra exclusiva ── --}}
@if ($u->hasRole('loja') || $u->hasRole('loja_master'))
    <a href="{{ url('/') }}"         class="{{ request()->is('/') ? 'active' : '' }}">
        <i class="bi bi-house{{ request()->is('/') ? '-fill' : '' }}"></i>Home
    </a>
    <a href="{{ url('/orders') }}"   class="{{ request()->is('orders*') ? 'active' : '' }}">
        <i class="bi bi-box{{ request()->is('orders*') ? '-fill' : '' }}"></i>Pedidos
    </a>
    <a href="{{ url('/seller') }}"   class="{{ request()->is('seller*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart{{ request()->is('seller*') ? '-fill' : '' }}"></i>Loja
    </a>
    <a href="{{ url('/shop') }}"     class="{{ request()->is('shop*') ? 'active' : '' }}">
        <i class="bi bi-bag{{ request()->is('shop*') ? '-fill' : '' }}"></i>Catálogo
    </a>
    <a href="{{ url('/profile') }}"  class="{{ request()->is('profile*') ? 'active' : '' }}">
        <i class="bi bi-person{{ request()->is('profile*') ? '-fill' : '' }}"></i>Perfil
    </a>

{{-- ── TODOS OS DEMAIS PERFIS (visitante, membro, assistente, dirigente, admin, moderador) ── --}}
@else
    <a href="{{ url('/') }}"          class="{{ request()->is('/') ? 'active' : '' }}">
        <i class="bi bi-house{{ request()->is('/') ? '-fill' : '' }}"></i>Home
    </a>
    <a href="{{ url('/events') }}"    class="{{ request()->is('events*') ? 'active' : '' }}">
        <i class="bi bi-calendar-event{{ request()->is('events*') ? '-fill' : '' }}"></i>Eventos
    </a>
    <a href="{{ url('/my-house') }}"  class="{{ request()->is('my-house*') ? 'active' : '' }}">
        <i class="bi bi-house-heart{{ request()->is('my-house*') ? '-fill' : '' }}"></i>Minha Casa
    </a>
    {{-- LOJA DESABILITADA — descomentar para reativar
    <a href="{{ url('/shop') }}"      class="{{ request()->is('shop*') ? 'active' : '' }}">
        <i class="bi bi-bag{{ request()->is('shop*') ? '-fill' : '' }}"></i>Loja
    </a>
    --}}
    <a href="{{ url('/profile') }}"   class="{{ request()->is('profile*') ? 'active' : '' }}">
        <i class="bi bi-person{{ request()->is('profile*') ? '-fill' : '' }}"></i>Perfil
    </a>
@endif

</nav>
