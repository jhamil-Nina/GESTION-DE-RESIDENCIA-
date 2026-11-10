
@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-t-xl text-white">
        <h2 class="text-2xl font-bold">
            ✏ Editar Registro de Residencia
        </h2>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg mb-5">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg mb-5">
                {{ session('error') }}
            </div>
        @endif

        {{-- INFORMACIÓN DEL CONTRATO --}}
        <div class="bg-gray-50 border rounded-lg p-4 mb-5">

            <h3 class="font-bold text-gray-700 mb-3">
                Información del Contrato
            </h3>

            <p class="mb-2">
                <strong>Residente:</strong>
                {{ $registro->user->name }}
            </p>

            <p class="mb-2">
                <strong>Habitación:</strong>
                {{ $registro->habitacion->numero }}
            </p>

            <p class="mb-2">
                <strong>Precio mensual:</strong>
                Bs {{ number_format($registro->precio_mensual, 2) }}
            </p>

            <p>
                <strong>Mensualidades pagadas:</strong>
                {{ $registro->mensualidades->where('estado', 'Pagado')->count() }}
                de {{ $registro->mensualidades->count() }}
            </p>

        </div>

        @if($tienePagos)
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 p-4 rounded-lg mb-5">
                ⚠ Este contrato tiene mensualidades pagadas.
                No se permite modificar su duración ni sus fechas.
            </div>
        @endif

        <form action="{{ route('registro_residencias.update', $registro->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            {{-- FECHA DE INGRESO --}}
            <div class="mb-4">

                <label class="font-semibold block mb-2">
                    Fecha de Ingreso
                </label>

                <input
                    type="date"
                    name="fecha_ingreso"
                    value="{{ old('fecha_ingreso', \Carbon\Carbon::parse($registro->fecha_ingreso)->format('Y-m-d')) }}"
                    @disabled($tienePagos)
                    required
                    class="w-full border rounded-lg px-4 py-2
                    {{ $tienePagos ? 'bg-gray-100 cursor-not-allowed' : '' }}">

            </div>

            {{-- CANTIDAD DE MESES --}}
            <div class="mb-4">

                <label class="font-semibold block mb-2">
                    Cantidad de Meses
                </label>

                <input
                    type="number"
                    name="cantidad_meses"
                    min="1"
                    max="120"
                    step="1"
                    value="{{ old('cantidad_meses', $registro->cantidad_meses) }}"
                    @disabled($tienePagos)
                    required
                    class="w-full border rounded-lg px-4 py-2
                    {{ $tienePagos ? 'bg-gray-100 cursor-not-allowed' : '' }}">

            </div>

            {{-- FECHA DE FINALIZACIÓN --}}
            <div class="mb-4">

                <label class="font-semibold block mb-2">
                    Fecha de Finalización Actual
                </label>

                <input
                    type="date"
                    value="{{ \Carbon\Carbon::parse($registro->fecha_salida)->format('Y-m-d') }}"
                    disabled
                    class="w-full border rounded-lg px-4 py-2 bg-gray-100">

                <p class="text-sm text-gray-500 mt-1">
                    Esta fecha se calcula automáticamente según los meses contratados.
                </p>

            </div>

            {{-- TOTAL ACTUAL --}}
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">

                <p class="font-semibold text-gray-700">
                    Monto total actual del contrato
                </p>

                <p class="text-2xl font-bold text-green-700">
                    Bs {{ number_format($registro->monto_total, 2) }}
                </p>

            </div>

            <div class="flex justify-end gap-3">

                <a href="{{ route('registro_residencias.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
                    Cancelar
                </a>

                @unless($tienePagos)
                    <button
                        type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg">
                        Actualizar
                    </button>
                @endunless

            </div>

        </form>

    </div>

</div>

@endsection
