@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Editar Observación</h1>

    <form action="{{ route('observacions.update',$observacion) }}"
        method="POST"
        class="bg-white p-6 rounded shadow">

        @csrf
        @method('PUT')

        <div class="mb-4">

            <label class="block mb-1">Registro de Residencia</label>

            <select name="registro_residencia_id"
                class="w-full border rounded p-2">

                @foreach($registros as $registro)

                <option value="{{ $registro->id }}"
                    @if($registro->id == $observacion->registro_residencia_id) selected @endif>

                    {{ $registro->user->name }} - Habitación {{ $registro->habitacion->numero }}

                </option>

                @endforeach

            </select>

        </div>

        <div class="mb-4">

            <label class="block mb-1">Descripción</label>

            <textarea name="descripcion"
                class="w-full border rounded p-2"
                rows="4">{{ $observacion->descripcion }}</textarea>

        </div>

        <div class="mb-4">

            <label class="block mb-1">Fecha</label>

            <input type="date"
                name="fecha"
                value="{{ $observacion->fecha }}"
                class="w-full border rounded p-2">

        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Actualizar
        </button>

    </form>

</div>

@endsection