@extends('layouts.app')
@section('title', 'Minha Casa — Aruanda Digital')

@push('styles')
<style>
    /* ── Hero ── */
    .mh-hero { background:linear-gradient(135deg, var(--p) 0%, var(--p-dk) 100%); padding:16px; color:#fff; }
    .mh-hero-top { display:flex;align-items:center;gap:12px; }
    .mh-logo { width:54px;height:54px;border-radius:14px;object-fit:cover;background:var(--p-lt);border:2.5px solid rgba(255,255,255,.4);flex-shrink:0; }
    .mh-name { font-size:16px;font-weight:800;margin:0 0 2px; }
    .mh-sub  { font-size:12px;opacity:.8; }

    /* ── Tabs ── */
    .mh-tabs { display:grid;grid-template-columns:repeat(3,1fr);gap:8px;padding:12px;background:#f8f9fa;border-bottom:1px solid var(--border-lt);position:sticky;top:0;z-index:50; }
    .mh-tab { display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;padding:10px 4px;background:#fff;border:1.5px solid #e5e7eb;border-radius:12px;font-size:11px;font-weight:700;color:var(--txt-3);cursor:pointer;transition:all .2s;text-decoration:none; }
    .mh-tab i { font-size:20px;line-height:1; }
    .mh-tab.active { background:var(--p);border-color:var(--p);color:#fff;box-shadow:0 4px 12px rgba(22,163,74,.3); }
    .mh-tab:not(.active):hover { border-color:var(--p);color:var(--p); }
    .mh-tab-ext { background:var(--p-xl);border-color:var(--p-lt);color:var(--p-dk); }

    /* ── Stat cards ── */
    .stat-grid { display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:14px; }
    .stat-card { background:#fff;border-radius:var(--r);padding:16px 12px;text-align:center;box-shadow:var(--shadow-sm); }
    .stat-card .num { font-size:28px;font-weight:800;color:var(--p);line-height:1; }
    .stat-card .lbl { font-size:11px;color:var(--txt-3);margin-top:4px;font-weight:600; }
    .stat-card .sub { font-size:10px;color:var(--p);margin-top:2px; }

    /* ── Activity feed ── */
    .activity-row { display:flex;align-items:flex-start;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border-lt);background:#fff; }
    .activity-icon { width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0; }
    .activity-text { flex:1;min-width:0; }
    .activity-main { font-size:13px;font-weight:600;color:var(--txt);line-height:1.4; }
    .activity-time { font-size:11px;color:var(--txt-4);margin-top:2px; }

    /* ── Membro ── */
    .member-row { display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border-lt);background:#fff; }
    .member-avatar { width:44px;height:44px;border-radius:50%;object-fit:cover;background:var(--p-lt);flex-shrink:0;border:2px solid var(--p-lt); }
    .member-name { font-size:14px;font-weight:700;color:var(--txt);margin-bottom:3px; }

    /* ── Tarefa ── */
    .task-row { padding:12px 16px;border-bottom:1px solid var(--border-lt);background:#fff;display:flex;align-items:flex-start;gap:12px; }
    .task-icon { width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0; }
    .ti-pending    { background:#f3f4f6;color:#6b7280; }
    .ti-progress   { background:#fef3c7;color:#d97706; }
    .ti-completed  { background:#dbeafe;color:#1d4ed8; }
    .ti-approved   { background:#dcfce7;color:#16a34a; }
    .task-title { font-size:14px;font-weight:700;color:var(--txt);margin-bottom:4px; }
    .task-meta  { display:flex;align-items:center;gap:5px;flex-wrap:wrap; }
    .task-actions { display:flex;gap:4px;margin-top:6px;flex-wrap:wrap; }

    /* ── Evento mini ── */
    .event-mini { display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border-lt);background:#fff;text-decoration:none;color:inherit;transition:background .15s; }
    .event-mini:hover { background:#fafafa; }
    .event-mini-date { width:44px;flex-shrink:0;text-align:center;background:var(--p-xl);border-radius:var(--r-sm);border:1.5px solid var(--p-lt);padding:6px 4px; }
    .event-mini-date .d { font-size:19px;font-weight:800;color:var(--p-dk);line-height:1; }
    .event-mini-date .m { font-size:9px;font-weight:700;color:var(--p);text-transform:uppercase; }
    .event-mini img { width:48px;height:48px;border-radius:var(--r-sm);object-fit:cover;background:var(--p-lt); }
    .event-mini-name { font-size:13px;font-weight:700;margin-bottom:2px;color:var(--txt); }
    .event-mini-info { font-size:11px;color:var(--txt-3); }

    /* ── Financeiro accordion ── */
    .finance-row { border-bottom:1px solid var(--border-lt);background:#fff; }
    .finance-header { display:flex;align-items:center;gap:10px;padding:12px 16px;cursor:pointer;user-select:none; }
    .finance-header:active { background:var(--p-xl); }
    .finance-icon { width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0; }
    .fi-credit { background:#dcfce7;color:#16a34a; }
    .fi-debit  { background:#fee2e2;color:#dc2626; }
    .finance-desc { font-size:13px;color:var(--txt-2);font-weight:600; }
    .finance-date { font-size:11px;color:var(--txt-3);margin-top:2px;display:flex;align-items:center;gap:5px;flex-wrap:wrap; }
    .finance-amt  { font-size:16px;font-weight:800;flex-shrink:0; }
    .amt-credit   { color:#16a34a; }
    .amt-debit    { color:#dc2626; }
    .finance-body { display:none;padding:0 16px 14px;border-top:1px solid var(--border-lt);background:#fafafa; }
    .finance-row.open .finance-body { display:block; }
    .finance-row.open .finance-toggle-icon { transform:rotate(180deg); }
    .finance-toggle-icon { transition:transform .2s;font-size:12px;color:var(--txt-4);flex-shrink:0; }

    /* ── Tarefas blocos accordion ── */
    .task-block { background:#fff;border-radius:var(--r);margin:10px 12px;box-shadow:var(--shadow-sm);overflow:hidden; }
    .task-block-header { padding:12px 14px;display:flex;align-items:center;gap:10px; }
    .task-block-header:active { background:var(--p-xl); }
    .task-block.open .task-block-body { display:block !important; }
    .task-block.open .task-block-header > i.bi-chevron-down { transform:rotate(180deg); }
    .task-block-header > i.bi-chevron-down { transition:transform .2s; }
    .task-member-row   { display:flex;align-items:center;gap:10px;padding:8px 14px;border-bottom:1px solid var(--border-lt); }
    .task-member-row:last-child { border-bottom:none; }

    /* Pending banner */
    .pending-banner { background:#fef9c3;border-bottom:1px solid #fde68a;padding:10px 16px; }
    .pending-banner-title { font-size:12px;font-weight:700;color:#92400e;margin-bottom:8px; }
    .pending-item { background:#fff;border-radius:var(--r);padding:10px 12px;margin-bottom:6px;display:flex;align-items:center;gap:10px;box-shadow:var(--shadow-sm); }

    /* ── Fix iOS Modal ── */
    .modal { -webkit-overflow-scrolling: touch; }
    .modal-body { overflow-y: auto; -webkit-overflow-scrolling: touch; }
    .modal-dialog { margin: 12px auto; }

    /* Status badges */
    .st-pending   { background:#f3f4f6;color:#374151; }
    .st-in_progress { background:#fef9c3;color:#854d0e; }
    .st-completed { background:#dbeafe;color:#1d4ed8; }
    .st-approved  { background:#dcfce7;color:#166534; }
    .st-cancelled { background:#fee2e2;color:#991b1b; }
    .fin-pending  { background:#fef9c3;color:#854d0e; }
    .fin-paid     { background:#dcfce7;color:#166534; }
    .fin-overdue  { background:#fee2e2;color:#991b1b; }

    /* ── Lembrete de gira ── */
    .gira-reminder {
        background:linear-gradient(135deg,#166534,#16a34a);
        padding:12px 16px;display:flex;align-items:center;gap:12px;
        border-bottom:2px solid rgba(255,255,255,.2);
    }
    .gira-reminder-icon {
        width:42px;height:42px;background:rgba(255,255,255,.2);
        border-radius:12px;display:flex;align-items:center;justify-content:center;
        font-size:22px;flex-shrink:0;
    }
    .gira-reminder-title { font-size:13px;font-weight:800;color:#fff;margin-bottom:2px; }
    .gira-reminder-sub   { font-size:11px;color:rgba(255,255,255,.8); }
    .gira-reminder-badge {
        margin-left:auto;padding:4px 10px;border-radius:20px;font-size:11px;font-weight:700;
        white-space:nowrap;flex-shrink:0;
    }
    .badge-confirmed { background:#dcfce7;color:#166534; }
    .badge-pending   { background:rgba(255,255,255,.25);color:#fff;border:1px solid rgba(255,255,255,.4); }

    /* ── Stats de frequência ── */
    .freq-bar {
        display:flex;gap:6px;padding:8px 16px 4px;
        background:#fff;border-bottom:1px solid var(--border-lt);
        flex-wrap:wrap;
    }
    .freq-chip {
        display:inline-flex;align-items:center;gap:4px;
        padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;
    }
    .fc-going    { background:#dcfce7;color:#166534; }
    .fc-maybe    { background:#fef3c7;color:#92400e; }
    .fc-noreply  { background:#f3f4f6;color:#6b7280; }
    .fc-checkin  { background:#dbeafe;color:#1d4ed8; }
</style>
@endpush

@section('content')

@if (!$house)
<div class="empty-state" style="padding:80px 24px;">
    <i class="bi bi-house-x" style="font-size:60px;opacity:.3;display:block;margin-bottom:14px;"></i>
    <p style="font-size:15px;font-weight:600;color:var(--txt-2);">Você não está associado a nenhuma casa ainda.</p>
    <a href="{{ route('houses') }}" class="btn btn-primary mt-3" style="border-radius:20px;">
        <i class="bi bi-compass me-1"></i>Explorar Casas
    </a>
</div>
@else

{{-- Hero --}}
<div class="mh-hero">
    <div class="mh-hero-top">
        <img src="{{ $house->logo_image_url }}" class="mh-logo" alt="{{ $house->name }}"
             onerror="this.src='https://placehold.co/54x54/dcfce7/166534?text=A'">
        <div style="flex:1;min-width:0;">
            <p class="mh-name">{{ $house->name }}</p>
            <span class="mh-sub">{{ ucfirst($house->type ?? '') }} · {{ $house->city }}{{ $house->state ? '/'.$house->state : '' }}</span>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            @if($user->hasRole('dirigente,admin'))
            <a href="{{ route('houses.edit', $house->id) }}" style="color:rgba(255,255,255,.8);font-size:20px;text-decoration:none;" title="Editar página pública">
                <i class="bi bi-pencil-square"></i>
            </a>
            @endif
            <a href="{{ route('notifications') }}" style="color:rgba(255,255,255,.8);font-size:20px;text-decoration:none;">
                <i class="bi bi-bell"></i>
            </a>
        </div>
    </div>
</div>

{{-- Lembrete automático de gira (se tiver evento hoje) --}}
@foreach($todayEvents as $tevt)
@php
    $tevtStatus = $todayUserIntents->get($tevt->id);
    $tevtConfirmed = $tevtStatus === 'checked_in';
    $tevtGoing     = $tevtStatus === 'registered';
@endphp
<a href="{{ route('events.show', $tevt->id) }}" class="gira-reminder" style="text-decoration:none;">
    <div class="gira-reminder-icon">🪘</div>
    <div>
        <div class="gira-reminder-title">Hoje tem {{ $tevt->name }}!</div>
        <div class="gira-reminder-sub">
            <i class="bi bi-clock me-1"></i>{{ $tevt->starts_at->format('H:i') }}
            @if($tevt->address) · {{ mb_strlen($tevt->address) > 30 ? mb_substr($tevt->address, 0, 30).'…' : $tevt->address }}@endif
        </div>
    </div>
    @if($tevtConfirmed)
        <span class="gira-reminder-badge badge-confirmed">✅ Check-in feito</span>
    @elseif($tevtGoing)
        <span class="gira-reminder-badge badge-pending">🔔 Você vai!</span>
    @else
        <span class="gira-reminder-badge badge-pending">Confirmar?</span>
    @endif
</a>
@endforeach

{{-- Tabs — grid 3×2 --}}
<nav class="mh-tabs">
    <button class="mh-tab {{ $tab === 'visao-geral' ? 'active' : '' }}" onclick="switchTab('visao-geral')">
        <i class="bi bi-grid-fill"></i>Visão Geral
    </button>
    <button class="mh-tab {{ $tab === 'membros' ? 'active' : '' }}" onclick="switchTab('membros')">
        <i class="bi bi-people-fill"></i>Membros
    </button>
    <button class="mh-tab {{ $tab === 'tarefas' ? 'active' : '' }}" onclick="switchTab('tarefas')">
        <i class="bi bi-check2-square"></i>Tarefas
    </button>
    <button class="mh-tab {{ $tab === 'eventos' ? 'active' : '' }}" onclick="switchTab('eventos')">
        <i class="bi bi-calendar-event-fill"></i>Eventos
    </button>
    <button class="mh-tab {{ $tab === 'estudos' ? 'active' : '' }}" onclick="switchTab('estudos')">
        <i class="bi bi-book-fill"></i>Estudos
    </button>

    {{-- 6ª célula: financeiro para gestores / check-in para membro --}}
    @if($isManager)
    <button class="mh-tab {{ $tab === 'financeiro' ? 'active' : '' }}" onclick="switchTab('financeiro')">
        <i class="bi bi-cash-stack"></i>Financeiro
    </button>
    @elseif($user->hasRole('moderador'))
    <a href="{{ url('/admin') }}" class="mh-tab mh-tab-ext">
        <i class="bi bi-shield-check"></i>Admin
    </a>
    @else
    <button class="mh-tab" onclick="openCheckinConfirm()" style="color:#16a34a;">
        <i class="bi bi-qr-code-scan"></i>Check-in
    </button>
    @endif
</nav>

{{-- ══════════════════ VISÃO GERAL ══════════════════ --}}
<div id="tab-visao-geral" style="{{ $tab !== 'visao-geral' ? 'display:none;' : '' }}">
    @php $bal = ($balanceCredit ?? 0) - ($balanceDebit ?? 0); @endphp
    <div class="stat-grid">
        <div class="stat-card">
            <div class="num">{{ $house->activeMembers->count() }}</div>
            <div class="lbl">Membros Ativos</div>
            @php $pend = $members->where('pivot.status','pending')->count(); @endphp
            @if($pend > 0)<div class="sub">+{{ $pend }} pendente(s)</div>@endif
        </div>
        <div class="stat-card">
            <div class="num">{{ $house->upcomingEvents->count() }}</div>
            <div class="lbl">Próximos Eventos</div>
        </div>
        <div class="stat-card">
            <div class="num">{{ $tasks->whereIn('status',['pending','in_progress'])->count() }}</div>
            <div class="lbl">Tarefas Abertas</div>
        </div>
        @if($isManager)
        <div class="stat-card">
            @if($avgFrequency !== null)
            <div class="num" style="color:{{ $avgFrequency >= 70 ? 'var(--p)' : ($avgFrequency >= 40 ? '#d97706' : '#dc2626') }};">{{ $avgFrequency }}%</div>
            <div class="lbl">Freq. Média</div>
            @if($inactiveCount > 0)<div class="sub" style="color:#dc2626;">{{ $inactiveCount }} inativos</div>@endif
            @else
            <div class="num" style="font-size:20px;color:{{ $bal >= 0 ? 'var(--p)' : '#dc2626' }};">
                R$ {{ number_format(abs($bal),0,',','.') }}
            </div>
            <div class="lbl">Saldo</div>
            @endif
        </div>
        @endif
    </div>

    @if($isManager)
    @php $nextEvent = $house->upcomingEvents->first(); @endphp
    @if($nextEvent && isset($eventIntentStats[$nextEvent->id]))
    @php $ns = $eventIntentStats[$nextEvent->id]; $totalM = $house->activeMembers->count(); @endphp
    <div style="padding:10px 14px;background:#fff;border-bottom:1px solid var(--border-lt);">
        <div style="font-size:10px;font-weight:700;color:var(--p);text-transform:uppercase;letter-spacing:.4px;margin-bottom:8px;">
            <i class="bi bi-calendar-event me-1"></i>Próxima Gira — {{ $nextEvent->name }}
            <span style="font-weight:500;color:var(--txt-3);font-size:10px;margin-left:6px;">{{ $nextEvent->starts_at->translatedFormat('d/m \à\s H:i') }}</span>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <span class="freq-chip fc-going" style="font-size:12px;">✅ {{ $ns['going'] }} vão</span>
            <span class="freq-chip fc-maybe" style="font-size:12px;">🤔 {{ $ns['maybe'] }} talvez</span>
            @php $noreply = max(0, $totalM - $ns['total']); @endphp
            <span class="freq-chip fc-noreply" style="font-size:12px;">{{ $noreply }} sem resposta</span>
            @if($ns['checked_in'] > 0)<span class="freq-chip fc-checkin" style="font-size:12px;">{{ $ns['checked_in'] }} check-in</span>@endif
        </div>
    </div>
    @endif
    @endif

    <div class="sec-label">
        <span>Atividades Recentes</span>
        <a href="{{ route('houses.show', $house->id) }}" style="font-size:12px;font-weight:600;color:var(--p);text-decoration:none;">Ver página pública</a>
    </div>

    @foreach($tasks->take(3) as $t)
    <div class="activity-row">
        <div class="activity-icon" style="background:var(--p-xl);color:var(--p);"><i class="bi bi-check2-square"></i></div>
        <div class="activity-text">
            <div class="activity-main">{{ $t->title }}</div>
            <div class="activity-time">
                @php $tsMap=['pending'=>'Pendente','in_progress'=>'Em andamento','completed'=>'Concluída','approved'=>'Aprovada']; @endphp
                {{ $tsMap[$t->status] ?? $t->status }}
                @if($t->due_date) · Vence {{ \Carbon\Carbon::parse($t->due_date)->format('d/m') }}@endif
                @if($t->assignedTo) · {{ $t->assignedTo->name }}@endif
            </div>
        </div>
    </div>
    @endforeach

    @foreach($house->upcomingEvents->take(2) as $ev)
    <div class="activity-row">
        <div class="activity-icon" style="background:#ede9fe;color:#5b21b6;"><i class="bi bi-calendar-event"></i></div>
        <div class="activity-text">
            <div class="activity-main">{{ $ev->name }}</div>
            <div class="activity-time">{{ $ev->starts_at->translatedFormat('d \d\e M \à\s H:i') }}</div>
        </div>
    </div>
    @endforeach

    @if($tasks->count() === 0 && $house->upcomingEvents->count() === 0)
    <div class="activity-row">
        <div class="activity-icon" style="background:var(--bg);color:var(--txt-4);"><i class="bi bi-bell-slash"></i></div>
        <div class="activity-text"><div class="activity-main" style="color:var(--txt-3);">Nenhuma atividade recente.</div></div>
    </div>
    @endif
</div>

{{-- ══════════════════ MEMBROS ══════════════════ --}}
<div id="tab-membros" style="{{ $tab !== 'membros' ? 'display:none;' : '' }}">

    {{-- Pendentes --}}
    @php $pending = $members->where('pivot.status', 'pending'); @endphp
    @if($pending->count() > 0 && $user->hasRole('dirigente,admin'))
    <div class="pending-banner">
        <div class="pending-banner-title">
            <i class="bi bi-clock-history me-1"></i>{{ $pending->count() }} solicitação(ões) pendente(s)
        </div>
        @foreach($pending as $m)
        <div class="pending-item" style="flex-wrap:wrap;gap:6px;">
            <img src="{{ $m->avatar_url }}" alt="{{ $m->name }}"
                 style="width:36px;height:36px;border-radius:50%;object-fit:cover;background:var(--p-lt);flex-shrink:0;"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->name) }}&background=dcfce7&color=166534&size=36'">
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;font-weight:700;">{{ $m->name }}</div>
                <div style="font-size:11px;color:var(--txt-3);">{{ $m->email }}</div>
                @if($m->pivot->role_membro)
                    <div style="font-size:11px;color:#1d4ed8;font-weight:600;">
                        <i class="bi bi-person-badge"></i> {{ ucfirst($m->pivot->role_membro) }}
                    </div>
                @endif
                @if($m->pivot->message)
                    <div style="font-size:11px;color:#374151;margin-top:3px;background:#f9fafb;border-radius:6px;padding:4px 8px;border-left:3px solid var(--p);">
                        "{{ $m->pivot->message }}"
                    </div>
                @endif
            </div>
            <div style="display:flex;gap:4px;flex-shrink:0;">
                <button type="button" class="btn btn-sm btn-success" style="font-size:11px;padding:4px 10px;border-radius:6px;"
                        onclick="openApproveModal({{ $m->id }}, '{{ addslashes($m->name) }}', '{{ addslashes($m->pivot->role_membro ?? '') }}', '{{ route('my-house.members.approve', $m->id) }}')">
                    <i class="bi bi-check2"></i> Aprovar
                </button>
                <form method="POST" action="{{ route('my-house.members.reject', $m->id) }}" style="margin:0;">
                    @csrf
                    <button type="button" class="btn btn-sm btn-outline-danger" style="font-size:11px;padding:4px 8px;border-radius:6px;"
                            onclick="confirmReject('{{ addslashes($m->name) }}', this.form)">
                        <i class="bi bi-x"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="sec-label">
        <span>{{ $members->where('pivot.status','active')->count() }} membro(s) ativo(s)</span>
        @if($user->hasRole('dirigente,admin'))
        <div style="display:flex;gap:8px;align-items:center;">
            <button onclick="Swal.fire({icon:'info',title:'Convites',text:'Compartilhe o link da casa para convidar membros.',confirmButtonColor:'#16a34a'})"
                    style="font-size:12px;font-weight:700;color:var(--p);background:none;border:none;cursor:pointer;padding:0;">
                <i class="bi bi-link-45deg"></i> Convidar
            </button>
            <button onclick="openNotifyModal('all', null, 'Todos os membros')"
                    style="font-size:12px;font-weight:700;color:#1e40af;background:#dbeafe;border:none;cursor:pointer;padding:3px 10px;border-radius:8px;">
                <i class="bi bi-megaphone me-1"></i>Avisar todos
            </button>
        </div>
        @endif
    </div>

    @php $hasActiveDirigente = $members->where('pivot.status','active')->where('pivot.role','dirigente')->count() > 0; @endphp
    @forelse($members->where('pivot.status','active') as $m)
    <div class="member-row">
        <img src="{{ $m->avatar_url }}" alt="{{ $m->name }}" class="member-avatar"
             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->name) }}&background=dcfce7&color=166534&size=44'">
        <div style="flex:1;min-width:0;">
            <div class="member-name">{{ $m->name }}</div>
            <div style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;">
                <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:6px;background:var(--p-xl);color:var(--p-dk);">
                    {{ ucfirst($m->pivot->role ?? 'membro') }}
                </span>
                @if($m->pivot->role_membro)
                <span style="font-size:10px;font-weight:600;padding:2px 8px;border-radius:6px;background:#ede9fe;color:#5b21b6;">
                    {{ ucfirst($m->pivot->role_membro) }}
                </span>
                @endif
                @if($m->pivot->entities)
                <span style="font-size:10px;font-weight:600;padding:2px 8px;border-radius:6px;background:#fef3c7;color:#92400e;">
                    <i class="bi bi-stars" style="font-size:9px;"></i> {{ $m->pivot->entities }}
                </span>
                @endif
            </div>
        </div>
        @if($user->hasRole('dirigente,admin'))
        <div style="display:flex;gap:4px;flex-shrink:0;align-items:center;">
            {{-- Enviar mensagem individual --}}
            <button type="button"
                    onclick="openNotifyModal('individual', {{ $m->id }}, '{{ addslashes($m->name) }}')"
                    style="font-size:16px;padding:4px 6px;color:#1e40af;background:none;border:none;cursor:pointer;"
                    title="Enviar mensagem">
                <i class="bi bi-chat-dots"></i>
            </button>
            {{-- Mudar cargo via SweetAlert --}}
            <button type="button"
                    onclick="changeMemberRole({{ $m->id }}, '{{ addslashes($m->name) }}', '{{ $m->pivot->role ?? 'membro' }}', '{{ addslashes($m->pivot->role_membro ?? '') }}', '{{ addslashes($m->pivot->entities ?? '') }}', '{{ route('my-house.members.role', $m->id) }}', {{ $hasActiveDirigente ? 'true' : 'false' }})"
                    style="font-size:11px;padding:3px 8px;border:1.5px solid var(--border);border-radius:6px;color:var(--txt-2);background:#fff;cursor:pointer;font-weight:600;">
                <i class="bi bi-person-gear me-1"></i>{{ ucfirst($m->pivot->role ?? 'membro') }}
            </button>
            {{-- Remover --}}
            <form method="POST" action="{{ route('my-house.members.reject', $m->id) }}">
                @csrf
                <button type="button" style="font-size:16px;padding:4px 6px;color:var(--txt-4);background:none;border:none;cursor:pointer;"
                        onclick="confirmReject('{{ addslashes($m->name) }}', this.form)">
                    <i class="bi bi-person-dash"></i>
                </button>
            </form>
        </div>
        @endif
    </div>
    @empty
    <div class="empty-state"><i class="bi bi-people"></i><p>Nenhum membro ativo.</p></div>
    @endforelse

    {{-- Transferências pendentes --}}
    @php $pendingTrans = $members->where('pivot.status', 'pending_transfer'); @endphp
    @if($pendingTrans->count() > 0 && $user->hasRole('dirigente,admin'))
    <div style="background:#fef3c7;border-left:4px solid #f59e0b;padding:10px 16px;margin-bottom:4px;">
        <div style="font-size:12px;font-weight:700;color:#92400e;margin-bottom:6px;">
            <i class="bi bi-hourglass-split me-1"></i>Transferência de dirigência pendente — aguardando aprovação do admin
        </div>
        @foreach($pendingTrans as $m)
        <div style="display:flex;align-items:center;gap:10px;font-size:13px;color:#78350f;">
            <img src="{{ $m->avatar_url }}" style="width:28px;height:28px;border-radius:50%;object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->name) }}&size=28'">
            <span><strong>{{ $m->name }}</strong> aguarda aprovação para se tornar dirigente</span>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- ══════════════════ TAREFAS ══════════════════ --}}
<div id="tab-tarefas" style="{{ $tab !== 'tarefas' ? 'display:none;' : '' }}">
    <div class="sec-label">
        <span>Tarefas <span id="task-count" style="font-size:11px;font-weight:600;color:var(--txt-3);"></span></span>
        <div style="display:flex;gap:8px;align-items:center;">
            @if($user->hasRole('assistente,dirigente,admin'))
            @php $unassigned = $tasks->where('status','pending')->whereNull('assigned_to')->count(); @endphp
            @if($unassigned > 0)
            <form method="POST" action="{{ route('my-house.tasks.randomize') }}">
                @csrf
                <button type="submit" title="Distribuir aleatoriamente"
                        style="font-size:11px;font-weight:700;color:var(--p);background:none;border:none;cursor:pointer;padding:2px 6px;border-radius:6px;border:1.5px solid var(--p-lt);">
                    <i class="bi bi-shuffle"></i> Randomizar
                </button>
            </form>
            @endif
            <button data-bs-toggle="modal" data-bs-target="#modalTarefa"
                    style="background:var(--p);color:#fff;width:28px;height:28px;border-radius:50%;border:none;display:flex;align-items:center;justify-content:center;font-size:18px;cursor:pointer;">
                <i class="bi bi-plus"></i>
            </button>
            @endif
        </div>
    </div>

    {{-- Filtros de status --}}
    <div style="display:flex;gap:6px;padding:8px 16px 4px;overflow-x:auto;scrollbar-width:none;background:#fff;border-bottom:1px solid var(--border-lt);">
        <button class="filter-chip active" id="tf-all"      onclick="filterTasks('')">Todas</button>
        <button class="filter-chip"        id="tf-pending"  onclick="filterTasks('pending')"><i class="bi bi-circle me-1"></i>Pendente</button>
        <button class="filter-chip"        id="tf-progress" onclick="filterTasks('in_progress')"><i class="bi bi-arrow-repeat me-1"></i>Em Andamento</button>
        <button class="filter-chip"        id="tf-done"     onclick="filterTasks('completed')"><i class="bi bi-check-circle me-1"></i>Concluída</button>
        <button class="filter-chip"        id="tf-approved" onclick="filterTasks('approved')"><i class="bi bi-check2-circle me-1"></i>Aprovada</button>
    </div>

    @php
        $tsLabel = ['pending'=>'Pendente','in_progress'=>'Em andamento','completed'=>'Concluída','approved'=>'Aprovada'];
        $taskGroups = $tasks->groupBy('title');
    @endphp
    @forelse($taskGroups as $groupTitle => $groupTasks)
    @php
        $dominant = $groupTasks->sortBy(fn($t) => match($t->status){
            'approved'=>0,'completed'=>1,'in_progress'=>2,default=>3
        })->first();
        $tiClass = match($dominant->status){
            'approved'=>'ti-approved','completed'=>'ti-completed','in_progress'=>'ti-progress',default=>'ti-pending'
        };
        $tiIcon = match($dominant->status){
            'approved'=>'check2-all','completed'=>'check-circle-fill','in_progress'=>'arrow-repeat',default=>'circle'
        };
        $total    = $groupTasks->count();
        $approved = $groupTasks->where('status','approved')->count();
        $done     = $groupTasks->whereIn('status',['completed','approved'])->count();
    @endphp
    <div class="task-block">
        {{-- Cabeçalho colapsável --}}
        <div class="task-block-header" onclick="this.closest('.task-block').classList.toggle('open')" style="cursor:pointer;user-select:none;">
            <div class="task-icon {{ $tiClass }}" style="width:40px;height:40px;font-size:19px;flex-shrink:0;">
                <i class="bi bi-{{ $tiIcon }}"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div class="task-title" style="font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $groupTitle }}</div>
                <div class="task-meta" style="margin-top:3px;">
                    <span class="badge-cat st-{{ $dominant->status }}" style="font-size:9px;padding:1px 6px;">{{ $tsLabel[$dominant->status] ?? $dominant->status }}</span>
                    @if($total > 1)
                    <span style="font-size:10px;color:var(--txt-3);font-weight:600;">
                        <i class="bi bi-people" style="font-size:9px;"></i> {{ $done }}/{{ $total }} concluídos
                    </span>
                    @endif
                    @if($dominant->due_date)
                    <span class="t-muted"><i class="bi bi-clock" style="font-size:9px;"></i> {{ \Carbon\Carbon::parse($dominant->due_date)->format('d/m') }}</span>
                    @endif
                    @if($dominant->points > 0)
                    <span class="t-muted"><i class="bi bi-star" style="font-size:9px;"></i> {{ $dominant->points }} pts</span>
                    @endif
                </div>
            </div>
            <i class="bi bi-chevron-down" style="font-size:12px;color:var(--txt-4);flex-shrink:0;transition:transform .2s;" class="task-toggle-icon"></i>
        </div>

        {{-- Corpo colapsável — membros + ações --}}
        <div class="task-block-body" style="display:none;border-top:1px solid var(--border-lt);background:#fafafa;">
            @foreach($groupTasks as $task)
            <div class="task-member-row"
                 data-id="{{ $task->id }}"
                 data-status="{{ $task->status }}"
                 data-title="{{ addslashes($task->title) }}"
                 data-description="{{ addslashes($task->description ?? '') }}"
                 data-assigned="{{ $task->assigned_to ?? '' }}"
                 data-points="{{ $task->points ?? 0 }}"
                 data-due="{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '' }}">
                {{-- Avatar --}}
                @if($task->assignedTo)
                <img src="{{ $task->assignedTo->avatar_url }}"
                     style="width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0;"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($task->assignedTo->name) }}&size=28'">
                <span style="font-size:12px;font-weight:600;color:var(--txt-2);flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                    {{ $task->assignedTo->name }}
                </span>
                @else
                <div style="width:28px;height:28px;border-radius:50%;background:var(--p-lt);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-person" style="font-size:13px;color:var(--p);"></i>
                </div>
                <span style="font-size:12px;font-weight:600;color:var(--txt-3);flex:1;">Sem atribuição</span>
                @endif
                {{-- Status badge --}}
                <span class="badge-cat st-{{ $task->status }}" style="font-size:9px;padding:1px 6px;flex-shrink:0;">{{ $tsLabel[$task->status] ?? $task->status }}</span>

                {{-- Botões de ação com regras claras de papel --}}
                @php
                    $isOwner   = $task->assigned_to == $user->id;
                    $isManager = $user->hasRole('dirigente,admin');
                    $taskName  = addslashes($task->title);
                    $memberName = addslashes($task->assignedTo->name ?? 'Membro');
                @endphp
                <div style="flex-shrink:0;display:flex;gap:4px;">

                    {{-- INICIAR: somente o próprio membro atribuído (quando pending) --}}
                    @if($task->status === 'pending' && $isOwner)
                    <form method="POST" action="{{ route('my-house.tasks.status', $task->id) }}" style="margin:0;"
                          onsubmit="return confirmTaskAction(event,this,'Iniciar tarefa?','Você confirma o início de <strong>{{ $taskName }}</strong>?','Sim, iniciar','#854d0e')">
                        @csrf<input type="hidden" name="status" value="in_progress">
                        <button type="submit" style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:6px;background:#fef9c3;color:#854d0e;border:1.5px solid #fde68a;cursor:pointer;white-space:nowrap;">
                            ▶ Iniciar
                        </button>
                    </form>

                    {{-- Se pendente e o dirigente vê, mostra quem ainda não iniciou --}}
                    @elseif($task->status === 'pending' && $isManager)
                    <span style="font-size:10px;color:var(--txt-4);padding:3px 6px;white-space:nowrap;">Aguardando início</span>

                    {{-- CONCLUIR: somente o próprio membro (quando in_progress) --}}
                    @elseif($task->status === 'in_progress' && $isOwner)
                    <form method="POST" action="{{ route('my-house.tasks.status', $task->id) }}" style="margin:0;"
                          onsubmit="return confirmTaskAction(event,this,'Concluir tarefa?','Confirma que <strong>{{ $taskName }}</strong> foi concluída?','Sim, concluir','#1d4ed8')">
                        @csrf<input type="hidden" name="status" value="completed">
                        <button type="submit" style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:6px;background:#dbeafe;color:#1d4ed8;border:1.5px solid #bfdbfe;cursor:pointer;white-space:nowrap;">
                            ✓ Concluir
                        </button>
                    </form>

                    {{-- Se em andamento e dirigente vê --}}
                    @elseif($task->status === 'in_progress' && $isManager)
                    <span style="font-size:10px;color:#854d0e;padding:3px 6px;white-space:nowrap;"><i class="bi bi-arrow-repeat"></i> Em andamento</span>

                    {{-- AGUARDANDO VALIDAÇÃO: membro já concluiu, dirigente valida --}}
                    @elseif($task->status === 'completed')
                        @if($isManager)
                        {{-- Aprovar --}}
                        <form method="POST" action="{{ route('my-house.tasks.approve', $task->id) }}" style="margin:0;"
                              onsubmit="return confirmTaskAction(event,this,'Validar conclusão?','Confirma que <strong>{{ $memberName }}</strong> concluiu a tarefa corretamente?','Sim, validar','#166534')">
                            @csrf
                            <button type="submit" style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:6px;background:#dcfce7;color:#166534;border:1.5px solid #bbf7d0;cursor:pointer;white-space:nowrap;">
                                ✔ Validar
                            </button>
                        </form>
                        {{-- Rejeitar --}}
                        <form method="POST" action="{{ route('my-house.tasks.reject', $task->id) }}" style="margin:0;"
                              onsubmit="return confirmTaskAction(event,this,'Devolver tarefa?','A tarefa voltará para <strong>Em andamento</strong> para {{ $memberName }} revisar.','Sim, devolver','#dc2626')">
                            @csrf
                            <button type="submit" style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:6px;background:#fee2e2;color:#dc2626;border:1.5px solid #fecaca;cursor:pointer;white-space:nowrap;">
                                ✗ Devolver
                            </button>
                        </form>
                        @else
                        <span style="font-size:10px;color:#1d4ed8;font-weight:700;padding:3px 6px;white-space:nowrap;">Aguardando validação</span>
                        @endif

                    @elseif($task->status === 'approved')
                    <span style="font-size:14px;color:#166534;padding:3px 6px;"><i class="bi bi-patch-check-fill"></i></span>
                    @endif

                </div>
            </div>
            @endforeach

            {{-- Botão editar no rodapé do bloco (dirigente) --}}
            @if($user->hasRole('dirigente,admin') && in_array($dominant->status, ['pending','in_progress']))
            <div style="padding:8px 14px;border-top:1px solid var(--border-lt);">
                <button type="button"
                        onclick="editTask(this.closest('.task-block').querySelector('.task-member-row'))"
                        style="width:100%;padding:7px;border-radius:8px;border:1.5px solid var(--border);background:#fff;color:var(--txt-2);font-size:12px;font-weight:700;cursor:pointer;">
                    <i class="bi bi-pencil me-1"></i>Editar tarefa
                </button>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state"><i class="bi bi-check2-circle"></i><p>Nenhuma tarefa criada.</p></div>
    @endforelse
</div>

{{-- ══════════════════ EVENTOS ══════════════════ --}}
<div id="tab-eventos" style="{{ $tab !== 'eventos' ? 'display:none;' : '' }}">
    <div class="sec-label">
        <span>Próximos Eventos</span>
        <div style="display:flex;gap:8px;align-items:center;">
            @if($user->hasRole('assistente,dirigente,admin'))
            <a href="{{ route('checkin') }}" style="font-size:11px;font-weight:700;color:var(--p);text-decoration:none;padding:2px 8px;border-radius:6px;border:1.5px solid var(--p-lt);">
                <i class="bi bi-qr-code-scan"></i> Check-in
            </a>
            @endif
            @if($user->hasRole('dirigente,admin'))
            <button data-bs-toggle="modal" data-bs-target="#modalEvento"
                    style="background:var(--p);color:#fff;width:28px;height:28px;border-radius:50%;border:none;display:flex;align-items:center;justify-content:center;font-size:18px;cursor:pointer;">
                <i class="bi bi-plus"></i>
            </button>
            @endif
        </div>
    </div>

    {{-- Frequência média (gestor) --}}
    @if($isManager && $avgFrequency !== null)
    <div style="padding:10px 16px;background:#f8fafc;border-bottom:1px solid var(--border-lt);display:flex;align-items:center;gap:8px;">
        <i class="bi bi-bar-chart-fill" style="color:var(--p);font-size:16px;"></i>
        <span style="font-size:12px;color:var(--txt-2);">Frequência média (últimos eventos):</span>
        <strong style="font-size:14px;color:{{ $avgFrequency >= 70 ? '#16a34a' : ($avgFrequency >= 40 ? '#d97706' : '#dc2626') }};">{{ $avgFrequency }}%</strong>
    </div>
    @endif

    @forelse($house->upcomingEvents as $event)
    <div class="event-mini"
         data-id="{{ $event->id }}"
         data-name="{{ addslashes($event->name) }}"
         data-starts="{{ $event->starts_at->format('Y-m-d\TH:i') }}"
         data-ends="{{ $event->ends_at ? $event->ends_at->format('Y-m-d\TH:i') : '' }}"
         data-price="{{ $event->price ?? 0 }}"
         data-capacity="{{ $event->capacity ?? '' }}"
         data-address="{{ addslashes($event->address ?? '') }}"
         data-description="{{ addslashes($event->description ?? '') }}"
         data-rules="{{ addslashes($event->rules ?? '') }}"
         data-recommendations="{{ addslashes($event->recommendations ?? '') }}"
         data-banner="{{ $event->banner_image ? asset('storage/'.$event->banner_image) : '' }}"
         data-visibility="{{ $event->visibility ?? 'public' }}">
        <div class="event-mini-date">
            <div class="d">{{ $event->starts_at->format('d') }}</div>
            <div class="m">{{ $event->starts_at->translatedFormat('M') }}</div>
        </div>
        <img src="{{ $event->banner_image_url }}" alt="{{ $event->name }}"
             onerror="this.src='https://placehold.co/48x48/dcfce7/166534?text=E'">
        <div style="flex:1;min-width:0;">
            <div class="event-mini-name">
                {{ $event->name }}
                @if(($event->visibility ?? 'public') === 'members')
                <span style="font-size:9px;font-weight:700;padding:1px 6px;border-radius:4px;background:#ede9fe;color:#5b21b6;vertical-align:middle;margin-left:4px;"><i class="bi bi-lock-fill"></i> Interna</span>
                @endif
            </div>
            <div class="event-mini-info">
                <i class="bi bi-clock me-1"></i>{{ $event->starts_at->format('H:i') }}
                @if($isManager && isset($eventIntentStats[$event->id]))
                @php $es = $eventIntentStats[$event->id]; @endphp
                · <span style="color:#16a34a;">✅ {{ $es['going'] }}</span>
                  <span style="color:#d97706;margin-left:4px;">🤔 {{ $es['maybe'] }}</span>
                  @if($es['total'] > 0)<span style="color:#6b7280;margin-left:4px;font-size:10px;">/ {{ $es['total'] }} total</span>@endif
                @else
                @if($event->attendees()->count() > 0)
                · <i class="bi bi-people me-1"></i>{{ $event->attendees()->count() }} inscritos
                @endif
                @endif
            </div>
            {{-- Stats bar para gestor --}}
            @if($isManager && isset($eventIntentStats[$event->id]) && $eventIntentStats[$event->id]['total'] > 0)
            @php $es = $eventIntentStats[$event->id]; $total = $house->activeMembers->count(); @endphp
            <div class="freq-bar" style="padding:6px 14px;margin:0;">
                @if($es['going'] > 0)<span class="freq-chip fc-going">✅ {{ $es['going'] }} vão</span>@endif
                @if($es['maybe'] > 0)<span class="freq-chip fc-maybe">🤔 {{ $es['maybe'] }} talvez</span>@endif
                @php $noReply = max(0, $total - $es['total']); @endphp
                @if($noReply > 0)<span class="freq-chip fc-noreply">{{ $noReply }} sem resposta</span>@endif
                @if($es['checked_in'] > 0)<span class="freq-chip fc-checkin">✅ {{ $es['checked_in'] }} check-in</span>@endif
            </div>
            @endif
        </div>
        <div style="display:flex;flex-direction:column;gap:4px;flex-shrink:0;align-items:flex-end;">
            <a href="{{ route('events.show', $event->id) }}"
               style="font-size:11px;font-weight:700;color:var(--p);text-decoration:none;">
                <i class="bi bi-eye"></i>
            </a>
            @if($user->hasRole('dirigente,admin'))
            <button type="button" onclick="editEvent(this.closest('.event-mini'))"
                    style="font-size:11px;color:var(--p);background:none;border:none;cursor:pointer;padding:0;"
                    title="Editar">
                <i class="bi bi-pencil"></i>
            </button>
            @endif
            @if($user->hasRole('dirigente,admin'))
            <form method="POST" action="{{ route('my-house.events.cancel', $event->id) }}">
                @csrf
                <button type="button" style="font-size:11px;color:var(--txt-4);background:none;border:none;cursor:pointer;padding:0;"
                        onclick="confirmCancel('{{ addslashes($event->name) }}', this.form)">
                    <i class="bi bi-x-circle"></i>
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state"><i class="bi bi-calendar-x"></i><p>Nenhum evento próximo.</p></div>
    @endforelse
</div>

{{-- ══════════════════ FINANCEIRO ══════════════════ --}}
<div id="tab-financeiro" style="{{ $tab !== 'financeiro' ? 'display:none;' : '' }}">
    <div class="sec-label">
        <span>Lançamentos</span>
        @if($user->hasRole('dirigente,admin'))
        <button data-bs-toggle="modal" data-bs-target="#modalFinanca"
                style="background:var(--p);color:#fff;width:28px;height:28px;border-radius:50%;border:none;display:flex;align-items:center;justify-content:center;font-size:18px;cursor:pointer;">
            <i class="bi bi-plus"></i>
        </button>
        @endif
    </div>

    {{-- Filtros --}}
    <div style="display:flex;gap:6px;padding:8px 16px 4px;overflow-x:auto;scrollbar-width:none;background:#fff;border-bottom:1px solid var(--border-lt);">
        <button class="filter-chip active" onclick="filterFinances('')">Todos</button>
        <button class="filter-chip" onclick="filterFinances('credit')" style="color:#16a34a;">↓ Entradas</button>
        <button class="filter-chip" onclick="filterFinances('debit')" style="color:#dc2626;">↑ Saídas</button>
        <button class="filter-chip" onclick="filterFinances('pending')">Pendentes</button>
        <button class="filter-chip" onclick="filterFinances('overdue')">Vencidos</button>
    </div>

    {{-- Saldo: apenas dirigente vê --}}
    @if($user->hasRole('dirigente,admin'))
    @php $bal = ($balanceCredit ?? 0) - ($balanceDebit ?? 0); @endphp
    <div style="margin:12px 14px;background:#fff;border-radius:var(--r);padding:14px;box-shadow:var(--shadow-sm);display:flex;gap:16px;">
        <div style="flex:1;text-align:center;">
            <div style="font-size:10px;font-weight:700;color:var(--txt-3);text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px;">Entradas</div>
            <div style="font-size:16px;font-weight:800;color:#16a34a;">R$ {{ number_format($balanceCredit ?? 0,2,',','.') }}</div>
        </div>
        <div style="width:1px;background:var(--border-lt);"></div>
        <div style="flex:1;text-align:center;">
            <div style="font-size:10px;font-weight:700;color:var(--txt-3);text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px;">Saídas</div>
            <div style="font-size:16px;font-weight:800;color:#dc2626;">R$ {{ number_format($balanceDebit ?? 0,2,',','.') }}</div>
        </div>
        <div style="width:1px;background:var(--border-lt);"></div>
        <div style="flex:1;text-align:center;">
            <div style="font-size:10px;font-weight:700;color:var(--txt-3);text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px;">Saldo</div>
            <div style="font-size:16px;font-weight:800;color:{{ $bal >= 0 ? '#16a34a' : '#dc2626' }};">R$ {{ number_format($bal,2,',','.') }}</div>
        </div>
    </div>

    {{-- Sugestões recebidas dos membros --}}
    @if($suggestions->isNotEmpty())
    <div class="sec-label" style="margin-top:0;"><span><i class="bi bi-lightbulb me-1" style="color:#f59e0b;"></i>Sugestões dos Membros</span></div>
    @foreach($suggestions as $sug)
    <div style="display:flex;align-items:flex-start;gap:10px;padding:10px 16px;border-bottom:1px solid var(--border-lt);background:#fff;">
        <img src="{{ $sug->user->avatar_url }}" style="width:30px;height:30px;border-radius:50%;flex-shrink:0;object-fit:cover;"
             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($sug->user->name ?? 'M') }}&size=30'">
        <div style="flex:1;min-width:0;">
            <div style="font-size:11px;font-weight:700;color:var(--txt-2);">{{ $sug->user->name }} <span style="font-weight:400;color:var(--txt-4);">· {{ $sug->created_at->diffForHumans() }}</span></div>
            <div style="font-size:13px;color:var(--txt);margin-top:3px;">{{ $sug->message }}</div>
        </div>
        @if(! $sug->read_at)
        <span style="width:8px;height:8px;background:#f59e0b;border-radius:50%;flex-shrink:0;margin-top:6px;"></span>
        @endif
    </div>
    @endforeach
    @endif

    @else
    {{-- Para membros: painel de sugestões --}}
    <div style="margin:14px 14px 0;">
        <div style="background:#fffbeb;border-radius:var(--r);padding:14px;box-shadow:var(--shadow-sm);border:1px solid #fde68a;">
            <div style="font-size:13px;font-weight:700;color:#92400e;margin-bottom:8px;display:flex;align-items:center;gap:6px;">
                <i class="bi bi-lightbulb-fill" style="color:#f59e0b;"></i>Enviar sugestão ao dirigente
            </div>
            <form method="POST" action="{{ route('my-house.suggestions.store') }}">
                @csrf
                <textarea name="message" rows="3" class="form-control form-control-sm"
                          placeholder="Compartilhe uma ideia, sugestão ou melhoria para a casa..." required
                          style="border-radius:8px;font-size:13px;resize:none;margin-bottom:8px;border-color:#fde68a;"></textarea>
                <button type="submit"
                        style="width:100%;padding:8px;background:#f59e0b;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:700;cursor:pointer;">
                    <i class="bi bi-send me-1"></i>Enviar sugestão
                </button>
            </form>
        </div>
    </div>
    @endif

    @forelse($finances as $fin)
    @php $finScope = $fin->scope ?? 'global'; @endphp
    <div class="finance-row"
         data-id="{{ $fin->id }}"
         data-type="{{ $fin->type }}"
         data-title="{{ addslashes($fin->title) }}"
         data-amount="{{ $fin->amount }}"
         data-status="{{ $fin->status }}"
         data-scope="{{ $finScope }}"
         data-due="{{ $fin->due_date ? \Carbon\Carbon::parse($fin->due_date)->format('Y-m-d') : '' }}">
        {{-- Cabeçalho colapsável --}}
        <div class="finance-header" onclick="this.closest('.finance-row').classList.toggle('open')">
            <div class="finance-icon {{ $fin->type === 'credit' ? 'fi-credit' : 'fi-debit' }}">
                <i class="bi bi-{{ $fin->type === 'credit' ? 'arrow-down-circle-fill' : 'arrow-up-circle-fill' }}"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div class="finance-desc">{{ $fin->title }}</div>
                <div class="finance-date">
                    <span class="badge-cat fin-{{ $fin->status }}" style="font-size:9px;padding:1px 6px;">
                        {{ ['pending'=>'Pendente','paid'=>'Pago','overdue'=>'Vencido'][$fin->status] ?? $fin->status }}
                    </span>
                    @if($finScope !== 'global')
                    <span style="font-size:9px;font-weight:700;padding:1px 5px;border-radius:4px;background:#dbeafe;color:#1e40af;">
                        <i class="bi bi-people-fill"></i> Membros
                    </span>
                    @endif
                </div>
            </div>
            <div class="finance-amt {{ $fin->type === 'credit' ? 'amt-credit' : 'amt-debit' }}" style="font-size:14px;">
                {{ $fin->type === 'credit' ? '+' : '−' }} R$ {{ number_format($fin->amount,2,',','.') }}
            </div>
            <i class="bi bi-chevron-down finance-toggle-icon"></i>
        </div>
        {{-- Corpo expandido --}}
        <div class="finance-body">
            <div style="font-size:11px;color:var(--txt-3);margin-bottom:10px;padding-top:10px;">
                <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($fin->due_date ?? $fin->created_at)->format('d/m/Y') }}
                @if($fin->notes)
                · {{ $fin->notes }}
                @endif
            </div>

            {{-- Ação de confirmar pagamento para lançamentos globais --}}
            @if($user->hasRole('dirigente,admin') && $finScope === 'global' && $fin->status !== 'paid')
            <form method="POST" action="{{ route('my-house.finances.member.toggle', [$fin->id, $user->id]) }}" style="margin-bottom:10px;">
                @csrf
                <button type="submit"
                        style="width:100%;padding:8px;border-radius:8px;border:none;cursor:pointer;font-size:12px;font-weight:700;background:#dcfce7;color:#166534;">
                    <i class="bi bi-check-circle me-1"></i>Confirmar Pagamento
                </button>
            </form>
            @endif

            {{-- Lista de pagamentos por membro --}}
            @if($finScope !== 'global' && $fin->memberEntries->isNotEmpty())
            <div style="display:flex;flex-direction:column;gap:6px;">
                @php
                    $paid   = $fin->memberEntries->where('status','paid')->count();
                    $total  = $fin->memberEntries->count();
                @endphp
                <div style="font-size:11px;font-weight:700;color:var(--txt-3);margin-bottom:4px;">
                    Pagamentos: <span style="color:#166534;">{{ $paid }}</span>/{{ $total }} confirmados
                </div>
                @foreach($fin->memberEntries as $entry)
                <div style="display:flex;align-items:center;gap:8px;padding:6px 8px;border-radius:8px;background:#fff;border:1px solid var(--border-lt);">
                    <img src="{{ $entry->user->avatar_url }}" style="width:26px;height:26px;border-radius:50%;object-fit:cover;"
                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($entry->user->name ?? 'M') }}&size=26'">
                    <span style="font-size:12px;flex:1;color:var(--txt-2);font-weight:600;">{{ $entry->user->name ?? '—' }}</span>
                    @if($user->hasRole('dirigente,admin'))
                    <form method="POST" action="{{ route('my-house.finances.member.toggle', [$fin->id, $entry->user_id]) }}" style="margin:0;"
                          onsubmit="return confirmTogglePayment(event, this, '{{ addslashes($entry->user->name ?? '') }}', '{{ $entry->status }}')">
                        @csrf
                        <button type="submit"
                                style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:6px;border:none;cursor:pointer;
                                       background:{{ $entry->status === 'paid' ? '#dcfce7' : '#fef9c3' }};
                                       color:{{ $entry->status === 'paid' ? '#166534' : '#854d0e' }};">
                            <i class="bi bi-{{ $entry->status === 'paid' ? 'check-circle-fill' : 'clock' }}" style="font-size:9px;"></i>
                            {{ $entry->status === 'paid' ? 'Pago' : 'Pendente' }}
                        </button>
                    </form>
                    @else
                    <span style="font-size:10px;font-weight:700;padding:3px 10px;border-radius:6px;
                                 background:{{ $entry->status === 'paid' ? '#dcfce7' : '#fef9c3' }};
                                 color:{{ $entry->status === 'paid' ? '#166534' : '#854d0e' }};">
                        {{ $entry->status === 'paid' ? 'Pago' : 'Pendente' }}
                    </span>
                    @endif
                </div>
                @endforeach
            </div>
            @endif

            @if($user->hasRole('dirigente,admin'))
            <button type="button" onclick="editFinance(this.closest('.finance-row'))"
                    style="margin-top:10px;width:100%;padding:7px;border-radius:8px;border:1.5px solid var(--border);background:#fff;color:var(--txt-2);font-size:12px;font-weight:700;cursor:pointer;">
                <i class="bi bi-pencil me-1"></i>Editar lançamento
            </button>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state"><i class="bi bi-cash-stack"></i><p>Nenhum lançamento.</p></div>
    @endforelse
</div>

{{-- ══════════════════ ESTUDOS ══════════════════ --}}
<div id="tab-estudos" style="{{ $tab !== 'estudos' ? 'display:none;' : '' }}">
    <div class="sec-label">
        <span>Materiais de Estudo <span style="font-size:11px;color:var(--txt-3);">({{ $studies->count() }})</span></span>
        @if($isManager)
        <button data-bs-toggle="modal" data-bs-target="#modalEstudo"
                style="background:var(--p);color:#fff;width:28px;height:28px;border-radius:50%;border:none;display:flex;align-items:center;justify-content:center;font-size:18px;cursor:pointer;">
            <i class="bi bi-plus"></i>
        </button>
        @endif
    </div>

    {{-- Filtros --}}
    <div style="display:flex;gap:6px;padding:8px 16px 4px;overflow-x:auto;scrollbar-width:none;background:#fff;border-bottom:1px solid var(--border-lt);">
        <button class="filter-chip active" onclick="filterStudies('')">Todos</button>
        @if($isManager)
        <button class="filter-chip" onclick="filterStudies('published')"><i class="bi bi-check-circle me-1"></i>Publicados</button>
        <button class="filter-chip" onclick="filterStudies('draft')"><i class="bi bi-pencil me-1"></i>Rascunhos</button>
        @endif
        <button class="filter-chip" onclick="filterStudies('public')"><i class="bi bi-globe me-1"></i>Públicos</button>
        <button class="filter-chip" onclick="filterStudies('members')"><i class="bi bi-lock me-1"></i>Membros</button>
    </div>

    @forelse($studies as $study)
    <div class="study-row"
         data-published="{{ $study->published ? '1' : '0' }}"
         data-public="{{ $study->is_public ? '1' : '0' }}"
         data-id="{{ $study->id }}"
         data-title="{{ addslashes($study->title) }}"
         data-description="{{ addslashes($study->description ?? '') }}"
         data-type="{{ $study->content_type }}"
         data-url="{{ addslashes($study->content_url ?? '') }}"
         data-body="{{ addslashes(mb_substr($study->content_body ?? '', 0, 300)) }}"
         data-category="{{ addslashes($study->category ?? '') }}"
         data-points="{{ $study->points }}"
         data-ispublic="{{ $study->is_public ? '1' : '0' }}"
         data-ispublished="{{ $study->published ? '1' : '0' }}"
         data-pdf="{{ addslashes($study->content_file ?? '') }}">
        <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border-lt);background:#fff;">
            @php
                $studyBg   = match($study->content_type) { 'video' => '#ede9fe', 'audio' => '#e0e7ff', 'pdf' => '#fee2e2', default => '#ccfbf1' };
                $studyClr  = match($study->content_type) { 'video' => '#5b21b6', 'audio' => '#3730a3', 'pdf' => '#991b1b', default => '#0f766e' };
                $studyIcon = match($study->content_type) { 'video' => 'play-btn-fill', 'audio' => 'music-note-list', 'pdf' => 'file-earmark-pdf-fill', default => 'file-text-fill' };
            @endphp
            <div style="width:44px;height:44px;border-radius:12px;background:{{ $studyBg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:20px;color:{{ $studyClr }};">
                <i class="bi bi-{{ $studyIcon }}"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:14px;font-weight:700;color:var(--txt);margin-bottom:4px;">{{ $study->title }}</div>
                @if($study->description)
                <div style="font-size:12px;color:var(--txt-3);margin-bottom:5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $study->description }}</div>
                @endif
                <div style="display:flex;gap:4px;flex-wrap:wrap;align-items:center;">
                    @if($isManager)
                    @if($study->published)
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:6px;background:#dcfce7;color:#166534;"><i class="bi bi-check-circle me-1"></i>Publicado</span>
                    @else
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:6px;background:#f3f4f6;color:#6b7280;"><i class="bi bi-pencil me-1"></i>Rascunho</span>
                    @endif
                    @endif
                    @if($study->is_public)
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:6px;background:#dbeafe;color:#1d4ed8;"><i class="bi bi-globe me-1"></i>Público</span>
                    @else
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:6px;background:#ede9fe;color:#5b21b6;"><i class="bi bi-lock me-1"></i>Membros</span>
                    @endif
                    @if($study->category)
                    <span style="font-size:10px;color:var(--txt-3);">{{ $study->category }}</span>
                    @endif
                    <span style="font-size:10px;color:var(--txt-3);"><i class="bi bi-star me-1"></i>{{ $study->points }} pts</span>
                </div>
            </div>
            <div style="display:flex;flex-direction:column;gap:6px;align-items:flex-end;flex-shrink:0;">
                <a href="{{ route('studies.show', $study->id) }}"
                   style="font-size:13px;color:var(--p);text-decoration:none;" title="Visualizar">
                    <i class="bi bi-eye"></i>
                </a>
                @if($isManager)
                <button type="button" onclick="editStudy(this.closest('.study-row'))"
                        style="background:none;border:none;font-size:13px;color:var(--txt-4);cursor:pointer;padding:0;" title="Editar">
                    <i class="bi bi-pencil"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state"><i class="bi bi-book"></i><p>Nenhum material disponível.</p></div>
    @endforelse
</div>

{{-- Formulários hidden de check-in (um por evento de hoje) --}}
@if($house)
@foreach($house->upcomingEvents->filter(fn($e)=>$e->starts_at->isToday()) as $ev)
<form id="formCheckin{{ $ev->id }}" method="POST" action="{{ route('events.checkin.self', $ev->id) }}" style="display:none;">
    @csrf
</form>
@endforeach
@endif

@endif {{-- end $house --}}

<div style="height:24px;"></div>

{{-- ══════════════════ MODAIS ══════════════════ --}}
@if($house)

@if($user->hasRole('assistente,dirigente,admin'))
<div class="modal fade" id="modalTarefa" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered mx-3">
    <div class="modal-content" style="border-radius:var(--r);">
      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title fw-bold" id="modalTarefaTitle"><i class="bi bi-check2-square me-2" style="color:var(--p);"></i>Nova Tarefa</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formTarefa" method="POST" action="{{ route('my-house.tasks.store') }}">
        @csrf
        <div class="modal-body pt-3">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Título *</label>
            <input type="text" name="title" class="form-control" required placeholder="Ex: Preparar altar">
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Descrição</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Detalhes da tarefa..."></textarea>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label small fw-semibold">Atribuir a</label>
              <select name="assigned_to" class="form-select form-select-sm">
                <option value="">Ninguém (randomizar)</option>
                <option value="all">-- Todos os membros --</option>
                @foreach($members->where('pivot.status','active') as $m)
                <option value="{{ $m->id }}">{{ $m->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold">Pontos</label>
              <input type="number" name="points" class="form-control form-control-sm" min="0" value="0" placeholder="0">
            </div>
          </div>
          <div class="mb-1">
            <label class="form-label small fw-semibold">Data limite</label>
            <input type="date" name="due_date" class="form-control">
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" id="btnSubmitTarefa" class="btn btn-primary btn-sm">Criar Tarefa</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

{{-- Modal Notificação/Mensagem para membros --}}
@if($user->hasRole('dirigente,admin'))
<div class="modal fade" id="modalNotify" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered mx-3">
    <div class="modal-content" style="border-radius:var(--r);">
      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title fw-bold" id="modalNotifyTitle"><i class="bi bi-megaphone me-2" style="color:#1e40af;"></i>Enviar Mensagem</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formNotify" method="POST" action="{{ route('my-house.notify') }}">
        @csrf
        <input type="hidden" name="target" id="notifyTarget" value="all">
        <input type="hidden" name="user_id" id="notifyUserId" value="">
        <div class="modal-body pt-2">
          <div id="notifyRecipientBadge" style="margin-bottom:10px;font-size:12px;font-weight:700;padding:6px 10px;border-radius:8px;background:#dbeafe;color:#1e40af;display:inline-flex;align-items:center;gap:6px;">
            <i class="bi bi-people-fill"></i><span>Todos os membros</span>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Assunto *</label>
            <input type="text" name="title" class="form-control" required placeholder="Ex: Aviso sobre a gira de sexta">
          </div>
          <div class="mb-2">
            <label class="form-label small fw-semibold">Mensagem *</label>
            <textarea name="body" class="form-control" rows="4" required placeholder="Escreva a mensagem para os membros..."></textarea>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send me-1"></i>Enviar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

{{-- Modal Estudo --}}
@if($user->hasRole('dirigente,assistente,admin'))
<div class="modal fade" id="modalEstudo" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered mx-3">
    <div class="modal-content" style="border-radius:var(--r);">
      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title fw-bold" id="modalEstudoTitle"><i class="bi bi-book me-2" style="color:var(--p);"></i>Novo Material</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEstudo" method="POST" action="{{ route('my-house.studies.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body pt-2" style="max-height:70vh;overflow-y:auto;">

          <div class="mb-3">
            <label class="form-label small fw-semibold">Título *</label>
            <input type="text" name="title" class="form-control" required placeholder="Ex: Introdução à Umbanda">
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold">Descrição breve</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Resumo do conteúdo..."></textarea>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label small fw-semibold">Tipo *</label>
              <select name="content_type" class="form-select form-select-sm" id="estudoTipo" onchange="toggleEstudoFields()">
                <option value="text">Texto</option>
                <option value="video">Video</option>
                <option value="audio">Audio</option>
                <option value="pdf">PDF</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold">Categoria</label>
              <input type="text" name="category" class="form-control form-control-sm" placeholder="Ex: História, Ritual">
            </div>
          </div>

          <div class="mb-3" id="estudo-url-field">
            <label class="form-label small fw-semibold">URL do conteúdo</label>
            <input type="url" name="content_url" class="form-control form-control-sm" placeholder="https://...">
            <div class="form-text" style="font-size:11px;">Link do vídeo/áudio (YouTube, SoundCloud, etc.)</div>
          </div>

          <div class="mb-3" id="estudo-pdf-field" style="display:none;">
            <label class="form-label small fw-semibold">Arquivo PDF *</label>
            <input type="file" name="content_file" id="estudoPdfInput" class="form-control form-control-sm" accept=".pdf">
            <div class="form-text" style="font-size:11px;">Máximo 20 MB. Apenas arquivos .pdf</div>
            <div id="estudoPdfAtual" style="display:none;font-size:11px;color:#16a34a;margin-top:4px;">
              <i class="bi bi-file-earmark-pdf me-1"></i><span></span>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold">Conteúdo em texto</label>
            <textarea name="content_body" class="form-control" rows="4" placeholder="Escreva o conteúdo do material aqui..."></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold">Pontos ao concluir</label>
            <input type="number" name="points" class="form-control form-control-sm" min="0" value="20">
          </div>

          {{-- Visibilidade e publicação --}}
          <div style="background:#f8f9fa;border-radius:10px;padding:12px;display:flex;flex-direction:column;gap:10px;">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div style="font-size:13px;font-weight:700;">Publicar material</div>
                <div style="font-size:11px;color:var(--txt-3);">Rascunho = visível só para você</div>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" name="published" id="estudoPublished" style="width:40px;height:22px;">
              </div>
            </div>
            <hr style="margin:0;border-color:#e5e7eb;">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div style="font-size:13px;font-weight:700;"><i class="bi bi-globe me-1 text-primary"></i>Acesso público</div>
                <div style="font-size:11px;color:var(--txt-3);">Desativado = somente membros da casa</div>
              </div>
              <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" name="is_public" id="estudoIsPublic" style="width:40px;height:22px;">
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer border-0 pt-2">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" id="btnSubmitEstudo" class="btn btn-primary btn-sm"><i class="bi bi-floppy me-1"></i>Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@if($user->hasRole('dirigente,admin'))
<div class="modal fade" id="modalEvento" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered mx-3">
    <div class="modal-content" style="border-radius:var(--r);">
      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title fw-bold" id="modalEventoTitle"><i class="bi bi-calendar-plus me-2" style="color:var(--p);"></i>Novo Evento</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formEvento" method="POST" action="{{ route('my-house.events.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body pt-2" style="max-height:75vh;overflow-y:auto;">

          {{-- Banner --}}
          <div class="mb-3">
            <label class="form-label small fw-semibold">Banner do Evento</label>
            <input type="file" name="banner_image" id="eventoBannerInput" class="form-control form-control-sm" accept="image/*" onchange="previewEventBanner(this)">
            <div id="eventoBannerPreview" style="display:none;margin-top:6px;">
              <img id="eventoBannerImg" src="" style="width:100%;height:120px;object-fit:cover;border-radius:8px;">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold">Nome do evento *</label>
            <input type="text" name="name" class="form-control" required placeholder="Ex: Gira de Umbanda">
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label small fw-semibold">Início *</label>
              <input type="datetime-local" name="starts_at" class="form-control form-control-sm" required>
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold">Término</label>
              <input type="datetime-local" name="ends_at" class="form-control form-control-sm">
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label small fw-semibold">Preço (R$)</label>
              <input type="number" name="price" class="form-control form-control-sm" step="0.01" min="0" placeholder="0,00">
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold">Vagas</label>
              <input type="number" name="capacity" class="form-control form-control-sm" min="1" placeholder="Ilimitado">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold">Local / Endereço</label>
            <input type="text" name="address" class="form-control form-control-sm" placeholder="Rua, número, bairro, cidade">
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold">Descrição</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Informações gerais sobre o evento..."></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold">Regras / Orientações</label>
            <textarea name="rules" class="form-control form-control-sm" rows="2" placeholder="Ex: Use roupas brancas, chegue com 30 min de antecedência..."></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label small fw-semibold">Recomendações</label>
            <textarea name="recommendations" class="form-control form-control-sm" rows="2" placeholder="Ex: Leve vela branca, terço, água..."></textarea>
          </div>

          <div class="mb-1">
            <label class="form-label small fw-semibold">Visibilidade</label>
            <div style="display:flex;gap:8px;">
              <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid #e5e7eb;border-radius:10px;cursor:pointer;">
                <input type="radio" name="visibility" value="public" id="vis-public" checked style="accent-color:#16a34a;">
                <div>
                  <div style="font-size:12px;font-weight:700;">Público</div>
                  <div style="font-size:10px;color:#6b7280;">Visível para todos</div>
                </div>
              </label>
              <label style="flex:1;display:flex;align-items:center;gap:8px;padding:10px 12px;border:1.5px solid #e5e7eb;border-radius:10px;cursor:pointer;">
                <input type="radio" name="visibility" value="members" id="vis-members" style="accent-color:#16a34a;">
                <div>
                  <div style="font-size:12px;font-weight:700;"><i class="bi bi-lock-fill me-1" style="color:#5b21b6;"></i>Gira Interna</div>
                  <div style="font-size:10px;color:#6b7280;">Só membros da casa</div>
                </div>
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" id="btnSubmitEvento" class="btn btn-primary btn-sm">Criar Evento</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalFinanca" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered mx-3">
    <div class="modal-content" style="border-radius:var(--r);">
      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title fw-bold" id="modalFinancaTitle"><i class="bi bi-cash-stack me-2" style="color:var(--p);"></i>Novo Lançamento</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="formFinanca" method="POST" action="{{ route('my-house.finances.store') }}">
        @csrf
        <div class="modal-body pt-3" style="max-height:70vh;overflow-y:auto;">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Tipo *</label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="type" value="credit" id="fcredit" checked>
                <label class="form-check-label text-success fw-semibold small" for="fcredit">Entrada</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="type" value="debit" id="fdebit">
                <label class="form-check-label text-danger fw-semibold small" for="fdebit">Saída</label>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Descrição *</label>
            <input type="text" name="title" class="form-control" required placeholder="Ex: Mensalidade, Vela ritual">
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label small fw-semibold">Valor (R$) *</label>
              <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required placeholder="0,00">
            </div>
            <div class="col-6">
              <label class="form-label small fw-semibold">Status</label>
              <select name="status" class="form-select" id="fstatus">
                <option value="paid">Pago</option>
                <option value="pending">Pendente</option>
                <option value="overdue">Vencido</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label small fw-semibold">Data de vencimento</label>
            <input type="date" name="due_date" class="form-control">
          </div>
          {{-- Escopo: quem paga? --}}
          <div class="mb-3" id="fScopeGroup">
            <label class="form-label small fw-semibold">Cobrar de *</label>
            <div style="display:flex;flex-direction:column;gap:6px;">
              <label style="display:flex;align-items:center;gap:8px;padding:8px 10px;border:1.5px solid #e5e7eb;border-radius:8px;cursor:pointer;">
                <input type="radio" name="scope" value="global" id="fsglobal" checked style="accent-color:#16a34a;" onchange="toggleFinanceScope()">
                <div>
                  <div style="font-size:12px;font-weight:700;">Geral</div>
                  <div style="font-size:10px;color:#6b7280;">Lançamento interno da casa, sem cobrança individual</div>
                </div>
              </label>
              <label style="display:flex;align-items:center;gap:8px;padding:8px 10px;border:1.5px solid #e5e7eb;border-radius:8px;cursor:pointer;">
                <input type="radio" name="scope" value="all_members" id="fsall" style="accent-color:#16a34a;" onchange="toggleFinanceScope()">
                <div>
                  <div style="font-size:12px;font-weight:700;">Todos os membros</div>
                  <div style="font-size:10px;color:#6b7280;">Gera cobrança para cada membro ativo</div>
                </div>
              </label>
              <label style="display:flex;align-items:center;gap:8px;padding:8px 10px;border:1.5px solid #e5e7eb;border-radius:8px;cursor:pointer;">
                <input type="radio" name="scope" value="selected_members" id="fsselect" style="accent-color:#16a34a;" onchange="toggleFinanceScope()">
                <div>
                  <div style="font-size:12px;font-weight:700;">Membros selecionados</div>
                  <div style="font-size:10px;color:#6b7280;">Escolha quais membros devem pagar</div>
                </div>
              </label>
            </div>
          </div>
          {{-- Lista de membros (aparece ao selecionar "selected_members") --}}
          <div class="mb-1" id="fMemberList" style="display:none;">
            <label class="form-label small fw-semibold">Selecionar membros</label>
            <div style="background:#f8f9fa;border-radius:8px;padding:8px;max-height:160px;overflow-y:auto;display:flex;flex-direction:column;gap:4px;">
              @foreach($members->where('pivot.status','active') as $m)
              <label style="display:flex;align-items:center;gap:8px;padding:4px 6px;border-radius:6px;cursor:pointer;">
                <input type="checkbox" name="member_ids[]" value="{{ $m->id }}" style="accent-color:#16a34a;width:16px;height:16px;">
                <img src="{{ $m->avatar_url }}" style="width:24px;height:24px;border-radius:50%;object-fit:cover;"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($m->name) }}&size=24'">
                <span style="font-size:13px;font-weight:600;">{{ $m->name }}</span>
              </label>
              @endforeach
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary btn-sm">Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@endif
@endsection

@push('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('.mh-tab').forEach(function(b) { b.classList.remove('active'); });
    document.querySelectorAll('[id^="tab-"]').forEach(function(p) { p.style.display = 'none'; });
    document.querySelector('.mh-tab[onclick="switchTab(\'' + tab + '\')"]').classList.add('active');
    document.getElementById('tab-' + tab).style.display = 'block';
    history.replaceState(null, '', '?tab=' + tab);
}

// Clique no card de evento navega para a página do evento
document.querySelectorAll('.event-mini').forEach(function(el) {
    el.style.cursor = 'pointer';
    el.addEventListener('click', function(e) {
        if (e.target.closest('button') || e.target.closest('a') || e.target.closest('form')) return;
        window.location.href = '/events/' + el.dataset.id;
    });
});

// Mantém os cabeçalhos de seção (com botões +) visíveis abaixo da barra de tabs sticky
(function() {
    var tabs = document.querySelector('.mh-tabs');
    if (!tabs) return;
    var tabsH = tabs.offsetHeight;
    document.querySelectorAll('[id^="tab-"] .sec-label').forEach(function(el) {
        el.style.position = 'sticky';
        el.style.top      = tabsH + 'px';
        el.style.zIndex   = '49';
        el.style.background = '#fff';
        el.style.borderBottom = '1px solid var(--border-lt)';
    });
})();

function openApproveModal(userId, userName, currentRole, approveUrl) {
    Swal.fire({
        title: 'Aprovar ' + userName,
        html: `
            <p style="font-size:13px;color:#6b7280;margin-bottom:10px;">Selecione a função desta pessoa na casa:</p>
            <select id="swal-approve-role" style="width:100%;padding:8px 10px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;color:#374151;">
                <option value="">-- Selecione --</option>
                <option value="médium" ${currentRole==='médium'?'selected':''}>Médium</option>
                <option value="cambone" ${currentRole==='cambone'?'selected':''}>Cambone</option>
                <option value="dirigente auxiliar" ${currentRole==='dirigente auxiliar'?'selected':''}>Dirigente Auxiliar</option>
            </select>
        `,
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Aprovar',
        cancelButtonText: 'Cancelar',
        preConfirm: function() {
            var role = document.getElementById('swal-approve-role').value;
            if (!role) {
                Swal.showValidationMessage('Selecione a função');
                return false;
            }
            return role;
        },
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = approveUrl;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                <input type="hidden" name="role_membro" value="${result.value}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function changeMemberRole(userId, userName, currentRole, currentRoleMembro, currentEntities, actionUrl, hasCurrentDirigente) {
    var dirigentWarning = (hasCurrentDirigente && currentRole !== 'dirigente')
        ? '<div style="background:#fef3c7;border-radius:8px;padding:8px 12px;margin-top:10px;font-size:11px;color:#92400e;"><i class="bi bi-exclamation-triangle me-1"></i>Já existe um dirigente ativo. Selecionar <strong>Dirigente</strong> enviará uma solicitação para aprovação do admin.</div>'
        : '';

    Swal.fire({
        title: 'Cargo de ' + userName,
        html: `
            <div style="text-align:left;">
                <p style="font-size:12px;color:#6b7280;margin-bottom:14px;">Configure o cargo e a função espiritual deste membro.</p>

                <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">Nível de acesso</label>
                <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:16px;">
                    <label style="display:flex;align-items:center;gap:10px;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:10px;cursor:pointer;">
                        <input type="radio" name="swal-role" value="membro" ${currentRole==='membro'?'checked':''} style="accent-color:#16a34a;width:15px;height:15px;">
                        <div><div style="font-weight:700;font-size:13px;">Membro</div><div style="font-size:11px;color:#6b7280;">Acesso básico à casa</div></div>
                    </label>
                    <label style="display:flex;align-items:center;gap:10px;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:10px;cursor:pointer;">
                        <input type="radio" name="swal-role" value="assistente" ${currentRole==='assistente'?'checked':''} style="accent-color:#16a34a;width:15px;height:15px;">
                        <div><div style="font-weight:700;font-size:13px;">Assistente</div><div style="font-size:11px;color:#6b7280;">Tarefas e check-in</div></div>
                    </label>
                    <label style="display:flex;align-items:center;gap:10px;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:10px;cursor:pointer;">
                        <input type="radio" name="swal-role" value="dirigente auxiliar" ${currentRole==='dirigente auxiliar'?'checked':''} style="accent-color:#16a34a;width:15px;height:15px;">
                        <div><div style="font-weight:700;font-size:13px;">Dirigente Auxiliar</div><div style="font-size:11px;color:#6b7280;">Acesso à gestão, sem ser o responsável</div></div>
                    </label>
                    <label style="display:flex;align-items:center;gap:10px;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:10px;cursor:pointer;">
                        <input type="radio" name="swal-role" value="dirigente" ${currentRole==='dirigente'?'checked':''} style="accent-color:#16a34a;width:15px;height:15px;">
                        <div><div style="font-weight:700;font-size:13px;">Dirigente</div><div style="font-size:11px;color:#6b7280;">Responsável principal da casa</div></div>
                    </label>
                </div>

                <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;">Cargo espiritual</label>
                <select id="swal-role-membro" style="width:100%;padding:8px 10px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;color:#374151;margin-bottom:14px;">
                    <option value="">Nenhum</option>
                    <option value="médium" ${currentRoleMembro==='médium'?'selected':''}>Médium</option>
                    <option value="cambone" ${currentRoleMembro==='cambone'?'selected':''}>Cambone</option>
                </select>

                <label style="font-size:12px;font-weight:700;color:#374151;display:block;margin-bottom:6px;"><i class="bi bi-stars" style="color:#f59e0b;"></i> Entidades / Orixás que trabalha</label>
                <input type="text" id="swal-entities" value="${currentEntities}"
                    placeholder="Ex: Oxalá, Iemanjá, Ogum..."
                    style="width:100%;padding:8px 10px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;color:#374151;">
                <div style="font-size:10px;color:#9ca3af;margin-top:4px;">Separe por vírgula. Aparece na carteirinha do membro.</div>
                ${dirigentWarning}
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="bi bi-check2 me-1"></i>Confirmar alteração',
        cancelButtonText: 'Cancelar',
        preConfirm: function() {
            var roleEl = document.querySelector('input[name="swal-role"]:checked');
            if (!roleEl) { Swal.showValidationMessage('Selecione o nível de acesso'); return false; }
            return { role: roleEl.value, role_membro: document.getElementById('swal-role-membro').value, entities: document.getElementById('swal-entities').value.trim() };
        }
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = actionUrl;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
                <input type="hidden" name="role" value="${result.value.role}">
                <input type="hidden" name="role_membro" value="${result.value.role_membro}">
                <input type="hidden" name="entities" value="${result.value.entities}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function confirmReject(name, form) {
    Swal.fire({
        title: 'Rejeitar solicitação?',
        text: name + ' será notificado(a) da rejeição.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Remover',
        cancelButtonText: 'Cancelar'
    }).then(function(r) { if (r.isConfirmed) form.submit(); });
}

function confirmCancel(name, form) {
    Swal.fire({
        title: 'Cancelar evento?',
        text: '"' + name + '" será marcado como cancelado.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Cancelar evento',
        cancelButtonText: 'Voltar'
    }).then(function(r) { if (r.isConfirmed) form.submit(); });
}

// ── Filtro de Tarefas ──
function filterTasks(status) {
    document.querySelectorAll('[id^="tf-"]').forEach(function(b) { b.classList.remove('active'); });
    var idMap = {'':'tf-all','pending':'tf-pending','in_progress':'tf-progress','completed':'tf-done','approved':'tf-approved'};
    var btn = document.getElementById(idMap[status] || 'tf-all');
    if (btn) btn.classList.add('active');
    var blocks = document.querySelectorAll('#tab-tarefas .task-block');
    var visible = 0;
    blocks.forEach(function(block) {
        var rows = block.querySelectorAll('.task-member-row');
        var show = !status;
        if (status) {
            rows.forEach(function(r) { if (r.dataset.status === status) show = true; });
        }
        block.style.display = show ? '' : 'none';
        // Se filtrou e tem correspondência, expande o bloco
        if (show && status) block.classList.add('open');
        if (show) visible++;
    });
    var count = document.getElementById('task-count');
    if (count) count.textContent = '(' + visible + ')';
}

// ── Confirmação de toggle de pagamento ──
function confirmTaskAction(e, form, title, html, confirmText, color) {
    e.preventDefault();
    Swal.fire({
        title: title,
        html: html,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: color,
        reverseButtons: true,
    }).then(function(r) {
        if (r.isConfirmed) form.submit();
    });
    return false;
}

function confirmTogglePayment(e, form, name, currentStatus) {
    e.preventDefault();
    var action = currentStatus === 'paid' ? 'marcar como Pendente' : 'confirmar pagamento';
    Swal.fire({
        title: currentStatus === 'paid' ? 'Estornar pagamento?' : 'Confirmar pagamento?',
        html: '<strong>' + name + '</strong><br><span style="font-size:13px;color:#6b7280;">Deseja ' + action + ' para este membro?</span>',
        icon: currentStatus === 'paid' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: currentStatus === 'paid' ? 'Sim, estornar' : 'Sim, confirmar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: currentStatus === 'paid' ? '#dc2626' : '#16a34a',
        reverseButtons: true,
    }).then(function(r) {
        if (r.isConfirmed) form.submit();
    });
    return false;
}

// ── Filtro de Finanças ──
function filterFinances(filter) {
    document.querySelectorAll('#tab-financeiro .filter-chip').forEach(function(b) { b.classList.remove('active'); });
    event.currentTarget.classList.add('active');
    document.querySelectorAll('#tab-financeiro .finance-row').forEach(function(row) {
        if (!filter) { row.style.display = ''; return; }
        var matchType   = filter === 'credit' || filter === 'debit';
        var matchStatus = filter === 'pending' || filter === 'overdue';
        var show = (matchType && row.dataset.type === filter) || (matchStatus && row.dataset.status === filter);
        row.style.display = show ? '' : 'none';
    });
}

// ── Inicializa contagem de tarefas ──
$(function() {
    var count = document.querySelectorAll('#tab-tarefas .task-block').length;
    var el = document.getElementById('task-count');
    if (el) el.textContent = '(' + count + ')';
});

// ── Edit Tarefa ──
function editTask(row) {
    // row pode ser .task-member-row ou .task-block — pega o primeiro membro
    var src = row.dataset.id ? row : row.querySelector('.task-member-row');
    if (!src) return;
    $('#formTarefa').attr('action', '/my-house/tasks/' + src.dataset.id + '/update');
    $('#modalTarefaTitle').html('<i class="bi bi-pencil-square me-2" style="color:var(--p);"></i>Editar Tarefa');
    $('#btnSubmitTarefa').text('Salvar');
    $('#formTarefa [name=title]').val(src.dataset.title);
    $('#formTarefa [name=description]').val(src.dataset.description);
    $('#formTarefa [name=assigned_to]').val(src.dataset.assigned);
    $('#formTarefa [name=points]').val(src.dataset.points);
    $('#formTarefa [name=due_date]').val(src.dataset.due);
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTarefa')).show();
}

$('#modalTarefa').on('hidden.bs.modal', function() {
    $('#formTarefa').attr('action', '{{ route("my-house.tasks.store") }}');
    $('#modalTarefaTitle').html('<i class="bi bi-check2-square me-2" style="color:var(--p);"></i>Nova Tarefa');
    $('#btnSubmitTarefa').text('Criar Tarefa');
    $('#formTarefa')[0].reset();
});

// ── Preview de banner do evento ──
function previewEventBanner(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#eventoBannerImg').attr('src', e.target.result);
            $('#eventoBannerPreview').show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// ── Edit Evento ──
function editEvent(row) {
    $('#formEvento').attr('action', '/my-house/events/' + row.dataset.id + '/update');
    $('#modalEventoTitle').html('<i class="bi bi-pencil-square me-2" style="color:var(--p);"></i>Editar Evento');
    $('#btnSubmitEvento').text('Salvar');
    $('#formEvento [name=name]').val(row.dataset.name);
    $('#formEvento [name=starts_at]').val(row.dataset.starts);
    $('#formEvento [name=ends_at]').val(row.dataset.ends || '');
    $('#formEvento [name=price]').val(row.dataset.price);
    $('#formEvento [name=capacity]').val(row.dataset.capacity);
    $('#formEvento [name=address]').val(row.dataset.address || '');
    $('#formEvento [name=description]').val(row.dataset.description);
    $('#formEvento [name=rules]').val(row.dataset.rules || '');
    $('#formEvento [name=recommendations]').val(row.dataset.recommendations || '');
    var vis = row.dataset.visibility || 'public';
    $('#formEvento [name=visibility][value=' + vis + ']').prop('checked', true);
    // Mostra banner atual se existir
    if (row.dataset.banner) {
        $('#eventoBannerImg').attr('src', row.dataset.banner);
        $('#eventoBannerPreview').show();
    } else {
        $('#eventoBannerPreview').hide();
    }
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEvento')).show();
}

$('#modalEvento').on('hidden.bs.modal', function() {
    $('#formEvento').attr('action', '{{ route("my-house.events.store") }}');
    $('#modalEventoTitle').html('<i class="bi bi-calendar-plus me-2" style="color:var(--p);"></i>Novo Evento');
    $('#btnSubmitEvento').text('Criar Evento');
    $('#formEvento')[0].reset();
    $('#eventoBannerPreview').hide();
});

// ── Edit Financeiro ──
function editFinance(row) {
    $('#formFinanca').attr('action', '/my-house/finances/' + row.dataset.id + '/update');
    $('#modalFinancaTitle').html('<i class="bi bi-pencil-square me-2" style="color:var(--p);"></i>Editar Lançamento');
    $('#formFinanca [name=type][value=' + row.dataset.type + ']').prop('checked', true);
    $('#formFinanca [name=title]').val(row.dataset.title);
    $('#formFinanca [name=amount]').val(row.dataset.amount);
    $('#formFinanca [name=status]').val(row.dataset.status);
    $('#formFinanca [name=due_date]').val(row.dataset.due);
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalFinanca')).show();
}

$('#modalFinanca').on('hidden.bs.modal', function() {
    $('#formFinanca').attr('action', '{{ route("my-house.finances.store") }}');
    $('#modalFinancaTitle').html('<i class="bi bi-cash-stack me-2" style="color:var(--p);"></i>Novo Lançamento');
    $('#formFinanca')[0].reset();
    $('#fMemberList').hide();
    $('#fstatus').prop('disabled', false);
    $('#fcredit').prop('checked', true);
});

// ── Escopo de finanças ──
function toggleFinanceScope() {
    var scope = $('input[name=scope]:checked').val();
    $('#fMemberList').toggle(scope === 'selected_members');
    // Se cobrança de membros, força status pending
    if (scope === 'all_members' || scope === 'selected_members') {
        $('#fstatus').val('pending').prop('disabled', true);
    } else {
        $('#fstatus').prop('disabled', false);
    }
}

// ── Estudos ──
function toggleEstudoFields() {
    var tipo = $('#estudoTipo').val();
    $('#estudo-url-field').toggle(tipo === 'video' || tipo === 'audio');
    $('#estudo-pdf-field').toggle(tipo === 'pdf');
}

function editStudy(row) {
    $('#formEstudo').attr('action', '/my-house/studies/' + row.dataset.id + '/update');
    $('#modalEstudoTitle').html('<i class="bi bi-pencil-square me-2" style="color:var(--p);"></i>Editar Material');
    $('#btnSubmitEstudo').html('<i class="bi bi-floppy me-1"></i>Salvar');
    $('#formEstudo [name=title]').val(row.dataset.title);
    $('#formEstudo [name=description]').val(row.dataset.description);
    $('#formEstudo [name=content_type]').val(row.dataset.type);
    $('#formEstudo [name=content_url]').val(row.dataset.url);
    $('#formEstudo [name=content_body]').val(row.dataset.body);
    $('#formEstudo [name=category]').val(row.dataset.category);
    $('#formEstudo [name=points]').val(row.dataset.points);
    $('#estudoIsPublic').prop('checked', row.dataset.ispublic === '1');
    $('#estudoPublished').prop('checked', row.dataset.ispublished === '1');
    // Mostrar PDF atual se tiver
    var pdfFile = row.dataset.pdf || '';
    if (pdfFile && row.dataset.type === 'pdf') {
        $('#estudoPdfAtual').show().find('span').text(pdfFile.split('/').pop());
    } else {
        $('#estudoPdfAtual').hide();
    }
    toggleEstudoFields();
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEstudo')).show();
}

$('#modalEstudo').on('hidden.bs.modal', function() {
    $('#formEstudo').attr('action', '{{ route("my-house.studies.store") }}');
    $('#modalEstudoTitle').html('<i class="bi bi-book me-2" style="color:var(--p);"></i>Novo Material');
    $('#btnSubmitEstudo').html('<i class="bi bi-floppy me-1"></i>Salvar');
    $('#formEstudo')[0].reset();
    $('#estudo-url-field').hide();
    $('#estudo-pdf-field').hide();
    $('#estudoPdfAtual').hide();
});

function filterStudies(filter) {
    document.querySelectorAll('#tab-estudos .filter-chip').forEach(function(b) { b.classList.remove('active'); });
    event.currentTarget.classList.add('active');
    document.querySelectorAll('#tab-estudos .study-row').forEach(function(row) {
        var show = true;
        if (filter === 'published') show = row.dataset.published === '1';
        else if (filter === 'draft')    show = row.dataset.published === '0';
        else if (filter === 'public')   show = row.dataset.public === '1';
        else if (filter === 'members')  show = row.dataset.public === '0';
        row.style.display = show ? '' : 'none';
    });
}

$(function() { toggleEstudoFields(); });

// ── Check-in do membro ──
function openCheckinConfirm() {
    @php $todayEvent = $house ? $house->upcomingEvents->filter(fn($e)=>$e->starts_at->isToday())->first() : null; @endphp
    @if($todayEvent)
    var eventName = '{{ addslashes($todayEvent->name) }}';
    var eventTime = '{{ $todayEvent->starts_at->format("H:i") }}';
    Swal.fire({
        title: 'Confirmar Check-in',
        html: '<div style="font-size:15px;font-weight:700;margin-bottom:6px;">' + eventName + '</div>'
            + '<div style="font-size:13px;color:#6b7280;"><i class="bi bi-clock me-1"></i>' + eventTime + '</div>'
            + '<div style="margin-top:10px;font-size:13px;">Confirmar sua presença neste evento?</div>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-check-circle me-1"></i>Sim, fazer check-in',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#16a34a',
        reverseButtons: true,
    }).then(function(r) {
        if (r.isConfirmed) {
            document.getElementById('formCheckin{{ $todayEvent->id }}').submit();
        }
    });
    @else
    Swal.fire({
        icon: 'info',
        title: 'Sem evento hoje',
        text: 'Não há gira ou evento agendado para hoje nesta casa.',
        confirmButtonColor: '#16a34a',
    });
    @endif
}

// ── Notificação/Mensagem ──
function openNotifyModal(target, userId, name) {
    $('#notifyTarget').val(target);
    $('#notifyUserId').val(userId || '');
    if (target === 'individual') {
        $('#notifyRecipientBadge').html('<i class="bi bi-person-fill"></i><span>' + name + '</span>').css('background', '#ede9fe').css('color', '#5b21b6');
        $('#modalNotifyTitle').html('<i class="bi bi-chat-dots me-2" style="color:#5b21b6;"></i>Mensagem para ' + name);
    } else {
        $('#notifyRecipientBadge').html('<i class="bi bi-people-fill"></i><span>Todos os membros</span>').css('background', '#dbeafe').css('color', '#1e40af');
        $('#modalNotifyTitle').html('<i class="bi bi-megaphone me-2" style="color:#1e40af;"></i>Enviar Mensagem');
    }
    $('#formNotify')[0].reset();
    $('#notifyTarget').val(target);
    $('#notifyUserId').val(userId || '');
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalNotify')).show();
}
</script>
@endpush
