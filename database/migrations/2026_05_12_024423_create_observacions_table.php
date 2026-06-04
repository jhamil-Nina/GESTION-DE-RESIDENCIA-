<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observacions', function (Blueprint $table) {

            $table->id();

            $table->foreignId('registro_residencia_id')
                  ->constrained('registro_residencias')
                  ->onDelete('cascade');

            $table->text('descripcion');

            $table->date('fecha');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observacions');
    }
    
};  
