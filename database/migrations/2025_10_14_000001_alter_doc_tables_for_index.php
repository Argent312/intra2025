<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Asegura 'documento_secciones'
        if (Schema::hasTable('documento_secciones')) {
            Schema::table('documento_secciones', function (Blueprint $table) {
                if (!Schema::hasColumn('documento_secciones', 'version')) {
                    $table->unsignedInteger('version')->default(0)->after('procesos_id');
                }
                if (!Schema::hasColumn('documento_secciones', 'titulo')) {
                    $table->string('titulo')->after('version');
                }
                // Unique recomendado
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = array_map(fn($i) => $i->getName(), $sm->listTableIndexes('documento_secciones'));
                if (!in_array('documento_secciones_procesos_id_version_titulo_unique', $indexes)) {
                    $table->unique(['procesos_id', 'version', 'titulo']);
                }
            });
        }

        // Asegura 'documento_chunks'
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

                // Índices útiles
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexes = array_map(fn($i) => $i->getName(), $sm->listTableIndexes('documento_chunks'));

                if (!in_array('documento_chunks_procesos_id_version_index', $indexes)) {
                    $table->index(['procesos_id', 'version']);
                }
                if (!in_array('documento_chunks_seccion_id_index', $indexes) && Schema::hasColumn('documento_chunks', 'seccion_id')) {
                    $table->index(['seccion_id']);
                }
            });
        }

        // Si por algún motivo existe una columna vieja 'version_int', puedes eliminarla:
        if (Schema::hasTable('documento_chunks') && Schema::hasColumn('documento_chunks', 'version_int')) {
            Schema::table('documento_chunks', function (Blueprint $table) {
                $table->dropColumn('version_int');
            });
        }
    }

    public function down(): void
    {
        // No hacemos down destructivo; deja vacío o solo quita índices si quieres.
    }
};
