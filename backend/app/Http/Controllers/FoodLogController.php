<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodLog;

class FoodLogController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $logs = FoodLog::where('user_id', $request->user()->id)
            ->where('date', $date)
            ->get();

        $totals = [
            'calories' => $logs->sum('calories'),
            'proteins'  => $logs->sum('proteins'),
            'carbs'     => $logs->sum('carbs'),
            'fats'      => $logs->sum('fats'),
        ];

        return response()->json(['logs' => $logs, 'totals' => $totals]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'calories' => 'required|integer|min:0',
            'proteins' => 'nullable|integer|min:0',
            'carbs'    => 'nullable|integer|min:0',
            'fats'     => 'nullable|integer|min:0',
        ]);

        $log = FoodLog::create([
            'user_id'  => $request->user()->id,
            'name'     => $request->name,
            'calories' => $request->calories,
            'proteins' => $request->proteins ?? 0,
            'carbs'    => $request->carbs ?? 0,
            'fats'     => $request->fats ?? 0,
            'date'     => $request->date ?? now()->toDateString(),
        ]);

        return response()->json($log, 201);
    }

    public function destroy(Request $request, $id)
    {
        $log = FoodLog::where('user_id', $request->user()->id)->findOrFail($id);
        $log->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
