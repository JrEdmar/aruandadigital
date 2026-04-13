<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#166534">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Aruanda Digital')</title>

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    {{-- Bootstrap 5.3.3 --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    {{-- Bootstrap Icons 1.11.3 --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Fonts — Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">

    <style>
        :root {
            --color-primary:    #16A34A;
            --color-primary-dk: #166534;
            --color-primary-lt: #DCFCE7;
            --color-muted:      #6B7280;
            --color-text:       #111827;
            --color-bg:         #F5F5F5;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            /* Gradiente escuro verde — sem imagem externa */
            background: linear-gradient(145deg, #0f172a 0%, #14532d 50%, #166534 100%);
            -webkit-font-smoothing: antialiased;
        }

        /* Overlay de partículas sutis */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(22,163,74,.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(22,163,74,.10) 0%, transparent 40%);
            pointer-events: none;
            z-index: 0;
        }

        /* Wrapper da página */
        .guest-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        /* Logo no topo */
        .guest-logo {
            text-align: center;
            margin-bottom: 28px;
        }

        .guest-logo .logo-icon {
            width: 56px;
            height: 56px;
            background: var(--color-primary);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            box-shadow: 0 4px 20px rgba(22,163,74,.4);
        }

        .guest-logo .logo-icon i {
            font-size: 28px;
            color: #fff;
        }

        .guest-logo h1 {
            color: #fff;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0;
            text-shadow: 0 2px 8px rgba(0,0,0,.3);
        }

        .guest-logo p {
            color: rgba(255,255,255,.7);
            font-size: 13px;
            margin: 4px 0 0;
        }

        /* Card central */
        .guest-card {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.97);
            border-radius: 20px;
            padding: 28px 24px;
            box-shadow:
                0 20px 60px rgba(0,0,0,.35),
                0 0 0 1px rgba(255,255,255,.1);
            backdrop-filter: blur(10px);
        }

        /* Botão primário (verde) */
        .btn-primary {
            background: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: #fff !important;
            border-radius: 8px !important;
            font-weight: 600;
            transition: background .2s, transform .1s;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: #15803d !important;
            border-color: #15803d !important;
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Input customizado */
        .form-control,
        .form-select {
            border-radius: 8px !important;
            border: 1.5px solid #e5e7eb;
            padding: 10px 14px;
            font-size: 15px;
            transition: border-color .2s, box-shadow .2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px rgba(22,163,74,.15) !important;
        }

        .input-group .input-group-text {
            border-radius: 8px 0 0 8px !important;
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-right: none;
            color: var(--color-muted);
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0 !important;
        }

        /* Link discreto */
        .link-subtle {
            color: var(--color-muted);
            font-size: 13px;
            text-decoration: none;
        }

        .link-subtle:hover {
            color: var(--color-primary);
            text-decoration: underline;
        }

        /* Divider */
        .divider-text {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--color-muted);
            font-size: 13px;
            margin: 4px 0;
        }

        .divider-text::before,
        .divider-text::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* Botões sociais */
        .btn-google {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            color: #374151;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            padding: 10px;
            transition: background .2s, border-color .2s;
        }

        .btn-google:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .btn-facebook {
            background: #1877F2;
            border: none;
            color: #fff;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            padding: 10px;
            transition: background .2s;
        }

        .btn-facebook:hover {
            background: #166fe5;
            color: #fff;
        }

        /* Tabs do cadastro */
        .register-tabs {
            display: flex;
            gap: 6px;
            background: #f3f4f6;
            border-radius: 10px;
            padding: 4px;
            margin-bottom: 20px;
        }

        .register-tabs button {
            flex: 1;
            padding: 8px 4px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, color .2s;
            background: transparent;
            color: var(--color-muted);
        }

        .register-tabs button.active {
            background: var(--color-primary);
            color: #fff;
            box-shadow: 0 2px 8px rgba(22,163,74,.3);
        }

        /* Avatar upload */
        .avatar-upload {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--color-primary-lt);
            border: 2px dashed var(--color-primary);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin: 0 auto 16px;
            position: relative;
            overflow: hidden;
            transition: border-color .2s;
        }

        .avatar-upload:hover {
            border-color: #15803d;
        }

        .avatar-upload i {
            font-size: 22px;
            color: var(--color-primary);
        }

        .avatar-upload span {
            font-size: 10px;
            color: var(--color-primary);
            font-weight: 600;
            margin-top: 2px;
        }

        /* Checkbox personalizado */
        .form-check-input:checked {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(22,163,74,.2);
            border-color: var(--color-primary);
        }

        /* Label de seção */
        .section-title {
            color: var(--color-text);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .section-subtitle {
            color: var(--color-muted);
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="guest-wrapper">

    {{-- Logo --}}
    <div class="guest-logo">
        <div class="logo-icon">
            <i class="bi bi-star-fill"></i>
        </div>
        <h1>Aruanda Digital</h1>
        <p>Sua espiritualidade conectada</p>
    </div>

    {{-- Card do formulário --}}
    <div class="guest-card">
        @yield('content')
    </div>

</div>

{{-- jQuery 3.7.1 --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

{{-- Bootstrap 5.3.3 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- SweetAlert2 v11 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Flash messages via SweetAlert2 --}}
@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Ops!',
        text: '{{ addslashes(session('error')) }}',
        confirmButtonColor: '#16A34A',
        confirmButtonText: 'OK',
    });
</script>
@endif

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: '{{ addslashes(session('success')) }}',
        confirmButtonColor: '#16A34A',
        confirmButtonText: 'Continuar',
        timer: 4000,
        timerProgressBar: true,
    });
</script>
@endif

@stack('scripts')
</body>
</html>
