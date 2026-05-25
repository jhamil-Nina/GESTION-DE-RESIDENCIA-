@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            👁 Detalle del Registro
        </h2>

    </div>

    <div class="bg-white p-6 rounded-b-xl shadow space-y-4">

        <div>

            <p class="text-gray-500 text-sm">Residente</p>

            <p class="font-semibold">
                {{ $registro->user->name }}
            </p>

        </div>

        <div>

            <p class="text-gray-500 text-sm">Habitación</p>

            <p class="font-semibold">
                {{ $registro->habitacion->numero }}
            </p>

        </div>

        <div>

            <p class="text-gray-500 text-sm">Categoría</p>

            <p class="font-semibold">
                {{ $registro->categoriaOcupacion->nombre }}
            </p>

        </div>

        <div>

            <p class="text-gray-500 text-sm">Fecha Ingreso</p>

            <p class="font-semibold">
                {{ $registro->fecha_ingreso }}
            </p>

        </div>

        <div>

            <p class="text-gray-500 text-sm">Fecha Salida</p>

            <p class="font-semibold">
                {{ $registro->fecha_salida ?? '—' }}
            </p>

        </div>

        <div class="pt-4">

            <a href="{{ route('registro_residencias.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Volver

            </a>

        </div>

    </div>

</div>

@endsection