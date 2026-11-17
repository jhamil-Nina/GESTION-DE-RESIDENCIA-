<div class="mb-6">
    <label for="max_habitaciones" class="block font-semibold mb-2">Máximo de habitaciones</label>
    <input id="max_habitaciones" name="max_habitaciones" type="number" min="1" max="2147483647" required
        value="{{ old('max_habitaciones', $residencia->max_habitaciones ?? null) }}"
        class="w-full border rounded-lg px-4 py-2">
    @error('max_habitaciones')
        <p class="text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
