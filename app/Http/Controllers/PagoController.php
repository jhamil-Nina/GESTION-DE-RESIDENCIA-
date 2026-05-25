<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\RegistroResidencia;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    // LISTAR
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $pagos = Pago::with('registroResidencia.user', 'registroResidencia.habitacion')

            ->when($buscar, function ($query, $buscar) {

                $query->where('metodo_pago', 'like', "%{$buscar}%")
                    ->orWhere('estado', 'like', "%{$buscar}%")
                    ->orWhere('monto', 'like', "%{$buscar}%")
                    ->orWhere('id', $buscar);
            })

            ->orderBy('id', 'desc')
            ->get();

        return view('pagos.index', compact('pagos', 'buscar'));
    }


    // FORM CREAR
    public function create()
    {
        $registros = RegistroResidencia::with('user', 'habitacion')->get();

        return view('pagos.create', compact('registros'));
    }


    // GUARDAR
    public function store(Request $request)
    {
        $request->validate([
            'registro_residencia_id' => 'required|exists:registro_residencias,id',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'metodo_pago' => 'required|string|max:50',
            'estado' => 'required|string|max:20'
        ]);

        Pago::create($request->all());

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago registrado correctamente');
    }


    // MOSTRAR
    public function show(string $id)
    {
        $pago = Pago::with('registroResidencia.user', 'registroResidencia.habitacion')
            ->findOrFail($id);

        return view('pagos.show', compact('pago'));
    }


    // FORM EDITAR
    public function edit(string $id)
    {
        $pago = Pago::findOrFail($id);

        $registros = RegistroResidencia::with('user', 'habitacion')->get();

        return view('pagos.edit', compact('pago', 'registros'));
    }


    // ACTUALIZAR
    public function update(Request $request, string $id)
    {
        $request->validate([
            'registro_residencia_id' => 'required|exists:registro_residencias,id',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'metodo_pago' => 'required|string|max:50',
            'estado' => 'required|string|max:20'
        ]);

        $pago = Pago::findOrFail($id);

        $pago->update($request->all());

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago actualizado correctamente');
    }


    // ELIMINAR
    public function destroy(string $id)
    {
        $pago = Pago::findOrFail($id);

        $pago->delete();

        return redirect()
            ->route('pagos.index')
            ->with('success', 'Pago eliminado correctamente');
    }
}
