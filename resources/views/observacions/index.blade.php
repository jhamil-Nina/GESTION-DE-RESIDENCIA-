@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Observaciones</h1>

        <a href="{{ route('observacions.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded">
            Nueva Observación
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">

        <table class="w-full text-left">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Usuario</th>
                    <th class="p-3">Habitación</th>
                    <th class="p-3">Fecha</th>
                    <th class="p-3">Acciones</th>
                </tr>
            </thead>

            <tbody>

                @foreach($observaciones as $observacion)

                <tr class="border-b">

                    <td class="p-3">{{ $observacion->id }}</td>

                    <td class="p-3">
                        {{ $observacion->registroResidencia->user->name }}
                    </td>

                    <td class="p-3">
                        {{ $observacion->registroResidencia->habitacion->numero }}
                    </td>

                    <td class="p-3">
                        {{ $observacion->fecha }}
                    </td>

                    <td class="px-4 py-3">

                        <div class="flex items-center justify-center gap-2">

                            {{-- VER --}}
                            <a href="{{ route('observacions.show', $observacion) }}"
                                class="flex items-center gap-1 bg-green-500 hover:bg-green-600
                            text-white px-4 py-2 rounded-lg shadow-md
                            transition duration-200 text-sm font-medium">

                                 Ver
                            </a>

                            {{-- EDITAR --}}
                            <a href="{{ route('observacions.edit', $observacion) }}"
                                    class="flex items-center gap-1 bg-yellow-500 hover:bg-yellow-600
                                text-white px-4 py-2 rounded-lg shadow-md
                                transition duration-200 text-sm font-medium">

                                 Editar
                            </a>

                            {{-- ELIMINAR --}}
                            <form action="{{ route('observacions.destroy', $observacion) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    onclick="return confirm('¿Eliminar observación?')"
                                    class="flex items-center gap-1 bg-red-500 hover:bg-red-600
                                    text-white px-4 py-2 rounded-lg shadow-md
                                    transition duration-200 text-sm font-medium">

                                     Eliminar
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection