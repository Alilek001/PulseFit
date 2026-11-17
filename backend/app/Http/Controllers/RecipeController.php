<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    private const RULES = [
        'name'        => 'required|string|max:120',
        'calories'    => 'required|integer|min:0|max:10000',
        'proteins'    => 'nullable|integer|min:0|max:1000',
        'carbs'       => 'nullable|integer|min:0|max:1000',
        'fats'        => 'nullable|integer|min:0|max:1000',
        'description' => 'nullable|string|max:2000',
        'image_url'   => 'nullable|url|max:500',
    ];

    public function index(Request $request)
    {
        $user = $request->user();
        $isPremium = in_array($user->role, ['premium', 'admin']);

        // Recetas globales + las privadas del propio usuario
        $recipes = Recipe::whereNull('user_id')
            ->orWhere('user_id', $user->id)
            ->orderBy('name')
            ->get()
            ->map(function (Recipe $recipe) use ($user, $isPremium) {
                if ($recipe->is_premium && !$isPremium) {
                    $recipe->description = 'Receta exclusiva del plan Élite.';
                }
                return $this->withPermissions($recipe, $user);
            });

        return response()->json($recipes);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate(self::RULES);

        // Un admin crea recetas globales; el resto, recetas privadas suyas
        $recipe = Recipe::create([
            ...$this->fill($data),
            'user_id'    => $user->role === 'admin' ? null : $user->id,
            'is_premium' => false,
        ]);

        return response()->json($this->withPermissions($recipe, $user), 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $recipe = Recipe::findOrFail($id);

        if (!$this->canEdit($recipe, $user)) {
            return response()->json(['message' => 'No puedes editar esta receta.'], 403);
        }

        $data = $request->validate(self::RULES);
        $recipe->update($this->fill($data));

        return response()->json($this->withPermissions($recipe, $user));
    }

    private function fill(array $data): array
    {
        return [
            'name'        => $data['name'],
            'calories'    => $data['calories'],
            'proteins'    => $data['proteins'] ?? 0,
            'carbs'       => $data['carbs'] ?? 0,
            'fats'        => $data['fats'] ?? 0,
            'description' => $data['description'] ?? '',
            'image_url'   => $data['image_url'] ?? null,
        ];
    }

    private function canEdit(Recipe $recipe, User $user): bool
    {
        return $user->role === 'admin' || (int) $recipe->user_id === (int) $user->id;
    }

    private function withPermissions(Recipe $recipe, User $user): Recipe
    {
        $recipe->setAttribute('can_edit', $this->canEdit($recipe, $user));
        return $recipe;
    }
}
