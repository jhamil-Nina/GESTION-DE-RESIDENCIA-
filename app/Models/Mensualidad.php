<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensualidad extends Model
{
    // Nombre exacto de la tabla en MySQL
    protected $table = 'mensualidades';

    protected $fillable = [
        'registro_residencia_id',
        'numero_mes',
        'fecha_inicio',
        'fecha_fin',
        'monto',
        'estado'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'monto' => 'decimal:2'
    ];

    public function registroResidencia()
    {
        return $this->belongsTo(RegistroResidencia::class);
    }

    public function pagos()
    {
        return $this->belongsToMany(
            Pago::class,
            'mensualidad_pago',
            'mensualidad_id',
            'pago_id'
        )->withPivot('monto_aplicado')->withTimestamps();
    }
}

