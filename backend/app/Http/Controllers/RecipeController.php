<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Verifica si es un usuario que paga suscripción premium o es admin
        $isPremium = in_array($user->role, ['premium', 'admin']);
        
        $recipes = Recipe::all()->map(function ($recipe) use ($isPremium) {
            // LÓGICA DE GATEKEEPER: Si la receta es premium y el usuario no...
            if ($recipe->is_premium && !$isPremium) {
                $recipe->description = '🔒 CONTENIDO ÉLITE BLOQUEADO. Mejora tu suscripción a PulseFit Premium para ver los ingredientes y pasos de preparación exactos de este plato avanzado.';
            }
            return $recipe;
        });

        return response()->json($recipes);
    }
}
