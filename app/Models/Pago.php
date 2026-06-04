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
}
 