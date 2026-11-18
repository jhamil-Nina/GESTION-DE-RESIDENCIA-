@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow p-6">
    <div class="module-heading rounded-lg p-4 flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-2xl font-bold">Tipos de habitación</h2>
        <a href="{{ route('tipos_habitacion.create') }}" class="module-accent px-4 py-2 rounded-lg">
            Nuevo tipo
        </a>
    </div>

    @if(session('success'))
        <p class="text-green-700 mb-4" role="status">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p class="text-red-700 mb-4" role="alert">{{ session('error') }}</p>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Nombre</th>
                    <th class="p-3">Tarifa mensual por residente</th>
                    <th class="p-3">Habitaciones</th>
                    <th class="p-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tipos as $tipo)
                    <tr class="border-b">
                        <td class="p-3">{{ $tipo->nombre }}</td>
                        <td class="p-3">Bs {{ number_format($tipo->costo_mensual, 2) }}</td>
                        <td class="p-3">{{ $tipo->habitaciones_count }}</td>
                        <td class="p-3">
                            <div class="flex gap-3">
                                <a class="text-blue-700" href="{{ route('tipos_habitacion.edit', $tipo) }}">Editar</a>
                                @if($tipo->habitaciones_count === 0)
                                    <form action="{{ route('tipos_habitacion.destroy', $tipo) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar este tipo de habitación?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-700">Eliminar</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-3">Aún no hay tipos de habitación.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('habitacions.index') }}" class="inline-block mt-6 text-blue-700">Volver a habitaciones</a>
</div>
@endsection
