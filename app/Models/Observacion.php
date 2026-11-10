<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $fillable = [
        'habitacion_id',
        'descripcion',
        'fecha',
        'estado',
        'fecha_solucion'
    ];

    // Relación con la habitación
    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    // Residentes asociados al momento de registrar el daño
    public function registrosResidencia()
    {
        return $this->belongsToMany(
            RegistroResidencia::class,
            'observacion_residentes',
            'observacion_id',
            'registro_residencia_id'
        )->withTimestamps();
    }
}
