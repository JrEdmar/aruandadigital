@extends('layouts.app')
@section('title'){{ $event->name }} — Aruanda Digital@endsection

@push('styles')
<style>
    /* ── Capa ── */
    .ev-cover-wrap {
        position: relative;
        height: 220px;
        background: var(--p-lt);
        overflow: hidden;
    }
    .ev-cover { width:100%;height:100%;object-fit:cover;display:block; }
    .ev-cover-overlay {
        position:absolute;inset:0;
        background:linear-gradient(to bottom,rgba(0,0,0,.15) 0%,rgba(0,0,0,.6) 100%);
    }
    .ev-cover-back {
        position:absolute;top:12px;left:12px;
        width:38px;height:38px;background:rgba(255,255,255,.85);
        border-radius:50%;display:flex;align-items:center;justify-content:center;
        font-size:18px;color:var(--txt);text-decoration:none;
        backdrop-filter:blur(4px);box-shadow:var(--shadow);
    }
    .ev-cover-actions {
        position:absolute;top:12px;right:12px;
        display:flex;gap:8px;
    }
    .ev-cover-btn {
        width:38px;height:38px;background:rgba(255,255,255,.85);
        border-radius:50%;border:none;display:flex;align-items:center;justify-content:center;
        font-size:17px;color:var(--txt);cursor:pointer;
        backdrop-filter:blur(4px);box-shadow:var(--shadow);
        text-decoration:none;
    }
    .ev-cover-title-wrap {
        position:absolute;bottom:14px;left:16px;right:16px;
    }
    .ev-cover-name {
        font-size:20px;font-weight:800;color:#fff;
        text-shadow:0 2px 8px rgba(0,0,0,.5);
        line-height:1.2;margin-bottom:6px;
    }
    .ev-cover-meta { display:flex;gap:6px;flex-wrap:wrap; }
    .ev-cover-chip {
        display:inline-flex;align-items:center;gap:4px;
        background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.3);
        border-radius:20px;padding:3px 9px;
        font-size:11px;font-weight:600;color:#fff;
    }

    /* ── Tabs ── */
    .ev-tabs {
        display:flex;background:#fff;
        border-bottom:1px solid var(--border-lt);
        position:sticky;top:0;z-index:50;
    }
    .ev-tab {
        flex:1;padding:12px 4px;
        text-align:center;font-size:12px;font-weight:700;
        color:var(--txt-3);border:none;background:none;
        border-bottom:3px solid transparent;cursor:pointer;
        transition:color .2s,border-color .2s;
        white-space:nowrap;
    }
    .ev-tab.active { color:var(--p);border-bottom-color:var(--p); }

    /* ── Info grid ── */
    .info-grid-2 {
        display:grid;grid-template-columns:1fr 1fr;
        gap:10px;padding:14px 16px;
        background:#fff;border-bottom:1px solid var(--border-lt);
    }
    .ig-box {
        background:var(--p-xl);border-radius:var(--r);
        border:1.5px solid var(--p-lt);padding:12px;
        text-align:center;
    }
    .ig-box .lbl { font-size:10px;font-weight:700;color:var(--p);text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px; }
    .ig-box .val { font-size:15px;font-weight:800;color:var(--p-dk);line-height:1.2; }
    .ig-box .sub { font-size:10px;color:var(--txt-3);margin-top:2px; }

    /* ── Seção de conteúdo ── */
    .ev-sec {
        background:#fff;padding:16px;
        border-bottom:1px solid var(--border-lt);
    }
    .ev-sec-title {
        font-size:11px;font-weight:700;color:var(--p);
        text-transform:uppercase;letter-spacing:.5px;
        margin-bottom:10px;display:flex;align-items:center;gap:6px;
    }
    .ev-sec-title::after { content:'';flex:1;height:1px;background:var(--p-lt); }
    .ev-sec p { font-size:14px;line-height:1.7;color:var(--txt-2);margin:0;white-space:pre-line; }

    /* ── Organizador ── */
    .org-row {
        display:flex;align-items:center;gap:12px;
        padding:14px 16px;background:#fff;
        text-decoration:none;color:inherit;
        border-bottom:1px solid var(--border-lt);
        transition:background .15s;
    }
    .org-row:hover { background:var(--bg); }
    .org-row img { width:48px;height:48px;border-radius:50%;object-fit:cover;background:var(--p-lt);border:2px solid var(--p-lt); }

    /* ── Participantes ── */
    .att-row {
        display:flex;align-items:center;gap:12px;
        padding:11px 16px;border-bottom:1px solid var(--border-lt);
        background:#fff;
    }
    .att-avatar { width:38px;height:38px;border-radius:50%;background:var(--p-lt);display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:var(--p);flex-shrink:0; }
    .att-name { font-size:13px;font-weight:600;color:var(--txt);flex:1; }

    /* ── Regras ── */
    .rule-item {
        display:flex;align-items:flex-start;gap:10px;
        padding:10px 16px;border-bottom:1px solid var(--border-lt);
        background:#fff;font-size:13px;color:var(--txt-2);
    }
    .rule-item i { color:var(--p);font-size:15px;margin-top:1px;flex-shrink:0; }

    /* ── Mapa mini ── */
    .ev-map-mini {
        height:160px;border-radius:var(--r);overflow:hidden;
        background:var(--bg);margin-bottom:10px;
        display:flex;align-items:center;justify-content:center;
    }
    .ev-map-mini iframe { width:100%;height:100%;border:none; }

    /* ── Action bar ── */
    .action-bar {
        position:sticky;bottom:var(--bb-h);left:0;right:0;
        background:#fff;border-top:1px solid var(--border);
        padding:10px 16px;display:flex;gap:8px;z-index:40;
        box-shadow:0 -4px 16px rgba(0,0,0,.06);
    }
    .btn-inscricao {
        flex:1;height:50px;font-size:15px;font-weight:700;
        border-radius:var(--r-sm);border:none;
        background:var(--p);color:#fff;cursor:pointer;
        display:flex;align-items:center;justify-content:center;gap:8px;
        transition:background .15s,transform .1s;
        text-decoration:none;
    }
    .btn-inscricao:hover { background:var(--p-hov);color:#fff; }
    .btn-inscricao:active { transform:scale(.98); }
    .btn-inscricao:disabled { background:var(--txt-4);cursor:not-allowed; }
    .btn-share-bar {
        width:50px;height:50px;border-radius:var(--r-sm);
        border:1.5px solid var(--border);background:#fff;
        color:var(--txt-2);font-size:20px;
        display:flex;align-items:center;justify-content:center;
        cursor:pointer;flex-shrink:0;
        transition:border-color .15s,color .15s;
    }
    .btn-share-bar:hover { border-color:var(--p);color:var(--p); }
    /* ── Intent buttons ── */
    .intent-bar {
        display:flex;gap:6px;flex:1;
    }
    .btn-intent {
        flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;
        gap:2px;height:54px;border-radius:var(--r-sm);border:2px solid var(--border);
        background:#fff;cursor:pointer;font-size:11px;font-weight:700;color:var(--txt-3);
        transition:all .15s;padding:0;
    }
    .btn-intent .emoji { font-size:20px;line-height:1; }
    .btn-intent:active { transform:scale(.96); }
    .btn-intent.going  { border-color:#16a34a;background:#dcfce7;color:#166534; }
    .btn-intent.maybe  { border-color:#d97706;background:#fef3c7;color:#92400e; }
    .btn-intent.notgoing { border-color:#dc2626;background:#fee2e2;color:#991b1b; }
    /* ── Check-in button ── */
    .btn-checkin {
        flex:1;height:54px;font-size:14px;font-weight:700;
        border-radius:var(--r-sm);border:none;
        background:#166534;color:#fff;cursor:pointer;
        display:flex;align-items:center;justify-content:center;gap:8px;
    }
</style>
@endpush

@section('content')

{{-- Capa --}}
<div class="ev-cover-wrap">
    <img src="{{ $event->banner_image_url }}" alt="{{ $event->name }}" class="ev-cover"
         onerror="this.src='https://placehold.co/400x220/dcfce7/166534?text=Evento'">
    <div class="ev-cover-overlay"></div>
    <a href="{{ url()->previous() }}" class="ev-cover-back"><i class="bi bi-arrow-left"></i></a>
    <div class="ev-cover-actions">
        <button class="ev-cover-btn" id="btnShareTop"><i class="bi bi-share"></i></button>
        <a href="{{ route('events.my-list') }}" class="ev-cover-btn"><i class="bi bi-bookmark"></i></a>
    </div>
    <div class="ev-cover-title-wrap">
        <div class="ev-cover-name">{{ $event->name }}</div>
        <div class="ev-cover-meta">
            @php
                $sStyle=['open'=>'background:rgba(22,163,74,.8);','full'=>'background:rgba(234,179,8,.8);','cancelled'=>'background:rgba(220,38,38,.8);','finished'=>'background:rgba(107,114,128,.8);'];
                $sLabel=['open'=>'✅ Aberto','full'=>'⚠ Lotado','cancelled'=>'❌ Cancelado','finished'=>'Encerrado'];
            @endphp
            <span class="ev-cover-chip" style="{{ $sStyle[$event->status] ?? '' }}">
                {{ $sLabel[$event->status] ?? $event->status }}
            </span>
            <span class="ev-cover-chip">
                <i class="bi bi-calendar3"></i>{{ $event->starts_at->translatedFormat('d \d\e M') }}
            </span>
            <span class="ev-cover-chip">
                <i class="bi bi-clock"></i>{{ $event->starts_at->format('H:i') }}
            </span>
        </div>
    </div>
</div>

{{-- Tabs --}}
<div class="ev-tabs">
    <button class="ev-tab active" data-p="sobre"><i class="bi bi-info-circle me-1"></i>Sobre</button>
    <button class="ev-tab" data-p="localizacao"><i class="bi bi-geo-alt me-1"></i>Local</button>
    <button class="ev-tab" data-p="participantes"><i class="bi bi-people me-1"></i>Inscritos</button>
    <button class="ev-tab" data-p="regras"><i class="bi bi-shield-check me-1"></i>Regras</button>
</div>

{{-- ─── ABA: SOBRE ─── --}}
<div id="p-sobre">

    {{-- Grid info --}}
    <div class="info-grid-2">
        <div class="ig-box">
            <div class="lbl"><i class="bi bi-calendar3 me-1"></i>Data</div>
            <div class="val">{{ $event->starts_at->format('d/m') }}</div>
            <div class="sub">{{ $event->starts_at->translatedFormat('l') }}</div>
        </div>
        <div class="ig-box">
            <div class="lbl"><i class="bi bi-ticket me-1"></i>Entrada</div>
            <div class="val">{{ $event->price > 0 ? 'R$ '.number_format($event->price,2,',','.') : 'Grátis' }}</div>
            @if($event->capacity)<div class="sub">{{ $event->capacity }} vagas</div>@endif
        </div>
        <div class="ig-box">
            <div class="lbl"><i class="bi bi-clock me-1"></i>Horário</div>
            <div class="val">{{ $event->starts_at->format('H:i') }}</div>
            @if($event->ends_at)<div class="sub">até {{ $event->ends_at->format('H:i') }}</div>@endif
        </div>
        <div class="ig-box">
            <div class="lbl"><i class="bi bi-people me-1"></i>Vagas</div>
            @php $cnt = $event->attendees->count(); @endphp
            <div class="val">{{ $cnt }}{{ $event->capacity ? '/'.$event->capacity : '' }}</div>
            <div class="sub">inscritos</div>
        </div>
    </div>

    {{-- Descrição --}}
    @if ($event->description)
    <div class="ev-sec">
        <div class="ev-sec-title"><i class="bi bi-file-text"></i>Sobre o Evento</div>
        <p>{{ $event->description }}</p>
    </div>
    @endif

    {{-- O que levar --}}
    @if ($event->recommendations)
    <div class="ev-sec">
        <div class="ev-sec-title"><i class="bi bi-bag-heart"></i>Recomendações</div>
        <p>{{ $event->recommendations }}</p>
    </div>
    @endif

    {{-- Organizador --}}
    @if ($event->house)
    <a href="{{ route('houses.show', $event->house->id) }}" class="org-row">
        <img src="{{ $event->house->logo_image_url }}" alt="{{ $event->house->name }}"
             onerror="this.src='https://placehold.co/48x48/dcfce7/166534?text=A'">
        <div style="flex:1;min-width:0;">
            <div style="font-size:10px;color:var(--txt-3);font-weight:600;text-transform:uppercase;letter-spacing:.4px;">Organizado por</div>
            <div style="font-size:14px;font-weight:700;color:var(--txt);">{{ $event->house->name }}</div>
            <div style="font-size:11px;color:var(--txt-3);">{{ $event->house->city }}{{ $event->house->state ? '/'.$event->house->state : '' }}</div>
        </div>
        <i class="bi bi-chevron-right" style="font-size:13px;color:var(--txt-4);"></i>
    </a>
    @endif
</div>

{{-- ─── ABA: LOCALIZAÇÃO ─── --}}
<div id="p-localizacao" style="display:none;">
    <div class="ev-sec">
        <div class="ev-sec-title"><i class="bi bi-geo-alt"></i>Local do Evento</div>
        @if ($event->address)
            <div style="display:flex;align-items:flex-start;gap:10px;margin-bottom:14px;">
                <i class="bi bi-pin-map-fill" style="font-size:20px;color:var(--p);flex-shrink:0;"></i>
                <div>
                    <div style="font-size:14px;font-weight:600;color:var(--txt);">{{ $event->address }}</div>
                    @if($event->house)<div style="font-size:12px;color:var(--txt-3);">{{ $event->house->name }}</div>@endif
                </div>
            </div>
        @endif
        @if ($event->latitude && $event->longitude)
        <div class="ev-map-mini">
            <iframe
                src="https://www.openstreetmap.org/export/embed.html?bbox={{ $event->longitude-0.005 }},{{ $event->latitude-0.005 }},{{ $event->longitude+0.005 }},{{ $event->latitude+0.005 }}&layer=mapnik&marker={{ $event->latitude }},{{ $event->longitude }}"
                loading="lazy"
            ></iframe>
        </div>
        @elseif($event->house && $event->house->latitude)
        <div class="ev-map-mini">
            <iframe
                src="https://www.openstreetmap.org/export/embed.html?bbox={{ $event->house->longitude-0.005 }},{{ $event->house->latitude-0.005 }},{{ $event->house->longitude+0.005 }},{{ $event->house->latitude+0.005 }}&layer=mapnik&marker={{ $event->house->latitude }},{{ $event->house->longitude }}"
                loading="lazy"
            ></iframe>
        </div>
        @else
        <div style="background:var(--bg);border-radius:var(--r);height:120px;display:flex;align-items:center;justify-content:center;color:var(--txt-4);">
            <div class="text-center"><i class="bi bi-map" style="font-size:32px;display:block;margin-bottom:8px;opacity:.4;"></i>Localização não disponível</div>
        </div>
        @endif
    </div>
</div>

{{-- ─── ABA: PARTICIPANTES ─── --}}
<div id="p-participantes" style="display:none;">
    <div style="padding:12px 16px;background:#fff;border-bottom:1px solid var(--border-lt);">
        <div style="font-size:13px;color:var(--txt-3);">
            <strong style="color:var(--p);font-size:20px;font-weight:800;">{{ $cnt }}</strong>
            pessoa(s) inscrita(s)
            @if($event->capacity)
                de <strong>{{ $event->capacity }}</strong> vagas
            @endif
        </div>
        @if($event->capacity)
        <div style="height:6px;background:var(--bg);border-radius:6px;margin-top:8px;overflow:hidden;">
            @php $pct = $cnt > 0 ? min(100, round(($cnt / $event->capacity) * 100)) : 0; @endphp
            <div style="height:100%;width:{{ $pct }}%;background:var(--p);border-radius:6px;transition:width .4s;"></div>
        </div>
        @endif
    </div>
    @forelse ($event->attendees->take(30) as $att)
    <div class="att-row">
        <div class="att-avatar">{{ strtoupper(substr($att->name,0,1)) }}</div>
        <div class="att-name">{{ $att->name }}</div>
        @if ($att->pivot->status === 'checked_in')
            <span class="badge-cat" style="background:#dcfce7;color:#166534;">Check-in</span>
        @else
            <span class="badge-cat" style="background:#f3f4f6;color:#6b7280;">Inscrito</span>
        @endif
    </div>
    @empty
    <div class="empty-state">
        <i class="bi bi-people"></i>
        <p>Nenhum inscrito ainda.<br>Seja o primeiro!</p>
    </div>
    @endforelse
</div>

{{-- ─── ABA: REGRAS ─── --}}
<div id="p-regras" style="display:none;">
    @if ($event->rules)
    <div class="ev-sec">
        <div class="ev-sec-title"><i class="bi bi-shield-check"></i>Regras do Evento</div>
        @foreach (array_filter(explode("\n", $event->rules)) as $rule)
        <div class="rule-item">
            <i class="bi bi-check2-circle"></i>
            <span>{{ trim($rule) }}</span>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state" style="padding:40px 24px;">
        <i class="bi bi-shield-check"></i>
        <p>Nenhuma regra específica cadastrada.</p>
    </div>
    @endif
</div>

<div style="height:80px;"></div>

{{-- Action bar --}}
@php $isSubscribed = $event->attendees->contains('id', Auth::id()); @endphp
<div class="action-bar">
    <button class="btn-share-bar" id="btnShare"><i class="bi bi-share"></i></button>

    @if (in_array($event->status, ['cancelled','finished']))
        <button class="btn-inscricao" disabled style="background:var(--txt-4);">
            <i class="bi bi-slash-circle"></i>{{ $event->status === 'cancelled' ? 'Cancelado' : 'Encerrado' }}
        </button>
    @elseif ($isCheckedIn)
        {{-- Já fez check-in --}}
        <div class="intent-bar">
            <button class="btn-intent going" disabled style="flex:2;">
                <span class="emoji">✅</span><span>Presença confirmada</span>
            </button>
        </div>
    @elseif ($isToday && $isSubscribed)
        {{-- Dia do evento: mostrar check-in + opção de cancelar --}}
        <div class="intent-bar">
            <form method="POST" action="{{ route('events.checkin.self', $event->id) }}" style="flex:2;">
                @csrf
                <button type="submit" class="btn-checkin" style="width:100%;">
                    <i class="bi bi-qr-code-scan"></i>Check-in — Cheguei!
                </button>
            </form>
            <button class="btn-intent notgoing" onclick="setIntent({{ $event->id }}, 'not_going')" title="Não vou">
                <span class="emoji">❌</span><span>Não vou</span>
            </button>
        </div>
    @else
        {{-- Botões de intenção --}}
        <div class="intent-bar">
            <button class="btn-intent {{ $userIntent === 'going' ? 'going' : '' }}"
                    onclick="setIntent({{ $event->id }}, '{{ $userIntent === 'going' ? 'not_going' : 'going' }}')"
                    {{ $event->status === 'full' && !$isSubscribed ? 'disabled' : '' }}>
                <span class="emoji">✅</span><span>Vou</span>
            </button>
            <button class="btn-intent {{ $userIntent === 'maybe' ? 'maybe' : '' }}"
                    onclick="setIntent({{ $event->id }}, '{{ $userIntent === 'maybe' ? 'not_going' : 'maybe' }}')"
                    {{ $event->status === 'full' && !$isSubscribed ? 'disabled' : '' }}>
                <span class="emoji">🤔</span><span>Talvez</span>
            </button>
            <button class="btn-intent {{ !$isSubscribed ? 'notgoing' : '' }}"
                    onclick="setIntent({{ $event->id }}, 'not_going')">
                <span class="emoji">❌</span><span>Não vou</span>
            </button>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
$(function () {
    // Tabs
    $('.ev-tab').on('click', function () {
        $('.ev-tab').removeClass('active');
        $(this).addClass('active');
        $('[id^="p-"]').hide();
        $('#p-' + $(this).data('p')).show();
    });

    // Share
    function doShare() {
        if (navigator.share) {
            navigator.share({ title: '{{ addslashes($event->name) }}', url: window.location.href });
        } else {
            navigator.clipboard.writeText(window.location.href);
            Swal.fire({ icon:'success', title:'Link copiado!', timer:1500, showConfirmButton:false, toast:true, position:'top-end' });
        }
    }
    $('#btnShare, #btnShareTop').on('click', doShare);
});

function setIntent(eventId, intent) {
    var labels = { going: '✅ Vou', maybe: '🤔 Talvez', not_going: '❌ Não vou' };
    var colors = { going: '#16a34a', maybe: '#d97706', not_going: '#dc2626' };
    Swal.fire({
        icon: intent === 'not_going' ? 'warning' : 'question',
        title: intent === 'not_going' ? 'Confirmar ausência?' : labels[intent],
        text: intent === 'going'    ? 'Você será inscrito neste evento.' :
              intent === 'maybe'    ? 'Você será inscrito como "Talvez".' :
                                      'Sua inscrição será cancelada.',
        showCancelButton: true,
        confirmButtonColor: colors[intent],
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Voltar',
    }).then(function(res) {
        if (!res.isConfirmed) return;
        $.ajax({
            url: '/events/' + eventId + '/intent',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', intent: intent },
            success: function(r) {
                Swal.fire({ icon:'success', title:r.message, timer:1500, showConfirmButton:false, toast:true, position:'top-end' });
                setTimeout(function(){ location.reload(); }, 1500);
            },
            error: function(xhr) {
                Swal.fire({ icon:'error', title:'Erro', text:xhr.responseJSON?.message ?? 'Tente novamente.', confirmButtonColor:'#16a34a' });
            }
        });
    });
}
</script>
@endpush
