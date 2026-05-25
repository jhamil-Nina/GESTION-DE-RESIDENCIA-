{{-- resources/views/habitacions/show.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 rounded-t-xl text-white">

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
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Volver

            </a>

        </div>

    </div>

</div>

@endsection