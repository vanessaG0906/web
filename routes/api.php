<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ParaleloController;

Route::get('/paralelos', [ParaleloController::class, 'index']); // Listar todos los hoteles
Route::post('/paralelos', [ParaleloController::class, 'store']); // Crear un nuevo hotel
Route::get('/paralelos/{id}', [ParaleloController::class, 'show']); // Mostrar un hotel específico
Route::put('/paralelos/{id}', [ParaleloController::class, 'update']); // Actualizar un hotel específico
Route::delete('/paralelos/{id}', [ParaleloController::class, 'destroy']); // Eliminar un hotel específico


Route::get('/estudiantes', [EstudianteController::class, 'index']); // Listar todos los hoteles
Route::post('/estudiantes', [EstudianteController::class, 'store']); // Crear un nuevo hotel
Route::get('/estudiantes/{id}', [EstudianteController::class, 'show']); // Mostrar un hotel específico
Route::put('/estudiantes/{id}', [EstudianteController::class, 'update']); // Actualizar un hotel específico
Route::delete('/estudiantes/{id}', [EstudianteController::class, 'destroy']); // Eliminar un hotel específico

