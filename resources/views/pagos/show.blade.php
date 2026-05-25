@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            👁 Detalle de Pago
        </h2>

    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        <div class="space-y-4">

            <div>
                <p class="text-gray-500 text-sm">ID</p>
                <p class="font-semibold">{{ $pago->id }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Estudiante</p>
                <p class="font-semibold">
                    {{ $pago->registroResidencia->user->name }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Habitación</p>
                <p class="font-semibold">
                    {{ $pago->registroResidencia->habitacion->numero }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Monto</p>
                <p class="font-semibold">Bs {{ $pago->monto }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Fecha Pago</p>
                <p class="font-semibold">{{ $pago->fecha_pago }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Método</p>
                <p class="font-semibold">{{ $pago->metodo_pago }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Estado</p>
                <p class="font-semibold">{{ $pago->estado }}</p>
            </div>

        </div>

        <div class="mt-6">

            <a href="{{ route('pagos.index') }}"
                class="bg-gray-500 text-white px-5 py-2 rounded-lg">

                Volver

            </a>

        </div>

    </div>

</div>

@endsection