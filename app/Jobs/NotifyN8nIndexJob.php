<?php

namespace App\Jobs;

use App\Services\N8nNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyN8nIndexJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $payload;

    /**
     * Crea el Job con el payload para n8n
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
        // Si quieres una cola dedicada:
        // $this->onQueue('n8n');
    }

    /**
     * Ejecuta la notificación (con reintentos en el propio servicio).
     */
    public function handle(N8nNotifier $notifier): void
    {
        $ok = $notifier->notifyIndex($this->payload);

        // Si falla, reintenta más tarde (reintento de la cola)
        if (!$ok) {
            $this->release(30); // vuelve a intentar en 30s
        }
    }
}
