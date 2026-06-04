@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-6 rounded-t-xl text-white">
        <h2 class="text-2xl font-bold">💰 Lista de Pagos</h2>
        <p class="text-sm opacity-90">Administra los pagos de las residencias</p>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <!-- ESTADISTICAS -->
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
                <p class="text-sm text-gray-600">Residentes</p>
                <p class="text-2xl font-bold text-blue-700">
                    {{ $registros->count() }}
                </p>
            </div>

            <div class="bg-yellow-100 p-4 rounded-lg shadow">
                <p class="text-sm text-gray-600">Con Deuda</p>
                <p class="text-2xl font-bold text-yellow-700">
                    {{ $residentesConDeuda }}
                </p>
            </div>

        </div>
        <!-- BUSCADOR -->
        <div class="flex justify-between items-center mb-6 gap-4">

            <form method="GET" action="{{ route('pagos.index') }}" class="flex gap-2 w-2/3">

                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar ?? '' }}"
                    placeholder="Buscar pago..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">

                <button
                    type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg">
                    Buscar
                </button>

            </form>

            <a href="{{ route('pagos.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow">

                + Nuevo Pago

            </a>

        </div>

        <table class="w-full border rounded-lg overflow-hidden">

            ```
            <thead class="bg-gray-100">

                <tr>

                    <th class="px-4 py-3 text-left">Estudiante</th>

                    <th class="px-4 py-3 text-left">Habitación</th>

                    <th class="px-4 py-3 text-center">Costo Mensual</th>

                    <th class="px-4 py-3 text-center">Total Pagado</th>

                    <th class="px-4 py-3 text-center">Deuda</th>

                    <th class="px-4 py-3 text-center">Estado</th>

                    <th class="px-4 py-3 text-center">Acciones</th>

                </tr>

            </thead>

            <tbody>

                @forelse($registros as $registro)

                @php

                $costo = $registro->habitacion->costo_mensual ?? 0;

                $pagado = $registro->pagos->sum('monto');

                $deuda = max(0, $costo - $pagado);

                $ultimoPago = $registro->pagos->sortByDesc('id')->first();

                @endphp

                <tr class="border-t hover:bg-gray-50">

                    <td class="px-4 py-3">
                        {{ $registro->user->name }}
                    </td>

                    <td class="px-4 py-3">
                        Habitación {{ $registro->habitacion->numero }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        Bs {{ number_format($costo, 2) }}
                    </td>

                    <td class="px-4 py-3 text-center text-green-600 font-semibold">
                        Bs {{ number_format($pagado, 2) }}
                    </td>

                    <td class="px-4 py-3 text-center font-bold">

                        @if($deuda > 0)

                        <span class="text-red-600">
                            Bs {{ number_format($deuda, 2) }}
                        </span>

                        @else

                        <span class="text-green-600">
                            Bs 0.00
                        </span>

                        @endif

                    </td>

                    <td class="px-4 py-3 text-center">

                        @if($deuda > 0)

                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                            Deuda
                        </span>

                        @else

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            Pagado
                        </span>

                        @endif

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-center items-center gap-2">
                            @if($ultimoPago)

                            <!-- VER -->
                            <a href="{{ route('pagos.show', $ultimoPago->id) }}"
                                class="w-24 h-10 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-lg">

                                Ver

                            </a>

                            <!-- EDITAR -->
                            <a href="{{ route('pagos.edit', $ultimoPago->id) }}"
                                class="w-24 h-10 flex items-center justify-center bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg">

                                Editar

                            </a>

                            <!-- ELIMINAR -->
                            <form action="{{ route('pagos.destroy', $ultimoPago->id) }}"
                                method="POST">
                                class="inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('¿Seguro que deseas eliminar este pago?')"
                                    class="w-24 h-10 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-lg">

                                    Eliminar

                                </button>

                            </form>

                            @else

                            <span class="text-gray-400 text-sm">
                                Sin pagos
                            </span>

                            @endif

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7"
                        class="text-center py-6 text-gray-500">

                        No hay registros de residencia

                    </td>

                </tr>

                @endforelse

            </tbody>
            ```

        </table>


    </div>

</div>

@endsection