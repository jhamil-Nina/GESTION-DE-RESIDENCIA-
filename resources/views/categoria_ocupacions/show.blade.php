@extends('layouts.app')

@section('content')

<div class="container">

    <h2>Detalle de Categoría</h2>

    <p>
        <strong>ID:</strong>
        {{ $categoria->id }}
    </p>

    <p>
        <strong>Nombre:</strong>
        {{ $categoria->nombre }}
    </p>

    <br>

    <a href="{{ route('categoria_ocupacions.index') }}">
        Volver
    </a>

</div>

@endsection