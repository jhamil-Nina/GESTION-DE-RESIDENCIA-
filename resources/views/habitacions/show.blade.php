{{-- resources/views/habitacions/show.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="module-heading p-6 rounded-t-xl ">

        <h2 class="text-2xl font-bold">
            👁 Detalle de Habitación
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
                    {{ $habitacion->id }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">
                    Número
                </p>

                <p class="font-semibold">
                    {{ $habitacion->numero }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">
                    Capacidad
                </p>

                <p class="font-semibold">
                    {{ $habitacion->capacidad }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Tipo de habitación</p>
                <p class="font-semibold">{{ $habitacion->tipoHabitacion?->nombre ?? 'Sin asignar' }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">
                    Tarifa mensual por residente
                </p>

                <p class="font-semibold text-green-600">
                    Bs {{ number_format($habitacion->costo_mensual, 2) }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">
                    Estado
                </p>

                <p class="font-semibold">
                    {{ $habitacion->estado }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">
                    Residencia
                </p>

                <p class="font-semibold">
                    {{ $habitacion->residencia->nombre }}
                </p>
            </div>

        </div>

        <!-- BOTON -->
        <div class="mt-6">

            <a href="{{ route('habitacions.index') }}"
                class="module-secondary px-5 py-2 rounded-lg">

                Volver

            </a>

        </div>

    </div>

</div>

@endsection