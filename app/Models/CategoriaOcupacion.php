<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaOcupacion extends Model
{
    protected $table = 'categoria_ocupacions';

    protected $fillable = [
        'nombre'
    ];

    // RELACIONES

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
 