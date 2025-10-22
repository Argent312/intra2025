<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\role;
use App\Models\proceso;
use App\Models\direction;
use App\Models\area;
use App\Models\union;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\PdfTextExtractor;
use App\Services\TextPostProcessor;
use App\Services\DocIndexWriter;
use App\Jobs\NotifyN8nIndexJob; 
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Jobs\ReindexProcesoJob;

class SgcController extends Controller
{
    public function admin()
    {
        if (Auth::user()->administrator !== 1) {
            abort(403, 'Acceso denegado');
        }
        return view('admin');
    }


    public function list()
    {
        if (Auth::user()->administrator !== 1) {
            abort(403, 'Acceso denegado');
        }

        $directions = direction::all();
        $areas = area::orderBy('directions_id', 'asc')->get();
        $roles = role::orderBy('area_id', 'asc')->get();
        

    return view('sgcAdmin', compact('directions', 'areas', 'roles'));

    }



    public function all()
    {
        if (Auth::user()->administrator !== 1) {
            abort(403, 'Acceso denegado');
        }
        $procesos = proceso::all();
        return view('sgcAll', compact('procesos'));
    }



    public function guardarDatos(Request $request)
    {
        $proceso = new proceso();

        if ($request->hasFile('documento')) {
        $archivo = $request->file('documento');

        // Opcional: generar nombre único
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();

        // Guardar en storage/app/public/documentos
        $ruta = $archivo->storeAs('public/documentos', $nombreArchivo);

        // Guardar la ruta en la base de datos (sin 'public/')
        $proceso->ruta = str_replace('public', 'storage/public', $ruta);
        $subioPdf = true;
    }


        $proceso->nombre_proceso = $request->input('nombre');
        $proceso->codigo = $request->input('codigo');
        $proceso->version = $request->input('version');
        $proceso->estado = $request->input('estado');
        $proceso->tipo = $request->input('tipo');
        $proceso->fecha_version = $request->input('fecha_version');
        $proceso->save();

        
        $rolesSeleccionados = $request->input('roles', []); // array de IDs
        $proceso->roles()->attach($rolesSeleccionados);

        $areasSeleccionados = $request->input('areas', []); // array de IDs
        $proceso->areas()->attach($areasSeleccionados);

        $directionsSeleccionados = $request->input('direcciones', []); // array de IDs
        $proceso->directions()->attach($directionsSeleccionados);

        
try {
    // Solo encola el indexado
    ReindexProcesoJob::dispatch($proceso->id)->onQueue('index');
} catch (\Throwable $e) {
    Log::error('[SGC] Error al encolar index', ['e' => $e->getMessage()]);
}

        return redirect()->route('adminSGC')->with('success', 'Datos guardados correctamente.');
    }


    public function showProcedimientos(Request $request)
    {
        
        $user = Auth::user();
        $tipoFiltro = $request->input('tipo'); // Ej: 'Manual', 'Política', etc.

        $unionRecords = collect();

        if ($user->id_directions) {
            $unionRecords = DB::table('unions')
                ->where('directions_id', $user->id_directions)
                ->pluck('procesos_id');
        }

        if ($user->id_areas) {
            $unionRecords = DB::table('unions')
                ->where('areas_id', $user->id_areas)
                ->pluck('procesos_id');
        }

        if ($user->id_roles) {
            $unionRecords = DB::table('unions')
                ->where('roles_id', $user->id_roles)
                ->pluck('id_procedimiento');
        }

        $query = DB::table('procesos')->whereIn('id', $unionRecords);

        if ($tipoFiltro) {
            $query->where('tipo', $tipoFiltro);
        }

        $procedimientos = $query->get();

        return view('sgc', compact('procedimientos', 'tipoFiltro'));
    }


