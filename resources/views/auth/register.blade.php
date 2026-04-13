@extends('layouts.guest')

@section('title', 'Cadastro — Aruanda Digital')

@push('styles')
<style>
    /* Ajuste para card de cadastro (mais alto) */
    .guest-card { max-width: 440px; }
</style>
@endpush

@section('content')

<h2 class="section-title">Criar conta</h2>
<p class="section-subtitle">Escolha seu perfil e preencha os dados</p>

{{-- Erros de validação --}}
@if($errors->any())
    @push('scripts')
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Corrija os erros',
            html: '<ul style="text-align:left;margin:0;padding-left:20px">@foreach($errors->all() as $error)<li>{{ addslashes($error) }}</li>@endforeach</ul>',
            confirmButtonColor: '#16A34A',
        });
    </script>
    @endpush
@endif

{{-- Avatar upload (visual) --}}
<div class="avatar-upload" id="avatarPreview" onclick="$('#avatarInput').click()">
    <i class="bi bi-camera"></i>
    <span>Foto</span>
    <input type="file" id="avatarInput" accept="image/*" style="display:none">
</div>

{{-- Tabs de seleção de perfil --}}
<div class="register-tabs">
    <button type="button" class="active" data-tab="visitante">
        <i class="bi bi-person me-1"></i>Visitante
    </button>
    <button type="button" data-tab="casa">
        <i class="bi bi-house me-1"></i>Casa
    </button>
    <button type="button" data-tab="loja">
        <i class="bi bi-bag me-1"></i>Loja
    </button>
</div>

