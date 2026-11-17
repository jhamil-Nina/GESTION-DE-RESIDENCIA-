<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $fillable = [
        'numero',
        'capacidad',
        'costo_mensual',
        'estado',
        'tipo_habitacion_id',
        'residencia_id'
    ];

    protected function costoMensual(): Attribute
    {
        return Attribute::get(fn ($valor) => $this->tipoHabitacion?->costo_mensual ?? $valor);
    }

    public function tipoHabitacion()
    {
        return $this->belongsTo(TipoHabitacion::class);
    }

    // RELACIONES

    public function residencia()
    {
        return $this->belongsTo(Residencia::class);
    }

    public function registrosResidencia()
    {
        return $this->hasMany(
            \App\Models\RegistroResidencia::class,
            'habitacion_id'
        );
    }

    // Una habitación puede tener varias observaciones
    public function observaciones()
    {
        return $this->hasMany(Observacion::class);
    }
}
