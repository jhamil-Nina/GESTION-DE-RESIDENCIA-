@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-6 rounded-t-xl text-white">
        <h2 class="text-2xl font-bold">💰 Lista de Pagos</h2>
        <p class="text-sm opacity-90">Administra los pagos de las residencias</p>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <!-- BUSCADOR -->
        <div class="flex justify-between items-center mb-6 gap-4">

            <form method="GET" action="{{ route('pagos.index') }}" class="flex gap-2 w-2/3">

                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar ?? '' }}"
                    placeholder="Buscar pago..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">

                <button
                    type="submit"
                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-5 py-2 rounded-lg">
                    Buscar
                </button>

            </form>

            <a href="{{ route('pagos.create') }}"
                class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg shadow">

                + Nuevo Pago

            </a>

        </div>

        <!-- TABLA -->
        <table class="w-full border rounded-lg overflow-hidden">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Estudiante</th>
                    <th class="px-4 py-3 text-left">Habitación</th>
                    <th class="px-4 py-3 text-center">Monto</th>
                    <th class="px-4 py-3 text-left">Método</th>
                    <th class="px-4 py-3 text-left">Estado</th>
                    <th class="px-4 py-3 text-center">Acciones</th>

                </tr>

            </thead>

            <tbody>

                @forelse($pagos as $pago)

                <tr class="border-t hover:bg-gray-50">

                    <td class="px-4 py-3">{{ $pago->id }}</td>

                    <td class="px-4 py-3">
                        {{ $pago->registroResidencia->user->name }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $pago->registroResidencia->habitacion->numero }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        Bs {{ $pago->monto }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $pago->metodo_pago }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $pago->estado }}
                    </td>

                    <td class="p-3 flex gap-2 items-center">

                        <a href="{{ route('pagos.show', $pago) }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded">
                            Ver
                        </a>

                        <a href="{{ route('pagos.edit', $pago) }}"
                            class="bg-yellow-500 text-white px-4 py-2 rounded">
                            Editar
                        </a>

                        <form action="{{ route('pagos.destroy', $pago) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                                Eliminar
                            </button>
                        </form>

                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        No hay pagos registrados
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection