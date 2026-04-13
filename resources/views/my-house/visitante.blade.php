@extends('layouts.app')
@section('title', 'Minha Casa — Aruanda Digital')

@push('styles')
<style>
    .minha-casa-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 48px 24px 32px;
    }
    .minha-casa-icon {
        width: 88px; height: 88px;
        background: var(--p-xl);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 40px; color: var(--p);
        margin-bottom: 20px;
    }
    .minha-casa-title {
        font-size: 18px; font-weight: 800; color: var(--txt);
        margin-bottom: 8px;
    }
    .minha-casa-sub {
        font-size: 14px; color: var(--txt-3);
        line-height: 1.6; max-width: 300px;
        margin-bottom: 28px;
    }
    .btn-filiar {
        display: flex; align-items: center; justify-content: center;
        gap: 8px;
        width: 100%; max-width: 280px;
        padding: 14px 20px;
        background: var(--p);
        color: #fff; border: none;
        border-radius: 14px;
        font-size: 15px; font-weight: 700;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(22,163,74,.3);
        transition: background .2s, transform .1s;
    }
    .btn-filiar:hover  { background: var(--p-hov); color: #fff; }
    .btn-filiar:active { transform: scale(.97); }

    /* Seção informativa */
    .info-cards {
        padding: 0 16px 24px;
        display: flex; flex-direction: column; gap: 10px;
    }
    .info-card {
        display: flex; align-items: center; gap: 14px;
        background: var(--surface);
        border-radius: var(--r);
        padding: 14px 16px;
        box-shadow: var(--shadow-sm);
    }
    .info-card-icon {
        width: 42px; height: 42px; flex-shrink: 0;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
    }
    .info-card-text strong {
        font-size: 13px; font-weight: 700; color: var(--txt);
        display: block; margin-bottom: 2px;
    }
    .info-card-text span {
        font-size: 12px; color: var(--txt-3); line-height: 1.4;
    }
</style>
@endpush

@section('content')

<div class="page-hdr">
    <h6><i class="bi bi-house-heart me-2" style="color:var(--p);"></i>Minha Casa</h6>
</div>

<div class="minha-casa-empty">
    <div class="minha-casa-icon">
        <i class="bi bi-house-heart"></i>
    </div>
    <div class="minha-casa-title">Você ainda não é filiado</div>
    <div class="minha-casa-sub">
        Encontre uma casa ou templo e solicite sua filiação para participar de rituais, estudos e eventos exclusivos.
    </div>
    <a href="{{ route('houses') }}" class="btn-filiar">
        <i class="bi bi-person-plus-fill"></i>
        Filiar-se a uma Casa
    </a>
</div>

{{-- Benefícios de ser filiado --}}
<div class="info-cards">
    <div class="info-card">
        <div class="info-card-icon" style="background:#dcfce7;">
            <i class="bi bi-book" style="color:#166534;"></i>
        </div>
        <div class="info-card-text">
            <strong>Estudos Exclusivos</strong>
            <span>Acesse materiais e cursos espirituais da sua casa.</span>
        </div>
    </div>
    <div class="info-card">
        <div class="info-card-icon" style="background:#dbeafe;">
            <i class="bi bi-calendar-check" style="color:#1e40af;"></i>
        </div>
        <div class="info-card-text">
            <strong>Eventos e Rituais</strong>
            <span>Participe de giras, sessões e celebrações.</span>
        </div>
    </div>
    <div class="info-card">
        <div class="info-card-icon" style="background:#fce7f3;">
            <i class="bi bi-people" style="color:#9d174d;"></i>
        </div>
        <div class="info-card-text">
            <strong>Comunidade</strong>
            <span>Conecte-se com membros e dirigentes da sua casa.</span>
        </div>
    </div>
    <div class="info-card">
        <div class="info-card-icon" style="background:#fef3c7;">
            <i class="bi bi-trophy" style="color:#92400e;"></i>
        </div>
        <div class="info-card-text">
            <strong>Pontos & Conquistas</strong>
            <span>Ganhe pontos e suba no ranking da sua casa.</span>
        </div>
    </div>
</div>

@endsection
