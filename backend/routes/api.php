<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoutineController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FoodLogController;
use App\Http\Controllers\UpgradeController;
use App\Http\Controllers\WorkoutLogController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', fn(Request $r) => $r->user());

    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::post('/progress', [DashboardController::class, 'addProgress']);
    Route::post('/profile', [DashboardController::class, 'updateProfile']);

    // Routines
    Route::get('/routines', [RoutineController::class, 'index']);
    Route::post('/routines', [RoutineController::class, 'store']);
    Route::delete('/routines/{id}', [RoutineController::class, 'destroy']);

    // Weekly plan
    Route::get('/weekly-plan', [RoutineController::class, 'getWeeklyPlan']);
    Route::post('/weekly-plan', [RoutineController::class, 'setWeeklyPlanDay']);
    Route::delete('/weekly-plan/{day}', [RoutineController::class, 'removeWeeklyPlanDay']);

    // Recipes
    Route::get('/recipes', [RecipeController::class, 'index']);
    Route::post('/recipes', [RecipeController::class, 'store']);
    Route::put('/recipes/{id}', [RecipeController::class, 'update']);

    // Food log
    Route::get('/food-log', [FoodLogController::class, 'index']);
    Route::post('/food-log', [FoodLogController::class, 'store']);
    Route::delete('/food-log/{id}', [FoodLogController::class, 'destroy']);

    // Workout log
    Route::get('/workout-logs', [WorkoutLogController::class, 'index']);
    Route::post('/workout-logs', [WorkoutLogController::class, 'store']);

    // Upgrade to Elite
    Route::post('/upgrade/request', [UpgradeController::class, 'request']);
    Route::get('/upgrade/status', [UpgradeController::class, 'myStatus']);

    // Admin
    Route::get('/admin/stats', [\App\Http\Controllers\AdminController::class, 'stats']);
    Route::post('/admin/users/{id}/role', [\App\Http\Controllers\AdminController::class, 'updateUserRole']);
    Route::get('/admin/upgrade-requests', [UpgradeController::class, 'list']);
    Route::post('/admin/upgrade-requests/{id}/approve', [UpgradeController::class, 'approve']);
    Route::post('/admin/upgrade-requests/{id}/reject', [UpgradeController::class, 'reject']);
    Route::get('/admin/exercises', fn() => response()->json(\App\Models\Exercise::all()));
});

require __DIR__.'/auth.php';
