{{-- resources/views/habitacions/index.blade.php --}}

@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold flex items-center gap-2">
            🚪 Lista de Habitaciones
        </h2>

        <p class="text-sm opacity-90">
            Administra las habitaciones del sistema
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
                action="{{ route('habitacions.index') }}"
                class="flex gap-2 w-2/3">

                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar ?? '' }}"
                    placeholder="Buscar habitación..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">

                <button
                    type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg">
                    Buscar
                </button>

            </form>

            <!-- BOTON -->
            <a href="{{ route('habitacions.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow">

                + Nueva Habitación

            </a>

        </div>


        <!-- TABLA -->
        <table class="w-full border rounded-lg overflow-hidden">

            <thead class="bg-gray-100">

                <tr>

                    <th class="text-left px-4 py-3">ID</th>

                    <th class="text-left px-4 py-3">
                        Número
                    </th>

                    <th class="text-center px-4 py-3">
                        Capacidad
                    </th>

                    <th class="text-left px-4 py-3">
                        Residencia
                    </th>

                    <th class="text-center px-4 py-3">
                        Acciones
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($habitacions as $habitacion)

                <tr class="border-t hover:bg-gray-50">

                    <td class="px-4 py-3">
                        {{ $habitacion->id }}
                    </td>

                    <td class="px-4 py-3">
                        Habitación {{ $habitacion->numero }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        {{ $habitacion->capacidad }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $habitacion->residencia->nombre }}
                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-center items-center gap-2">

                            <!-- VER -->
                            <a href="{{ route('habitacions.show', $habitacion->id) }}"
                                class="w-24 text-center bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg shadow">

                                Ver

                            </a>

                            <!-- EDITAR -->
                            <a href="{{ route('habitacions.edit', $habitacion->id) }}"
                                class="w-24 text-center bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded-lg shadow">

                                Editar

                            </a>

                            <!-- ELIMINAR -->
                            <form
                                action="{{ route('habitacions.destroy', $habitacion->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('¿Seguro que deseas eliminar esta habitación?')"
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

                        No hay habitaciones registradas

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>


        <!-- FOOTER -->
        <div class="mt-4 text-sm text-gray-500">

            Mostrando {{ $habitacions->count() }} habitaciones

        </div>

    </div>

</div>

@endsection