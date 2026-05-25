@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-6 rounded-t-xl text-white">
        <h2 class="text-2xl font-bold">➕ Registrar Pago</h2>
    </div>

    <div class="bg-white p-6 rounded-b-xl shadow">

        <form action="{{ route('pagos.store') }}" method="POST">

            @csrf

            <div class="mb-4">

                <label class="block font-semibold mb-2">Registro Residencia</label>

                <select name="registro_residencia_id" class="w-full border rounded-lg px-4 py-2">

                    <option value="">Seleccione</option>

                    @foreach($registros as $registro)

                    <option value="{{ $registro->id }}">

                        {{ $registro->user->name }} - Hab {{ $registro->habitacion->numero }}

                    </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-4">

                <label class="block font-semibold mb-2">Monto</label>

                <input type="number" step="0.01" name="monto"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <div class="mb-4">

                <label class="block font-semibold mb-2">Fecha Pago</label>

                <input type="date" name="fecha_pago"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <div class="mb-4">

                <label class="block font-semibold mb-2">Método Pago</label>

                <select name="metodo_pago" class="w-full border rounded-lg px-4 py-2">

                    <option>Efectivo</option>
                    <option>Transferencia</option>
                    <option>QR</option>

                </select>

            </div>

            <div class="mb-6">

                <label class="block font-semibold mb-2">Estado</label>

                <select name="estado" class="w-full border rounded-lg px-4 py-2">

                    <option>Pagado</option>
                    <option>Pendiente</option>

                </select>

            </div>

            <div class="flex justify-end gap-3">

                <a href="{{ route('pagos.index') }}"
                    class="bg-gray-500 text-white px-5 py-2 rounded-lg">
                    Cancelar
                </a>

                <button
                    class="bg-green-500 text-white px-5 py-2 rounded-lg">

                    Guardar

                </button>

            </div>

        </form>

    </div>

</div>

@endsection