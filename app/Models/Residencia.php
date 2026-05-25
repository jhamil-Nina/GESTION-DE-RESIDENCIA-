<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Residencia extends Model
{
    protected $fillable = [
        'nombre',
        'direccion',
        'capacidad'
    ];


    // RELACIONES

    public function habitacions()
    {
        return $this->hasMany(Habitacion::class);
    }
}
