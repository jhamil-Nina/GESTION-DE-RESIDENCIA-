<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('habitacions', function (Blueprint $table) {

            $table->id();

            $table->string('numero'); // número de habitación

            $table->integer('capacidad'); // cuántas personas pueden vivir

            $table->foreignId('residencia_id')
                  ->constrained('residencias')
                  ->onDelete('cascade');
            
            $table->unique(['numero','residencia_id']); //evitar dos habitaciones con el mismo numero

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('habitacions');
    }
};
