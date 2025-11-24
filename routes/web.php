<?php

use App\Http\Controllers\CalificacionesController;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\PonderacionesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/estudiantes', [EstudiantesController::class, 'index'])->name('estudiantes.index');
Route::get('/estudiantes/crear', [EstudiantesController::class, 'crear'])->name('estudiantes.crear');
Route::post('/estudiantes/guardar', [EstudiantesController::class, 'guardar'])->name('estudiantes.guardar');
Route::get('/estudiantes/{estudiante}/editar', [EstudiantesController::class, 'editar'])->name('estudiantes.editar');
Route::put('/estudiantes/{estudiante}', [EstudiantesController::class, 'modificar'])->name('estudiantes.modificar');
Route::delete('/estudiantes/{estudiante}', [EstudiantesController::class, 'eliminar'])->name('estudiantes.eliminar');

Route::get('/calificaciones', [CalificacionesController::class, 'index'])->name('calificaciones.index');
Route::put('/calificaciones/modificar', [CalificacionesController::class, 'modificar'])->name('calificaciones.modificar');

Route::get('/ponderaciones', [PonderacionesController::class, 'index'])->name('ponderaciones.index');