<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('registro_residencia_id')
                  ->constrained('registro_residencias')
                  ->onDelete('cascade');

            $table->decimal('monto', 8, 2);

            $table->date('fecha_pago');

            $table->string('metodo_pago'); // efectivo, transferencia, etc

            $table->string('estado')->default('pagado'); // pagado, pendiente

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
     
}; 
