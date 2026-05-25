{{-- resources/views/residencias/create.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ➕ Nueva Residencia
        </h2>

        <p class="text-sm opacity-90">
            Registra una nueva residencia en el sistema
        </p>

    </div>

    <!-- FORM -->
    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('residencias.store') }}" method="POST">

            @csrf

            <!-- NOMBRE -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    value="{{ old('nombre') }}"
                    class="w-full border rounded-lg px-4 py-2">

                @error('nombre')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
                @enderror

            </div>

            <!-- DIRECCION -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Dirección
                </label>

                <input
                    type="text"
                    name="direccion"
                    value="{{ old('direccion') }}"
                    class="w-full border rounded-lg px-4 py-2">

                @error('direccion')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
                @enderror

            </div>

            <!-- CAPACIDAD -->
            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Capacidad
                </label>

                <input
                    type="number"
                    name="capacidad"
                    value="{{ old('capacidad') }}"
                    class="w-full border rounded-lg px-4 py-2">

                @error('capacidad')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
                @enderror

            </div>

            <!-- BOTONES -->
            <div class="flex justify-end gap-3">

                <a href="{{ route('residencias.index') }}"
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