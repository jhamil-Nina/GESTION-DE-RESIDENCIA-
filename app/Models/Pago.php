<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'registro_residencia_id',
        'monto',
        'fecha_pago',
        'metodo_pago',
        'estado'
    ];

    // RELACION

    public function registroResidencia()
    {
        return $this->belongsTo(RegistroResidencia::class);
    }

    // Un pago puede cubrir varias mensualidades
    public function mensualidades()
    {
        return $this->belongsToMany(
            Mensualidad::class,
            'mensualidad_pago',
            'pago_id',
            'mensualidad_id'
        )->withPivot('monto_aplicado')->withTimestamps();
    }

}
