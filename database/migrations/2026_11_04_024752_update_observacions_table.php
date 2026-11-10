
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('observacions', function (Blueprint $table) {

            // Eliminar la relación anterior
            $table->dropForeign(['registro_residencia_id']);
            $table->dropColumn('registro_residencia_id');

            // Relacionar las observaciones con habitaciones
            $table->foreignId('habitacion_id')
                  ->constrained('habitacions')
                  ->cascadeOnDelete();

            // Seguimiento del daño
            $table->enum('estado', [
                'Pendiente',
                'En reparación',
                'Solucionado'
            ])->default('Pendiente');

            $table->dateTime('fecha_solucion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('observacions', function (Blueprint $table) {

            $table->dropForeign(['habitacion_id']);

            $table->dropColumn([
                'habitacion_id',
                'estado',
                'fecha_solucion'
            ]);

            // Restaurar la relación anterior
            $table->foreignId('registro_residencia_id')
                  ->constrained('registro_residencias')
                  ->cascadeOnDelete();
        });
    }
};
