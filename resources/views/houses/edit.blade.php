@extends('layouts.app')
@section('title', 'Editar ' . $house->name . ' — Aruanda Digital')

@push('styles')
<style>
    .edit-wrap { padding: 16px; }

    /* Header */
    .page-header { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
    .page-header .back-arrow { color: #16a34a; font-size: 20px; text-decoration: none; line-height: 1; }
    .page-header .page-title { font-size: 16px; font-weight: 700; color: #111827; margin: 0; line-height: 1.3; }
    .page-header .page-subtitle { font-size: 12px; color: #6b7280; margin: 0; }

    /* Sections */
    .section-label {
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin: 24px 0 12px;
        padding-bottom: 6px;
        border-bottom: 1px solid #f3f4f6;
    }

    /* Form elements */
    .form-label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 5px; }
    .form-hint  { font-size: 11px; color: #9ca3af; margin-top: 3px; }
    .form-control, .form-select {
        border-radius: 8px;
        font-size: 14px;
        border: 1px solid #d1d5db;
        color: #111827;
    }
    .form-control:focus, .form-select:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 .15rem rgba(22, 163, 74, .15);
        outline: none;
    }

    /* Side-by-side pairs */
    .row-pair { display: flex; gap: 10px; }
    .row-pair .col-2-3 { flex: 2; min-width: 0; }
    .row-pair .col-1-3 { flex: 1; min-width: 0; }
    .row-pair .col-3-4 { flex: 3; min-width: 0; }
    .row-pair .col-1-4 { flex: 1; min-width: 0; }

    /* GPS hint box */
    .gps-hint {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 12px;
        color: #166534;
        margin-bottom: 12px;
    }

    /* Save button */
    .save-btn {
        display: block;
        width: 100%;
        padding: 13px;
        background: #16a34a;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        margin-top: 24px;
        cursor: pointer;
    }
    .save-btn:hover { background: #166534; }

    /* Back link */
    .back-link {
        display: block;
        text-align: center;
        margin-top: 14px;
        font-size: 13px;
        color: #16a34a;
        text-decoration: none;
    }
    .back-link:hover { text-decoration: underline; }

    /* Flash messages */
    .flash-success {
        background: #f0fdf4;
        border: 1px solid #86efac;
        color: #166534;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        margin-bottom: 16px;
    }
    .flash-error {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        color: #991b1b;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        margin-bottom: 16px;
    }
</style>
@endpush

@section('content')
<div class="edit-wrap">

    {{-- Page Header --}}
    <div class="page-header">
        <a href="{{ route('houses.show', $house->id) }}" class="back-arrow">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <p class="page-title">{{ $house->name }}</p>
            <p class="page-subtitle">Editar informações da casa</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flash-success">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flash-error">
            <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="flash-error">
            <i class="bi bi-exclamation-circle me-1"></i>
            Corrija os erros abaixo antes de salvar.
        </div>
    @endif

    <form method="POST" action="{{ route('houses.update', $house->id) }}">
        @csrf
        @method('PUT')

        {{-- ===================== SECTION 1: Identidade ===================== --}}
        <div class="section-label">Identidade</div>

        <div class="mb-3">
            <label class="form-label">Nome da casa *</label>
            <input type="text"
                   name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $house->name) }}"
                   required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo *</label>
            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                <option value="">— Selecione —</option>
                @foreach(['umbanda' => 'Umbanda', 'candomble' => 'Candomblé', 'misto' => 'Misto', 'outro' => 'Outro'] as $val => $label)
                    <option value="{{ $val }}" {{ old('type', $house->type) === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Data de fundação</label>
            <input type="date"
                   name="foundation_date"
                   class="form-control @error('foundation_date') is-invalid @enderror"
                   value="{{ old('foundation_date', $house->foundation_date ? $house->foundation_date->format('Y-m-d') : '') }}">
            @error('foundation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Capacidade de pessoas</label>
            <input type="number"
                   name="capacity"
                   class="form-control @error('capacity') is-invalid @enderror"
                   value="{{ old('capacity', $house->capacity) }}"
                   min="1">
            @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- ===================== SECTION 2: Descrição ===================== --}}
        <div class="section-label">Descrição</div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description"
                      class="form-control @error('description') is-invalid @enderror"
                      rows="4">{{ old('description', $house->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Linha espiritual</label>
            <textarea name="spiritual_line"
                      class="form-control @error('spiritual_line') is-invalid @enderror"
                      rows="3">{{ old('spiritual_line', $house->spiritual_line) }}</textarea>
            @error('spiritual_line')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">História</label>
            <textarea name="history"
                      class="form-control @error('history') is-invalid @enderror"
                      rows="3">{{ old('history', $house->history) }}</textarea>
            @error('history')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Diferenciais</label>
            <textarea name="differentials"
                      class="form-control @error('differentials') is-invalid @enderror"
                      rows="2">{{ old('differentials', $house->differentials) }}</textarea>
            <p class="form-hint">Separe com vírgulas</p>
            @error('differentials')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- ===================== SECTION 3: Contato ===================== --}}
        <div class="section-label">Contato</div>

        <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $house->email) }}">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Telefone</label>
            <input type="tel"
                   name="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $house->phone) }}">
            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">WhatsApp</label>
            <input type="tel"
                   name="whatsapp"
                   class="form-control @error('whatsapp') is-invalid @enderror"
                   value="{{ old('whatsapp', $house->whatsapp) }}">
            <p class="form-hint">Somente números com DDD</p>
            @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Website</label>
            <input type="url"
                   name="website"
                   class="form-control @error('website') is-invalid @enderror"
                   value="{{ old('website', $house->website) }}"
                   placeholder="https://...">
            @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- ===================== SECTION 4: Redes Sociais ===================== --}}
        <div class="section-label">Redes Sociais</div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-facebook me-1"></i>Facebook</label>
            <input type="text"
                   name="facebook"
                   class="form-control @error('facebook') is-invalid @enderror"
                   value="{{ old('facebook', $house->facebook) }}"
                   placeholder="facebook.com/suacasa">
            @error('facebook')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-instagram me-1"></i>Instagram</label>
            <input type="text"
                   name="instagram"
                   class="form-control @error('instagram') is-invalid @enderror"
                   value="{{ old('instagram', $house->instagram) }}"
                   placeholder="@suacasa">
            @error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="bi bi-youtube me-1"></i>YouTube</label>
            <input type="text"
                   name="youtube"
                   class="form-control @error('youtube') is-invalid @enderror"
                   value="{{ old('youtube', $house->youtube) }}"
                   placeholder="youtube.com/c/suacasa">
            @error('youtube')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- ===================== SECTION 5: Endereço ===================== --}}
        <div class="section-label">Endereço</div>

        <div class="mb-3">
            <label class="form-label">CEP</label>
            <input type="text"
                   name="zip_code"
                   class="form-control @error('zip_code') is-invalid @enderror"
                   value="{{ old('zip_code', $house->zip_code) }}"
                   placeholder="00000-000"
                   maxlength="9">
            @error('zip_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row-pair mb-3">
            <div class="col-2-3">
                <label class="form-label">Logradouro</label>
                <input type="text"
                       name="street"
                       class="form-control @error('street') is-invalid @enderror"
                       value="{{ old('street', $house->street) }}">
                @error('street')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-1-3">
                <label class="form-label">Número</label>
                <input type="text"
                       name="number"
                       class="form-control @error('number') is-invalid @enderror"
                       value="{{ old('number', $house->number) }}">
                @error('number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Complemento</label>
            <input type="text"
                   name="complement"
                   class="form-control @error('complement') is-invalid @enderror"
                   value="{{ old('complement', $house->complement) }}"
                   placeholder="Apto, bloco, sala…">
            @error('complement')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Bairro</label>
            <input type="text"
                   name="neighborhood"
                   class="form-control @error('neighborhood') is-invalid @enderror"
                   value="{{ old('neighborhood', $house->neighborhood) }}">
            @error('neighborhood')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="row-pair mb-3">
            <div class="col-3-4">
                <label class="form-label">Cidade</label>
                <input type="text"
                       name="city"
                       class="form-control @error('city') is-invalid @enderror"
                       value="{{ old('city', $house->city) }}">
                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-1-4">
                <label class="form-label">UF</label>
                <input type="text"
                       name="state"
                       class="form-control @error('state') is-invalid @enderror"
                       value="{{ old('state', $house->state) }}"
                       maxlength="2"
                       placeholder="SP"
                       style="text-transform:uppercase;">
                @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ===================== SECTION 6: Localização GPS ===================== --}}
        <div class="section-label">Localização (GPS)</div>

        <div class="gps-hint">
            <i class="bi bi-geo-alt me-1"></i>
            Preenchimento opcional — usado para exibir no mapa
        </div>

        <div class="row-pair mb-3">
            <div class="col-2-3">
                <label class="form-label">Latitude</label>
                <input type="number"
                       name="latitude"
                       class="form-control @error('latitude') is-invalid @enderror"
                       value="{{ old('latitude', $house->latitude) }}"
                       step="0.000001"
                       placeholder="-23.550520">
                @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-2-3">
                <label class="form-label">Longitude</label>
                <input type="number"
                       name="longitude"
                       class="form-control @error('longitude') is-invalid @enderror"
                       value="{{ old('longitude', $house->longitude) }}"
                       step="0.000001"
                       placeholder="-46.633309">
                @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ===================== SECTION 7: Funcionamento ===================== --}}
        <div class="section-label">Funcionamento</div>

        <div class="mb-3">
            <label class="form-label">Horários e dias de atendimento</label>
            <textarea name="schedule"
                      class="form-control @error('schedule') is-invalid @enderror"
                      rows="2"
                      placeholder="Ex: Segundas e quartas, 20h — Giras abertas às terças">{{ old('schedule', $house->schedule) }}</textarea>
            @error('schedule')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- ===================== Actions ===================== --}}
        <button type="submit" class="save-btn">
            <i class="bi bi-check-lg me-2"></i>Salvar Alterações
        </button>
    </form>

    <a href="{{ route('houses.show', $house->id) }}" class="back-link">
        ← Voltar para a casa
    </a>

</div>
<div style="height: 80px;"></div>
@endsection
