@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            ✏ Editar Usuario
        </h2>

    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('users.update', $user->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-2">Nombre</label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">CI</label>

                <input
                    type="text"
                    name="ci"
                    value="{{ old('ci', $user->ci) }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Email</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Teléfono</label>

                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono', $user->telefono) }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2">Dirección</label>

                <input
                    type="text"
                    name="direccion"
                    value="{{ old('direccion', $user->direccion) }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-6">

                <label class="block font-semibold mb-2">
                    Categoría de ocupación
                </label>

                <select
                    name="categoria_ocupacion_id"
                    class="w-full border rounded-lg px-4 py-2">

                    @foreach($categorias as $categoria)

                    <option
                        value="{{ $categoria->id }}"
                        {{ $user->categoria_ocupacion_id == $categoria->id ? 'selected' : '' }}>

                        {{ $categoria->nombre }}

                    </option>

                    @endforeach

                </select>

            </div>

            <div class="flex justify-end gap-3">

                <a href="{{ route('users.index') }}"
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