<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ---- documento_secciones ----
        if (Schema::hasTable('documento_secciones')) {
            Schema::table('documento_secciones', function (Blueprint $table) {
                if (!Schema::hasColumn('documento_secciones', 'version')) {
                    $table->unsignedInteger('version')->default(0)->after('procesos_id');
                }
                if (!Schema::hasColumn('documento_secciones', 'titulo')) {
                    $table->string('titulo')->after('version');
                }
            });

            // UNIQUE (procesos_id, version, titulo) si no existe
            $exists = DB::table('information_schema.statistics')
                ->where('table_schema', DB::getDatabaseName())
                ->where('table_name', 'documento_secciones')
                ->where('index_name', 'documento_secciones_procesos_id_version_titulo_unique')
                ->exists();

            if (!$exists) {
                try {
                    DB::statement('
                        ALTER TABLE `documento_secciones`
                        ADD UNIQUE `documento_secciones_procesos_id_version_titulo_unique`
                        (`procesos_id`, `version`, `titulo`)
                    ');
                } catch (\Throwable $e) { /* noop */ }
            }
        }

        // ---- documento_chunks ----
        if (Schema::hasTable('documento_chunks')) {
            Schema::table('documento_chunks', function (Blueprint $table) {
                if (!Schema::hasColumn('documento_chunks', 'version')) {
                    $table->unsignedInteger('version')->default(0)->after('procesos_id');
                }
                if (!Schema::hasColumn('documento_chunks', 'embeddings_pending')) {
                    $table->boolean('embeddings_pending')->default(1)->after('texto');
                }
                if (!Schema::hasColumn('documento_chunks', 'embedding_model')) {
                    $table->string('embedding_model')->nullable()->after('embeddings_pending');
                }
                if (!Schema::hasColumn('documento_chunks', 'embedding_vector')) {
                    $table->longText('embedding_vector')->nullable()->after('embedding_model');
                }
            });

            // INDEX (procesos_id, version)
            $idx1 = DB::table('information_schema.statistics')
                ->where('table_schema', DB::getDatabaseName())
                ->where('table_name', 'documento_chunks')
                ->where('index_name', 'documento_chunks_procesos_id_version_index')
                ->exists();
            if (!$idx1) {
                try {
                    DB::statement('
                        ALTER TABLE `documento_chunks`
                        ADD INDEX `documento_chunks_procesos_id_version_index` (`procesos_id`, `version`)
                    ');
                } catch (\Throwable $e) { /* noop */ }
            }

            // INDEX (seccion_id) si existe la columna
            if (Schema::hasColumn('documento_chunks', 'seccion_id')) {
                $idx2 = DB::table('information_schema.statistics')
                    ->where('table_schema', DB::getDatabaseName())
                    ->where('table_name', 'documento_chunks')
                    ->where('index_name', 'documento_chunks_seccion_id_index')
                    ->exists();
                if (!$idx2) {
                    try {
                        DB::statement('
                            ALTER TABLE `documento_chunks`
                            ADD INDEX `documento_chunks_seccion_id_index` (`seccion_id`)
                        ');
                    } catch (\Throwable $e) { /* noop */ }
                }
            }

            // elimina la columna obsoleta si existiera
            if (Schema::hasColumn('documento_chunks', 'version_int')) {
                try {
                    Schema::table('documento_chunks', function (Blueprint $table) {
                        $table->dropColumn('version_int');
                    });
                } catch (\Throwable $e) { /* noop */ }
            }
        }
    }

    public function down(): void
    {
        // No hacemos rollback destructivo.
    }
};
