
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observacion_residentes', function (Blueprint $table) {

            $table->id();

            // Observación a la que pertenece
            $table->foreignId('observacion_id')
                  ->constrained('observacions')
                  ->cascadeOnDelete();

            // Estancia del residente al registrar el daño
            $table->foreignId('registro_residencia_id')
                  ->constrained('registro_residencias')
                  ->restrictOnDelete();

            // Evitar duplicar un residente en la misma observación
            $table->unique(
                ['observacion_id', 'registro_residencia_id'],
                'obs_residentes_unique'
            );

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observacion_residentes');
    }
};

