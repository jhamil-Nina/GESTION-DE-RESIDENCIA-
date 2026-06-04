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
        'fecha_salida'
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

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function observaciones()
    {
        return $this->hasMany(Observacion::class);
    }
}
 