<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('documento_chunks', function (Blueprint $table) {
            if (!Schema::hasColumn('documento_chunks', 'chunk_index')) {
                $table->unsignedInteger('chunk_index')->nullable()->after('seccion_id');
            } else {
                // Por si existe pero es NOT NULL sin default:
                try {
                    $table->unsignedInteger('chunk_index')->nullable()->change();
                } catch (\Throwable $e) {
                    // Si tu MySQL requiere doctrine/dbal para change(), ignora el catch; abajo te doy alternativa SQL.
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('documento_chunks', function (Blueprint $table) {
            if (Schema::hasColumn('documento_chunks', 'chunk_index')) {
                $table->dropColumn('chunk_index');
            }
        });
    }
};
