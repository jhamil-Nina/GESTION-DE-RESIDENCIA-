<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_residencias', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('habitacion_id')
                  ->constrained('habitacions')
                  ->onDelete('cascade');

            $table->foreignId('categoria_ocupacion_id')
                  ->constrained('categoria_ocupacions')
                  ->onDelete('cascade');

            $table->date('fecha_ingreso');

            $table->date('fecha_salida')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_residencias');
    }
};
