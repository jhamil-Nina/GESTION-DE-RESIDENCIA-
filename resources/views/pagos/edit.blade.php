
@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-t-xl text-white">
        <h2 class="text-2xl font-bold">
            ✏ Modificación de Pago
        </h2>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-5 mb-6">

            <h3 class="font-bold text-yellow-800 mb-2">
                Pago protegido
            </h3>

            <p class="text-gray-700">
                Los pagos registrados no pueden modificarse directamente
                porque están relacionados con mensualidades canceladas.
            </p>

            <p class="text-gray-700 mt-2">
                Para corregir un pago será necesario implementar
                un procedimiento de anulación que conserve el historial.
            </p>

        </div>

        <a href="{{ route('pagos.index') }}"
           class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
            Volver
        </a>

    </div>

</div>

@endsection
