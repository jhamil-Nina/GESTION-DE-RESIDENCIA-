<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $fillable = [
        'numero',
        'capacidad',
        'costo_mensual',
        'estado',
        'residencia_id'
    ];

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
