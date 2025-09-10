<?php

// app/Http/Controllers/Api/MeetingController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\sala;
use App\Models\comedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class MeetingController extends Controller
{
    // GET /api/meetings -> FullCalendar events
    public function index(Request $request)
    {
        // FullCalendar suele mandar ?start=YYYY-MM-DD&end=YYYY-MM-DD
        $start = $request->query('start'); // opcional, para filtrar por rango
        $end   = $request->query('end');

        $query = sala::query();

        if ($start && $end) {
            $query->where(function ($q) use ($start, $end) {
                // eventos que caen (parcial o totalmente) dentro del rango pedido
                $q->where('fecha_inicio', '<', $end)
                  ->where('fecha_fin', '>', $start);
            });
        }

        // Mapea al formato que FullCalendar espera
        $events = $query->get()->map(function ($m) {
            return [
                'id'    => $m->id,
                'title' => "Anfitrion: {$m->nombre_reservante}\nMotivo: {$m->motivo_reunion}\nParticipantes: {$m->participantes}",
                'participantes' => "{$m->participantes}",
                'start' => $m->fecha_inicio->format('Y-m-d\TH:i:s'),
                'end'   => $m->fecha_fin->format('Y-m-d\TH:i:s'),
                'backgroundColor' => '#0d6efd',
            ];
        });

        return response()->json($events);
    }

    // POST /api/meetings -> crear reserva
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nombre_reservante' => 'required|string|max:120',
            'motivo_reunion'    => 'required|string|max:255',
            'participantes'     => 'required|string|max:255',
            'fecha_inicio'      => 'required|date',
            'fecha_fin'         => 'required|date|after:fecha_inicio',
        ]);

        if ($v->fails()) {
            throw new ValidationException($v);
        }

        $inicio = new \DateTime($request->fecha_inicio);
        $fin    = new \DateTime($request->fecha_fin);

        // Regla de negocio (opcional): limitar a horario laboral 08:00–18:00
        // if ((int)$inicio->format('H') < 8 || (int)$fin->format('H') > 18) { ... }

        // Validar traslapes:
        $existeTraslape = sala::where(function ($q) use ($inicio, $fin) {
            $q->where('fecha_inicio', '<', $fin)   // inicio existente antes de que termine la nueva
              ->where('fecha_fin', '>', $inicio);  // fin existente después de que empiece la nueva
        })->exists();

        if ($existeTraslape) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una reunión en el rango seleccionado.',
            ], 409);
        }

        $meeting = sala::create([
            'nombre_reservante' => $request->nombre_reservante,
            'motivo_reunion'    => $request->motivo_reunion,
            'participantes'     => $request->participantes,
            'fecha_inicio'      => $inicio,
            'fecha_fin'         => $fin,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reservación creada correctamente.',
            'id'      => $meeting->id,
        ], 201);
    }

    // (Opcional) DELETE /api/meetings/{id}
    public function destroy(sala $meeting)
    {
        $meeting->delete();
        return response()->json(['success' => true]);
    }



    // GET /api/meetingsComedor -> FullCalendar events
    public function indexcomedor(Request $request)
    {
        // FullCalendar suele mandar ?start=YYYY-MM-DD&end=YYYY-MM-DD
        $start = $request->query('start'); // opcional, para filtrar por rango
        $end   = $request->query('end');

        $query = comedor::query();

        if ($start && $end) {
            $query->where(function ($q) use ($start, $end) {
                // eventos que caen (parcial o totalmente) dentro del rango pedido
                $q->where('fecha_inicio', '<', $end)
                  ->where('fecha_fin', '>', $start);
            });
        }

        // Mapea al formato que FullCalendar espera
        $events = $query->get()->map(function ($m) {
            return [
                'id'    => $m->id,
                'title' => "{$m->nombre_reservante}: {$m->motivo_reunion}",
                'start' => $m->fecha_inicio->format('Y-m-d\TH:i:s'),
                'end'   => $m->fecha_fin->format('Y-m-d\TH:i:s'),
                'backgroundColor' => '#0d6efd',
            ];
        });

        return response()->json($events);
    }

    // POST /api/meetings -> crear reserva
    public function storecomedor(Request $request)
    {
        $v = Validator::make($request->all(), [
            'nombre_reservante' => 'required|string|max:120',
            'motivo_reunion'    => 'required|string|max:255',
            'fecha_inicio'      => 'required|date',
            'fecha_fin'         => 'required|date|after:fecha_inicio',
        ]);

        if ($v->fails()) {
            throw new ValidationException($v);
        }

        $inicio = new \DateTime($request->fecha_inicio);
        $fin    = new \DateTime($request->fecha_fin);

        // Regla de negocio (opcional): limitar a horario laboral 08:00–18:00
        // if ((int)$inicio->format('H') < 8 || (int)$fin->format('H') > 18) { ... }

        // Validar traslapes:
        $existeTraslape = comedor::where(function ($q) use ($inicio, $fin) {
            $q->where('fecha_inicio', '<', $fin)   // inicio existente antes de que termine la nueva
              ->where('fecha_fin', '>', $inicio);  // fin existente después de que empiece la nueva
        })->exists();

        if ($existeTraslape) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una reunión en el rango seleccionado.',
            ], 409);
        }

        $meeting = comedor::create([
            'nombre_reservante' => $request->nombre_reservante,
            'motivo_reunion'    => $request->motivo_reunion,
            'fecha_inicio'      => $inicio,
            'fecha_fin'         => $fin,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reservación creada correctamente.',
            'id'      => $meeting->id,
        ], 201);
    }

    public function reuniones()
    {
        $reuniones = sala::orderBy('fecha_inicio')->where('fecha_inicio', '>=', Carbon::today())->get();
        return view('reuniones', compact('reuniones'));
    }
}
