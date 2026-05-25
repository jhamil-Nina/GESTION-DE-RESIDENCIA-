@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-6">
        Crear Categoría de Ocupación
    </h2>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <form action="{{ route('categoria_ocupacions.store') }}" method="POST">

        @csrf

        <div class="mb-4">

            <label class="block text-gray-700 font-bold mb-2">
                Nombre de la categoría
            </label>

            <input
                type="text"
                name="nombre"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                required>

        </div>


        <div class="flex gap-3">

            <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">

                Guardar

            </button>


            <a
                href="{{ route('categoria_ocupacions.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded">

                Volver

            </a>

        </div>

    </form>

</div>

@endsection