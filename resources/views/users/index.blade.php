@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold flex items-center gap-2">
            👤 Lista de Usuarios
        </h2>

        <p class="text-sm opacity-90">
            Administra los usuarios del sistema
        </p>

    </div>

    <!-- CARD -->
    <div class="bg-white p-6 rounded-b-xl shadow">

        <!-- MENSAJE -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif


        <!-- BUSCADOR -->
        <div class="flex justify-between items-center mb-6 gap-4">

            <form
                method="GET"
                action="{{ route('users.index') }}"
                class="flex gap-2 w-2/3">

                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar ?? '' }}"
                    placeholder="Buscar usuario..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">

                <button
                    type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg">
                    Buscar
                </button>

            </form>

            <a href="{{ route('users.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow">

                + Nuevo Usuario

            </a>

        </div>


        <!-- TABLA -->
        <table class="w-full border rounded-lg overflow-hidden">

            <thead class="bg-gray-100">

                <tr>

                    <th class="text-left px-4 py-3">ID</th>
                    <th class="text-left px-4 py-3">Nombre</th>
                    <th class="text-left px-4 py-3">CI</th>
                    <th class="text-left px-4 py-3">Email</th>
                    <th class="text-left px-4 py-3">Categoría</th>
                    <th class="text-center px-4 py-3">Acciones</th>

                </tr>

            </thead>

            <tbody>

                @forelse($users as $user)

                <tr class="border-t hover:bg-gray-50">

                    <td class="px-4 py-3">
                        {{ $user->id }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $user->name }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $user->ci }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $user->email }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $user->categoriaOcupacion->nombre ?? 'Sin categoría' }}
                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-center items-center gap-2">

                            <a href="{{ route('users.show', $user->id) }}"
                                class="w-24 text-center bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg shadow">
                                Ver
                            </a>

                            <a href="{{ route('users.edit', $user->id) }}"
                                class="w-24 text-center bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg shadow">
                                Editar
                            </a>

                            <form
                                action="{{ route('users.destroy', $user->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('¿Seguro que deseas eliminar este usuario?')"
                                    class="w-24 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg shadow">

                                    Eliminar

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center py-6 text-gray-500">

                        No hay usuarios registrados

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>


        <div class="mt-4 text-sm text-gray-500">
            Mostrando {{ $users->count() }} usuarios
        </div>

    </div>

</div>

@endsection