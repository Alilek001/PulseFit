<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Routine;

class AdminController extends Controller
{
    public function stats(Request $request)
    {
        // Solo administradores
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Acceso Denegado. Solo administradores pueden ver estas métricas.'], 403);
        }

        $totalUsers = User::count();
        $premiumUsers = User::whereIn('role', ['premium', 'admin'])->count();
        $totalRoutines = Routine::count();

        // Extraer los últimos registros para la tabla
        $recentUsers = User::orderBy('created_at', 'desc')->take(15)->get();

        return response()->json([
            'stats' => [
                'total_users' => $totalUsers,
                'premium_subs' => $premiumUsers,
                'active_workouts' => $totalRoutines
            ],
            'recent_users' => $recentUsers
        ]);
    }

    public function updateUserRole(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Acceso Denegado'], 403);
        }

        $request->validate([
            'role' => 'required|in:user,premium,admin'
        ]);

        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return response()->json(['message' => 'Rol actualizado correctamente', 'user' => $user]);
    }
}
