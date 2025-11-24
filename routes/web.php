<?php

use App\Http\Controllers\EstudiantesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('home');
});
Route::get('/estudiantes', [EstudiantesController::class, 'index'])->name('estudiantes.index');
Route::get('/estudiantes/crear', [EstudiantesController::class, 'crear'])->name('estudiantes.crear');
Route::post('/estudiantes/guardar', [EstudiantesController::class, 'guardar'])->name('estudiantes.guardar');
Route::get('/estudiantes/{estudiante}/editar', [EstudiantesController::class, 'editar'])->name('estudiantes.editar');
Route::put('/estudiantes/{estudiante}', [EstudiantesController::class, 'modificar'])->name('estudiantes.modificar');
Route::delete('/estudiantes/{estudiante}', [EstudiantesController::class, 'eliminar'])->name('estudiantes.eliminar');