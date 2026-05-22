<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\RecipeController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Rutas del Panel y Medidas Biométricas
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/progress', [DashboardController::class, 'addProgress']);
    Route::post('/profile', [DashboardController::class, 'updateProfile']);
    
    // Rutas de Entrenamientos Inteligentes
    Route::get('/routines', [RoutineController::class, 'index']);
    Route::post('/routines/generate', [RoutineController::class, 'generate']);
    
    // Rutas de Alimentación
    Route::get('/recipes', [RecipeController::class, 'index']);
    
    // Rutas de Administrador
    Route::get('/admin/stats', [\App\Http\Controllers\AdminController::class, 'stats']);
    Route::post('/admin/users/{id}/role', [\App\Http\Controllers\AdminController::class, 'updateUserRole']);
});

require __DIR__.'/auth.php';
