<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\Routine;

class RoutineController extends Controller
{
    public function index(Request $request)
    {
        // Devuelve las rutinas propias del usuario
        $routines = Routine::with('exercises')
            ->where('user_id', $request->user()->id)
            ->get();
        return response()->json($routines);
    }

    public function generate(Request $request)
    {
        $user = $request->user();

        // Protección de Ruta API: Solo Premium
        if (!in_array($user->role, ['premium', 'admin'])) {
            return response()->json([
                'message' => 'Tu plan actual no incluye acceso al Generador de Inteligencia Artificial PulseFit. Únete a Élite para desbloquearlo.'
            ], 403);
        }

        $request->validate([
            'muscle_group' => 'required|string'
        ]);

        $group = $request->muscle_group;

        $exercises = Exercise::where('muscle_group', 'like', "%{$group}%")
            ->get();

        if ($exercises->isEmpty()) {
            return response()->json([
                'message' => 'No tenemos suficientes datos en la biblioteca de ejercicios para armar una rutina de ' . $group
            ], 404);
        }

        // Simulación del Generador IA: Ensamblaje en caliente
        try {
            $routine = Routine::create([
                'name' => "Entrenamiento IA Avanzado: {$group}",
                'user_id' => $user->id,
                'is_premium' => true,
                'training_days' => 1,
            ]);

            // Agregamos ejercicios al pivote (routine_exercise)
            foreach ($exercises as $ex) {
                $routine->exercises()->attach($ex->id, [
                    'sets' => rand(3, 4),
                    'reps' => rand(8, 12),
                    'notes' => 'Técnica estricta. RIR 2 recomendado por IA.',
                ]);
            }

            return response()->json($routine->load('exercises'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error interno al generar la rutina.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
