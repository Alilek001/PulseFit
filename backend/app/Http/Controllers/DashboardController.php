<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Progress;
use App\Models\Routine;
use App\Models\WeeklyPlanItem;
use App\Models\WorkoutLog;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Mifflin-St Jeor TDEE
        $edad = 25;
        $bmr  = (10 * $user->weight) + (6.25 * $user->height) - (5 * $edad) + 5;
        $tdee = $bmr * 1.55;

        $calories = round($tdee);
        $proteins = round($user->weight * 2.2);
        $fats     = round($user->weight * 1.0);

        if ($user->goal === 'lose_weight')   $calories -= 400;
        elseif ($user->goal === 'gain_muscle') $calories += 300;

        $carbs = round(($calories - ($proteins * 4) - ($fats * 9)) / 4);

        $goals = compact('calories', 'proteins', 'carbs', 'fats');

        // Weight progress history
        $progress = Progress::where('user_id', $user->id)
            ->orderBy('date', 'asc')
            ->get(['date', 'weight']);

        // Today's planned routine (Monday=0 index)
        $dow     = now()->dayOfWeek; // 0=Sun..6=Sat
        $dayIdx  = $dow === 0 ? 6 : $dow - 1;
        $todayPlan = WeeklyPlanItem::where('user_id', $user->id)
            ->where('day_of_week', $dayIdx)
            ->with('routine.exercises')
            ->first();
        $routine = $todayPlan ? $todayPlan->routine : null;

        // Workout stats (current week Mon–Sun)
        $weekStart = now()->startOfWeek(\Carbon\Carbon::MONDAY);
        $workoutStats = [
            'thisWeek'        => WorkoutLog::where('user_id', $user->id)->where('logged_at', '>=', $weekStart)->count(),
            'minutesThisWeek' => WorkoutLog::where('user_id', $user->id)->where('logged_at', '>=', $weekStart)->sum('duration_minutes'),
        ];

        $weeklyPlanCount = WeeklyPlanItem::where('user_id', $user->id)->count();

        return response()->json(compact('user', 'goals', 'progress', 'routine', 'workoutStats', 'weeklyPlanCount'));
    }

    public function addProgress(Request $request)
    {
        $request->validate(['weight' => 'required|numeric', 'date' => 'required|date']);

        $progress = Progress::create([
            'user_id'  => $request->user()->id,
            'weight'   => $request->weight,
            'body_fat' => $request->body_fat,
            'date'     => $request->date,
        ]);

        $request->user()->update(['weight' => $request->weight]);

        return response()->json($progress);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'weight' => 'nullable|numeric',
            'height' => 'nullable|integer',
            'goal'   => 'nullable|string|in:lose_weight,maintain,gain_muscle',
        ]);

        $request->user()->update(array_filter([
            'weight' => $request->weight,
            'height' => $request->height,
            'goal'   => $request->goal,
        ], fn($v) => $v !== null && $v !== ''));

        return response()->json(['message' => 'Profile updated']);
    }
}
