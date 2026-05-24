<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\Routine;
use App\Models\WeeklyPlanItem;

class RoutineController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $isPremium = in_array($user->role, ['premium', 'admin']);

        // Global routines (user_id null = admin-created)
        $query = Routine::with('exercises')->whereNull('user_id');
        if (!$isPremium) {
            $query->where('tier', 'basic');
        }
        $global = $query->get();

        // User's own AI-generated routines
        $own = Routine::with('exercises')
            ->where('user_id', $user->id)
            ->get();

        return response()->json($global->merge($own)->values());
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'name'           => 'required|string',
            'tier'           => 'required|in:basic,elite',
            'description'    => 'nullable|string',
            'training_days'  => 'nullable|integer|min:1|max:7',
            'exercises'      => 'nullable|array',
            'exercises.*.id'   => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required|integer|min:1',
        ]);

        $routine = Routine::create([
            'name'          => $request->name,
            'tier'          => $request->tier,
            'description'   => $request->description,
            'training_days' => $request->training_days ?? 3,
            'is_premium'    => $request->tier === 'elite',
            'user_id'       => null,
        ]);

        foreach ($request->exercises ?? [] as $ex) {
            $routine->exercises()->attach($ex['id'], [
                'sets'  => $ex['sets'],
                'reps'  => $ex['reps'],
                'notes' => $ex['notes'] ?? null,
            ]);
        }

        return response()->json($routine->load('exercises'), 201);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        Routine::whereNull('user_id')->findOrFail($id)->delete();
        return response()->json(['message' => 'Eliminada']);
    }

    public function generate(Request $request)
    {
        $user = $request->user();
        if (!in_array($user->role, ['premium', 'admin'])) {
            return response()->json([
                'message' => 'Tu plan actual no incluye acceso al Generador de Inteligencia Artificial PulseFit. Únete a Élite para desbloquearlo.'
            ], 403);
        }

        $request->validate(['muscle_group' => 'required|string']);
        $group = $request->muscle_group;
        $exercises = Exercise::where('muscle_group', 'like', "%{$group}%")->get();

        if ($exercises->isEmpty()) {
            return response()->json(['message' => 'No hay ejercicios para ese grupo muscular.'], 404);
        }

        try {
            $routine = Routine::create([
                'name'          => "Entrenamiento IA: {$group}",
                'tier'          => 'elite',
                'user_id'       => $user->id,
                'is_premium'    => true,
                'training_days' => 1,
            ]);
            foreach ($exercises as $ex) {
                $routine->exercises()->attach($ex->id, [
                    'sets'  => rand(3, 4),
                    'reps'  => rand(8, 12),
                    'notes' => 'Técnica estricta. RIR 2 recomendado por IA.',
                ]);
            }
            return response()->json($routine->load('exercises'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error interno.', 'error' => $e->getMessage()], 500);
        }
    }

    // Weekly plan
    public function getWeeklyPlan(Request $request)
    {
        $items = WeeklyPlanItem::where('user_id', $request->user()->id)
            ->with('routine.exercises')
            ->get()
            ->keyBy('day_of_week');
        return response()->json($items);
    }

    public function setWeeklyPlanDay(Request $request)
    {
        $request->validate([
            'routine_id'  => 'required|exists:routines,id',
            'day_of_week' => 'required|integer|min:0|max:6',
        ]);

        $item = WeeklyPlanItem::updateOrCreate(
            ['user_id' => $request->user()->id, 'day_of_week' => $request->day_of_week],
            ['routine_id' => $request->routine_id]
        );

        return response()->json($item->load('routine.exercises'));
    }

    public function removeWeeklyPlanDay(Request $request, $day)
    {
        WeeklyPlanItem::where('user_id', $request->user()->id)
            ->where('day_of_week', $day)
            ->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
