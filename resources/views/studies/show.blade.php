@extends('layouts.app')
@section('title', $study->title . ' — Aruanda Digital')

@push('styles')
<style>
    .study-hero {
        position:relative;background:var(--p-lt);
        min-height:200px;overflow:hidden;
    }
    .study-hero img {
        width:100%;max-height:220px;object-fit:cover;display:block;
    }
    .study-hero-overlay {
        position:absolute;inset:0;
        background:linear-gradient(to top, rgba(0,0,0,.7) 0%, rgba(0,0,0,.1) 60%);
    }
    .study-hero-back {
        position:absolute;top:12px;left:12px;
        width:38px;height:38px;background:rgba(255,255,255,.9);
        border-radius:50%;border:none;
        display:flex;align-items:center;justify-content:center;
        font-size:18px;color:var(--txt);text-decoration:none;
        box-shadow:var(--shadow);backdrop-filter:blur(4px);
    }
    .study-hero-chips {
        position:absolute;bottom:12px;left:12px;right:12px;
        display:flex;gap:6px;flex-wrap:wrap;
    }
    .chip-hero {
        font-size:10px;font-weight:700;padding:3px 10px;border-radius:12px;
        background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.35);
        color:#fff;backdrop-filter:blur(4px);
    }

    /* Tabs */
    .study-tabs {
        display:flex;background:#fff;
        border-bottom:1px solid var(--border-lt);
        position:sticky;top:0;z-index:50;
    }
    .s-tab {
        flex:1;padding:12px 8px;text-align:center;
        font-size:13px;font-weight:700;color:var(--txt-3);
        border:none;background:none;
        border-bottom:3px solid transparent;cursor:pointer;
        transition:color .2s,border-color .2s;
    }
    .s-tab.active { color:var(--p);border-bottom-color:var(--p); }

    /* Corpo */
    .study-body { padding:16px; }
    .study-title-txt { font-size:19px;font-weight:800;color:var(--txt);margin:0 0 8px;line-height:1.2; }
    .study-meta-row { display:flex;align-items:center;gap:8px;margin-bottom:16px;flex-wrap:wrap; }

    /* Grid de info */
    .info-grid {
        display:grid;grid-template-columns:1fr 1fr;gap:8px;
        margin-bottom:16px;
    }
    .info-cell {
        background:var(--bg);border-radius:var(--r-sm);
        padding:12px;text-align:center;
    }
    .info-cell i { font-size:18px;color:var(--p);display:block;margin-bottom:4px; }
    .info-cell .val { font-size:14px;font-weight:800;color:var(--txt);line-height:1; }
    .info-cell .lbl { font-size:10px;color:var(--txt-3);margin-top:2px;font-weight:600; }

    /* Vídeo */
    .video-wrap {
        border-radius:var(--r);overflow:hidden;
        margin-bottom:16px;background:#000;
    }

    /* Conteúdo texto */
    .study-content {
        font-size:14px;line-height:1.8;color:var(--txt-2);
    }

    /* Barra de ação */
    .study-bar {
        position:sticky;bottom:var(--bb-h);left:0;right:0;
        padding:10px 14px;background:var(--surface);
        border-top:1px solid var(--border);
        box-shadow:0 -4px 16px rgba(0,0,0,.06);
        display:flex;gap:10px;
    }
    .btn-complete {
        flex:1;height:46px;background:var(--p);
        border:none;border-radius:var(--r-sm);
        font-size:14px;font-weight:700;color:#fff;
        cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;
        transition:background .15s;
    }
    .btn-complete:hover { background:var(--p-hov); }
    .btn-complete:disabled { background:var(--txt-4);cursor:not-allowed; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="study-hero">
    <img src="{{ $study->thumbnail_url }}" alt="{{ $study->title }}"
         onerror="this.src='https://placehold.co/400x220/dcfce7/166534?text=Estudo'">
    <div class="study-hero-overlay"></div>
    <a href="{{ route('studies') }}" class="study-hero-back">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="study-hero-chips">
        @if($study->content_type)
        <span class="chip-hero">
            @if($study->content_type === 'video') 📹 Vídeo
            @elseif($study->content_type === 'audio') 🎧 Áudio
            @elseif($study->content_type === 'quiz') ❓ Quiz
            @else 📄 Texto
            @endif
        </span>
        @endif
        @if($study->category)
        <span class="chip-hero">{{ $study->category }}</span>
        @endif
        <span class="chip-hero">⭐ {{ $study->points }} pts</span>
    </div>
</div>

{{-- Tabs --}}
<div class="study-tabs">
    <button class="s-tab active" onclick="switchTab('conteudo')">
        <i class="bi bi-play-circle me-1"></i>Conteúdo
    </button>
    <button class="s-tab" onclick="switchTab('info')">
        <i class="bi bi-info-circle me-1"></i>Informações
    </button>
</div>

{{-- TAB: Conteúdo --}}
<div id="tab-conteudo" class="study-body">
    <h1 class="study-title-txt">{{ $study->title }}</h1>
    <div class="study-meta-row">
        @if($study->category)
        <span class="badge-cat">{{ strtoupper($study->category) }}</span>
        @endif
        <span class="badge-cat" style="background:var(--p-xl);color:var(--p);">
            <i class="bi bi-star-fill me-1" style="font-size:9px;"></i>{{ $study->points }} pts ao concluir
        </span>
    </div>

    @if($study->content_type === 'video' && $study->content_url)
    <div class="video-wrap">
        <div class="ratio ratio-16x9">
            <iframe src="{{ $study->content_url }}" allowfullscreen
                    style="border:none;"></iframe>
        </div>
    </div>
    @endif

    @if($study->content_type === 'pdf' && $study->content_file)
    <div style="padding:0 16px 16px;">
        <div style="border-radius:10px;overflow:hidden;border:1px solid var(--border-lt);background:#f8f9fa;">
            <div style="padding:10px 14px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border-lt);">
                <span style="font-size:13px;font-weight:700;color:var(--txt);"><i class="bi bi-file-earmark-pdf me-2" style="color:#dc2626;"></i>Documento PDF</span>
                <a href="{{ asset('storage/' . $study->content_file) }}" target="_blank" download
                   style="font-size:12px;font-weight:700;color:#16a34a;text-decoration:none;padding:4px 10px;border:1.5px solid #16a34a;border-radius:6px;">
                    <i class="bi bi-download me-1"></i>Baixar
                </a>
            </div>
            <iframe src="{{ asset('storage/' . $study->content_file) }}"
                    style="width:100%;height:65vh;border:none;display:block;"
                    title="{{ $study->title }}"></iframe>
        </div>
    </div>
    @endif

    @if($study->content_body)
    <div class="study-content">
        {!! nl2br(e($study->content_body)) !!}
    </div>
    @endif

    <div style="height:80px;"></div>
</div>

{{-- TAB: Informações --}}
<div id="tab-info" class="study-body" style="display:none;">
    <div class="info-grid">
        <div class="info-cell">
            <i class="bi bi-tag"></i>
            <div class="val">{{ $study->category ?? '—' }}</div>
            <div class="lbl">Categoria</div>
        </div>
        <div class="info-cell">
            <i class="bi bi-star"></i>
            <div class="val">{{ $study->points }}</div>
            <div class="lbl">Pontos</div>
        </div>
        <div class="info-cell">
            <i class="bi bi-file-earmark-text"></i>
            <div class="val">{{ ucfirst($study->content_type ?? 'texto') }}</div>
            <div class="lbl">Formato</div>
        </div>
        <div class="info-cell">
            <i class="bi bi-calendar3"></i>
            <div class="val">{{ $study->created_at->format('m/Y') }}</div>
            <div class="lbl">Publicado</div>
        </div>
    </div>

    @if($study->content_body && $study->content_type !== 'video')
    <div class="study-content">{!! nl2br(e($study->content_body)) !!}</div>
    @endif

    <div style="height:80px;"></div>
</div>

{{-- Barra de ação --}}
<div class="study-bar">
    <button class="btn-complete" id="btn-complete" onclick="markComplete()">
        <i class="bi bi-check-circle"></i>Marcar como Concluído (+{{ $study->points }} pts)
    </button>
</div>

@endsection

@push('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('.s-tab').forEach(function (t) { t.classList.remove('active'); });
    document.querySelectorAll('[id^="tab-"]').forEach(function (p) { p.style.display = 'none'; });
    document.getElementById('tab-' + tab).style.display = 'block';
    event.currentTarget.classList.add('active');
}

function markComplete() {
    var btn = document.getElementById('btn-complete');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Registrando...';

    $.post('{{ route("studies.complete", $study->id) }}', { _token: '{{ csrf_token() }}' })
    .done(function (r) {
        btn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Concluído!';
        Swal.fire({
            icon: 'success',
            title: 'Parabéns!',
            text: r.message ?? 'Estudo concluído. Pontos adicionados!',
            confirmButtonColor: '#16a34a',
            timer: 2500,
            showConfirmButton: false
        });
    })
    .fail(function (xhr) {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Marcar como Concluído (+{{ $study->points }} pts)';
        var msg = xhr.responseJSON?.message ?? 'Não foi possível registrar.';
        Swal.fire({ icon: 'info', title: 'Aviso', text: msg, confirmButtonColor: '#16a34a' });
    });
}
</script>
@endpush
