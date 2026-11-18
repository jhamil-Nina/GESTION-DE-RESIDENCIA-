<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Residencias · EMI</title>
    <link rel="stylesheet" href="{{ asset('css/paleta-emi.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
    @php
        $modulos = [
            ['dashboard', 'Resumen'],
            ['residencias.index', 'Residencias'],
            ['users.index', 'Residentes y usuarios'],
            ['habitacions.index', 'Habitaciones'],
            ['tipos_habitacion.index', 'Tipos de habitación'],
            ['registro_residencias.index', 'Contratos'],
            ['pagos.index', 'Pagos'],
            ['observacions.index', 'Observaciones'],
            ['antecedentes.index', 'Antecedentes'],
            ['categoria_ocupacions.index', 'Categorías'],
        ];
        if (auth()->user()->rol === 'admin') {
            $modulos[] = ['reportes', 'Reportes'];
        }
        $indicadores = [
            ['Residentes activos', $residentesActivos, "$usuarios usuarios registrados", 'registro_residencias.index'],
            ['Habitaciones', $habitaciones, "$habitacionesOcupadas ocupadas actualmente", 'habitacions.index'],
            ['Total recaudado', 'Bs ' . number_format($totalRecaudado, 2), "$pagos pagos cobrados", 'pagos.index'],
            ['Deuda pendiente', 'Bs ' . number_format($deudaPendiente, 2), "$cuotasPendientes mensualidades pendientes", 'pagos.index'],
        ];
    @endphp

    <aside class="panel-sidebar">
        <a class="panel-brand" href="{{ route('dashboard') }}">
            <strong>EMI</strong>
            <span>Escuela Militar de Ingeniería<br>Unidad Académica Trópico</span>
        </a>
        <nav aria-label="Módulos administrativos">
            @foreach($modulos as [$ruta, $nombre])
                <a href="{{ route($ruta) }}" @if(request()->routeIs($ruta)) aria-current="page" @endif>{{ $nombre }}</a>
            @endforeach
        </nav>
        <div class="panel-account">
            <a href="{{ route('profile.edit') }}">{{ auth()->user()->name }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </div>
    </aside>

    <main class="panel-main">
        <header class="panel-header">
            <div>
                <h1>Gestión de Residencias</h1>
                <p>Administración de las residencias universitarias</p>
            </div>
            <div class="panel-controls">
                <form action="{{ route('dashboard') }}" method="GET">
                    <label for="periodo">Período de recaudación</label>
                    <div class="period-inputs">
                        <select name="periodo" id="periodo">
                            @foreach($periodos as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected($periodo === $valor)>{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                        <button type="submit">Aplicar</button>
                    </div>
                </form>
                @if(auth()->user()->rol === 'admin')
                    <a class="primary-action" href="{{ route('reportes') }}">Ver reportes <span aria-hidden="true">→</span></a>
                @endif
            </div>
        </header>

        <section class="panel-metrics" aria-label="Indicadores principales">
            @foreach($indicadores as [$etiqueta, $valor, $detalle, $ruta])
                <a class="metric" href="{{ route($ruta) }}">
                    <span class="metric-label">{{ $etiqueta }}</span>
                    <strong>{{ $valor }}</strong>
                    <span class="metric-detail">{{ $detalle }}</span>
                    @if($etiqueta === 'Total recaudado')
                        <span class="metric-detail">{{ $desde ? $desde->format('d/m/Y') . ' – ' . \Illuminate\Support\Carbon::parse($hoy)->format('d/m/Y') : $periodos[$periodo] }}</span>
                    @endif
                </a>
            @endforeach
        </section>

        <div class="panel-grid panel-grid-main">
            <section class="panel-card">
                <div class="card-heading">
                    <h2>Últimos pagos</h2>
                    <a href="{{ route('pagos.index') }}">Ver todos</a>
                </div>
                <div class="payment-table">
                    <table>
                        <thead>
                            <tr><th scope="col">Residente</th><th scope="col">Fecha</th><th scope="col">Método</th><th scope="col">Monto</th><th scope="col">Detalle</th></tr>
                        </thead>
                        <tbody>
                            @forelse($ultimosPagos as $pago)
                                <tr>
                                    <td>{{ $pago->registroResidencia->user->name ?? 'Sin residente' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</td>
                                    <td>{{ $pago->metodo_pago }}</td>
                                    <td>Bs {{ number_format($pago->monto, 2) }}</td>
                                    <td><a href="{{ route('pagos.show', $pago->id) }}" aria-label="Ver pago {{ $pago->id }}">Ver</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5">No hay pagos cobrados en este período.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="panel-card">
                <div class="card-heading">
                    <h2>Ocupación general</h2>
                    <strong>{{ $porcentajeOcupacion }}%</strong>
                </div>
                <progress aria-label="Ocupación de habitaciones" value="{{ $porcentajeOcupacion }}" max="100">{{ $porcentajeOcupacion }}%</progress>
                <dl class="occupancy">
                    <div><dt>Ocupadas</dt><dd>{{ $habitacionesOcupadas }}</dd></div>
                    <div><dt>Libres</dt><dd>{{ $habitacionesDisponibles }}</dd></div>
                </dl>
                <a href="{{ route('habitacions.index') }}">Ver habitaciones →</a>
            </section>
        </div>

        <div class="panel-grid">
            <section class="panel-card">
                <div class="card-heading">
                    <h2>Observaciones</h2>
                    <a href="{{ route('observacions.index') }}">Ver todas</a>
                </div>
                <dl class="detail-list">
                    <div><dt>Pendientes</dt><dd>{{ $observacionesPendientes }}</dd></div>
                    <div><dt>En reparación</dt><dd>{{ $observacionesReparacion }}</dd></div>
                    <div><dt>Solucionadas</dt><dd>{{ $observacionesSolucionadas }}</dd></div>
                </dl>
            </section>

            <section class="panel-card">
                <div class="card-heading">
                    <h2>Últimos ingresos</h2>
                    <a href="{{ route('registro_residencias.index') }}">Ver todos</a>
                </div>
                <ul class="detail-list">
                    @forelse($ultimosIngresos as $registro)
                        <li>
                            <div>
                                <strong>{{ $registro->user->name ?? 'Sin usuario' }}</strong>
                                <span>Habitación {{ $registro->habitacion->numero ?? 'N/D' }}</span>
                            </div>
                            <time datetime="{{ $registro->fecha_ingreso->toDateString() }}">{{ $registro->fecha_ingreso->format('d/m/Y') }}</time>
                        </li>
                    @empty
                        <li>Todavía no existen ingresos registrados.</li>
                    @endforelse
                </ul>
            </section>
        </div>

        <section class="panel-card">
            <div class="card-heading"><h2>Acciones rápidas</h2></div>
            <div class="quick-actions">
                @foreach([
                    ['users.create', 'Nuevo residente'],
                    ['registro_residencias.create', 'Registrar contrato'],
                    ['pagos.create', 'Registrar pago'],
                    ['habitacions.create', 'Nueva habitación'],
                    ['observacions.create', 'Nueva observación'],
                    ['antecedentes.create', 'Registrar antecedente'],
                ] as [$ruta, $nombre])
                    <a href="{{ route($ruta) }}"><span aria-hidden="true">+</span> {{ $nombre }}</a>
                @endforeach
            </div>
        </section>
    </main>
</body>
</html>
