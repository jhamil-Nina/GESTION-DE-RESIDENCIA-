{{-- resources/views/residencias/show.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="module-heading p-6 rounded-t-xl ">

        <h2 class="text-2xl font-bold">
            👁 Detalle de Residencia
        </h2>

    </div>

    <!-- CARD -->
    <div class="bg-white p-6 rounded-b-xl shadow">

        <div class="space-y-4">

            <div>
                <p class="text-gray-500 text-sm">
                    ID
                </p>

                <p class="font-semibold">
                    {{ $residencia->id }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">
                    Nombre
                </p>

                <p class="font-semibold">
                    {{ $residencia->nombre }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">
                    Dirección
                </p>

                <p class="font-semibold">
                    {{ $residencia->direccion }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">
                    Capacidad máxima de residentes
                </p>

                <p class="font-semibold">
                    {{ $residencia->capacidad }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Máximo de habitaciones</p>
                <p class="font-semibold">{{ $residencia->max_habitaciones ?? 'Sin definir' }}</p>
            </div>

        </div>

        <!-- BOTON -->
        <div class="mt-6">

            <a href="{{ route('residencias.index') }}"
                class="module-secondary px-5 py-2 rounded-lg">

                Volver

            </a>

        </div>

    </div>

</div>

@endsection