{{-- resources/views/residencias/edit.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ✏ Editar Residencia
        </h2>

    </div>

    <!-- FORM -->
    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('residencias.update', $residencia->id) }}" method="POST">

            @csrf
            @method('PUT')

            <!-- NOMBRE -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    value="{{ old('nombre', $residencia->nombre) }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- DIRECCION -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Dirección
                </label>

                <input
                    type="text"
                    name="direccion"
                    value="{{ old('direccion', $residencia->direccion) }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- CAPACIDAD -->
            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Capacidad
                </label>

                <input
                    type="number"
                    name="capacidad"
                    value="{{ old('capacidad', $residencia->capacidad) }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <!-- BOTONES -->
            <div class="flex justify-end gap-3">

                <a href="{{ route('residencias.index') }}"
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