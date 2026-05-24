<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UpgradeRequest;
use App\Models\User;

class UpgradeController extends Controller
{
    public function request(Request $request)
    {
        $user = $request->user();
        if (in_array($user->role, ['premium', 'admin'])) {
            return response()->json(['message' => 'Ya tienes acceso Élite.'], 400);
        }

        $existing = UpgradeRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();
        if ($existing) {
            return response()->json(['message' => 'Ya tienes una solicitud pendiente.'], 400);
        }

        $req = UpgradeRequest::create([
            'user_id' => $user->id,
            'message' => $request->message,
            'status'  => 'pending',
        ]);

        return response()->json($req, 201);
    }

    public function myStatus(Request $request)
    {
        $req = UpgradeRequest::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->first();
        return response()->json($req);
    }

    // Admin only
    public function list(Request $request)
    {
        if ($request->user()->role !== 'admin') abort(403);
        return response()->json(
            UpgradeRequest::with('user')->orderBy('created_at', 'desc')->get()
        );
    }

    public function approve(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') abort(403);
        $req = UpgradeRequest::findOrFail($id);
        $req->update(['status' => 'approved']);
        User::find($req->user_id)->update(['role' => 'premium']);
        return response()->json(['message' => 'Aprobado']);
    }

    public function reject(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') abort(403);
        $req = UpgradeRequest::findOrFail($id)->update(['status' => 'rejected']);
        return response()->json(['message' => 'Rechazado']);
    }
}
