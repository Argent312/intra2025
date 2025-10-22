<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class DocHelper
{
    public static function sha256File(string $storagePath): string
    {
        // $storagePath puede ser 'public/documentos/archivo.pdf' o 'documentos/archivo.pdf'
        $disk = str_starts_with($storagePath, 'public/')
            ? Storage::disk('public')
            : Storage::disk(config('filesystems.default'));

        $relative = str_replace('public/', '', $storagePath);
        $stream = $disk->readStream($relative);
        if ($stream === false) return '';

        $ctx = hash_init('sha256');
        while (!feof($stream)) {
            $buf = fread($stream, 8192);
            hash_update($ctx, $buf);
        }
        fclose($stream);

        return hash_final($ctx);
    }

    public static function publicUrlFromRuta(string $ruta): string
    {
        // Si en DB guardas 'storage/documentos/archivo.pdf':
        return url($ruta); // https://tu-dominio/storage/documentos/archivo.pdf
    }

    public static function hmacHeader(array $payload, string $secret): string
    {
        $body = json_encode($payload, JSON_UNESCAPED_UNICODE);
        return 'sha256=' . hash_hmac('sha256', $body, $secret);
    }
}
