<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\n8nController;
use App\Http\Controllers\Api\MeetingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
/**Route::get('/directorio', function () {
    return view('directorio');
})->middleware(['auth', 'verified'])->name('directorio'); */


Route::get('/directorio', [ProfileController::class, 'directorio'])->middleware(['auth', 'verified'])->name('directorio');

Route::get('/SGC', function () {
    return view('sgc');
})->middleware(['auth', 'verified'])->name('sgc');

Route::get('/SGC/Construccion', function () {
    return view('/SGC/construccion');
})->middleware(['auth', 'verified'])->name('sgc.construccion');

Route::get('/SGC/RH', function () {
    return view('/SGC/rh');
})->middleware(['auth', 'verified'])->name('sgc.rh');

Route::get('/SGC/ADFI', function () {
    return view('/SGC/adfi');
})->middleware(['auth', 'verified'])->name('sgc.adfi');

Route::get('/SGC/Comercial', function () {
    return view('/SGC/comercial');
})->middleware(['auth', 'verified'])->name('sgc.comercial');

Route::get('/SGC/Proyectos', function () {
    return view('/SGC/proyectos');
})->middleware(['auth', 'verified'])->name('sgc.proyectos');

Route::get('/SGC/RI', function () {
    return view('/SGC/ri');
})->middleware(['auth', 'verified'])->name('sgc.ri');

Route::get('/SGC/Presupuestos', function () {
    return view('/SGC/presupuestos');
})->middleware(['auth', 'verified'])->name('sgc.presupuestos');

Route::get('/SGC/Juridico', function () {
    return view('/SGC/juridico');
})->middleware(['auth', 'verified'])->name('sgc.juridico');


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

Route::get('/Mis Reuniones', [MeetingController::class, 'reuniones'])->middleware(['auth', 'verified'])->name('reuniones');

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

require __DIR__.'/auth.php';

Route::get('/Presupuestos', function () {
    return view('emailPresup');
})->middleware(['auth', 'verified'])->name('presupuestos');

Route::post('/mailPresup', [n8nController::class, 'correoPresup'])->name('mailPresup');
