<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residencias', function (Blueprint $table) {
            $table->unsignedInteger('max_habitaciones')->nullable();
        });

        Schema::create('tipos_habitacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->decimal('costo_mensual', 8, 2);
            $table->timestamps();
        });

        Schema::table('habitacions', function (Blueprint $table) {
            $table->foreignId('tipo_habitacion_id')->nullable()
                ->constrained('tipos_habitacion')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('habitacions', function (Blueprint $table) {
            $table->dropForeign(['tipo_habitacion_id']);
        });

        Schema::table('habitacions', function (Blueprint $table) {
            $table->dropColumn('tipo_habitacion_id');
        });

        Schema::dropIfExists('tipos_habitacion');

        Schema::table('residencias', function (Blueprint $table) {
            $table->dropColumn('max_habitaciones');
        });
    }
};
