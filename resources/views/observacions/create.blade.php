@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Registrar Observación</h1>

    <form action="{{ route('observacions.store') }}" method="POST"
        class="bg-white p-6 rounded shadow">

        @csrf

        <div class="mb-4">
            <label class="block mb-1">Registro de Residencia</label>

            <select name="registro_residencia_id" class="w-full border rounded p-2">

                @foreach($registros as $registro)

                <option value="{{ $registro->id }}">
                    {{ $registro->user->name }} - Habitación {{ $registro->habitacion->numero }}
                </option>

                @endforeach

            </select>
        </div>

        <div class="mb-4">

            <label class="block mb-1">Descripción</label>

            <textarea name="descripcion"
                class="w-full border rounded p-2"
                rows="4"></textarea>

        </div>

        <div class="mb-4">

            <label class="block mb-1">Fecha</label>

            <input type="date"
                name="fecha"
                class="w-full border rounded p-2">

        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Guardar
        </button>

    </form>

</div>

@endsection