{{-- ================================================================== --}}
{{-- FORM unificado — action e campos mudam com JavaScript              --}}
{{-- ================================================================== --}}
<form method="POST" action="{{ route('register.visitante') }}" id="registerForm">
    @csrf

    {{-- ============================================================== --}}
    {{-- TAB VISITANTE                                                    --}}
    {{-- ============================================================== --}}
    <div id="tab-visitante">

        {{-- Nome --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="d_name">Nome Completo</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" id="d_name" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Seu nome completo">
            </div>
        </div>

        {{-- E-mail --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="d_email">E-mail</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" id="d_email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="seu@email.com">
            </div>
        </div>

        {{-- Telefone --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="d_phone">Telefone / WhatsApp</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                <input type="tel" id="d_phone" name="phone"
                       class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone') }}" placeholder="(11) 99999-9999">
            </div>
        </div>

        {{-- CPF + Data de Nascimento --}}
        <div class="row g-2 mb-3">
            <div class="col-7">
                <label class="form-label fw-semibold small" for="d_cpf">CPF</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" id="d_cpf" name="cpf"
                           class="form-control @error('cpf') is-invalid @enderror"
                           value="{{ old('cpf') }}" placeholder="000.000.000-00" maxlength="14">
                </div>
            </div>
            <div class="col-5">
                <label class="form-label fw-semibold small" for="d_birth_date">Nascimento</label>
                <input type="date" id="d_birth_date" name="birth_date"
                       class="form-control @error('birth_date') is-invalid @enderror"
                       value="{{ old('birth_date') }}" max="{{ date('Y-m-d') }}">
            </div>
        </div>

        {{-- Senha --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="d_password">Senha</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" id="d_password" name="password"
                       class="form-control" placeholder="Mínimo 8 caracteres">
            </div>
        </div>

        {{-- Confirmar senha --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="d_password_confirmation">Confirmar Senha</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" id="d_password_confirmation" name="password_confirmation"
                       class="form-control" placeholder="Repita a senha">
            </div>
        </div>

        {{-- LGPD --}}
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="lgpd_accepted" id="d_lgpd" value="1">
                <label class="form-check-label small" for="d_lgpd">
                    Aceito os <a href="#" class="text-success fw-semibold">Termos de Uso</a>
                    e a <a href="#" class="text-success fw-semibold">Política de Privacidade (LGPD)</a>
                </label>
            </div>
        </div>

    </div>{{-- /tab-visitante --}}

    {{-- ============================================================== --}}
    {{-- TAB CASA                                                        --}}
    {{-- ============================================================== --}}
    <div id="tab-casa" style="display:none">

        {{-- Nome da Casa --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="c_house_name">Nome da Casa / Templo</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-house-heart"></i></span>
                <input type="text" id="c_house_name" name="house_name"
                       class="form-control" value="{{ old('house_name') }}"
                       placeholder="Ex.: Tenda Espírita Oxalá">
            </div>
        </div>

        {{-- CNPJ --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="c_cnpj">CNPJ</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-building"></i></span>
                <input type="text" id="c_cnpj" name="cnpj"
                       class="form-control" value="{{ old('cnpj') }}"
                       placeholder="00.000.000/0001-00">
            </div>
        </div>

        {{-- Tipo --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="c_type">Tipo</label>
            <select id="c_type" name="type" class="form-select">
                <option value="">Selecione...</option>
                <option value="umbanda"   {{ old('type') === 'umbanda'   ? 'selected' : '' }}>Umbanda</option>
                <option value="candomble" {{ old('type') === 'candomble' ? 'selected' : '' }}>Candomblé</option>
                <option value="misto"     {{ old('type') === 'misto'     ? 'selected' : '' }}>Misto</option>
                <option value="outro"     {{ old('type') === 'outro'     ? 'selected' : '' }}>Outro</option>
            </select>
        </div>

        {{-- Cidade / Estado --}}
        <div class="row g-2 mb-3">
            <div class="col-8">
                <label class="form-label fw-semibold small" for="c_street">Endereço</label>
                <input type="text" id="c_street" name="street"
                       class="form-control" value="{{ old('street') }}"
                       placeholder="Rua, número">
            </div>
            <div class="col-4">
                <label class="form-label fw-semibold small" for="c_state">Estado</label>
                <input type="text" id="c_state" name="state"
                       class="form-control text-uppercase" value="{{ old('state') }}"
                       placeholder="SP" maxlength="2">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small" for="c_city">Cidade</label>
            <input type="text" id="c_city" name="city"
                   class="form-control" value="{{ old('city') }}"
                   placeholder="Sua cidade">
        </div>

        {{-- E-mail institucional --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="c_email">E-mail Institucional</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" id="c_email" name="email"
                       class="form-control" value="{{ old('email') }}"
                       placeholder="contato@casa.com.br">
            </div>
        </div>

        {{-- Telefone --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="c_phone">Telefone</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                <input type="tel" id="c_phone" name="phone"
                       class="form-control" value="{{ old('phone') }}"
                       placeholder="(11) 99999-9999">
            </div>
        </div>

        {{-- Dirigente --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="c_dirigente_name">Nome do Dirigente Responsável</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                <input type="text" id="c_dirigente_name" name="dirigente_name"
                       class="form-control" value="{{ old('dirigente_name') }}"
                       placeholder="Nome completo do dirigente">
            </div>
        </div>

        {{-- CPF + Data de Nascimento do Dirigente --}}
        <div class="row g-2 mb-3">
            <div class="col-7">
                <label class="form-label fw-semibold small" for="c_cpf">CPF do Dirigente</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" id="c_cpf" name="cpf"
                           class="form-control" value="{{ old('cpf') }}"
                           placeholder="000.000.000-00" maxlength="14">
                </div>
            </div>
            <div class="col-5">
                <label class="form-label fw-semibold small" for="c_birth_date">Nascimento</label>
                <input type="date" id="c_birth_date" name="birth_date"
                       class="form-control" value="{{ old('birth_date') }}"
                       max="{{ date('Y-m-d') }}">
            </div>
        </div>

        {{-- Senha --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="c_password">Senha</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" id="c_password" name="password"
                       class="form-control" placeholder="Mínimo 8 caracteres">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small" for="c_password_confirmation">Confirmar Senha</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" id="c_password_confirmation" name="password_confirmation"
                       class="form-control" placeholder="Repita a senha">
            </div>
        </div>

        {{-- LGPD --}}
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="lgpd_accepted" id="c_lgpd" value="1">
                <label class="form-check-label small" for="c_lgpd">
                    Aceito os <a href="#" class="text-success fw-semibold">Termos de Uso</a>
                    e a <a href="#" class="text-success fw-semibold">Política de Privacidade (LGPD)</a>
                </label>
            </div>
        </div>

    </div>{{-- /tab-casa --}}

    {{-- ============================================================== --}}
    {{-- TAB LOJA                                                        --}}
    {{-- ============================================================== --}}
    <div id="tab-loja" style="display:none">

        {{-- Nome da Loja --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="l_store_name">Nome da Loja</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-shop"></i></span>
                <input type="text" id="l_store_name" name="store_name"
                       class="form-control" value="{{ old('store_name') }}"
                       placeholder="Nome da sua loja">
            </div>
        </div>

        {{-- CNPJ --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="l_cnpj">CNPJ</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-building"></i></span>
                <input type="text" id="l_cnpj" name="cnpj"
                       class="form-control" value="{{ old('cnpj') }}"
                       placeholder="00.000.000/0001-00">
            </div>
        </div>

        {{-- Tipo --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="l_store_type">Tipo de Loja</label>
            <select id="l_store_type" name="store_type" class="form-select">
                <option value="">Selecione...</option>
                <option value="varejo"  {{ old('store_type') === 'varejo'  ? 'selected' : '' }}>Varejo</option>
                <option value="atacado" {{ old('store_type') === 'atacado' ? 'selected' : '' }}>Atacado</option>
            </select>
        </div>

        {{-- E-mail --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="l_email">E-mail</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" id="l_email" name="email"
                       class="form-control" value="{{ old('email') }}"
                       placeholder="loja@email.com">
            </div>
        </div>

        {{-- Telefone --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="l_phone">Telefone / WhatsApp</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                <input type="tel" id="l_phone" name="phone"
                       class="form-control" value="{{ old('phone') }}"
                       placeholder="(11) 99999-9999">
            </div>
        </div>

        {{-- Responsável --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="l_name">Nome do Responsável</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" id="l_name" name="name"
                       class="form-control" value="{{ old('name') }}"
                       placeholder="Nome completo">
            </div>
        </div>

        {{-- CPF + Data de Nascimento --}}
        <div class="row g-2 mb-3">
            <div class="col-7">
                <label class="form-label fw-semibold small" for="l_cpf">CPF do Responsável</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" id="l_cpf" name="cpf"
                           class="form-control" value="{{ old('cpf') }}"
                           placeholder="000.000.000-00" maxlength="14">
                </div>
            </div>
            <div class="col-5">
                <label class="form-label fw-semibold small" for="l_birth_date">Nascimento</label>
                <input type="date" id="l_birth_date" name="birth_date"
                       class="form-control" value="{{ old('birth_date') }}"
                       max="{{ date('Y-m-d') }}">
            </div>
        </div>

        {{-- Senha --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small" for="l_password">Senha</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" id="l_password" name="password"
                       class="form-control" placeholder="Mínimo 8 caracteres">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold small" for="l_password_confirmation">Confirmar Senha</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" id="l_password_confirmation" name="password_confirmation"
                       class="form-control" placeholder="Repita a senha">
            </div>
        </div>

        {{-- LGPD --}}
        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="lgpd_accepted" id="l_lgpd" value="1">
                <label class="form-check-label small" for="l_lgpd">
                    Aceito os <a href="#" class="text-success fw-semibold">Termos de Uso</a>
                    e a <a href="#" class="text-success fw-semibold">Política de Privacidade (LGPD)</a>
                </label>
            </div>
        </div>

    </div>{{-- /tab-loja --}}

    {{-- Botão submit --}}
    <button type="submit" class="btn btn-primary w-100 py-2 mb-3" id="submitBtn">
        <i class="bi bi-person-check me-1"></i>Cadastrar
    </button>

</form>

{{-- Link para login --}}
<div class="text-center">
    <span class="small text-muted">Já tem conta?</span>
    <a href="{{ route('login') }}" class="text-success fw-semibold small ms-1">Faça login</a>
</div>

@endsection

@push('scripts')
<script>
$(function () {

    // ----------------------------------------------------------------
    // Mapeamento de tab → action da rota
    // ----------------------------------------------------------------
    const routes = {
        visitante: '{{ route("register.visitante") }}',
        casa:   '{{ route("register.casa") }}',
        loja:   '{{ route("register.loja") }}',
    };

    // Injeta campo _tab oculto ANTES de chamar switchTab para que o valor seja gravado corretamente
    $('<input type="hidden" id="_tab_hidden" name="_tab">').appendTo('#registerForm');

    // Aba ativa inicial (volta com erro mantém a aba certa)
    const activeTab = '{{ old("_tab", "visitante") }}' || 'visitante';
    switchTab(activeTab);

    // ----------------------------------------------------------------
    // Clique nos botões de aba
    // ----------------------------------------------------------------
    $('.register-tabs button').on('click', function () {
        const tab = $(this).data('tab');
        switchTab(tab);
    });

    function switchTab(tab) {
        // Botões
        $('.register-tabs button').removeClass('active');
        $(`.register-tabs button[data-tab="${tab}"]`).addClass('active');

        // Seções: mostra a ativa, oculta as outras
        $('#tab-visitante, #tab-casa, #tab-loja').hide();
        $(`#tab-${tab}`).show();

        // Desabilita os campos das abas ocultas para não serem enviados no POST
        $('#tab-visitante, #tab-casa, #tab-loja').not(`#tab-${tab}`)
            .find('input, select, textarea').prop('disabled', true);
        $(`#tab-${tab}`).find('input, select, textarea').prop('disabled', false);

        // Action do form
        $('#registerForm').attr('action', routes[tab]);

        // Campo oculto para retorno de erro
        $('#_tab_hidden').val(tab);
    }

    // ----------------------------------------------------------------
    // Preview de avatar
    // ----------------------------------------------------------------
    $('#avatarInput').on('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#avatarPreview')
                .html(`<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`)
                .css('border', 'none');
        };
        reader.readAsDataURL(file);
    });

    // ----------------------------------------------------------------
    // Máscaras simples (CNPJ, CPF, Telefone)
    // ----------------------------------------------------------------
    function maskInput(selector, mask) {
        $(selector).on('input', function () {
            let v = $(this).val().replace(/\D/g, '');
            let r = '';
            for (let i = 0, j = 0; i < mask.length && j < v.length; i++) {
                if (mask[i] === '9') { r += v[j++]; }
                else { r += mask[i]; if (j < v.length || mask[i] !== ' ') {} }
            }
            // Simples: apenas remove não-dígitos para campos de máscara
            $(this).val(r);
        });
    }

    // Telefone
    $('[name="phone"]').on('input', function () {
        let v = $(this).val().replace(/\D/g, '').slice(0, 11);
        if (v.length > 2) v = '(' + v.slice(0,2) + ') ' + v.slice(2);
        if (v.length > 10) v = v.slice(0,10) + '-' + v.slice(10);
        $(this).val(v);
    });

    // CNPJ
    $('[name="cnpj"]').on('input', function () {
        let v = $(this).val().replace(/\D/g, '').slice(0, 14);
        if (v.length > 12) v = v.slice(0,2)+'.'+v.slice(2,5)+'.'+v.slice(5,8)+'/'+v.slice(8,12)+'-'+v.slice(12);
        else if (v.length > 8) v = v.slice(0,2)+'.'+v.slice(2,5)+'.'+v.slice(5,8)+'/'+v.slice(8);
        else if (v.length > 5) v = v.slice(0,2)+'.'+v.slice(2,5)+'.'+v.slice(5);
        else if (v.length > 2) v = v.slice(0,2)+'.'+v.slice(2);
        $(this).val(v);
    });

    // CPF
    $('[name="cpf"]').on('input', function () {
        let v = $(this).val().replace(/\D/g, '').slice(0, 11);
        if (v.length > 9) v = v.slice(0,3)+'.'+v.slice(3,6)+'.'+v.slice(6,9)+'-'+v.slice(9);
        else if (v.length > 6) v = v.slice(0,3)+'.'+v.slice(3,6)+'.'+v.slice(6);
        else if (v.length > 3) v = v.slice(0,3)+'.'+v.slice(3);
        $(this).val(v);
    });

    // Estado uppercase
    $('[name="state"]').on('input', function () {
        $(this).val($(this).val().toUpperCase().slice(0,2));
    });

});
</script>
@endpush
