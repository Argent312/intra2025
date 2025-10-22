<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class PdfTextExtractor
{
    /**
     * Recibe la RUTA PÚBLICA guardada en "procesos.ruta" (p.ej. 'storage/documentos/xxx.pdf')
     * y devuelve el texto extraído.
     */
    public function extractFromRutaPublica(string $rutaPublica): string
    {
        // Convierte a ruta real en disco del disk 'public'
        $rutaInterna = str_replace('storage/', '', $rutaPublica); // 'documentos/...'
        $pdfPath = Storage::disk('public')->path($rutaInterna);

        $parser = new Parser();
        $pdf = $parser->parseFile($pdfPath);

        $text = $pdf->getText();

        // Normalización básica
        $text = preg_replace('/[ \t]+\n/u', "\n", $text); // espacios al final de línea
        $text = preg_replace('/\n{3,}/u', "\n\n", $text); // saltos de línea excesivos
        $text = preg_replace('/\s+/u', ' ', $text);       // espacios múltiples
        return trim($text);
    }
}
