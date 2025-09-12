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
        Schema::create('unions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();


            $table->unsignedBigInteger('procesos_id');
            $table->foreign('procesos_id')
                  ->references('id')
                  ->on('procesos')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('directions_id');
            $table->foreign('directions_id')
                  ->references('id')
                  ->on('directions')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')
                  ->references('id')
                  ->on('areas')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('roles_id');
            $table->foreign('roles_id')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unions');
    }
};
