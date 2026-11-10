
@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-6 rounded-t-xl text-white">
        <h2 class="text-2xl font-bold">💰 Lista de Pagos</h2>
        <p class="text-sm opacity-90">
            Control de mensualidades, cobros y deudas
        </p>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- ESTADÍSTICAS --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

            <div class="bg-green-100 p-4 rounded-lg shadow">
                <p class="text-sm text-gray-600">Recaudado</p>
                <p class="text-2xl font-bold text-green-700">
                    Bs {{ number_format($totalRecaudado, 2) }}
                </p>
            </div>

            <div class="bg-red-100 p-4 rounded-lg shadow">
                <p class="text-sm text-gray-600">Deuda Total</p>
                <p class="text-2xl font-bold text-red-700">
                    Bs {{ number_format($totalDeuda, 2) }}
                </p>
            </div>

            <div class="bg-blue-100 p-4 rounded-lg shadow">
                <p class="text-sm text-gray-600">Contratos</p>
                <p class="text-2xl font-bold text-blue-700">
                    {{ $registros->count() }}
                </p>
            </div>

            <div class="bg-yellow-100 p-4 rounded-lg shadow">
                <p class="text-sm text-gray-600">Residentes con Deuda</p>
                <p class="text-2xl font-bold text-yellow-700">
                    {{ $residentesConDeuda }}
                </p>
            </div>

        </div>

        {{-- BUSCADOR --}}
        <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

            <form method="GET"
                  action="{{ route('pagos.index') }}"
                  class="flex gap-2 w-full md:w-2/3">

                <input type="text"
                       name="buscar"
                       value="{{ $buscar ?? '' }}"
                       placeholder="Buscar por residente o habitación..."
                       class="w-full border rounded-lg px-4 py-2">

                <button type="submit"
                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg">
                    Buscar
                </button>
            </form>


            <a href="{{ route('pagos.create') }}"
            class="inline-flex items-center justify-center gap-2
                    self-start md:self-center
                    bg-green-600 hover:bg-green-700
                    text-white font-semibold
                    px-5 py-2.5 rounded-lg
                    shadow-sm hover:shadow-md
                    transition-all duration-200
                    whitespace-nowrap">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 4v16m8-8H4"/>
                </svg>

                Nuevo Pago

            </a>


        </div>

        {{-- TABLA --}}
        <div class="overflow-x-auto">
            <table class="w-full border rounded-lg overflow-hidden">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Residente</th>
                        <th class="px-4 py-3 text-left">Habitación</th>
                        <th class="px-4 py-3 text-center">Meses</th>
                        <th class="px-4 py-3 text-center">Total Contrato</th>
                        <th class="px-4 py-3 text-center">Pagado</th>
                        <th class="px-4 py-3 text-center">Deuda</th>
                        <th class="px-4 py-3 text-center">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($registros as $registro)

                        @php
                            $pagado = $registro->pagos
                                ->where('estado', 'Pagado')
                                ->sum('monto');

                            $deuda = $registro->mensualidades
                                ->where('estado', 'Pendiente')
                                ->sum('monto');

                            $ultimoPago = $registro->pagos
                                ->sortByDesc('id')
                                ->first();
                        @endphp

                        <tr class="border-t hover:bg-gray-50">

                            <td class="px-4 py-3">
                                {{ $registro->user->name }}
                            </td>

                            <td class="px-4 py-3">
                                Habitación {{ $registro->habitacion->numero }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                {{ $registro->cantidad_meses ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                Bs {{ number_format($registro->monto_total, 2) }}
                            </td>

                            <td class="px-4 py-3 text-center text-green-600 font-semibold">
                                Bs {{ number_format($pagado, 2) }}
                            </td>

                            <td class="px-4 py-3 text-center font-bold">
                                <span class="{{ $deuda > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    Bs {{ number_format($deuda, 2) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if($deuda > 0)
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                        Pendiente
                                    </span>
                                @else
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                        Pagado
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-center">
                                @if($ultimoPago)
                                    <a href="{{ route('pagos.show', $ultimoPago->id) }}"
                                       class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                        Ver último pago
                                    </a>
                                @elseif($deuda > 0)
                                    <a href="{{ route('pagos.create') }}"
                                       class="inline-block bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                                        Cobrar
                                    </a>
                                @else
                                    <span class="text-gray-400">Sin pagos</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-500">
                                No hay registros de residencia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
