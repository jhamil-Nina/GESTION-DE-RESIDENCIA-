<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $fillable = [
        'numero',
        'capacidad',
        'residencia_id'
    ];

    // RELACIONES

    public function residencia()
    {
        return $this->belongsTo(Residencia::class);
    }

    public function registrosResidencia()
    {
        return $this->hasMany(RegistroResidencia::class);
    }
}
