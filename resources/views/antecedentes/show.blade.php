@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">

    <h1 class="text-2xl font-bold mb-6">Detalle del Antecedente</h1>

    <p><strong>ID:</strong> {{ $antecedente->id }}</p>

    <p><strong>Usuario:</strong> {{ $antecedente->user->name }}</p>

    <p><strong>Descripción:</strong> {{ $antecedente->descripcion }}</p>

    <p><strong>Fecha:</strong> {{ $antecedente->fecha }}</p>

    <a href="{{ route('antecedentes.index') }}"
        class="bg-gray-500 text-white px-4 py-2 rounded mt-4 inline-block">

        Volver

    </a>

</div>

@endsection