@extends('layouts.app')
@section('title', 'Admin — Aruanda Digital')

@push('styles')
<style>
    .admin-hdr {
        background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
        padding: 18px 16px;
        color: #fff;
    }
    .admin-hdr h6 { font-size: 17px; font-weight: 800; margin: 0 0 2px; }
    .admin-hdr small { font-size: 12px; opacity: .7; }

    .stat-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 14px;
    }
    .stat-card {
        background: var(--surface);
        border-radius: var(--r);
        padding: 16px 12px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }
    .stat-card .num { font-size: 28px; font-weight: 800; color: var(--p); line-height: 1; }
    .stat-card .lbl { font-size: 11px; color: var(--txt-3); margin-top: 4px; font-weight: 600; }

    .admin-sec {
        padding: 14px 16px 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .admin-sec-title { font-size: 14px; font-weight: 800; color: var(--txt); }

    .house-pending-card {
        margin: 0 14px 10px;
        background: var(--surface);
        border-radius: var(--r);
        box-shadow: var(--shadow-sm);
        border-left: 4px solid #f59e0b;
        overflow: hidden;
    }
    .house-pending-body { padding: 12px 14px; }
    .house-pending-name { font-size: 14px; font-weight: 700; color: var(--txt); margin-bottom: 3px; }
    .house-pending-meta { font-size: 12px; color: var(--txt-3); }
    .house-pending-actions {
        display: flex;
        gap: 8px;
        padding: 10px 14px;
        background: var(--bg);
        border-top: 1px solid var(--border-lt);
    }

    .user-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        border-bottom: 1px solid var(--border-lt);
        background: var(--surface);
    }
    .user-row img {
        width: 38px; height: 38px;
        border-radius: 50%;
        object-fit: cover;
        background: var(--p-lt);
        flex-shrink: 0;
    }
    .user-name  { font-size: 13px; font-weight: 700; color: var(--txt); }
    .user-email { font-size: 11px; color: var(--txt-3); }

    select.role-select {
        font-size: 11px; font-weight: 700;
        padding: 4px 8px;
        border: 1.5px solid var(--border);
        border-radius: 6px;
        background: var(--surface);
        color: var(--txt-2);
        cursor: pointer;
    }

    .admin-search {
        margin: 10px 14px 6px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--bg);
        border-radius: 10px;
        padding: 10px 14px;
    }
    .admin-search input {
        background: transparent;
        border: none;
        font-size: 14px;
        flex: 1;
        color: var(--txt);
        outline: none;
    }
    .admin-search i { color: var(--txt-4); font-size: 15px; }
</style>
@endpush

@section('content')

<div class="admin-hdr">
    <h6><i class="bi bi-shield-lock me-2"></i>Painel Administrativo</h6>
    <small>Gestão global da plataforma Aruanda Digital</small>
</div>

