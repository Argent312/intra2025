<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('documento_chunks', function (Blueprint $table) {
            // tinyInteger para ahorrar espacio; default=1 (pendiente)
            $table->tinyInteger('embeddings_pending')->default(1)->after('texto');
            $table->index(['procesos_id', 'version', 'embeddings_pending'], 'idx_chunks_embed_pending');
        });
    }

    public function down(): void
    {
        Schema::table('documento_chunks', function (Blueprint $table) {
            $table->dropIndex('idx_chunks_embed_pending');
            $table->dropColumn('embeddings_pending');
        });
    }
};
