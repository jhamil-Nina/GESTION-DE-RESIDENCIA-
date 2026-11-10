
@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 pb-10 space-y-7">

    {{-- BANNER --}}
    <div class="relative h-64 md:h-72 rounded-3xl overflow-hidden shadow-lg">

        <img
            src="{{ asset('images/banner-residencia1.jpg') }}"
            alt="Residencias Universitarias"
            class="absolute inset-0 w-full h-full object-cover">

        <div class="absolute inset-0 bg-gradient-to-r from-blue-950/90 via-blue-900/75 to-blue-800/40"></div>

        <div class="relative z-10 h-full flex flex-col justify-center px-6 md:px-10 text-white">

            <span class="text-blue-100 text-sm font-semibold uppercase tracking-widest mb-3">
                Panel Administrativo
            </span>

            <h1 class="text-3xl md:text-4xl font-bold max-w-2xl">
                Sistema de Gestión de Residencias
            </h1>

            <p class="mt-3 text-blue-100 text-sm md:text-base">
                Control y seguimiento de la residencia universitaria
            </p>

            <div class="mt-5">
                <span class="inline-block bg-white/20 border border-white/20 px-4 py-2 rounded-xl text-sm">
                    Bienvenido, {{ Auth::user()->name }}
                </span>
            </div>

        </div>
    </div>

    {{-- INDICADORES PRINCIPALES --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <a href="{{ route('registro_residencias.index') }}"
           class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">

            <div class="flex items-center justify-between">
                <span class="text-gray-500 text-sm">Residentes Activos</span>
                <span class="bg-blue-100 text-blue-700 rounded-xl p-2">👥</span>
            </div>

            <p class="text-3xl font-bold text-blue-600 mt-3">
                {{ $residentesActivos }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                {{ $usuarios }} usuarios registrados
            </p>
        </a>

        <a href="{{ route('habitacions.index') }}"
           class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">

            <div class="flex items-center justify-between">
                <span class="text-gray-500 text-sm">Habitaciones</span>
                <span class="bg-green-100 text-green-700 rounded-xl p-2">🛏️</span>
            </div>

            <p class="text-3xl font-bold text-green-600 mt-3">
                {{ $habitaciones }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                {{ $habitacionesOcupadas }} ocupadas actualmente
            </p>
        </a>

        <a href="{{ route('pagos.index') }}"
           class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">

            <div class="flex items-center justify-between">
                <span class="text-gray-500 text-sm">Total Recaudado</span>
                <span class="bg-emerald-100 text-emerald-700 rounded-xl p-2">💰</span>
            </div>

            <p class="text-3xl font-bold text-emerald-600 mt-3">
                Bs {{ number_format($totalRecaudado, 2) }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                {{ $pagos }} pagos registrados
            </p>
        </a>

        <a href="{{ route('pagos.index') }}"
           class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-md transition">

            <div class="flex items-center justify-between">
                <span class="text-gray-500 text-sm">Deuda Pendiente</span>
                <span class="bg-red-100 text-red-700 rounded-xl p-2">📋</span>
            </div>

            <p class="text-3xl font-bold text-red-600 mt-3">
                Bs {{ number_format($deudaPendiente, 2) }}
            </p>

            <p class="text-xs text-gray-500 mt-2">
                {{ $cuotasPendientes }} mensualidades pendientes
            </p>
        </a>

    </div>

    {{-- OCUPACIÓN GENERAL --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold text-gray-800">
                    Ocupación General
                </h2>
                <p class="text-sm text-gray-500">
                    Situación actual de las habitaciones
                </p>
            </div>

            <span class="text-2xl font-bold text-blue-600">
                {{ $porcentajeOcupacion }}%
            </span>
        </div>

        <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
            <div
                class="bg-gradient-to-r from-blue-500 to-indigo-600 h-4 rounded-full transition-all duration-500"
                style="width: {{ min(100, max(0, $porcentajeOcupacion)) }}%;">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">

            <div class="bg-blue-50 rounded-xl p-4">
                <p class="text-sm text-gray-600">Ocupadas</p>
                <p class="text-2xl font-bold text-blue-700">
                    {{ $habitacionesOcupadas }}
                </p>
            </div>

            <div class="bg-green-50 rounded-xl p-4">
                <p class="text-sm text-gray-600">Libres</p>
                <p class="text-2xl font-bold text-green-700">
                    {{ $habitacionesDisponibles }}
                </p>
            </div>

            <div class="bg-amber-50 rounded-xl p-4">
                <p class="text-sm text-gray-600">Mantenimiento</p>
                <p class="text-2xl font-bold text-amber-700">
                    {{ $habitacionesMantenimiento }}
                </p>
            </div>

        </div>

        <a href="{{ route('habitacions.index') }}"
           class="inline-block text-blue-600 hover:text-blue-800 text-sm font-semibold mt-5">
            Ver habitaciones →
        </a>
    </div>

    {{-- OBSERVACIONES Y REGISTROS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- OBSERVACIONES --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-lg text-gray-800">
                    Estado de Observaciones
                </h2>

                <a href="{{ route('observacions.index') }}"
                   class="text-sm text-blue-600 hover:underline">
                    Ver todas
                </a>
            </div>

            <div class="space-y-3">

                <div class="flex items-center justify-between bg-red-50 rounded-xl p-4">
                    <span class="text-red-700 font-medium">
                        Pendientes
                    </span>
                    <span class="font-bold text-red-700">
                        {{ $observacionesPendientes }}
                    </span>
                </div>

                <div class="flex items-center justify-between bg-amber-50 rounded-xl p-4">
                    <span class="text-amber-700 font-medium">
                        En reparación
                    </span>
                    <span class="font-bold text-amber-700">
                        {{ $observacionesReparacion }}
                    </span>
                </div>

                <div class="flex items-center justify-between bg-green-50 rounded-xl p-4">
                    <span class="text-green-700 font-medium">
                        Solucionadas
                    </span>
                    <span class="font-bold text-green-700">
                        {{ $observacionesSolucionadas }}
                    </span>
                </div>

            </div>
        </div>

        {{-- ÚLTIMOS INGRESOS --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-lg text-gray-800">
                    Últimos Ingresos
                </h2>

                <a href="{{ route('registro_residencias.index') }}"
                   class="text-sm text-blue-600 hover:underline">
                    Ver todos
                </a>
            </div>

            <div class="divide-y divide-gray-100">

                @forelse($ultimosIngresos as $registro)
                    <div class="py-3 flex justify-between gap-3">

                        <div>
                            <p class="font-semibold text-gray-800">
                                {{ $registro->user->name ?? 'Sin usuario' }}
                            </p>

                            <p class="text-sm text-gray-500">
                                Habitación {{ $registro->habitacion->numero ?? 'N/D' }}
                            </p>
                        </div>

                        <span class="text-xs text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($registro->fecha_ingreso)->format('d/m/Y') }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm py-4">
                        Todavía no existen ingresos registrados.
                    </p>
                @endforelse

            </div>
        </div>

    </div>

    {{-- ÚLTIMOS PAGOS --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

        <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-lg text-gray-800">
                Últimos Pagos
            </h2>

            <a href="{{ route('pagos.index') }}"
               class="text-sm text-blue-600 hover:underline">
                Ver todos
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3">Residente</th>
                        <th class="text-left px-4 py-3">Fecha</th>
                        <th class="text-left px-4 py-3">Método</th>
                        <th class="text-right px-4 py-3">Monto</th>
                        <th class="text-center px-4 py-3">Detalle</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($ultimosPagos as $pago)
                        <tr class="border-t border-gray-100">

                            <td class="px-4 py-3">
                                {{ $pago->registroResidencia->user->name ?? 'Sin residente' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $pago->metodo_pago }}
                            </td>

                            <td class="px-4 py-3 text-right font-semibold text-green-600">
                                Bs {{ number_format($pago->monto, 2) }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('pagos.show', $pago->id) }}"
                                   class="text-blue-600 hover:underline">
                                    Ver
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">
                                Todavía no hay pagos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    {{-- ACCIONES RÁPIDAS --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

        <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-lg text-gray-800">
                Acciones Rápidas
            </h2>

            <span class="text-sm text-gray-500">
                Accesos administrativos
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

            <a href="{{ route('users.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-4 rounded-xl text-center font-medium transition">
                + Nuevo Residente
            </a>

            <a href="{{ route('registro_residencias.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-4 rounded-xl text-center font-medium transition">
                + Registrar Residencia
            </a>

            <a href="{{ route('pagos.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-4 rounded-xl text-center font-medium transition">
                + Registrar Pago
            </a>

            <a href="{{ route('habitacions.create') }}"
               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-4 rounded-xl text-center font-medium transition">
                + Nueva Habitación
            </a>

            <a href="{{ route('observacions.create') }}"
               class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-4 rounded-xl text-center font-medium transition">
                + Nueva Observación
            </a>

            <a href="{{ route('antecedentes.create') }}"
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-4 rounded-xl text-center font-medium transition">
                + Registrar Antecedente
            </a>

        </div>
    </div>

</div>

@endsection