{{-- Stats --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="num">{{ $stats['users'] }}</div>
        <div class="lbl">Usuários</div>
    </div>
    <div class="stat-card">
        <div class="num">{{ $stats['houses'] }}</div>
        <div class="lbl">Casas Ativas</div>
    </div>
    <div class="stat-card">
        <div class="num" style="color:#f59e0b;">{{ $stats['houses_pending'] }}</div>
        <div class="lbl">Casas Pendentes</div>
    </div>
    <div class="stat-card">
        <div class="num">{{ $stats['lojas'] }}</div>
        <div class="lbl">Vendedores</div>
    </div>
    @if(isset($stats['events']))
    <div class="stat-card">
        <div class="num">{{ $stats['events'] }}</div>
        <div class="lbl">Eventos Ativos</div>
    </div>
    @endif
    @if(isset($stats['members']))
    <div class="stat-card">
        <div class="num">{{ $stats['members'] }}</div>
        <div class="lbl">Membros Ativos</div>
    </div>
    @endif
</div>

{{-- Transferências de Dirigência Pendentes --}}
@if(isset($pendingTransfers) && $pendingTransfers->count() > 0)
<div class="mb-4" style="padding:0 14px;">
    <h6 class="fw-bold mb-3" style="padding-top:14px;"><i class="bi bi-arrow-left-right me-2 text-warning"></i>Transferências de Dirigência Pendentes</h6>
    @foreach($pendingTransfers as $house)
        @foreach($house->members->where('pivot.status','pending_transfer') as $candidate)
        <div style="background:#fff;border-radius:10px;padding:14px 16px;margin-bottom:8px;display:flex;align-items:center;gap:12px;box-shadow:0 1px 4px rgba(0,0,0,.08);">
            <div style="flex:1;">
                <div style="font-size:13px;font-weight:700;">{{ $house->name }}</div>
                <div style="font-size:12px;color:#6b7280;">
                    <i class="bi bi-person-fill me-1"></i>{{ $candidate->name }} solicita ser dirigente
                </div>
            </div>
            <form method="POST" action="{{ route('admin.transfer.approve', [$house->id, $candidate->id]) }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-success" style="font-size:12px;">Aprovar</button>
            </form>
            <form method="POST" action="{{ route('admin.transfer.reject', [$house->id, $candidate->id]) }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size:12px;">Rejeitar</button>
            </form>
        </div>
        @endforeach
    @endforeach
</div>
@endif

{{-- Casas pendentes --}}
@if ($pendingHouses->isNotEmpty())
<div class="admin-sec">
    <span class="admin-sec-title">
        <i class="bi bi-hourglass-split me-1" style="color:#f59e0b;"></i>
        Casas aguardando aprovação
        <span style="background:#fef9c3;color:#92400e;font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;margin-left:4px;">{{ $pendingHouses->count() }}</span>
    </span>
</div>

@foreach ($pendingHouses as $house)
<div class="house-pending-card">
    <div class="house-pending-body">
        <div class="house-pending-name">{{ $house->name }}</div>
        <div class="house-pending-meta">
            <i class="bi bi-geo-alt" style="font-size:10px;"></i>
            {{ $house->city }}{{ $house->state ? '/'.$house->state : '' }}
            &nbsp;·&nbsp;{{ $house->type_name }}
        </div>
        @if ($house->owner)
        <div class="house-pending-meta" style="margin-top:2px;">
            <i class="bi bi-person" style="font-size:10px;"></i>
            {{ $house->owner->name }}
            @if($house->owner->cpf)
                &nbsp;·&nbsp; CPF: {{ $house->owner->cpf }}
            @endif
        </div>
        @endif
        <div class="house-pending-meta" style="margin-top:3px;">
            <i class="bi bi-clock" style="font-size:10px;"></i>
            Cadastrado {{ $house->created_at->diffForHumans() }}
        </div>
    </div>
    <div class="house-pending-actions">
        <form method="POST" action="{{ route('admin.houses.approve', $house->id) }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-sm btn-success" style="font-size:12px;border-radius:6px;">
                <i class="bi bi-check2-circle me-1"></i>Aprovar
            </button>
        </form>
        <button type="button" class="btn btn-sm btn-outline-danger" style="font-size:12px;border-radius:6px;"
                onclick="rejectHouse({{ $house->id }}, '{{ addslashes($house->name) }}')">
            <i class="bi bi-x-circle me-1"></i>Rejeitar
        </button>
        <a href="{{ route('houses.show', $house->id) }}" class="btn btn-sm btn-outline-secondary ms-auto" style="font-size:12px;border-radius:6px;">
            <i class="bi bi-eye"></i> Ver
        </a>
    </div>
</div>
@endforeach

@else
<div class="admin-sec">
    <span class="admin-sec-title" style="color:var(--txt-3);">
        <i class="bi bi-check2-circle me-1" style="color:var(--p);"></i>
        Nenhuma casa pendente de aprovação
    </span>
</div>
@endif

{{-- Usuários --}}
<div class="admin-sec">
    <span class="admin-sec-title">
        <i class="bi bi-people me-1" style="color:var(--p);"></i>
        Usuários recentes
    </span>
</div>

{{-- Busca --}}
<div class="admin-search">
    <i class="bi bi-search"></i>
    <input type="text" id="userSearch" placeholder="Buscar por nome ou e-mail..." oninput="searchUsers(this.value)">
</div>

@foreach ($recentUsers as $u)
<div class="user-row" data-name="{{ strtolower($u->name) }}" data-email="{{ strtolower($u->email) }}">
    <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}"
         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(substr($u->name,0,1)) }}&background=dcfce7&color=166534&size=38'">
    <div style="flex:1;min-width:0;">
        <div class="user-name">{{ $u->name }}</div>
        <div class="user-email">{{ $u->email }}</div>
        @if($u->cpf || $u->birth_date)
        <div style="font-size:10px;color:var(--txt-4);margin-top:1px;">
            @if($u->cpf) CPF: {{ $u->cpf }} @endif
            @if($u->birth_date) · {{ \Carbon\Carbon::parse($u->birth_date)->format('d/m/Y') }} @endif
        </div>
        @endif
    </div>
    <form method="POST" action="{{ route('admin.users.role', $u->id) }}" style="margin:0;">
        @csrf
        <select name="role" class="role-select" onchange="confirmRoleChange(this)">
            @foreach (['visitante','membro','assistente','dirigente','loja','loja_master','moderador','admin'] as $r)
            <option value="{{ $r }}" {{ $u->role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
            @endforeach
        </select>
    </form>
</div>
@endforeach

<div style="height:24px;"></div>
@endsection

@push('scripts')
<script>
function rejectHouse(id, name) {
    Swal.fire({
        title: 'Rejeitar "' + name + '"?',
        html: `
            <p style="font-size:13px;color:#6b7280;margin-bottom:10px;">Informe o motivo da rejeição. O dirigente será notificado.</p>
            <textarea id="swal-reject-reason" rows="3" placeholder="Ex: Documentação incompleta, endereço inválido..."
                style="width:100%;padding:8px 10px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:none;"></textarea>
        `,
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Confirmar rejeição',
        cancelButtonText: 'Cancelar',
        preConfirm: function() {
            var reason = document.getElementById('swal-reject-reason').value.trim();
            if (!reason) {
                Swal.showValidationMessage('Informe o motivo da rejeição');
                return false;
            }
            return reason;
        }
    }).then(function(result) {
        if (!result.isConfirmed) return;
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/houses/' + id + '/reject';
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').content}">
            <input type="hidden" name="reason" value="${result.value}">
        `;
        document.body.appendChild(form);
        form.submit();
    });
}

function confirmRoleChange(select) {
    var newRole = select.value;
    var form    = select.closest('form');
    var name    = select.closest('.user-row').querySelector('.user-name').textContent;
    Swal.fire({
        title: 'Alterar cargo?',
        text: name + ' passará a ter o papel: ' + newRole,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
    }).then(function(r) {
        if (r.isConfirmed) {
            form.submit();
        } else {
            // Reverte select
            var original = form.querySelector('select').dataset.original;
            if (original) select.value = original;
        }
    });
}

// Salva valor original ao focar
document.querySelectorAll('.role-select').forEach(function(s) {
    s.addEventListener('focus', function() { this.dataset.original = this.value; });
});

function searchUsers(query) {
    var q = query.toLowerCase();
    document.querySelectorAll('.user-row').forEach(function(row) {
        var match = !q || row.dataset.name.includes(q) || row.dataset.email.includes(q);
        row.style.display = match ? '' : 'none';
    });
}
</script>
@endpush
