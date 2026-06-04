@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ✏ Editar Categoría
        </h2>

        <p class="text-sm opacity-90">
            Modifica la información de la categoría
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('categoria_ocupacions.update', $categoria->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    value="{{ old('nombre', $categoria->nombre) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    required>

            </div>

            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('categoria_ocupacions.index') }}"
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