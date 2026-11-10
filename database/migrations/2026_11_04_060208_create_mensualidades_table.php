
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensualidades', function (Blueprint $table) {

            $table->id();

            // Registro al que pertenece la mensualidad
            $table->foreignId('registro_residencia_id')
                ->constrained('registro_residencias')
                ->restrictOnDelete();

            // Número de mensualidad dentro del contrato
            $table->unsignedInteger('numero_mes');

            // Período que cubre esta mensualidad
            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            // Importe correspondiente al mes
            $table->decimal('monto', 10, 2);

            // Estado del cobro
            $table->enum('estado', [
                'Pendiente',
                'Pagado'
            ])->default('Pendiente');

            $table->timestamps();

            // Evita duplicar un mes del mismo registro
            $table->unique(
                ['registro_residencia_id', 'numero_mes'],
                'mensualidad_registro_mes_unique'
            );

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensualidades');
    }
};
