@extends('layouts.app')
@section('title', 'Notificações — Aruanda Digital')

@push('styles')
<style>
    .notif-row {
        display:flex;align-items:flex-start;gap:12px;
        padding:14px 16px;border-bottom:1px solid var(--border-lt);
        background:#fff;transition:background .15s;
        position:relative;cursor:pointer;
    }
    .notif-row.unread { background:var(--p-xl); }
    .notif-row:active { background:var(--bg); }

    .notif-icon-wrap {
        width:42px;height:42px;border-radius:50%;
        background:var(--p-lt);flex-shrink:0;
        display:flex;align-items:center;justify-content:center;
        font-size:18px;color:var(--p);
    }
    .notif-row.unread .notif-icon-wrap { background:var(--p);color:#fff; }

    .notif-body { flex:1;min-width:0; }
    .notif-title {
        font-size:14px;font-weight:700;color:var(--txt);
        margin-bottom:3px;line-height:1.3;
    }
    .notif-row.unread .notif-title { color:var(--p-dk); }
    .notif-text {
        font-size:12px;color:var(--txt-3);line-height:1.4;
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
    }
    .notif-time {
        font-size:10px;color:var(--txt-4);margin-top:4px;
        display:flex;align-items:center;gap:3px;
    }
    .notif-dot {
        width:8px;height:8px;border-radius:50%;
        background:var(--p);flex-shrink:0;margin-top:5px;
    }
    .notif-row:not(.unread) .notif-dot { display:none; }

    /* Mark all read btn */
    .mark-all-btn {
        font-size:12px;font-weight:700;color:var(--p);
        background:none;border:none;cursor:pointer;
        padding:4px 8px;
    }
</style>
@endpush

@section('content')

<div class="page-hdr">
    <div>
        <h6 style="margin:0;"><i class="bi bi-bell me-2" style="color:var(--p);"></i>Notificações</h6>
        @php $unread = $notifications->where('read_at', null)->count(); @endphp
        @if($unread > 0)
        <div class="t-muted" style="font-size:11px;margin-top:1px;">{{ $unread }} não lida(s)</div>
        @endif
    </div>
    @if($unread > 0)
    <form method="POST" action="{{ route('notifications.read-all') }}">
        @csrf
        @method('PUT')
        <button type="submit" class="mark-all-btn">Marcar todas</button>
    </form>
    @endif
</div>

@forelse($notifications as $notification)
@php
    $isUnread = !$notification->read_at;
    $icons = [
        'event'   => 'bi-calendar-event',
        'house'   => 'bi-building',
        'store'   => 'bi-bag',
        'system'  => 'bi-bell',
        'points'  => 'bi-star',
    ];
    $icon = $icons[$notification->type ?? 'system'] ?? 'bi-bell';
@endphp
<div class="notif-row {{ $isUnread ? 'unread' : '' }}"
     onclick="readNotif('{{ $notification->id }}', this)">
    <div class="notif-icon-wrap">
        <i class="bi {{ $icon }}"></i>
    </div>
    <div class="notif-body">
        <div class="notif-title">{{ $notification->title }}</div>
        @if($notification->body)
        <div class="notif-text">{{ $notification->body }}</div>
        @endif
        <div class="notif-time">
            <i class="bi bi-clock" style="font-size:9px;"></i>
            {{ $notification->created_at->diffForHumans() }}
        </div>
    </div>
    @if($isUnread)
    <div class="notif-dot"></div>
    @endif
</div>
@empty
<div class="empty-state" style="padding-top:60px;">
    <i class="bi bi-bell-slash"></i>
    <p>Nenhuma notificação ainda.</p>
</div>
@endforelse

@if($notifications->hasPages())
<div style="padding:16px;">{{ $notifications->links('pagination::bootstrap-5') }}</div>
@endif

<div style="height:24px;"></div>
@endsection

@push('scripts')
<script>
function readNotif(id, el) {
    if (!$(el).hasClass('unread')) return;
    $.post('{{ url("/notifications") }}/' + id + '/read', { _token: '{{ csrf_token() }}' })
    .always(function () {
        $(el).removeClass('unread');
        $(el).find('.notif-dot').remove();
        $(el).find('.notif-icon-wrap').css({'background':'var(--p-lt)', 'color':'var(--p)'});
    });
}
</script>
@endpush
