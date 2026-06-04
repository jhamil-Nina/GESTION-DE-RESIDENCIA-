@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ➕ Nueva Observación
        </h2>

        <p class="text-sm opacity-90">
            Registrar una observación disciplinaria
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('observacions.store') }}" method="POST">

            @csrf

            {{-- REGISTRO --}}
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Registro de Residencia
                </label>

                <select
                    name="registro_residencia_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

                    <option value="">
                        Seleccione un residente
                    </option>

                    @foreach($registros as $registro)

                    <option
                        value="{{ $registro->id }}"
                        {{ old('registro_residencia_id') == $registro->id ? 'selected' : '' }}>

                        {{ $registro->user->name }} - Habitación {{ $registro->habitacion->numero }}

                    </option>

                    @endforeach

                </select>

                @error('registro_residencia_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Descripción
                </label>

                <textarea
                    name="descripcion"
                    rows="4"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('descripcion') }}</textarea>

                @error('descripcion')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            {{-- FECHA --}}
            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Fecha
                </label>

                <input
                    type="date"
                    name="fecha"
                    value="{{ old('fecha') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">

                @error('fecha')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3">

                <a href="{{ route('observacions.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                    Cancelar

                </a>

                <button
                    type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg">

                    Guardar

                </button>

            </div>

        </form>

    </div>

</div>

@endsection