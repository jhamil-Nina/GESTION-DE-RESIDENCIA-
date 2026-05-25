{{-- resources/views/residencias/index.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold flex items-center gap-2">
            🏢 Lista de Residencias
        </h2>

        <p class="text-sm opacity-90">
            Administra las residencias del sistema
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
                action="{{ route('residencias.index') }}"
                class="flex gap-2 w-2/3">

                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar ?? '' }}"
                    placeholder="Buscar residencia..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg">
                    Buscar
                </button>

            </form>

            <!-- BOTON -->
            <a href="{{ route('residencias.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow">

                + Nueva Residencia

            </a>

        </div>


        <!-- TABLA -->
        <table class="w-full border rounded-lg overflow-hidden">

            <thead class="bg-gray-100">

                <tr>

                    <th class="text-left px-4 py-3">ID</th>

                    <th class="text-left px-4 py-3">
                        Nombre
                    </th>

                    <th class="text-left px-4 py-3">
                        Dirección
                    </th>

                    <th class="text-center px-4 py-3">
                        Capacidad
                    </th>

                    <th class="text-center px-4 py-3">
                        Acciones
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($residencias as $residencia)

                <tr class="border-t hover:bg-gray-50">

                    <td class="px-4 py-3">
                        {{ $residencia->id }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $residencia->nombre }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $residencia->direccion }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        {{ $residencia->capacidad }}
                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-center items-center gap-2">

                            <!-- VER -->
                            <a href="{{ route('residencias.show', $residencia->id) }}"
                                class="w-24 text-center bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg shadow">

                                Ver

                            </a>

                            <!-- EDITAR -->
                            <a href="{{ route('residencias.edit', $residencia->id) }}"
                                class="w-24 text-center bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg shadow">

                                Editar

                            </a>

                            <!-- ELIMINAR -->
                            <form
                                action="{{ route('residencias.destroy', $residencia->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('¿Seguro que deseas eliminar esta residencia?')"
                                    class="w-24 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg shadow">

                                    Eliminar

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center py-6 text-gray-500">

                        No hay residencias registradas

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>


        <!-- FOOTER -->
        <div class="mt-4 text-sm text-gray-500">

            Mostrando {{ $residencias->count() }} residencias

        </div>

    </div>

</div>

@endsection