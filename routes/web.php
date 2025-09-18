<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\n8nController;
use App\Http\Controllers\Api\MeetingController;
use App\Http\Controllers\SgcController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});


//Setting admin sections
Route::get('/Admin', [SgcController::class, 'admin'])->middleware(['auth', 'verified'])->name('admin');


Route::get('/AdminSGC', [SgcController::class, 'list'])->middleware(['auth', 'verified'])->name('adminSGC');
Route::post('/guardar-datos', [SgcController::class, 'guardarDatos'])->middleware(['auth', 'verified'])->name('guardarDatos');
Route::get('/AdminSGCAll', [SgcController::class, 'all'])->middleware(['auth', 'verified'])->name('adminSGCAll');
Route::get('/procesos/{id}/edit', [SgcController::class, 'edit'])->name('editarProceso');
Route::put('/procesos/{id}', [SgcController::class, 'update'])->name('procesos.update');
Route::delete('/procesos/{id}', [SgcController::class, 'destroy'])->name('eliminarProceso');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
/**Route::get('/directorio', function () {
    return view('directorio');
})->middleware(['auth', 'verified'])->name('directorio'); */


Route::get('/directorio', [ProfileController::class, 'directorio'])->middleware(['auth', 'verified'])->name('directorio');
Route::get('/SGC', [SgcController::class, 'showProcedimientos'])->middleware(['auth', 'verified'])->name('sgc');
Route::get('/Tableros', [HomeController::class, 'tableros'])->middleware(['auth', 'verified'])->name('tableros');


//Calendario de reservas de comedor
Route::get('/comedor', function () {
    return view('comedor');
})->middleware(['auth', 'verified'])->name('comedor');

Route::get('/meetingsComedor', [MeetingController::class, 'indexcomedor'])->middleware(['auth', 'verified']);
Route::post('/meetingsComedor', [MeetingController::class, 'storecomedor'])->middleware(['auth', 'verified']);

//Calendario de reservas de sala
Route::get('/sala', function () {
    return view('sala');
})->middleware(['auth', 'verified'])->name('sala');

Route::get('/meetings', [MeetingController::class, 'index'])->middleware(['auth', 'verified']);
Route::post('/meetings', [MeetingController::class, 'store'])->middleware(['auth', 'verified']);

//Calendario de reservas de titulacion
Route::get('/titulacion', function () {
    return view('titulacion');
})->middleware(['auth', 'verified'])->name('titulacion');
Route::get('/meetingsTitulacion', [MeetingController::class, 'indextitulacion'])->middleware(['auth', 'verified']);
Route::post('/meetingsTitulacion', [MeetingController::class, 'storetitulacion'])->middleware(['auth', 'verified']);

Route::get('/Mis Reuniones', [MeetingController::class, 'reuniones'])->middleware(['auth', 'verified'])->name('reuniones');
//Rutas del comedor
Route::get('/reuniones/{id}/edit', [MeetingController::class, 'edit'])->name('reuniones.edit');
Route::put('/reuniones/{id}', [MeetingController::class, 'update'])->name('reuniones.update');
Route::delete('/reuniones/{id}', [MeetingController::class, 'destroy'])->name('reuniones.destroy');
//Rutas de la sala
Route::get('/reuniones/sala/{id}/edit', [MeetingController::class, 'editSala'])->name('reuniones.sala.edit');
Route::put('/reuniones/sala/{id}', [MeetingController::class, 'updateSala'])->name('reuniones.sala.update');
Route::delete('/reuniones/sala/{id}', [MeetingController::class, 'destroySala'])->name('reuniones.sala.destroy');
//Rutas de la titulacion
Route::get('/reuniones/titulacion/{id}/edit', [MeetingController::class, 'editTitulacion'])->name('reuniones.titulacion.edit');
Route::put('/reuniones/titulacion/{id}', [MeetingController::class, 'updateTitulacion'])->name('reuniones.titulacion.update');
Route::delete('/reuniones/titulacion/{id}', [MeetingController::class, 'destroyTitulacion'])->name('reuniones.titulacion.destroy');

//Pages Pruebas
Route::get('/Revista', function () {
    return view('picoteando');
})->middleware(['auth', 'verified'])->name('picoteando');

Route::get('/Capacitaciones', function () {
    return view('capacitaciones');
})->middleware(['auth', 'verified'])->name('capacitaciones');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//Pagina para dar de alta usuarios (solo admin)
Route::get('/alta', [HomeController::class, 'usuarioalta'])->name('usuarioalta');

require __DIR__.'/auth.php';

Route::get('/Presupuestos', function () {
    return view('emailPresup');
})->middleware(['auth', 'verified'])->name('presupuestos');

Route::post('/mailPresup', [n8nController::class, 'correoPresup'])->name('mailPresup');
