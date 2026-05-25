@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Detalle de Observación</h1>

    <div class="bg-white p-6 rounded shadow">

        <p class="mb-3">
            <strong>Usuario:</strong>
            {{ $observacion->registroResidencia->user->name }}
        </p>

        <p class="mb-3">
            <strong>Habitación:</strong>
            {{ $observacion->registroResidencia->habitacion->numero }}
        </p>

        <p class="mb-3">
            <strong>Fecha:</strong>
            {{ $observacion->fecha }}
        </p>

        <p class="mb-3">
            <strong>Descripción:</strong><br>
            {{ $observacion->descripcion }}
        </p>

        <a href="{{ route('observacions.index') }}"
            class="bg-gray-600 text-white px-4 py-2 rounded">
            Volver
        </a>

    </div>

</div>

@endsection