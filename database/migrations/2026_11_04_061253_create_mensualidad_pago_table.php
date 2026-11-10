
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensualidad_pago', function (Blueprint $table) {

            $table->id();

            // Mensualidad que se está pagando
            $table->foreignId('mensualidad_id')
                ->constrained('mensualidades')
                ->restrictOnDelete();

            // Pago que cubre la mensualidad
            $table->foreignId('pago_id')
                ->constrained('pagos')
                ->restrictOnDelete();

            // Monto aplicado a esta mensualidad
            $table->decimal('monto_aplicado', 10, 2);

            $table->timestamps();

            // Una mensualidad solo puede pertenecer
            // a un pago en este esquema
            $table->unique('mensualidad_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensualidad_pago');
    }
};
