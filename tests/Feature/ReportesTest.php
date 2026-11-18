<?php

namespace Tests\Feature;

use App\Models\CategoriaOcupacion;
use App\Models\Habitacion;
use App\Models\Observacion;
use App\Models\RegistroResidencia;
use App\Models\Residencia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportesTest extends TestCase
{
    use RefreshDatabase;

    private Habitacion $habitacion;

    private RegistroResidencia $contrato;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(now()->setDate(2026, 11, 17)->startOfDay());

        $residencia = Residencia::create(['nombre' => 'Residencia Norte', 'direccion' => 'EMI', 'capacidad' => 2]);
        $this->habitacion = $residencia->habitacions()->create(['numero' => '101', 'capacidad' => 2]);
        /** @var User $residente */
        $residente = User::factory()->create(['name' => 'Residente Mora', 'rol' => 'residente']);
        $categoria = CategoriaOcupacion::create(['nombre' => 'Estudiante']);

        $this->contrato = $residente->registrosResidencia()->create([
            'habitacion_id' => $this->habitacion->id,
            'categoria_ocupacion_id' => $categoria->id,
            'fecha_ingreso' => '2026-01-01',
        ]);

        /** @var User $admin */
        $admin = User::factory()->create(['rol' => 'admin']);
        $this->actingAs($admin);
    }

    public function test_filtra_recaudacion_y_pagos_por_fecha_y_estado(): void
    {
        foreach ([
            ['2026-10-17', 100, 'Pagado'],
            ['2026-10-16', 200, 'Pagado'],
            ['2026-05-16', 300, 'Pagado'],
            ['2025-10-01', 400, 'Pagado'],
            ['2026-11-17', 500, 'Pendiente'],
        ] as [$fecha, $monto, $estado]) {
            $this->contrato->pagos()->create(['fecha_pago' => $fecha, 'monto' => $monto, 'estado' => $estado, 'metodo_pago' => 'Efectivo']);
        }

        foreach (['mes' => 100, 'semestre' => 300, 'anio' => 600, 'historial' => 1000] as $periodo => $total) {
            $this->get(route('dashboard', compact('periodo')))->assertOk()
                ->assertViewHas('totalRecaudado', fn ($recaudado) => (float) $recaudado === (float) $total);
        }
    }

    public function test_imprime_incidencias_filtradas_con_formato_institucional(): void
    {
        foreach (['Pendiente' => 'Daño eléctrico', 'Solucionado' => 'Daño resuelto'] as $estado => $descripcion) {
            Observacion::create([
                'habitacion_id' => $this->habitacion->id,
                'descripcion' => $descripcion,
                'estado' => $estado,
                'fecha' => '2026-11-10',
            ]);
        }

        $this->get(route('reportes'))->assertOk()->assertSee('Daño eléctrico')->assertDontSee('Daño resuelto');
        $this->getJson(route('reportes', ['tipo' => 'danos', 'formato' => 'csv']))
            ->assertUnprocessable()->assertJsonValidationErrors('formato');
        $this->get(route('reportes', ['tipo' => 'danos', 'estado' => 'Solucionado', 'formato' => 'imprimir']))
            ->assertOk()->assertSee('Escuela Militar de Ingeniería')->assertSee('17/11/2026')
            ->assertSee('Daño resuelto')->assertDontSee('Daño eléctrico');

        $descarga = $this->get(route('reportes', ['tipo' => 'danos', 'estado' => 'Solucionado', 'formato' => 'pdf']))
            ->assertOk()->assertHeader('Content-Type', 'application/pdf')
            ->assertDownload('reporte-danos-2026-11-17.pdf');
        $this->assertStringStartsWith('%PDF-', $descarga->getContent());
    }

    public function test_agrupa_cuotas_vencidas_y_pendientes_por_residente(): void
    {
        foreach ([
            ['2026-09-01', '2026-10-01', 100, 'Pendiente'],
            ['2026-10-01', '2026-11-01', 110, 'Pendiente'],
            ['2026-11-01', '2026-12-01', 120, 'Pendiente'],
            ['2026-08-01', '2026-09-01', 130, 'Pagado'],
        ] as $indice => [$inicio, $fin, $monto, $estado]) {
            $this->contrato->mensualidades()->create([
                'numero_mes' => $indice + 1,
                'fecha_inicio' => $inicio,
                'fecha_fin' => $fin,
                'monto' => $monto,
                'estado' => $estado,
            ]);
        }

        $this->get(route('reportes', 'deudores'))->assertOk()->assertViewHas('registros', function ($registros) {
            $residente = $registros->first();

            return $registros->total() === 1 && (int) $residente->cuotas === 2 && (float) $residente->deuda === 210.0;
        });

        $this->get(route('reportes', ['tipo' => 'deudores', 'formato' => 'imprimir']))
            ->assertOk()->assertSee('Escuela Militar de Ingeniería')->assertSee('Residente Mora')->assertSee('210,00');

        $this->get(route('reportes', ['tipo' => 'deudores', 'formato' => 'pdf']))
            ->assertOk()->assertHeader('Content-Type', 'application/pdf')->assertDownload('reporte-deudores-2026-11-17.pdf');

        $this->actingAs(User::findOrFail($this->contrato->user_id));
        $this->get(route('reportes', 'deudores'))->assertForbidden();
        $this->get(route('reportes', ['tipo' => 'deudores', 'formato' => 'pdf']))->assertForbidden();
    }
}
