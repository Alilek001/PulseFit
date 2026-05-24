<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkoutLog;

class WorkoutLogController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            WorkoutLog::where('user_id', $request->user()->id)
                ->orderBy('logged_at', 'desc')
                ->limit(20)
                ->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'routine_name'     => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'notes'            => 'nullable|string',
            'logged_at'        => 'required|date',
        ]);

        $log = WorkoutLog::create([
            'user_id'          => $request->user()->id,
            'routine_id'       => $request->routine_id,
            'routine_name'     => $request->routine_name,
            'duration_minutes' => $request->duration_minutes,
            'notes'            => $request->notes,
            'logged_at'        => $request->logged_at,
        ]);

        return response()->json($log, 201);
    }
}
