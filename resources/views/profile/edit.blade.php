@extends('layouts.app')
@section('title', 'Editar Perfil — Aruanda Digital')

@push('styles')
<style>
    .edit-wrap { padding:16px; }
    /* Tab switcher */
    .profile-tabs { display:flex; gap:8px; margin-bottom:20px; background:#f3f4f6; border-radius:10px; padding:4px; }
    .profile-tab-btn { flex:1; padding:9px 0; border:none; border-radius:8px; background:transparent; font-size:13px; font-weight:600; color:#6b7280; cursor:pointer; transition:all .2s; }
    .profile-tab-btn.active { background:#fff; color:#16a34a; box-shadow:0 1px 4px rgba(0,0,0,.12); }
    /* Avatar */
    .avatar-wrap { text-align:center; margin-bottom:20px; position:relative; display:inline-block; }
    .avatar-wrap img { width:88px; height:88px; border-radius:50%; object-fit:cover; border:3px solid #e5e7eb; background:#dcfce7; }
    .avatar-btn { position:absolute; bottom:0; right:0; width:28px; height:28px; border-radius:50%; background:#16a34a; color:#fff; border:none; display:flex; align-items:center; justify-content:center; font-size:14px; cursor:pointer; }
    /* Form */
    .form-label { font-size:13px; font-weight:600; color:#374151; margin-bottom:5px; }
    .form-control, .form-select { border-radius:8px; font-size:14px; }
    .form-control:focus, .form-select:focus { border-color:#16a34a; box-shadow:0 0 0 .15rem rgba(22,163,74,.15); }
    .save-btn { display:block; width:100%; padding:13px; background:#16a34a; color:#fff; border:none; border-radius:10px; font-size:15px; font-weight:700; margin-top:20px; }
    .save-btn:hover { background:#166534; }
    .section-label { font-size:12px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:.5px; margin:20px 0 12px; border-bottom:1px solid #e5e7eb; padding-bottom:6px; }
    .house-section { background:#f9fafb; border-radius:10px; padding:14px; margin-bottom:14px; }
    .house-section .section-label { margin-top:0; }
</style>
@endpush

@section('content')
<div class="edit-wrap">

    @php $hasCasa = isset($house) && $house; @endphp

    {{-- Tabs só aparecem se o usuário tem casa --}}
    @if($hasCasa)
    <div class="profile-tabs">
        <button class="profile-tab-btn active" id="btn-tab-perfil" onclick="switchProfileTab('perfil')">
            <i class="bi bi-person me-1"></i>Meu Perfil
        </button>
        <button class="profile-tab-btn" id="btn-tab-casa" onclick="switchProfileTab('casa')">
            <i class="bi bi-house me-1"></i>Página da Casa
        </button>
    </div>
    @endif

    {{-- ===================== TAB 1: MEU PERFIL ===================== --}}
    <div id="tab-perfil">
        <div class="text-center">
            <div class="avatar-wrap d-inline-block">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" id="avatarPreview"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=dcfce7&color=166534&size=88'">
                <button class="avatar-btn" type="button" onclick="$('#avatarFile').click()">
                    <i class="bi bi-camera"></i>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <input type="file" id="avatarFile" name="avatar" accept="image/*" class="d-none">

            <div class="section-label">Dados Pessoais</div>

            <div class="mb-3">
                <label class="form-label">Nome completo *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Telefone</label>
                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone', $user->phone) }}" placeholder="(11) 90000-0000">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Data de nascimento</label>
                <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror"
                       value="{{ old('birth_date', $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d') : '') }}">
                @error('birth_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="section-label">Segurança</div>

            <div class="mb-3">
                <label class="form-label">Nova senha <span class="text-muted fw-normal">(deixe em branco para manter)</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar nova senha</label>
                <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
            </div>

            <button type="submit" class="save-btn">
                <i class="bi bi-check-lg me-2"></i>Salvar Alterações
            </button>
        </form>
    </div>

    {{-- ===================== TAB 2: PÁGINA DA CASA ===================== --}}
    @if($hasCasa)
    <div id="tab-casa" style="display:none;">
        @if(session('house_success'))
            <div class="alert alert-success py-2 px-3 mb-3" role="alert">{{ session('house_success') }}</div>
        @endif

        <form method="POST" action="{{ route('houses.update', $house->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <input type="hidden" name="redirect_back" value="profile">

            {{-- Imagens da Casa --}}
            <div class="house-section">
                <div class="section-label"><i class="bi bi-image me-1"></i>Imagens</div>

                {{-- Banner (cover) --}}
                <div class="mb-3">
                    <label class="form-label">Banner / Capa</label>
                    <div style="position:relative;width:100%;height:110px;border-radius:12px;overflow:hidden;background:#e5e7eb;margin-bottom:8px;cursor:pointer;" onclick="$('#coverFile').click()">
                        <img id="coverPreview"
                             src="{{ $house->cover_image ? asset('storage/'.$house->cover_image) : '' }}"
                             alt="Capa"
                             style="width:100%;height:100%;object-fit:cover;display:{{ $house->cover_image ? 'block' : 'none' }};">
                        <div id="coverPlaceholder" style="display:{{ $house->cover_image ? 'none' : 'flex' }};position:absolute;inset:0;flex-direction:column;align-items:center;justify-content:center;color:#9ca3af;">
                            <i class="bi bi-image" style="font-size:28px;"></i>
                            <span style="font-size:12px;margin-top:4px;">Toque para escolher</span>
                        </div>
                        <div style="position:absolute;bottom:6px;right:6px;background:rgba(0,0,0,.45);color:#fff;border-radius:6px;padding:3px 8px;font-size:11px;font-weight:600;">
                            <i class="bi bi-camera me-1"></i>Alterar
                        </div>
                    </div>
                    <input type="file" id="coverFile" name="cover_image" accept="image/*" class="d-none">
                    <div style="font-size:11px;color:#9ca3af;">Recomendado: 1200×400 px · máx. 4 MB</div>
                </div>

                {{-- Logo --}}
                <div class="mb-1">
                    <label class="form-label">Logo / Foto de perfil da casa</label>
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="position:relative;flex-shrink:0;cursor:pointer;" onclick="$('#logoFile').click()">
                            <img id="logoPreview"
                                 src="{{ $house->logo_image ? asset('storage/'.$house->logo_image) : 'https://ui-avatars.com/api/?name='.urlencode($house->name).'&background=dcfce7&color=166534&size=80' }}"
                                 style="width:72px;height:72px;border-radius:14px;object-fit:cover;border:2px solid #e5e7eb;">
                            <span style="position:absolute;bottom:0;right:0;width:22px;height:22px;border-radius:50%;background:#16a34a;color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;">
                                <i class="bi bi-camera"></i>
                            </span>
                        </div>
                        <div style="font-size:12px;color:#6b7280;line-height:1.5;">
                            Aparece no mapa e no perfil da casa.<br>
                            Recomendado: 300×300 px · máx. 2 MB
                        </div>
                    </div>
                    <input type="file" id="logoFile" name="logo_image" accept="image/*" class="d-none">
                </div>
            </div>

            {{-- Identidade --}}
            <div class="house-section">
                <div class="section-label"><i class="bi bi-info-circle me-1"></i>Identidade</div>

                <div class="mb-3">
                    <label class="form-label">Nome da Casa *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $house->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tipo</label>
                    <select name="type" class="form-select">
                        @foreach(['umbanda'=>'Umbanda','candomble'=>'Candomblé','misto'=>'Misto','outro'=>'Outro'] as $val=>$lbl)
                            <option value="{{ $val }}" @selected(old('type', $house->type) == $val)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Data de fundação</label>
                    <input type="date" name="foundation_date" class="form-control"
                           value="{{ old('foundation_date', $house->foundation_date ? \Carbon\Carbon::parse($house->foundation_date)->format('Y-m-d') : '') }}">
                </div>
            </div>

            {{-- Descrição --}}
            <div class="house-section">
                <div class="section-label"><i class="bi bi-file-text me-1"></i>Descrição</div>

                <div class="mb-3">
                    <label class="form-label">Sobre a casa</label>
                    <textarea name="description" class="form-control" rows="3" maxlength="3000"
                              placeholder="Apresente a casa para novos visitantes...">{{ old('description', $house->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Linha espiritual</label>
                    <textarea name="spiritual_line" class="form-control" rows="2" maxlength="1000"
                              placeholder="Ex: Umbanda Branca, Linha de Oxalá...">{{ old('spiritual_line', $house->spiritual_line) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">História</label>
                    <textarea name="history" class="form-control" rows="3" maxlength="3000"
                              placeholder="Como esta casa surgiu...">{{ old('history', $house->history) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Diferenciais</label>
                    <textarea name="differentials" class="form-control" rows="2" maxlength="500"
                              placeholder="O que torna esta casa especial...">{{ old('differentials', $house->differentials) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Dias e horários de atividade</label>
                    @php
                        // Novo formato JSON: {"seg":"20:00","sex":"21:00"}
                        // Legado: "seg,sex|20:00"
                        $scheduleData = [];
                        if ($house->schedule) {
                            if (str_starts_with(trim($house->schedule), '{')) {
                                $scheduleData = json_decode($house->schedule, true) ?? [];
                            } elseif (str_contains($house->schedule, '|')) {
                                [$dp, $st] = explode('|', $house->schedule, 2);
                                foreach (explode(',', $dp) as $d) { $scheduleData[trim($d)] = $st; }
                            }
                        }
                        $days = ['seg'=>'Segunda','ter'=>'Terça','qua'=>'Quarta','qui'=>'Quinta','sex'=>'Sexta','sab'=>'Sábado','dom'=>'Domingo'];
                    @endphp
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        @foreach($days as $val => $label)
                        @php $checked = array_key_exists($val, $scheduleData); $time = $scheduleData[$val] ?? ''; @endphp
                        <div style="display:flex;align-items:center;gap:8px;padding:8px 10px;border:1.5px solid #e5e7eb;border-radius:8px;background:#f9fafb;"
                             id="day-row-{{ $val }}">
                            <input type="checkbox" name="schedule_days[]" value="{{ $val }}"
                                   class="schedule-day-cb" id="day-cb-{{ $val }}"
                                   style="accent-color:#16a34a;width:16px;height:16px;cursor:pointer;"
                                   {{ $checked ? 'checked' : '' }}
                                   onchange="toggleDayTime('{{ $val }}')">
                            <label for="day-cb-{{ $val }}" style="font-size:13px;font-weight:600;width:70px;cursor:pointer;margin:0;">{{ $label }}</label>
                            <input type="time" name="schedule_time[{{ $val }}]"
                                   id="day-time-{{ $val }}"
                                   class="form-control form-control-sm schedule-day-time"
                                   style="max-width:110px;{{ $checked ? '' : 'opacity:.35;pointer-events:none;' }}"
                                   value="{{ old('schedule_time.'.$val, $time) }}">
                            <span id="day-label-{{ $val }}" style="font-size:11px;color:var(--txt-4);">
                                {{ $checked ? '' : 'Inativo' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="schedule" id="scheduleHidden" value="{{ old('schedule', $house->schedule) }}">
                    <div style="font-size:11px;color:#9ca3af;margin-top:8px;">Marque os dias de funcionamento e defina o horário de cada um.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Capacidade (pessoas)</label>
                    <input type="number" name="capacity" class="form-control" min="1"
                           value="{{ old('capacity', $house->capacity) }}" placeholder="Ex: 50">
                </div>
            </div>

            {{-- Contato --}}
            <div class="house-section">
                <div class="section-label"><i class="bi bi-telephone me-1"></i>Contato</div>

                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $house->email) }}" placeholder="contato@casa.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">Telefone</label>
                    <input type="tel" name="phone" class="form-control"
                           value="{{ old('phone', $house->phone) }}" placeholder="(11) 90000-0000">
                </div>

                <div class="mb-3">
                    <label class="form-label">WhatsApp</label>
                    <input type="tel" name="whatsapp" class="form-control"
                           value="{{ old('whatsapp', $house->whatsapp) }}" placeholder="(11) 90000-0000">
                </div>

                <div class="mb-3">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-control"
                           value="{{ old('website', $house->website) }}" placeholder="https://...">
                </div>
            </div>

            {{-- Redes Sociais --}}
            <div class="house-section">
                <div class="section-label"><i class="bi bi-share me-1"></i>Redes Sociais</div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-facebook me-1"></i>Facebook</label>
                    <input type="text" name="facebook" class="form-control"
                           value="{{ old('facebook', $house->facebook) }}" placeholder="usuario ou URL">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-instagram me-1"></i>Instagram</label>
                    <input type="text" name="instagram" class="form-control"
                           value="{{ old('instagram', $house->instagram) }}" placeholder="@usuario">
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-youtube me-1"></i>YouTube</label>
                    <input type="text" name="youtube" class="form-control"
                           value="{{ old('youtube', $house->youtube) }}" placeholder="canal ou URL">
                </div>
            </div>

            {{-- Endereço --}}
            <div class="house-section">
                <div class="section-label"><i class="bi bi-geo-alt me-1"></i>Endereço</div>

                <div class="row g-2 mb-3">
                    <div class="col-5">
                        <label class="form-label">CEP</label>
                        <input type="text" name="zip_code" class="form-control" id="hZipCode"
                               value="{{ old('zip_code', $house->zip_code) }}" placeholder="00000-000" maxlength="10">
                    </div>
                    <div class="col-7 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-success btn-sm w-100" onclick="buscarCep()">
                            <i class="bi bi-search me-1"></i>Buscar CEP
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Logradouro</label>
                    <input type="text" name="street" class="form-control" id="hStreet"
                           value="{{ old('street', $house->street) }}">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <label class="form-label">Número</label>
                        <input type="text" name="number" class="form-control"
                               value="{{ old('number', $house->number) }}">
                    </div>
                    <div class="col-8">
                        <label class="form-label">Complemento</label>
                        <input type="text" name="complement" class="form-control"
                               value="{{ old('complement', $house->complement) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Bairro</label>
                    <input type="text" name="neighborhood" class="form-control" id="hNeighborhood"
                           value="{{ old('neighborhood', $house->neighborhood) }}">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-8">
                        <label class="form-label">Cidade</label>
                        <input type="text" name="city" class="form-control" id="hCity"
                               value="{{ old('city', $house->city) }}">
                    </div>
                    <div class="col-4">
                        <label class="form-label">UF</label>
                        <input type="text" name="state" class="form-control" id="hState" maxlength="2"
                               value="{{ old('state', $house->state) }}">
                    </div>
                </div>
            </div>

            {{-- GPS --}}
            <div class="house-section">
                <div class="section-label"><i class="bi bi-crosshair me-1"></i>Coordenadas GPS</div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Latitude</label>
                        <input type="number" name="latitude" step="any" class="form-control"
                               value="{{ old('latitude', $house->latitude) }}" placeholder="-23.5505">
                    </div>
                    <div class="col-6">
                        <label class="form-label">Longitude</label>
                        <input type="number" name="longitude" step="any" class="form-control"
                               value="{{ old('longitude', $house->longitude) }}" placeholder="-46.6333">
                    </div>
                </div>
            </div>

            <button type="submit" class="save-btn">
                <i class="bi bi-check-lg me-2"></i>Salvar Página da Casa
            </button>
        </form>
    </div>
    @endif

</div>
<div style="height:80px;"></div>
@endsection

@push('scripts')
<script>
$('#avatarFile').on('change', function () {
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) { $('#avatarPreview').attr('src', e.target.result); };
        reader.readAsDataURL(file);
    }
});

$('#coverFile').on('change', function () {
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#coverPreview').attr('src', e.target.result).show();
            $('#coverPlaceholder').hide();
        };
        reader.readAsDataURL(file);
    }
});

$('#logoFile').on('change', function () {
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (e) { $('#logoPreview').attr('src', e.target.result); };
        reader.readAsDataURL(file);
    }
});

function switchProfileTab(tab) {
    document.getElementById('tab-perfil').style.display = (tab === 'perfil') ? '' : 'none';
    document.getElementById('tab-casa').style.display   = (tab === 'casa')   ? '' : 'none';
    document.getElementById('btn-tab-perfil').classList.toggle('active', tab === 'perfil');
    document.getElementById('btn-tab-casa').classList.toggle('active',   tab === 'casa');
}

// Abrir direto na aba da casa se vier do flash de sucesso da casa
@if(session('house_success'))
    switchProfileTab('casa');
@endif

// Monta schedule em JSON antes de submeter
document.querySelector('#tab-casa form')?.addEventListener('submit', function() {
    var obj = {};
    document.querySelectorAll('.schedule-day-cb:checked').forEach(function(cb) {
        var time = document.getElementById('day-time-' + cb.value)?.value || '';
        obj[cb.value] = time;
    });
    document.getElementById('scheduleHidden').value = Object.keys(obj).length ? JSON.stringify(obj) : '';
});

// Ativa/desativa campo de horário do dia
function toggleDayTime(day) {
    var cb   = document.getElementById('day-cb-' + day);
    var inp  = document.getElementById('day-time-' + day);
    var row  = document.getElementById('day-row-' + day);
    var lbl  = document.getElementById('day-label-' + day);
    if (cb.checked) {
        inp.style.opacity = '1';
        inp.style.pointerEvents = 'auto';
        row.style.borderColor = '#16a34a';
        row.style.background  = '#f0fdf4';
        if (lbl) lbl.textContent = '';
    } else {
        inp.style.opacity = '.35';
        inp.style.pointerEvents = 'none';
        row.style.borderColor = '#e5e7eb';
        row.style.background  = '#f9fafb';
        if (lbl) lbl.textContent = 'Inativo';
    }
}

// Estado inicial dos dias
document.querySelectorAll('.schedule-day-cb').forEach(function(cb) {
    if (cb.checked) toggleDayTime(cb.value);
});

// Busca CEP via ViaCEP
function buscarCep() {
    var cep = document.getElementById('hZipCode').value.replace(/\D/g, '');
    if (cep.length !== 8) { alert('CEP inválido.'); return; }
    fetch('https://viacep.com.br/ws/' + cep + '/json/')
        .then(r => r.json())
        .then(d => {
            if (d.erro) { alert('CEP não encontrado.'); return; }
            document.getElementById('hStreet').value      = d.logradouro || '';
            document.getElementById('hNeighborhood').value = d.bairro    || '';
            document.getElementById('hCity').value         = d.localidade || '';
            document.getElementById('hState').value        = d.uf         || '';
        })
        .catch(() => alert('Erro ao buscar CEP.'));
}
</script>
@endpush
