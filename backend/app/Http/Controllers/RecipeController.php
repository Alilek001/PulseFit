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

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string',
            'calories'  => 'required|integer|min:0',
            'proteins'  => 'nullable|integer|min:0',
            'carbs'     => 'nullable|integer|min:0',
            'fats'      => 'nullable|integer|min:0',
            'image_url' => 'nullable|url',
        ]);

        $recipe = Recipe::create([
            'name'       => $request->name,
            'calories'   => $request->calories,
            'proteins'   => $request->proteins ?? 0,
            'carbs'      => $request->carbs ?? 0,
            'fats'       => $request->fats ?? 0,
            'description'=> $request->description ?? '',
            'image_url'  => $request->image_url,
            'is_premium' => false,
        ]);

        return response()->json($recipe, 201);
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        $request->validate([
            'name'      => 'required|string',
            'calories'  => 'required|integer|min:0',
            'proteins'  => 'nullable|integer|min:0',
            'carbs'     => 'nullable|integer|min:0',
            'fats'      => 'nullable|integer|min:0',
            'image_url' => 'nullable|url',
        ]);

        $recipe->update([
            'name'      => $request->name,
            'calories'  => $request->calories,
            'proteins'  => $request->proteins ?? 0,
            'carbs'     => $request->carbs ?? 0,
            'fats'      => $request->fats ?? 0,
            'image_url' => $request->image_url,
        ]);

        return response()->json($recipe);
    }
}
