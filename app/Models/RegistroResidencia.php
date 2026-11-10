<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroResidencia extends Model
{
    protected $fillable = [
        'user_id',
        'habitacion_id',
        'categoria_ocupacion_id',
        'fecha_ingreso',
        'fecha_salida',
        'cantidad_meses',
        'precio_mensual',
        'monto_total'
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_salida' => 'date',
        'cantidad_meses' => 'integer',
        'precio_mensual' => 'decimal:2',
        'monto_total' => 'decimal:2',
    ];

    // RELACIONES

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function categoriaOcupacion()
    {
        return $this->belongsTo(CategoriaOcupacion::class);
    }

    // Un registro puede tener varios pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'registro_residencia_id');
    }

    // Un registro puede tener varias mensualidades
    public function mensualidades()
    {
        return $this->hasMany(Mensualidad::class, 'registro_residencia_id');
    }

    // Observaciones históricas vinculadas al residente
    public function observaciones()
    {
        return $this->belongsToMany(
            Observacion::class,
            'observacion_residentes',
            'registro_residencia_id',
            'observacion_id'
        );
    }
}

