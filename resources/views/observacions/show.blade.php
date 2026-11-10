
@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            📋 Detalle de Observación
        </h2>

        <p class="text-sm opacity-90 mt-1">
            Información del daño, reparación y ocupantes asociados
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-b-xl shadow">

        {{-- INFORMACIÓN GENERAL --}}
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            Información general
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

            <div>
                <p class="text-sm text-gray-500">N.º de observación</p>
                <p class="font-semibold text-gray-800">
                    {{ $observacion->id }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Habitación</p>
                <p class="font-semibold text-gray-800">
                    Habitación {{ $observacion->habitacion->numero }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Fecha de registro</p>
                <p class="font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($observacion->fecha)->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Estado actual</p>

                @if($observacion->estado === 'Pendiente')

                    <span class="inline-block mt-1 bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                        Pendiente
                    </span>

                @elseif($observacion->estado === 'En reparación')

                    <span class="inline-block mt-1 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                        En reparación
                    </span>

                @else

                    <span class="inline-block mt-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                        Solucionado
                    </span>

                @endif

            </div>

        </div>

        {{-- DESCRIPCIÓN --}}
        <div class="mb-6">

            <h3 class="text-lg font-bold text-gray-800 mb-3">
                Descripción del daño
            </h3>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-gray-700 whitespace-pre-line">{{ $observacion->descripcion }}</div>

        </div>

        {{-- SEGUIMIENTO --}}
        <div class="mb-6">

            <h3 class="text-lg font-bold text-gray-800 mb-3">
                Seguimiento de reparación
            </h3>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">

                @if($observacion->fecha_solucion)

                    <p class="text-sm text-gray-600 mb-1">
                        Fecha y hora de solución
                    </p>

                    <p class="font-semibold text-green-700">
                        {{ \Carbon\Carbon::parse($observacion->fecha_solucion)->format('d/m/Y H:i') }}
                    </p>

                @else

                    <p class="text-sm text-gray-700">
                        Esta observación todavía no tiene una fecha de solución registrada.
                    </p>

                @endif

            </div>

        </div>

        {{-- HISTORIAL DE OCUPANTES --}}
        <div class="mb-6">

            <h3 class="text-lg font-bold text-gray-800 mb-2">
                Residentes asociados
            </h3>

            <p class="text-sm text-gray-500 mb-4">
                Personas que ocupaban la habitación cuando se registró
                la observación. Esta información no determina
                responsabilidad por el daño.
            </p>

            @if($observacion->registrosResidencia->isNotEmpty())

                <div class="overflow-x-auto">

                    <table class="w-full border border-gray-200 rounded-lg">

                        <thead class="bg-gray-100 text-gray-700">

                            <tr>
                                <th class="text-left px-4 py-3">
                                    Residente
                                </th>

                                <th class="text-left px-4 py-3">
                                    Fecha de ingreso
                                </th>

                                <th class="text-left px-4 py-3">
                                    Fecha de salida
                                </th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($observacion->registrosResidencia as $registro)

                                <tr class="border-t hover:bg-gray-50">

                                    <td class="px-4 py-3 font-medium">
                                        {{ $registro->user->name ?? 'Usuario no disponible' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ \Carbon\Carbon::parse($registro->fecha_ingreso)->format('d/m/Y') }}
                                    </td>

                                    <td class="px-4 py-3">

                                        @if($registro->fecha_salida)

                                            {{ \Carbon\Carbon::parse($registro->fecha_salida)->format('d/m/Y') }}

                                        @else

                                            <span class="text-gray-500">
                                                Sin fecha de salida
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">

                    <p class="text-gray-600 text-sm">
                        No había residentes asociados a esta habitación
                        al momento de registrar la observación.
                    </p>

                </div>

            @endif

        </div>

        {{-- BOTONES --}}
        <div class="flex flex-wrap justify-end gap-3 border-t pt-5">

            <a href="{{ route('observacions.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition">
                Volver
            </a>

            <a href="{{ route('observacions.edit', $observacion) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg transition">
                Editar Observación
            </a>

        </div>

    </div>

</div>

@endsection
