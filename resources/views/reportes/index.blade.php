@extends('layouts.app')

@section('content')
<style>
    .reporte table { width: 100%; text-align: left; border-collapse: collapse; }
    .reporte th, .reporte td { padding: .75rem; border-bottom: 1px solid var(--emi-line); vertical-align: top; }
    .reporte td { overflow-wrap: anywhere; }
</style>
<div class="max-w-7xl mx-auto p-4 space-y-4">
    <h1 class="module-heading rounded-xl p-6 text-2xl font-bold">Reportes</h1>
    <nav aria-label="Tipos de reporte" class="flex flex-wrap gap-3">
        @foreach(['danos' => 'Habitaciones dañadas', 'deudores' => 'Residentes deudores'] as $clave => $nombre)
            <a href="{{ route('reportes', $clave) }}" @if($tipo === $clave) aria-current="page" @endif
                class="rounded-lg px-4 py-2 {{ $tipo === $clave ? 'module-primary' : 'module-secondary' }}">{{ $nombre }}</a>
        @endforeach
    </nav>

    <section class="bg-white rounded-xl shadow-sm p-5 space-y-4">
        <div class="flex flex-wrap justify-between gap-4">
            @if($tipo === 'danos')
                <form action="{{ route('reportes', $tipo) }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <label for="estado" class="font-semibold">Estado</label>
                    <select id="estado" name="estado" class="rounded-lg border-gray-300">
                        @foreach(['abiertas' => 'Incidencias abiertas', 'Pendiente' => 'Pendiente', 'En reparación' => 'En reparación', 'Solucionado' => 'Solucionado', 'todas' => 'Todas'] as $clave => $nombre)
                            <option value="{{ $clave }}" @selected($estado === $clave)>{{ $nombre }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-lg border px-3 py-2">Aplicar</button>
                </form>
            @else
                <p class="text-sm text-gray-600">Cuotas pendientes con fecha de fin anterior al {{ $fechaCorte->format('d/m/Y') }}.</p>
            @endif
            <div class="flex flex-wrap items-center gap-2">
                <a class="module-secondary rounded-lg px-4 py-2" href="{{ route('reportes', ['tipo' => $tipo, 'estado' => $estado, 'formato' => 'imprimir']) }}">Imprimir</a>
                <a class="module-primary rounded-lg px-4 py-2" href="{{ route('reportes', ['tipo' => $tipo, 'estado' => $estado, 'formato' => 'pdf']) }}">Guardar PDF</a>
            </div>
        </div>
        <div class="reporte overflow-x-auto">@include('reportes.tabla')</div>
        {{ $registros->links() }}
    </section>
</div>
@endsection
