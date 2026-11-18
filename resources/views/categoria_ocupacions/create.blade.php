@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- HEADER --}}
    <div class="module-heading p-6 rounded-t-xl ">

        <h2 class="text-2xl font-bold">
            ➕ Nueva Categoría de Ocupación
        </h2>

        <p class="text-sm opacity-90">
            Registrar una nueva categoría
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-b-xl shadow">

        @if ($errors->any())

        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">

            <ul class="list-disc list-inside">

                @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        <form action="{{ route('categoria_ocupacions.store') }}" method="POST">

            @csrf

            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Nombre de la categoría
                </label>

                <input
                    type="text"
                    name="nombre"
                    value="{{ old('nombre') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 "
                    required>

            </div>

            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('categoria_ocupacions.index') }}"
                    class="module-secondary px-5 py-2 rounded-lg">

                    Cancelar

                </a>

                <button
                    type="submit"
                    class="module-primary px-5 py-2 rounded-lg">

                    Guardar

                </button>

            </div>

        </form>

    </div>

</div>

@endsection