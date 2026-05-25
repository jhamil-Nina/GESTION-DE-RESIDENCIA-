@extends('layouts.app')

@section('content')

<div class="container">

    <h2>Editar Categoría</h2>

    <form action="{{ route('categoria_ocupacions.update', $categoria->id) }}" method="POST">

        @csrf
        @method('PUT') <!-- laravel necesita esto para actualizar -->

        <div>
            <label>Nombre</label>

            <input type="text" name="nombre" value="{{ $categoria->nombre }}" required>
        </div>

        <br>

        <button type="submit">
            Actualizar
        </button>

    </form>

    <br>

    <a href="{{ route('categoria_ocupacions.index') }}">
        Volver
    </a>

</div>

@endsection