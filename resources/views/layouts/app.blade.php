<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#16a34a">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Aruanda Digital')</title>

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">

    {{-- CSS Global --}}
    <style>
        :root {
            --p:        #16a34a;
            --p-dk:     #166534;
            --p-lt:     #dcfce7;
            --p-xl:     #f0fdf4;
            --p-act:    #22c55e;
            --p-hov:    #15803d;
            --txt:      #111827;
            --txt-2:    #374151;
            --txt-3:    #6b7280;
            --txt-4:    #9ca3af;
            --bg:       #f3f4f6;
            --surface:  #ffffff;
            --border:   #e5e7eb;
            --border-lt:#f3f4f6;
            --shadow-sm:0 1px 3px rgba(0,0,0,.08);
            --shadow:   0 2px 8px rgba(0,0,0,.10);
            --shadow-md:0 4px 16px rgba(0,0,0,.12);
            --r-sm:     8px;
            --r:        12px;
            --r-lg:     16px;
            --r-xl:     20px;
            --bb-h:     68px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--txt);
            padding-bottom: calc(var(--bb-h) + 8px);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-size: 14px;
            line-height: 1.5;
        }

        /* ── Tipografia ── */
        .t-title   { font-size: 18px; font-weight: 800; color: var(--txt); }
        .t-heading { font-size: 15px; font-weight: 700; color: var(--txt); }
        .t-label   { font-size: 11px; font-weight: 700; color: var(--txt-3); text-transform: uppercase; letter-spacing: .6px; }
        .t-muted   { font-size: 12px; color: var(--txt-3); }
        .t-price   { font-size: 18px; font-weight: 800; color: var(--p); }
        .t-price-sm{ font-size: 14px; font-weight: 700; color: var(--p); }

        /* ── Superfície / Cards ── */
        .surface {
            background: var(--surface);
            border-radius: var(--r);
            box-shadow: var(--shadow-sm);
        }
        .card {
            border: none;
            border-radius: var(--r);
            box-shadow: var(--shadow-sm);
        }
        .card-body { padding: 14px 16px; }

        /* ── Botões ── */
        .btn-primary {
            background: var(--p);
            border-color: var(--p);
            border-radius: var(--r-sm);
            font-weight: 700;
            font-size: 14px;
            padding: 10px 20px;
            transition: background .2s, transform .1s, box-shadow .2s;
        }
        .btn-primary:hover,
        .btn-primary:focus {
            background: var(--p-hov);
            border-color: var(--p-hov);
            box-shadow: 0 4px 12px rgba(22,163,74,.3);
        }
        .btn-primary:active { transform: scale(.98); }

        .btn-outline-success {
            border-color: var(--p);
            color: var(--p);
            border-radius: var(--r-sm);
            font-weight: 600;
        }
        .btn-outline-success:hover {
            background: var(--p);
            border-color: var(--p);
        }

        .btn-ghost {
            background: transparent;
            border: none;
            padding: 6px 10px;
            border-radius: var(--r-sm);
            color: var(--txt-3);
            font-size: 20px;
            cursor: pointer;
            transition: background .15s, color .15s;
        }
        .btn-ghost:hover { background: var(--bg); color: var(--txt); }

        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            border: none;
            cursor: pointer;
            transition: background .15s, transform .1s;
        }
        .btn-icon:active { transform: scale(.92); }
        .btn-icon-primary { background: var(--p); color: #fff; }
        .btn-icon-primary:hover { background: var(--p-hov); }
        .btn-icon-light { background: var(--p-xl); color: var(--p); }
        .btn-icon-light:hover { background: var(--p-lt); }

        /* ── Badges ── */
        .badge-cat {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: .4px;
            background: var(--p-xl);
            color: var(--p-dk);
        }
        .badge-price {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
            background: var(--p-xl);
            color: var(--p-dk);
        }
        .badge-status-open   { background: #dcfce7; color: #166534; }
        .badge-status-full   { background: #fef9c3; color: #854d0e; }
        .badge-status-cancel { background: #fee2e2; color: #991b1b; }
        .badge-hot  { background: #fef3c7; color: #92400e; }
        .badge-new  { background: #ede9fe; color: #5b21b6; }
        .badge-sale { background: #fee2e2; color: #991b1b; }

        /* ── Search Bar ── */
        .search-wrap {
            padding: 10px 16px;
            background: var(--surface);
            border-bottom: 1px solid var(--border-lt);
        }
        .search-wrap .input-group {
            background: var(--bg);
            border-radius: 10px;
            overflow: hidden;
        }
        .search-wrap .input-group-text {
            background: var(--bg);
            border: none;
            color: var(--txt-3);
            padding-left: 14px;
        }
        .search-wrap .form-control {
            background: var(--bg);
            border: none;
            font-size: 14px;
            padding: 10px 14px 10px 4px;
        }
        .search-wrap .form-control:focus {
            box-shadow: none;
            background: var(--bg);
        }

        /* ── Page Header ── */
        .page-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 16px 12px;
            background: var(--surface);
            border-bottom: 1px solid var(--border-lt);
        }
        .page-hdr h6 { margin: 0; font-size: 17px; font-weight: 800; color: var(--txt); }

        /* ── Section Label ── */
        .sec-label {
            padding: 14px 16px 8px;
            font-size: 13px;
            font-weight: 700;
            color: var(--txt);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sec-label a { font-size: 12px; font-weight: 600; color: var(--p); text-decoration: none; }

        /* ── Separador ── */
        .section-divider {
            height: 8px;
            background: var(--bg);
            border-top: 1px solid var(--border-lt);
            border-bottom: 1px solid var(--border-lt);
        }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 56px 24px;
            color: var(--txt-4);
        }
        .empty-state i    { font-size: 52px; display: block; margin-bottom: 12px; opacity: .35; }
        .empty-state p    { font-size: 14px; margin: 0; }
        .empty-state span { font-size: 12px; }

        /* ── Bottom Bar ── */
        .bottom-bar {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: var(--bb-h);
            background: var(--surface);
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-around;
            z-index: 1000;
            box-shadow: 0 -4px 16px rgba(0,0,0,.07);
            padding-bottom: env(safe-area-inset-bottom, 0);
        }
        .bottom-bar a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            text-decoration: none;
            color: var(--txt-4);
            font-size: 10px;
            font-weight: 600;
            flex: 1;
            padding: 6px 0;
            transition: color .2s;
            position: relative;
        }
        .bottom-bar a i { font-size: 22px; transition: transform .2s; }
        .bottom-bar a.active { color: var(--p); }
        .bottom-bar a.active i { transform: scale(1.1); }
        .bottom-bar a.active::before {
            content: '';
            position: absolute;
            top: 0; left: 50%;
            transform: translateX(-50%);
            width: 28px; height: 3px;
            background: var(--p);
            border-radius: 0 0 3px 3px;
        }
        .bottom-bar a:hover:not(.active) { color: var(--txt-2); }

        /* ── Scrollbars ── */
        .scroll-x { display: flex; overflow-x: auto; scrollbar-width: none; gap: 8px; }
        .scroll-x::-webkit-scrollbar { display: none; }

        /* ── Page transition ── */
        @keyframes pageIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        .page-main {
            animation: pageIn .18s ease-out both;
        }

        /* ── Horizontal scroll section ── */
        .h-scroll {
            display: flex;
            gap: 10px;
            padding: 0 16px 16px;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .h-scroll::-webkit-scrollbar { display: none; }

        /* ── Skeleton loader ── */
        .skeleton {
            background: linear-gradient(90deg, var(--border-lt) 25%, var(--border) 50%, var(--border-lt) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
            border-radius: var(--r-sm);
        }
        @keyframes shimmer {
            from { background-position: 200% 0; }
            to   { background-position: -200% 0; }
        }

        /* ── Utilitários ── */
        .text-primary { color: var(--p) !important; }
        .bg-primary-lt { background: var(--p-lt) !important; }
        .rounded-xl { border-radius: var(--r-xl) !important; }
        .fw-800 { font-weight: 800 !important; }
        .gap-6 { gap: 6px !important; }
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ── Tap highlight ── */
        a, button { -webkit-tap-highlight-color: transparent; }

        /* ── Active press feedback ── */
        a[href]:active, button:active { opacity: .85; }

        /* ── iOS: previne zoom automático nos inputs (limiar = 16px) ──
           iOS Safari faz zoom em inputs com font-size < 16px. Com
           maximum-scale bloqueado, isso impede o foco. Força 16px
           globalmente para evitar o comportamento. ── */
        input, select, textarea {
            font-size: 16px !important;
            touch-action: manipulation;
        }

        /* ── iOS Bootstrap Modal fix ── */
        .modal-dialog { touch-action: pan-y; }
    </style>

    @stack('styles')
</head>
<body>

    <main class="page-main">
    @yield('content')
    </main>

    {{-- Bottom Bar --}}
    @include('components.bottom-bar')

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

    {{-- Flash messages --}}
    @if (session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({ icon: 'success', title: '{{ addslashes(session('success')) }}', timer: 2500, showConfirmButton: false, toast: true, position: 'top-end' });
    });
    </script>
    @endif
    @if (session('error'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({ icon: 'error', title: 'Erro', text: '{{ addslashes(session('error')) }}', confirmButtonColor: '#16a34a' });
    });
    </script>
    @endif

    {{-- Fix Bootstrap Modal: mover modais para <body> garante stacking context correto --}}
    {{-- Sem isso, o backdrop (inserido no <body>) fica acima dos modais quando a    --}}
    {{-- página tem elementos com transform/opacity que criam stacking contexts.      --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Move todos os modais para serem filhos diretos do <body>
        document.querySelectorAll('.modal').forEach(function (modal) {
            document.body.appendChild(modal);
        });
    });

    // Fix iOS Safari: remove tabindex depois que o modal abre
    // (evita focus-trap que bloqueia inputs no iOS)
    document.addEventListener('shown.bs.modal', function(e) {
        e.target.removeAttribute('tabindex');
    });
    document.addEventListener('hide.bs.modal', function(e) {
        e.target.setAttribute('tabindex', '-1');
    });
    </script>

    {{-- Service Worker --}}
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').catch(function(){});
    }
    </script>
</body>
</html>
