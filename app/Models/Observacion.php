<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observacion extends Model
{
    protected $fillable = [
        'registro_residencia_id',
        'descripcion',
        'fecha'
    ];

    // RELACION

    public function registroResidencia()
    {
        return $this->belongsTo(RegistroResidencia::class);
    }
}
