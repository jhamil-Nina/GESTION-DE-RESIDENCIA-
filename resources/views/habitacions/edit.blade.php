{{-- resources/views/habitacions/edit.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ✏ Editar Habitación
        </h2>

    </div>

    <!-- FORM -->
    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('habitacions.update', $habitacion->id) }}" method="POST">

            @csrf
            @method('PUT')

            <!-- NUMERO -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Número
                </label>

                <input
                    type="text"
                    name="numero"
                    value="{{ old('numero', $habitacion->numero) }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- CAPACIDAD -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Capacidad
                </label>

                <input
                    type="number"
                    name="capacidad"
                    value="{{ old('capacidad', $habitacion->capacidad) }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- RESIDENCIA -->
            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Residencia
                </label>

                <select
                    name="residencia_id"
                    class="w-full border rounded-lg px-4 py-2">

                    @foreach($residencias as $residencia)

                    <option
                        value="{{ $residencia->id }}"
                        {{ $habitacion->residencia_id == $residencia->id ? 'selected' : '' }}>

                        {{ $residencia->nombre }}

                    </option>

                    @endforeach

                </select>

            </div>

            <!-- COSTO MENSUAL -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Costo Mensual (Bs)
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="costo_mensual"
                    value="{{ old('costo_mensual', $habitacion->costo_mensual) }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- ESTADO -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Estado
                </label>

                <select
                    name="estado"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="Disponible"
                        {{ $habitacion->estado == 'Disponible' ? 'selected' : '' }}>
                        Disponible
                    </option>

                    <option value="Ocupada"
                        {{ $habitacion->estado == 'Ocupada' ? 'selected' : '' }}>
                        Ocupada
                    </option>

                    <option value="Mantenimiento"
                        {{ $habitacion->estado == 'Mantenimiento' ? 'selected' : '' }}>
                        Mantenimiento
                    </option>

                </select>

            </div>

            <!-- BOTONES -->
            <div class="flex justify-end gap-3">

                <a href="{{ route('habitacions.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                    Cancelar

                </a>

                <button
                    type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg">

                    Actualizar

                </button>

            </div>

        </form>

    </div>

</div>

@endsection