<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    protected $table = 'tipos_habitacion';

    protected $fillable = ['nombre', 'costo_mensual'];

    protected $casts = ['costo_mensual' => 'decimal:2'];

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class);
    }
}
