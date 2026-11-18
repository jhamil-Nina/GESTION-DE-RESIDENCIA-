@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">
    <h2 class="module-heading rounded-lg p-4 text-2xl font-bold mb-6">{{ $tipo->exists ? 'Editar tipo de habitación' : 'Nuevo tipo de habitación' }}</h2>

    <form method="POST" action="{{ $tipo->exists ? route('tipos_habitacion.update', $tipo) : route('tipos_habitacion.store') }}">
        @csrf
        @if($tipo->exists)
            @method('PUT')
        @endif

        <div class="mb-4">
            <label for="nombre" class="block font-semibold mb-2">Nombre</label>
            <input id="nombre" name="nombre" type="text" maxlength="255" required
                value="{{ old('nombre', $tipo->nombre) }}" class="w-full border rounded-lg px-4 py-2">
            @error('nombre')
                <p class="text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="costo_mensual" class="block font-semibold mb-2">Tarifa mensual por residente (Bs)</label>
            <input id="costo_mensual" name="costo_mensual" type="number" min="0.01" max="999999.99" step="0.01" required
                value="{{ old('costo_mensual', $tipo->costo_mensual) }}" class="w-full border rounded-lg px-4 py-2">
            @error('costo_mensual')
                <p class="text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end items-center gap-3">
            <a href="{{ route('tipos_habitacion.index') }}" class="text-gray-700">Cancelar</a>
            <button type="submit" class="module-primary px-5 py-2 rounded-lg">Guardar</button>
        </div>
    </form>
</div>
@endsection
