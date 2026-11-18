<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo }} · EMI Trópico</title>
    <style>
        {!! file_get_contents(public_path('css/paleta-emi.css')) !!}
        @page { size: A4; margin: 15mm; }
        * { box-sizing: border-box; }
        body { margin: 0; font: 14px/1.4 Arial, sans-serif; color: var(--emi-navy); background: var(--emi-canvas); }
        .acciones, .documento { width: min(100% - 2rem, 210mm); margin-inline: auto; }
        .acciones { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; padding-block: 1rem; }
        .acciones a { color: var(--emi-blue); }
        .acciones button { background: var(--emi-blue); color: white; font: inherit; padding: .65rem 1rem; border: 0; border-radius: 6px; cursor: pointer; }
        .documento { padding: clamp(1rem, 5vw, 15mm); margin-bottom: 2rem; background: white; border: 1px solid var(--emi-line); }
        .membrete { border-bottom: 3px solid var(--emi-gold); padding-bottom: .9rem; }
        .marca, .identidad { display: inline-block; vertical-align: middle; }
        .marca { font-size: 2.1em; font-weight: 800; letter-spacing: .07em; padding-right: .9rem; margin-right: .7rem; border-right: 1px solid var(--emi-gold); }
        .institucion { font-weight: 700; }
        .unidad { color: var(--emi-muted); }
        .datos { margin-block: 1.4rem 1.1rem; break-inside: avoid; }
        .datos h1 { font-size: 1.65em; margin: 0 0 .45rem; }
        .datos p { margin: .25rem 0; color: var(--emi-muted); }
        .tabla { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { text-align: left; padding: .48em; border-bottom: 1px solid var(--emi-line); vertical-align: top; overflow-wrap: break-word; overflow-wrap: anywhere; }
        th { background: #edf2f8; }
        thead { display: table-header-group; }
        tr { break-inside: avoid; }
        .danos th:nth-child(1), .danos th:nth-child(4) { width: 18%; }
        .danos th:nth-child(2) { width: 13%; }
        .danos th:nth-child(3) { width: 15%; }
        .deudores th:nth-child(2) { width: 27%; }
        .deudores th:nth-child(3) { width: 14%; }
        .deudores th:nth-child(5), .deudores td:nth-child(5) { text-align: right; }
        .pie { margin-top: 1.2rem; padding-top: .6rem; border-top: 1px solid var(--emi-gold); color: var(--emi-muted); font-size: .85em; break-inside: avoid; }

        @media screen and (max-width: 540px) { table { min-width: 480px; } }

        @media print {
            body { background: white; font: 10pt/1.4 "DejaVu Sans", Arial, sans-serif; print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            .acciones { display: none; }
            .documento { width: 100%; margin: 0; padding: 0; border: 0; }
            .tabla { overflow: visible; }
        }
    </style>
</head>
<body>
    @empty($descargaPdf)
        <div class="acciones">
            <a href="{{ route('reportes', ['tipo' => $tipo, 'estado' => $estado]) }}">← Volver al reporte</a>
            <button type="button" onclick="window.print()">Imprimir</button>
        </div>
    @endempty
    <main class="documento">
        <header class="membrete">
            <span class="marca" aria-hidden="true">EMI</span>
            <div class="identidad">
                <div class="institucion">Escuela Militar de Ingeniería</div>
                <div class="unidad">Unidad Académica Trópico · Gestión de Residencias</div>
            </div>
        </header>
        <section class="datos" aria-labelledby="titulo">
            <h1 id="titulo">{{ $titulo }}</h1>
            <p><strong>Fecha de emisión</strong> <time datetime="{{ $fechaCorte->toDateString() }}">{{ $fechaCorte->format('d/m/Y') }}</time></p>
            @if($tipo === 'danos')
                <p><strong>Estado</strong> {{ match ($estado) { 'abiertas' => 'Incidencias abiertas', 'todas' => 'Todas las incidencias', default => $estado } }}</p>
            @else
                <p>Cuotas pendientes con fecha de fin anterior a la fecha de emisión.</p>
            @endif
        </section>
        <div class="tabla {{ $tipo }}">
            @include('reportes.tabla')
        </div>
        <footer class="pie">Residencias universitarias · EMI Trópico</footer>
    </main>
</body>
</html>
