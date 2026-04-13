@extends('layouts.app')
@section('title', 'Estudos — Aruanda Digital')

@push('styles')
<style>
    .filter-scroll {
        display:flex;gap:8px;padding:10px 16px;
        overflow-x:auto;scrollbar-width:none;
        background:#fff;border-bottom:1px solid var(--border-lt);
    }
    .filter-scroll::-webkit-scrollbar { display:none; }
    .fchip {
        flex-shrink:0;padding:6px 14px;
        border:1.5px solid var(--border);border-radius:20px;
        font-size:12px;font-weight:600;color:var(--txt-2);background:#fff;
        cursor:pointer;white-space:nowrap;transition:all .15s;
    }
    .fchip.active { border-color:var(--p);color:var(--p);background:var(--p-xl); }

    .study-row {
        display:flex;align-items:center;gap:12px;
        padding:12px 16px;border-bottom:1px solid var(--border-lt);
        background:var(--surface);text-decoration:none;color:inherit;
        transition:background .15s;
    }
    .study-row:active { background:var(--p-xl); }
    .study-thumb {
        width:64px;height:64px;border-radius:var(--r-sm);
        object-fit:cover;background:var(--p-lt);flex-shrink:0;
    }
    .study-info { flex:1;min-width:0; }
    .study-title {
        font-size:14px;font-weight:700;color:var(--txt);
        margin-bottom:4px;line-height:1.3;
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
    }
    .study-meta { display:flex;align-items:center;gap:6px;flex-wrap:wrap; }
    .study-type-badge {
        font-size:10px;font-weight:700;padding:2px 8px;border-radius:6px;
        background:var(--p-xl);color:var(--p);
    }
    .study-pts {
        font-size:11px;color:var(--txt-3);font-weight:600;
        display:flex;align-items:center;gap:3px;
    }
</style>
@endpush

@section('content')

<div class="page-hdr">
    <div>
        <h6 style="margin:0;"><i class="bi bi-book me-2" style="color:var(--p);"></i>Estudos</h6>
        <div class="t-muted" style="font-size:11px;margin-top:1px;">{{ $studies->total() }} disponíveis</div>
    </div>
    @auth
        @if(Auth::user()->hasRole('admin,moderador'))
        <a href="{{ route('studies.create') }}"
           class="btn btn-sm btn-outline-success"
           style="border-radius:20px;font-size:12px;font-weight:700;display:flex;align-items:center;gap:4px;">
            <i class="bi bi-plus"></i>Novo
        </a>
        @endif
    @endauth
</div>

<div class="search-wrap">
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input type="text" id="searchInput" class="form-control" placeholder="Buscar estudos...">
    </div>
</div>

<div class="filter-scroll">
    <button class="fchip active" data-cat="">Todos</button>
    <button class="fchip" data-cat="video">Vídeo</button>
    <button class="fchip" data-cat="texto">Texto</button>
    <button class="fchip" data-cat="audio">Áudio</button>
    <button class="fchip" data-cat="quiz">Quiz</button>
</div>

<div id="studies-wrapper">
@forelse($studies as $study)
<a href="{{ route('studies.show', $study->id) }}" class="study-row"
   data-name="{{ strtolower($study->title) }}"
   data-cat="{{ strtolower($study->content_type ?? '') }}">
    <img src="{{ $study->thumbnail_url }}" alt="{{ $study->title }}" class="study-thumb"
         onerror="this.src='https://placehold.co/64x64/dcfce7/166534?text=E'">
    <div class="study-info">
        <div class="study-title">{{ $study->title }}</div>
        <div class="study-meta">
            @if($study->content_type)
            <span class="study-type-badge">
                @if($study->content_type === 'video') <i class="bi bi-play-circle me-1"></i>Vídeo
                @elseif($study->content_type === 'audio') <i class="bi bi-music-note me-1"></i>Áudio
                @elseif($study->content_type === 'quiz') <i class="bi bi-question-circle me-1"></i>Quiz
                @else <i class="bi bi-file-text me-1"></i>Texto
                @endif
            </span>
            @endif
            @if($study->category)
            <span class="badge-cat">{{ strtoupper($study->category) }}</span>
            @endif
            <span class="study-pts">
                <i class="bi bi-star-fill" style="font-size:9px;color:var(--p);"></i>
                {{ $study->points }} pts
            </span>
        </div>
    </div>
    <i class="bi bi-chevron-right" style="font-size:13px;color:var(--txt-4);flex-shrink:0;"></i>
</a>
@empty
<div class="empty-state">
    <i class="bi bi-book"></i>
    <p>Nenhum estudo disponível ainda.</p>
</div>
@endforelse
</div>

@if($studies->hasPages())
<div style="padding:16px;">{{ $studies->links('pagination::bootstrap-5') }}</div>
@endif

<div style="height:24px;"></div>
@endsection

@push('scripts')
<script>
$(function () {
    var deb;
    $('#searchInput').on('input', function () {
        clearTimeout(deb);
        var q = $(this).val().toLowerCase();
        deb = setTimeout(function () {
            $('.study-row').each(function () {
                $(this).toggle($(this).data('name').includes(q));
            });
        }, 280);
    });

    $('.fchip').on('click', function () {
        $('.fchip').removeClass('active');
        $(this).addClass('active');
        var cat = $(this).data('cat');
        $('.study-row').each(function () {
            $(this).toggle(!cat || $(this).data('cat') === cat);
        });
    });
});
</script>
@endpush