    public function edit($id)
    {
        $proceso = Proceso::findOrFail($id);

    $relaciones = DB::table('unions')
        ->where('procesos_id', $proceso->id)
        ->get();

    $idsDirecciones = $relaciones->where('unionable_type', 'App\\Models\\Direction')->pluck('unionable_id')->toArray();
    $idsAreas = $relaciones->where('unionable_type', 'App\\Models\\Area')->pluck('unionable_id')->toArray();
    $idsRoles = $relaciones->where('unionable_type', 'App\\Models\\Role')->pluck('unionable_id')->toArray();

    $directions = Direction::all();
    $areas = Area::all();
    $roles = Role::all();

    return view('sgcEdit', compact('proceso', 'directions', 'areas', 'roles', 'idsDirecciones', 'idsAreas', 'idsRoles'));


    }

    


    public function update(Request $request, $id)
{
    $proceso = Proceso::findOrFail($id);
    // Detectar cambios para decidir reindex
    $versionAnterior = $proceso->version;
    $pdfCambiado = false;
    // Validación básica
    $request->validate([
        'nombre' => 'required|string|max:255',
        'version' => 'required|string|max:50',
        'codigo' => 'required|string|max:50',
        'fecha_version' => 'required|date',
        'tipo' => 'required|in:Procedimiento,Politica,Formato',
        'estado' => 'required|in:Piloto,Autorizado',
        'documento' => 'nullable|file|mimes:pdf|max:20480', // 20MB
    ]);

    // Actualizar campos
    $proceso->update([
        'nombre_proceso' => $request->nombre,
        'version' => $request->version,
        'codigo' => $request->codigo,
        'fecha_version' => $request->fecha_version,
        'tipo' => $request->tipo,
        'estado' => $request->estado,
    ]);

    // Reemplazar documento si se sube uno nuevo
    if ($request->hasFile('documento')) {

        
        if ($proceso->ruta) {
            // Convertir la ruta pública a ruta interna
            $rutaInterna = str_replace('storage/', '', $proceso->ruta);

            if (Storage::disk('public')->exists($rutaInterna)) {
                Storage::disk('public')->delete($rutaInterna);
            }
        }

        $path = $request->file('documento')->store('documentos', 'public');
        $proceso->ruta = 'storage/' . $path;
        $proceso->save();
    }
    Log::info('[SGC] Guardado OK, proceso_id='.$proceso->id.', version='. $proceso->version);
    if ($request->hasFile('documento')) {
        try {
    // Solo encola el indexado
    ReindexProcesoJob::dispatch($proceso->id)->onQueue('index');
} catch (\Throwable $e) {
    Log::error('[SGC] Error al encolar index', ['e' => $e->getMessage()]);
}
    }
    Log::info('[SGC] Encolando ReindexProcesoJob', ['proceso_id' => $proceso->id]);
ReindexProcesoJob::dispatch($proceso->id)->onQueue('index');
Log::info('[SGC] ReindexProcesoJob encolado', ['proceso_id' => $proceso->id]);

// (Opcional) notificación temprana a n8n SOLO PARA DIAGNÓSTICO:
NotifyN8nIndexJob::dispatch([
  'procesos_id' => $proceso->id,
  'version'     => (int) $proceso->version,
  'action'      => 'diagnostic_ping',
  'codigo'      => (string) $proceso->codigo,
  'titulo'      => (string) ($proceso->nombre_proceso ?? $proceso->codigo),
  'ruta'        => (string) $proceso->ruta,
])->onQueue('n8n');
Log::info('[SGC] NotifyN8nIndexJob encolado (diagnostic_ping)', ['proceso_id' => $proceso->id]);
    return redirect()->route('adminSGCAll')->with('success', 'Proceso eliminado correctamente.');

}


public function destroy($id)
{
    $proceso = Proceso::findOrFail($id);

    // Eliminar archivo asociado si existe
    if ($proceso->ruta) {
        $rutaInterna = str_replace('storage/', '', $proceso->ruta);
        if (Storage::disk('public')->exists($rutaInterna)) {
            Storage::disk('public')->delete($rutaInterna);
        }
    }

    // Eliminar relaciones en tabla unions
    DB::table('unions')->where('procesos_id', $proceso->id)->delete();

    // Eliminar el proceso
    $proceso->delete();

    return redirect()->route('adminSGCAll')->with('success', 'Proceso eliminado correctamente.');

}

}

