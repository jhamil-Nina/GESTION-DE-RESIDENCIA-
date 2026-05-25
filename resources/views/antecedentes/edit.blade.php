@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <h1 class="text-2xl font-bold mb-6">Editar Antecedente</h1>

    <form action="{{ route('antecedentes.update',$antecedente) }}" method="POST"
        class="bg-white p-6 rounded shadow">

        @csrf
        @method('PUT')

        <div class="mb-4">

            <label class="block">Usuario</label>

            <select name="user_id" class="w-full border p-2 rounded">

                @foreach($users as $user)

                <option value="{{ $user->id }}"
                    @if($antecedente->user_id == $user->id) selected @endif>

                    {{ $user->name }}

                </option>

                @endforeach

            </select>

        </div>

        <div class="mb-4">

            <label class="block">Descripción</label>

            <textarea name="descripcion"
                class="w-full border p-2 rounded">

            {{ $antecedente->descripcion }}

            </textarea>

        </div>

        <div class="mb-4">

            <label class="block">Fecha</label>

            <input type="date" name="fecha"
                value="{{ $antecedente->fecha }}"
                class="w-full border p-2 rounded">

        </div>

        <button class="bg-yellow-500 text-white px-4 py-2 rounded">
            Actualizar
        </button>

    </form>

</div>

@endsection