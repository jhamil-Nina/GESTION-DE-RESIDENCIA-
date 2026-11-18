<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') · Residencias EMI</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/paleta-emi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/institucional.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="site-container header-inner">
            <a class="brand" href="{{ url('/') }}">
                <span class="brand-mark" aria-hidden="true">EMI</span>
                <span>
                    <strong>Escuela Militar de Ingeniería</strong>
                    <span class="brand-campus">Unidad Académica Trópico</span>
                </span>
            </a>
            @yield('navigation')
        </div>
    </header>

    <main class="site-main @yield('main-class')">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="site-container footer-inner">
            <span>Residencias universitarias · EMI Trópico</span>
        </div>
    </footer>
</body>
</html>
