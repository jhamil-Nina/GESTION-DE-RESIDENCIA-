@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Registrar Antecedente</h1>

    <form action="{{ route('antecedentes.store') }}" method="POST"
        class="bg-white p-6 rounded shadow">

        @csrf

        <div class="mb-4">

            <label class="block">Usuario</label>

            <select name="user_id" class="w-full border p-2 rounded">

                @foreach($users as $user)

                <option value="{{ $user->id }}">{{ $user->name }}</option>

                @endforeach

            </select>

        </div>

        <div class="mb-4">

            <label class="block">Descripción</label>

            <textarea name="descripcion" class="w-full border p-2 rounded"></textarea>

        </div>

        <div class="mb-4">

            <label class="block">Fecha</label>

            <input type="date" name="fecha"
                class="w-full border p-2 rounded">

        </div>

        <button class="bg-green-500 text-white px-4 py-2 rounded">
            Guardar
        </button>

    </form>

</div>

@endsection