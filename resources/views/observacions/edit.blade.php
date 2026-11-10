
@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ✏️ Editar Observación
        </h2>

        <p class="text-sm opacity-90 mt-1">
            Actualizar el seguimiento de daños y reparaciones
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('observacions.update', $observacion) }}" method="POST">

            @csrf
            @method('PUT')

            {{-- HABITACIÓN --}}
            <div class="mb-5">

                <label class="block font-semibold mb-2 text-gray-700">
                    Habitación
                </label>

                <input
                    type="text"
                    value="Habitación {{ $observacion->habitacion->numero }}"
                    disabled
                    class="w-full border border-gray-200 bg-gray-100 text-gray-600 rounded-lg px-4 py-2">

                <input
                    type="hidden"
                    name="habitacion_id"
                    value="{{ $observacion->habitacion_id }}">

                <p class="text-sm text-gray-500 mt-2">
                    La habitación no puede modificarse para conservar el historial.
                </p>

                @error('habitacion_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="mb-5">

                <label for="descripcion" class="block font-semibold mb-2 text-gray-700">
                    Descripción del daño
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="4"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">{{ old('descripcion', $observacion->descripcion) }}</textarea>

                @error('descripcion')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            {{-- FECHA DE REGISTRO --}}
            <div class="mb-5">

                <label class="block font-semibold mb-2 text-gray-700">
                    Fecha de registro
                </label>

                <input
                    type="text"
                    value="{{ $observacion->fecha }}"
                    disabled
                    class="w-full border border-gray-200 bg-gray-100 text-gray-600 rounded-lg px-4 py-2">

            </div>

            {{-- ESTADO --}}
            <div class="mb-5">

                <label for="estado" class="block font-semibold mb-2 text-gray-700">
                    Estado de la observación
                </label>

                <select
                    id="estado"
                    name="estado"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500">

                    @foreach(['Pendiente', 'En reparación', 'Solucionado'] as $estado)

                        <option
                            value="{{ $estado }}"
                            {{ old('estado', $observacion->estado) === $estado ? 'selected' : '' }}>
                            {{ $estado }}
                        </option>

                    @endforeach

                </select>

                @error('estado')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            {{-- FECHA DE SOLUCIÓN --}}
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-lg p-4">

                <h3 class="font-semibold text-amber-800 mb-2">
                    Seguimiento automático
                </h3>

                @if($observacion->fecha_solucion)

                    <p class="text-sm text-gray-700">
                        <span class="font-semibold">Fecha de solución:</span>
                        {{ \Carbon\Carbon::parse($observacion->fecha_solucion)->format('d/m/Y H:i') }}
                    </p>

                @else

                    <p class="text-sm text-gray-700">
                        Esta observación todavía no tiene una fecha de solución registrada.
                    </p>

                @endif

                <p class="text-sm text-gray-600 mt-2">
                    Al cambiar el estado a
                    <span class="font-semibold">Solucionado</span>,
                    el sistema registrará automáticamente la fecha y hora.
                </p>

            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3">

                <a href="{{ route('observacions.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg transition">
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg transition">
                    Actualizar Observación
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
