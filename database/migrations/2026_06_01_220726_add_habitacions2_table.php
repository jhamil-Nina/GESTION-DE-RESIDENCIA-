<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('habitacions', function (Blueprint $table) {

            $table->decimal('costo_mensual', 8, 2)
                ->default(0)
                ->after('capacidad');

            $table->enum('estado', [
                'Disponible',
                'Ocupada',
                'Mantenimiento'
            ])->default('Disponible')
                ->after('costo_mensual');
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::table('habitacions', function (Blueprint $table) {

            $table->dropColumn('costo_mensual');
            $table->dropColumn('estado');
        });
    }
};
