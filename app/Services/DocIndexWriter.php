<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DocIndexWriter
{
    /**
     * Crea o devuelve la sección (por procesos_id+version+titulo).
     * Retorna el ID de documento_secciones.
     */
    public function upsertSeccion(int $procesoId, int $version, string $titulo): int
    {
        $titulo = trim($titulo) !== '' ? trim($titulo) : 'SECCIÓN';

        $existing = DB::table('documento_secciones')
            ->where('procesos_id', $procesoId)
            ->where('version', $version)
            ->where('titulo', $titulo)
            ->first();

        if ($existing) {
            return (int) $existing->id;
        }

        return (int) DB::table('documento_secciones')->insertGetId([
            'procesos_id' => $procesoId,
            'version'     => $version,
            'titulo'      => $titulo,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    /**
     * Elimina índice previo de esa versión (secciones y chunks).
     * OJO: usa la columna `version` (no `version_int`).
     */
    public function deleteOldVersion(int $procesoId, int $version): void
    {
        DB::table('documento_chunks')
            ->where('procesos_id', $procesoId)
            ->where('version', $version)
            ->delete();

        DB::table('documento_secciones')
            ->where('procesos_id', $procesoId)
            ->where('version', $version)
            ->delete();
    }

    /**
     * Inserta varios chunks. Si tienes un método propio ya creado,
     * conserva el tuyo. Este es un ejemplo compatible con `chunk_index`.
     */
    public function insertChunks(int $procesoId, int $version, int $seccionId, array $chunks): int
    {
        if (empty($chunks)) return 0;

        // Calcula el siguiente chunk_index continuo para esa sección
        $nextIndex = 0;
        if (Schema::hasColumn('documento_chunks', 'chunk_index')) {
            $max = DB::table('documento_chunks')
                ->where('procesos_id', $procesoId)
                ->where('version', $version)
                ->where('seccion_id', $seccionId)
                ->max('chunk_index');
            $nextIndex = is_null($max) ? 0 : ((int)$max + 1);
        }

        $now = now();
        $rows = [];

        foreach ($chunks as $c) {
            $texto = is_array($c) ? ($c['text'] ?? '') : (string)$c;
            $texto = trim($texto);
            if ($texto === '') continue;

            $row = [
                'procesos_id'        => $procesoId,
                'version'            => $version,
                'seccion_id'         => $seccionId,
                'texto'              => $texto,
                'created_at'         => $now,
                'updated_at'         => $now,
            ];

            // Campos opcionales si existen en tu tabla
            if (Schema::hasColumn('documento_chunks', 'chunk_index')) {
                $row['chunk_index'] = $nextIndex++;
            }
            if (Schema::hasColumn('documento_chunks', 'embeddings_pending')) {
                $row['embeddings_pending'] = 1;
            }
            if (Schema::hasColumn('documento_chunks', 'embedding_model')) {
                $row['embedding_model'] = null;
            }
            if (Schema::hasColumn('documento_chunks', 'embedding_vector')) {
                $row['embedding_vector'] = null; // o formato de vector si ya embebes aquí
            }

            $rows[] = $row;
        }

        if (!empty($rows)) {
            DB::table('documento_chunks')->insert($rows);
        }

        return count($rows);
    }

    /**
     * (Opcional) Atajo para una sola pieza de texto.
     */
    public function insertChunk(
        int $procesoId,
        int $version,
        int $seccionId,
        string $texto,
        int $chunkIndex = 0
    ): int {
        $texto = trim($texto);
        if ($texto === '') return 0;

        DB::table('documento_chunks')->insert([
            'procesos_id'        => $procesoId,
            'version'            => $version,
            'seccion_id'         => $seccionId,
            'chunk_index'        => $chunkIndex,               // <- requerido por tu schema
            'texto'              => $texto,
            'texto_hash'         => hash('sha256', $texto),    // <- requerido por tu schema
            'embeddings_pending' => 1,                         // pendiente de embed
            'embedding_model'    => null,                      // si no existe la columna, quita esta línea
            'embedding_vector'   => null,                      // si no existe, quita esta línea
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        return 1;
    }
}

