<?php

use App\Models\CategoriaOcupacion;
use App\Models\RegistroResidencia;
use App\Models\Residencia;
use App\Models\TipoHabitacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

it('aplica los límites al crear habitaciones, trasladarlas y editar la residencia', function () {
    $datos = ['nombre' => 'Varones', 'direccion' => 'EMI', 'capacidad' => 4, 'max_habitaciones' => 2];
    post(route('residencias.store'), $datos)->assertRedirectToRoute('residencias.index');
    $residencia = Residencia::firstOrFail();
    $otra = Residencia::create([...$datos, 'nombre' => 'Mujeres']);
    $tipo = TipoHabitacion::create(['nombre' => 'Doble', 'costo_mensual' => '150.00']);
    $habitacion = ['numero' => '101', 'capacidad' => 2, 'residencia_id' => $residencia->id, 'tipo_habitacion_id' => $tipo->id];

    post(route('habitacions.store'), $habitacion)->assertRedirectToRoute('habitacions.index');
    post(route('habitacions.store'), [...$habitacion, 'numero' => '102', 'capacidad' => 3])
        ->assertSessionHasErrors('capacidad');
    post(route('habitacions.store'), [...$habitacion, 'numero' => '102'])->assertSessionHasNoErrors();
    post(route('habitacions.store'), [...$habitacion, 'numero' => '103'])
        ->assertSessionHasErrors('residencia_id');

    $primera = $residencia->habitacions()->where('numero', '101')->firstOrFail();
    put(route('habitacions.update', $primera), $habitacion)->assertSessionHasNoErrors();
    put(route('residencias.update', $residencia), [...$datos, 'max_habitaciones' => 1])
        ->assertSessionHasErrors('max_habitaciones');

    $traslado = [...$habitacion, 'numero' => '103', 'residencia_id' => $otra->id];
    post(route('habitacions.store'), $traslado)->assertSessionHasNoErrors();
    $tercera = $otra->habitacions()->firstOrFail();
    put(route('habitacions.update', $tercera), [...$traslado, 'residencia_id' => $residencia->id])
        ->assertSessionHasErrors('residencia_id');
    expect($tercera->fresh()->residencia_id)->toBe($otra->id);
});

it('usa la tarifa vigente del tipo al crear contratos y conserva el precio pactado', function () {
    post(route('tipos_habitacion.store'), ['nombre' => 'Doble', 'costo_mensual' => '150.50'])
        ->assertRedirectToRoute('tipos_habitacion.index');
    $tipo = TipoHabitacion::firstOrFail();
    $residencia = Residencia::create(['nombre' => 'Varones', 'direccion' => 'EMI', 'capacidad' => 3]);
    $habitacion = $residencia->habitacions()->create(['numero' => '101', 'capacidad' => 2, 'tipo_habitacion_id' => $tipo->id]);
    $anterior = $residencia->habitacions()->create(['numero' => '102', 'capacidad' => 1, 'costo_mensual' => 100]);
    $categoria = CategoriaOcupacion::create(['nombre' => 'Estudiante']);
    $residente = User::factory()->create(['ci' => '100001']);
    $registro = [
        'user_id' => $residente->id,
        'habitacion_id' => $habitacion->id,
        'categoria_ocupacion_id' => $categoria->id,
        'fecha_ingreso' => '2027-01-01',
        'cantidad_meses' => 1,
    ];

    get(route('habitacions.index'))->assertOk()->assertSee('150.50');
    post(route('registro_residencias.store'), $registro)->assertRedirectToRoute('registro_residencias.index');
    put(route('tipos_habitacion.update', $tipo), ['nombre' => 'Doble', 'costo_mensual' => '200.00'])
        ->assertSessionHasNoErrors();
    post(route('registro_residencias.store'), [...$registro, 'fecha_ingreso' => '2027-02-01'])
        ->assertRedirectToRoute('registro_residencias.index');

    expect(RegistroResidencia::whereDate('fecha_ingreso', '2027-01-01')->firstOrFail()->precio_mensual)->toBe('150.50')
        ->and(RegistroResidencia::whereDate('fecha_ingreso', '2027-02-01')->firstOrFail()->precio_mensual)->toBe('200.00');
    expect((float) $anterior->fresh()->costo_mensual)->toBe(100.0);
    delete(route('tipos_habitacion.destroy', $tipo))->assertSessionHas('error');
    assertDatabaseHas('tipos_habitacion', ['id' => $tipo->id]);
});
