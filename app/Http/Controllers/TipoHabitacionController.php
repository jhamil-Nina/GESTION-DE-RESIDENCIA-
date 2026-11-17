<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TipoHabitacionController extends Controller
{
    public function index()
    {
        $tipos = TipoHabitacion::withCount('habitaciones')->orderBy('nombre')->get();

        return view('tipos_habitacion.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos_habitacion.form', ['tipo' => new TipoHabitacion()]);
    }

    public function store(Request $request)
    {
        TipoHabitacion::create($this->validar($request));

        return redirect()->route('tipos_habitacion.index')->with('success', 'Tipo creado correctamente.');
    }

    public function edit(TipoHabitacion $tipo)
    {
        return view('tipos_habitacion.form', compact('tipo'));
    }

    public function update(Request $request, TipoHabitacion $tipo)
    {
        $tipo->update($this->validar($request, $tipo));

        return redirect()->route('tipos_habitacion.index')->with('success', 'Tipo actualizado correctamente.');
    }

    public function destroy(TipoHabitacion $tipo)
    {
        $eliminado = DB::transaction(function () use ($tipo) {
            $tipo = TipoHabitacion::lockForUpdate()->findOrFail($tipo->id);

            return !$tipo->habitaciones()->exists() && $tipo->delete();
        }, 3);

        if (!$eliminado) {
            return back()->with('error', 'Reasigna las habitaciones de este tipo antes de eliminarlo.');
        }

        return redirect()->route('tipos_habitacion.index')->with('success', 'Tipo eliminado correctamente.');
    }

    private function validar(Request $request, ?TipoHabitacion $tipo = null): array
    {
        $nombreUnico = Rule::unique('tipos_habitacion', 'nombre');

        if ($tipo !== null) {
            $nombreUnico->ignore($tipo);
        }

        return $request->validate([
            'nombre' => ['required', 'string', 'max:255', $nombreUnico],
            'costo_mensual' => 'required|numeric|decimal:0,2|min:0.01|max:999999.99',
        ]);
    }
}
