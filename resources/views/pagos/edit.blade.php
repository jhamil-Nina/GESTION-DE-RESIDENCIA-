@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-6 rounded-t-xl text-white">
        <h2 class="text-2xl font-bold">✏ Editar Pago</h2>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('pagos.update',$pago->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-4">

                <label class="block font-semibold mb-2">Registro</label>

                <select name="registro_residencia_id" class="w-full border rounded-lg px-4 py-2">

                    @foreach($registros as $registro)

                    <option value="{{ $registro->id }}"
                        {{ $pago->registro_residencia_id == $registro->id ? 'selected':'' }}>

                        {{ $registro->user->name }} - Hab {{ $registro->habitacion->numero }}

                    </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-4">

                <label class="block font-semibold mb-2">Monto</label>

                <input type="number" step="0.01"
                    name="monto"
                    value="{{ $pago->monto }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <div class="mb-4">

                <label class="block font-semibold mb-2">Fecha Pago</label>

                <input type="date"
                    name="fecha_pago"
                    value="{{ $pago->fecha_pago }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <div class="mb-4">

                <label class="block font-semibold mb-2">Método Pago</label>

                <input type="text"
                    name="metodo_pago"
                    value="{{ $pago->metodo_pago }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <div class="mb-6">

                <label class="block font-semibold mb-2">Estado</label>

                <input type="text"
                    name="estado"
                    value="{{ $pago->estado }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <div class="flex justify-end gap-3">

                <a href="{{ route('pagos.index') }}"
                    class="bg-gray-500 text-white px-5 py-2 rounded-lg">
                    Cancelar
                </a>

                <button
                    class="bg-yellow-500 text-white px-5 py-2 rounded-lg">

                    Actualizar

                </button>

            </div>

        </form>

    </div>

</div>

@endsection