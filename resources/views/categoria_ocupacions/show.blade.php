@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            👁 Detalle de Categoría
        </h2>

        <p class="text-sm opacity-90">
            Información completa de la categoría registrada
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-b-xl shadow">

        <div class="space-y-4">

            <div>

                <p class="text-gray-500 text-sm">
                    ID
                </p>

                <p class="font-semibold">
                    {{ $categoria->id }}
                </p>

            </div>

            <div>

                <p class="text-gray-500 text-sm">
                    Nombre
                </p>

                <p class="font-semibold">
                    {{ $categoria->nombre }}
                </p>

            </div>

        </div>

        <div class="mt-6 flex gap-3">

            <a
                href="{{ route('categoria_ocupacions.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Volver

            </a>

            <a
                href="{{ route('categoria_ocupacions.edit', $categoria->id) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg">

                Editar

            </a>

        </div>

    </div>

</div>

@endsection