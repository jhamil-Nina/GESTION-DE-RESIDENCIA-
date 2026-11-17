<div class="mb-6">
    <label for="tipo_habitacion_id" class="block font-semibold mb-2">Tipo de habitación</label>
    <select id="tipo_habitacion_id" name="tipo_habitacion_id" required class="w-full border rounded-lg px-4 py-2">
        <option value="">Selecciona un tipo</option>
        @foreach($tipos as $tipo)
            <option value="{{ $tipo->id }}" @selected(old('tipo_habitacion_id', $habitacion->tipo_habitacion_id ?? null) == $tipo->id)>
                {{ $tipo->nombre }} · Bs {{ number_format($tipo->costo_mensual, 2) }} al mes por residente
            </option>
        @endforeach
    </select>
    @error('tipo_habitacion_id')
        <p class="text-red-600 mt-1">{{ $message }}</p>
    @enderror
    @if($tipos->isEmpty())
        <a href="{{ route('tipos_habitacion.create') }}" class="inline-block mt-2 text-blue-700">Crear un tipo de habitación</a>
    @endif
</div>
