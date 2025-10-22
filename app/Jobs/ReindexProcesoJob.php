<?php

namespace App\Jobs;

use App\Models\proceso; // si tu modelo es Proceso con P mayúscula, ajusta el use
use App\Services\PdfTextExtractor;
use App\Services\TextPostProcessor;
use App\Services\DocIndexWriter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReindexProcesoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    public int $procesoId;

    /**
     * @param int $procesoId
     */
    public function __construct(int $procesoId)
    {
        $this->procesoId = $procesoId;
        // Si quieres forzar la cola:
        // $this->onQueue('index');
    }

    /**
     * Extrae texto del PDF, limpia, secciona y guarda chunks en BD.
     */
    public function handle(
        PdfTextExtractor $extractor,
        TextPostProcessor $post,
        DocIndexWriter $writer
    ): void {
        $p = proceso::find($this->procesoId);
        if (!$p) {
            Log::warning('[IndexJob] Proceso no encontrado', ['proceso_id' => $this->procesoId]);
            return;
        }
        if (!$p->ruta) {
            Log::warning('[IndexJob] Proceso sin ruta', ['proceso_id' => $p->id]);
            return;
        }

        Log::info('[IndexJob] START', ['proceso_id' => $p->id]);

        // Lee y valida texto
        $raw = $extractor->extractFromRutaPublica($p->ruta);
        Log::info('[IndexJob] RAW length', ['len' => mb_strlen($raw)]);
        if (mb_strlen($raw) < 20) {
            Log::warning('[IndexJob] PDF vacío o escaneado sin OCR', ['proceso_id' => $p->id]);
            return;
        }

        // Limpieza + secciones
        $clean  = $post->removeBoilerplate($raw);
        $sects  = $post->splitByHeadings($clean); // devuelve array de ['title'=>..., 'text'=>...]
        $vInt   = $this->normalizeVersionToInt((string)$p->version);

        DB::beginTransaction();
        try {
            // Limpia versión anterior de ese proceso
            $writer->deleteOldVersion($p->id, $vInt);

            $secCount = 0;
            $chunkCount = 0;

            foreach ($sects as $s) {
                $title = $s['title'] ?? 'SECCIÓN';
                $text  = (string)($s['text'] ?? '');

                // Upsert sección
                $seccionId = $writer->upsertSeccion($p->id, $vInt, $title);
                $secCount++;

                // Genera chunks (streaming)
                foreach ($post->makeChunksGenerator($text, 4000, 600) as $chunk) {
                    // Inserta 1 chunk (embeddings_pending=1)
                    $writer->insertChunk($p->id, $vInt, $seccionId, $chunk, 1);
                    $chunkCount++;
                }
            }

            DB::commit();
            Log::info('[IndexJob] OK', [
                'proceso_id' => $p->id,
                'version'    => $vInt,
                'sections'   => $secCount,
                'chunks'     => $chunkCount,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[IndexJob] Error escribiendo índice en BD', [
                'proceso_id' => $p->id,
                'error'      => $e->getMessage(),
            ]);
            // Reintenta en 30s si falló algo transitorio
            $this->release(30);
        }
    }

    /**
     * Convierte "V-2" o "2" a 2.
     */
    private function normalizeVersionToInt(string $v): int
    {
        return preg_match('/(\d+)/', $v, $m) ? (int)$m[1] : 0;
    }
}
