
@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4">

    {{-- HEADER --}}
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-6 rounded-t-xl text-white">

        <h2 class="text-2xl font-bold">
            📋 Gestión de Observaciones
        </h2>

        <p class="text-sm opacity-90 mt-1">
            Control de daños y mantenimiento de habitaciones
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white p-6 rounded-b-xl shadow">

        {{-- MENSAJE --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-5">
                {{ session('success') }}
            </div>
        @endif

        {{-- BUSCADOR Y BOTÓN --}}
        <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">

            <form action="{{ route('observacions.index') }}" method="GET"
                  class="flex gap-2 w-full md:w-auto">

                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar }}"
                    placeholder="Buscar habitación o daño..."
                    class="w-full md:w-72 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">

                <button
                    type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                    Buscar
                </button>

            </form>

            <a href="{{ route('observacions.create') }}"
            class="inline-flex items-center justify-center self-start md:self-auto bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg shadow text-sm font-medium whitespace-nowrap">

                + Nueva Observación

            </a>

        </div>

        {{-- TABLA --}}
        <div class="overflow-x-auto">

            <table class="w-full border border-gray-200 rounded-lg overflow-hidden">

                <thead class="bg-gray-100 text-gray-700">

                    <tr>
                        <th class="text-left px-4 py-3">ID</th>
                        <th class="text-left px-4 py-3">Habitación</th>
                        <th class="text-left px-4 py-3">Descripción</th>
                        <th class="text-left px-4 py-3">Fecha</th>
                        <th class="text-center px-4 py-3">Estado</th>
                        <th class="text-center px-4 py-3">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($observaciones as $observacion)

                        <tr class="border-t hover:bg-gray-50">

                            <td class="px-4 py-3">
                                {{ $observacion->id }}
                            </td>

                            <td class="px-4 py-3 font-medium">
                                Habitación {{ $observacion->habitacion->numero }}
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ \Illuminate\Support\Str::limit($observacion->descripcion, 55) }}
                            </td>

                            <td class="px-4 py-3 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($observacion->fecha)->format('d/m/Y') }}
                            </td>

                            {{-- ESTADO --}}
                            <td class="px-4 py-3 text-center">

                                @if($observacion->estado === 'Pendiente')

                                    <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Pendiente
                                    </span>

                                @elseif($observacion->estado === 'En reparación')

                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        En reparación
                                    </span>

                                @elseif($observacion->estado === 'Solucionado')

                                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Solucionado
                                    </span>

                                @endif

                            </td>

                            {{-- ACCIONES --}}
                            <td class="px-4 py-3">

                                <div class="flex flex-wrap items-center justify-center gap-2">

                                    <a href="{{ route('observacions.show', $observacion) }}"
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm font-medium">
                                        Ver
                                    </a>

                                    <a href="{{ route('observacions.edit', $observacion) }}"
                                       class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-2 rounded-lg text-sm font-medium">
                                        Editar
                                    </a>

                                    <form action="{{ route('observacions.destroy', $observacion) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('¿Está seguro de eliminar esta observación? Se perderá también su historial de residentes asociados.')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium">
                                            Eliminar
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No existen observaciones registradas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- TOTAL --}}
        <div class="mt-5 text-sm text-gray-500">
            Mostrando {{ $observaciones->count() }} observaciones
        </div>

    </div>

</div>

@endsection
