<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class n8nController extends Controller
{
    public function correoPresup(Request $request)
    {
        // 1) Validación básica (ajusta a tu gusto)
        $request->validate([
            'cargo'     => ['required', 'in:Urbanizacion,Vivienda'],
            'miArchivo' => ['nullable', 'file', 'mimes:xlsx,xls,csv', 'max:20480'], // 20MB
            'miPDF'     => ['nullable', 'file', 'mimes:pdf', 'max:20480'],          // 20MB
            'email'     => ['required', 'email'],
        ]);

        // 2) Normalizar checkboxes (si no vienen, quedan en null)
        $payload = [
            'cargo'      => $request->string('cargo'),
            'email'      => $request->string('email'),
            'sistema'    => $request->boolean('sistema')    ? 'Ya se encuentra en sistema'                  : null,
            'cambio'     => $request->boolean('cambio')     ? 'Orden de cambio'                              : null,
            'control'    => $request->boolean('control')    ? 'Orden de cambio control'                      : null,
            'cerrar'     => $request->boolean('cerrar')     ? 'Cerrar Obra'                                  : null,
            'generacion' => $request->boolean('generacion') ? 'Generación automática del presupuesto'        : null,
        ];

        // 3) Preparar llamada multipart hacia n8n
        $http = Http::asMultipart();

        if ($request->hasFile('miArchivo')) {
            $file = $request->file('miArchivo');
            $http = $http->attach(
                'miArchivo',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            );
        }

        if ($request->hasFile('miPDF')) {
            $pdf = $request->file('miPDF');
            $http = $http->attach(
                'miPDF',
                file_get_contents($pdf->getRealPath()),
                $pdf->getClientOriginalName()
            );
        }

        // 4) Enviar a tu webhook de n8n
        $webhookUrl = 'http://localhost:5678/webhook-test/fd1dd49a-a8a1-4c2b-882f-b9e0654a3f66';

        $response = $http->post($webhookUrl, $payload);

        // Opcional: Log rápido si necesitas revisar respuesta
        // \Log::info('n8n response', ['status' => $response->status(), 'body' => $response->body()]);

        if (!$response->successful()) {
            // Si quieres, muestra un error controlado
            return redirect()->back()
                ->withInput([]) // igual limpiamos
                ->with('success', 'Se envió, pero n8n respondió: '.$response->status());
        }

        // 5) Regresar a la misma página con campos vacíos
        return redirect()->back()->withInput([])->with('success', 'Datos enviados correctamente');
    }
}
