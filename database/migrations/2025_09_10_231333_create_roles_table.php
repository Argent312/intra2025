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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_rol');
            $table->timestamps();


            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')
                  ->references('id')
                  ->on('areas')
                  ->onDelete('cascade'); // Opcional: elimina el área si se elimina el rol

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
