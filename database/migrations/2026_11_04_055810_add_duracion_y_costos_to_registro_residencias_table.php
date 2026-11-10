
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registro_residencias', function (Blueprint $table) {

            $table->unsignedInteger('cantidad_meses')->nullable();

            $table->decimal('precio_mensual', 10, 2)->nullable();

            $table->decimal('monto_total', 10, 2)->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('registro_residencias', function (Blueprint $table) {

            $table->dropColumn([
                'cantidad_meses',
                'precio_mensual',
                'monto_total'
            ]);

        });
    }
};
