@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ➕ Nuevo Usuario
        </h2>

        <p class="text-sm opacity-90">
            Registra un nuevo usuario
        </p>

    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('users.store') }}" method="POST">

            @csrf

            <!-- NOMBRE -->
            <div class="mb-4">
                <label class="block font-semibold mb-2">Nombre</label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border rounded-lg px-4 py-2">

                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- CI -->
            <div class="mb-4">
                <label class="block font-semibold mb-2">CI</label>

                <input
                    type="text"
                    name="ci"
                    value="{{ old('ci') }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- EMAIL -->
            <div class="mb-4">
                <label class="block font-semibold mb-2">Email</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- TELEFONO -->
            <div class="mb-4">
                <label class="block font-semibold mb-2">Teléfono</label>

                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono') }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- DIRECCION -->
            <div class="mb-4">
                <label class="block font-semibold mb-2">Dirección</label>

                <input
                    type="text"
                    name="direccion"
                    value="{{ old('direccion') }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- CATEGORIA -->
            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Categoría de ocupación
                </label>

                <select
                    name="categoria_ocupacion_id"
                    class="w-full border rounded-lg px-4 py-2">

                    <option value="">
                        Seleccione una categoría
                    </option>

                    @foreach($categorias as $categoria)

                    <option
                        value="{{ $categoria->id }}"
                        {{ old('categoria_ocupacion_id') == $categoria->id ? 'selected' : '' }}>

                        {{ $categoria->nombre }}

                    </option>

                    @endforeach

                </select>

            </div>

            <!-- PASSWORD -->
            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Contraseña
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg px-4 py-2">

            </div>


            <div class="flex justify-end gap-3">

                <a href="{{ route('users.index') }}"
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