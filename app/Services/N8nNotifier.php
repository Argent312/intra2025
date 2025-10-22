<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nNotifier
{
    public function notifyIndex(array $payload): bool
    {
        // Usa la clave que sí tienes en config/services.php
        $url     = config('services.n8n.index_webhook');
        $timeout = (int) config('services.n8n.timeout', 20);
        $secret  = (string) config('services.n8n.hmac_secret', '');

        if (!$url) {
            Log::error('[N8N] notify error', [
                'msg' => 'services.n8n.index_webhook es null (revisa .env y config/services.php)',
            ]);
            return false;
        }

        $request = Http::timeout($timeout)->asJson();

        // Firma opcional HMAC
        if ($secret !== '') {
            $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
            $sig  = 'sha256=' . hash_hmac('sha256', $json, $secret);
            $request = $request->withHeaders(['X-Signature' => $sig]);
        }

        try {
            $resp = $request->post($url, $payload);
            Log::info('[N8N] notify ok', ['status' => $resp->status()]);
            return $resp->successful();
        } catch (\Throwable $e) {
            Log::error('[N8N] notify exception', ['msg' => $e->getMessage()]);
            return false;
        }
    }
}
