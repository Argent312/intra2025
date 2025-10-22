<?php

namespace App\Services;

use Generator;
use Illuminate\Support\Str;

class TextPostProcessor
{
    /**
     * Limpia portadas, pies de página simples y espacios raros.
     */
    public function removeBoilerplate(string $text): string
    {
        // Normaliza saltos de línea y espacios
        $t = str_replace(["\r\n", "\r"], "\n", $text);
        $t = preg_replace('/[ \t]+/', ' ', $t);
        $t = preg_replace('/\n{3,}/', "\n\n", $t);

        // Quita líneas tipo número de página: "Página 12", "Page 3"
        $t = preg_replace('/^\s*(Página|Page)\s+\d+\s*$/mi', '', $t);

        // Quita líneas con solo números/guiones (frecuentes en pies)
        $t = preg_replace('/^\s*[-–—\d]+\s*$/m', '', $t);

        return trim($t);
    }

    /**
     * Separa por encabezados (##, mayúsculas, o palabras típicas).
     * Devuelve: [['title' => '...', 'text' => '...'], ...]
     */
    public function splitByHeadings(string $text): array
    {
        $lines = preg_split('/\n/', $text);
        $sections = [];
        $currentTitle = 'INTRODUCCIÓN';
        $currentBuf = [];

        $isHeading = function (string $line): bool {
            $l = trim($line);

            if ($l === '') return false;
            if (preg_match('/^#{1,6}\s+\S+/', $l)) return true;                // Markdown
            if (preg_match('/^(INTRODUCCIÓN|OBJETIVO|ALCANCE|DEFINICIONES|PROCEDIMIENTO|RESPONSABILIDADES|ANEXOS|REFERENCIAS)\b/iu', $l)) return true;
            // Línea corta y casi toda en mayúsculas → probable título
            if (mb_strlen($l) <= 80 && $l === mb_strtoupper($l) && preg_match('/[A-ZÁÉÍÓÚÑ]/u', $l)) return true;

            return false;
        };

        foreach ($lines as $line) {
            if ($isHeading($line)) {
                // Cierra sección previa
                if (!empty($currentBuf)) {
                    $sections[] = [
                        'title' => $currentTitle,
                        'text'  => trim(implode("\n", $currentBuf)),
                    ];
                    $currentBuf = [];
                }
                $currentTitle = trim(ltrim($line, '# '));
            } else {
                $currentBuf[] = $line;
            }
        }

        // Última sección
        if (!empty($currentBuf)) {
            $sections[] = [
                'title' => $currentTitle,
                'text'  => trim(implode("\n", $currentBuf)),
            ];
        }

        // Si no detectó nada, devuelve 1 sección
        if (empty($sections)) {
            return [[ 'title' => 'INTRODUCCIÓN', 'text' => trim($text) ]];
        }

        return $sections;
    }

    /**
     * Generador de chunks con solapamiento para optimizar tokens.
     * $maxChars ≈ 4000 y $overlap ≈ 600 suelen ir bien.
     */
    public function makeChunksGenerator(string $text, int $maxChars = 4000, int $overlap = 600): Generator
    {
        $text = trim($text);
        if ($text === '') {
            return; // no yield
        }

        // Primero partimos por párrafos para no romper frases
        $paras = preg_split("/\n{2,}/", $text);
        $buf = '';

        foreach ($paras as $p) {
            $p = trim($p);
            if ($p === '') continue;

            // Si el párrafo por sí solo excede maxChars, forzamos cortes por oración
            if (mb_strlen($p) > $maxChars) {
                yield from $this->chunkBySentences($p, $maxChars, $overlap);
                continue;
            }

            if ($buf === '') {
                $buf = $p;
                continue;
            }

            if (mb_strlen($buf) + 2 + mb_strlen($p) <= $maxChars) {
                $buf .= "\n\n".$p;
            } else {
                yield $buf;

                // Calcula solapamiento al final del buf
                if ($overlap > 0 && mb_strlen($buf) > $overlap) {
                    $bufTail = mb_substr($buf, -$overlap);
                    $buf = $bufTail . "\n\n" . $p;
                } else {
                    $buf = $p;
                }
            }
        }

        if ($buf !== '') {
            yield $buf;
        }
    }

    /**
     * Fallback: si un párrafo es enorme, corta por oraciones con solapamiento.
     */
    protected function chunkBySentences(string $text, int $maxChars, int $overlap): Generator
    {
        // Split básico por final de oración (.!?), conservando separadores simples
        $sentences = preg_split('/(?<=[\.\!\?])\s+/u', $text);
        $buf = '';

        foreach ($sentences as $s) {
            $s = trim($s);
            if ($s === '') continue;

            if ($buf === '') {
                $buf = $s;
                continue;
            }

            if (mb_strlen($buf) + 1 + mb_strlen($s) <= $maxChars) {
                $buf .= ' ' . $s;
            } else {
                yield $buf;

                if ($overlap > 0 && mb_strlen($buf) > $overlap) {
                    $bufTail = mb_substr($buf, -$overlap);
                    $buf = $bufTail . ' ' . $s;
                } else {
                    $buf = $s;
                }
            }
        }

        if ($buf !== '') {
            yield $buf;
        }
    }
}
