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
        Schema::create('comedors', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_reservante');
            $table->string('motivo_reunion');
            $table->string('participantes');
            // Usa dateTime en lugar de timestamp
            $table->dateTime('fecha_inicio'); 
            $table->dateTime('fecha_fin');    
            $table->timestamps(); // created_at / updated_at como timestamps NULL ok
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comedors');
    }
};
