@extends('layouts.app')
@section('title', 'Novo Estudo — Aruanda Digital')

@section('content')

<div class="page-hdr">
    <h6><i class="bi bi-book me-2" style="color:var(--p);"></i>Novo Estudo</h6>
</div>

<div style="padding:14px;">
<div style="background:var(--surface);border-radius:var(--r);padding:20px;box-shadow:var(--shadow-sm);">

@if($errors->any())
    <div class="alert alert-danger" style="border-radius:var(--r-sm);font-size:13px;">
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('studies.store') }}">
@csrf

<div class="mb-3">
    <label class="form-label fw-semibold small">Título *</label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
           value="{{ old('title') }}" required placeholder="Ex: Orixás e suas correspondências">
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small">Descrição curta</label>
    <textarea name="description" class="form-control" rows="2"
              placeholder="Breve resumo do conteúdo...">{{ old('description') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small">Tipo de conteúdo *</label>
    <select name="content_type" class="form-select @error('content_type') is-invalid @enderror">
        <option value="text" {{ old('content_type') === 'text' ? 'selected' : '' }}>Texto</option>
        <option value="video" {{ old('content_type') === 'video' ? 'selected' : '' }}>Vídeo</option>
        <option value="audio" {{ old('content_type') === 'audio' ? 'selected' : '' }}>Áudio</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small">URL (vídeo/áudio)</label>
    <input type="url" name="content_url" class="form-control"
           value="{{ old('content_url') }}" placeholder="https://...">
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small">Conteúdo *</label>
    <textarea name="content_body" class="form-control @error('content_body') is-invalid @enderror"
              rows="8" required placeholder="Escreva o conteúdo do estudo aqui...">{{ old('content_body') }}</textarea>
</div>

<div class="row g-2 mb-3">
    <div class="col-6">
        <label class="form-label fw-semibold small">Categoria</label>
        <input type="text" name="category" class="form-control"
               value="{{ old('category') }}" placeholder="Ex: Umbanda, Candomblé">
    </div>
    <div class="col-6">
        <label class="form-label fw-semibold small">Pontos</label>
        <input type="number" name="points" class="form-control" min="0"
               value="{{ old('points', 10) }}">
    </div>
</div>

<div class="form-check mb-4">
    <input class="form-check-input" type="checkbox" name="published" value="1" id="published"
           {{ old('published') ? 'checked' : '' }}>
    <label class="form-check-label small fw-semibold" for="published">Publicar imediatamente</label>
</div>

<button type="submit" class="btn btn-primary w-100" style="border-radius:var(--r-sm);padding:12px;">
    <i class="bi bi-check-circle me-1"></i>Salvar Estudo
</button>

<a href="{{ route('studies') }}" class="btn btn-outline-secondary w-100 mt-2" style="border-radius:var(--r-sm);padding:11px;">
    Cancelar
</a>

</form>
</div>
</div>

<div style="height:24px;"></div>
@endsection
