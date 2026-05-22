<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Progress;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // --- Fórmula Mifflin-St Jeor (Para TDEE) ---
        $edad_estimada = 25; // Variable simulada para el proyecto
        $bmr = (10 * $user->weight) + (6.25 * $user->height) - (5 * $edad_estimada) + 5; 
        
        // Multiplicador de Actividad Moderada
        $tdee = $bmr * 1.55; 
        
        $calories = round($tdee);
        $proteins = round(($user->weight * 2.2));
        $fats = round(($user->weight * 1.0));
        
        // Alteración calórica según objetivo de la cuenta
        if ($user->goal === 'lose_weight') {
            $calories -= 400; // Déficit
        } elseif ($user->goal === 'gain_muscle') {
            $calories += 300; // Superávit
        }

        // Resto de calorías van a carbohidratos
        $carbs = round(($calories - ($proteins * 4) - ($fats * 9)) / 4);

        $goals = [
            'calories' => $calories,
            'proteins' => $proteins,
            'carbs' => $carbs,
            'fats' => $fats
        ];

        // --- Extracción del Historial de Gráficas ---
        $progress = Progress::where('user_id', $user->id)
            ->orderBy('date', 'asc')
            ->get();

        return response()->json([
            'user' => $user,
            'goals' => $goals,
            'progress' => $progress
        ]);
    }

    public function addProgress(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric',
            'date' => 'required|date'
        ]);

        $progress = Progress::create([
            'user_id' => $request->user()->id,
            'weight' => $request->weight,
            'body_fat' => $request->body_fat,
            'date' => $request->date
        ]);

        $request->user()->update(['weight' => $request->weight]);

        return response()->json($progress);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'goal' => 'required|string|in:lose_weight,maintain,gain_muscle'
        ]);

        $request->user()->update([
            'goal' => $request->goal
        ]);

        return response()->json(['message' => 'Perfil actualizado']);
    }
}
