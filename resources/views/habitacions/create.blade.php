{{-- resources/views/habitacions/create.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ➕ Nueva Habitación
        </h2>

        <p class="text-sm opacity-90">
            Registra una nueva habitación
        </p>

    </div>

    <!-- FORM -->
    <div class="bg-white p-6 rounded-b-xl shadow">

        @if(session('error'))

        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>

        @endif

        <form action="{{ route('habitacions.store') }}" method="POST">

            @csrf

            <!-- NUMERO -->
            <div class="mb-4">

                <label class="block font-semibold mb-2">
                    Número
                </label>

                <input
                    type="text"
                    name="numero"
                    value="{{ old('numero') }}"
                    class="w-full border rounded-lg px-4 py-2">

                @error('numero')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
                @enderror

            </div>

            <!-- CAPACIDAD -->
            <div class="mb-4">

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

            <!-- RESIDENCIA -->
            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Residencia
                </label>

                <select
                    name="residencia_id"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="">
                        Seleccione una residencia
                    </option>

                    @foreach($residencias as $residencia)

                    <option
                        value="{{ $residencia->id }}"
                        {{ old('residencia_id') == $residencia->id ? 'selected' : '' }}>

                        {{ $residencia->nombre }}

                    </option>

                    @endforeach

                </select>

                @error('residencia_id')
                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>
                @enderror

            </div>

            <!-- BOTONES -->
            <div class="flex justify-end gap-3">

                <a href="{{ route('habitacions.index') }}"
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