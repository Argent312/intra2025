<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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


Route::get('/comedor', function () {
    return view('comedor');
})->middleware(['auth', 'verified'])->name('comedor');
Route::get('/sala', function () {
    return view('sala');
})->middleware(['auth', 'verified'])->name('sala');
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