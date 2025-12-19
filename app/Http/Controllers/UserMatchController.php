<?php

namespace App\Http\Controllers;

use App\Models\UserMatch;
use Illuminate\Http\Request;

class UserMatchController extends Controller
{
    /**
     * Voir tous mes matchs
     * GET /api/matches
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $matches = UserMatch::where('user_id', $user->id)
            ->with('matchedUser')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $matches
        ], 200);
    }

    /**
     * Voir un match précis
     * GET /api/matches/{id}
     */
    public function show(UserMatch $userMatch, Request $request)
    {
        $user = $request->user();

        // Sécurité : les deux personnes du match peuvent voir
        if (
            $user->id !== $userMatch->user_id &&
            $user->id !== $userMatch->matched_user_id
        ) {
            return response()->json([
                'message' => 'Accès interdit'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $userMatch->load('matchedUser')
        ], 200);
    }

    /**
     * 🔹 Supprimer un match (unfriend)
     * DELETE /api/matches/{id}
     */
    public function destroy(UserMatch $userMatch, Request $request)
    {
        $user = $request->user();

        // Sécurité : seuls les membres du match peuvent supprimer
        if (
            $user->id !== $userMatch->user_id &&
            $user->id !== $userMatch->matched_user_id
        ) {
            return response()->json([
                'message' => 'Action non autorisée'
            ], 403);
        }

        // Supprimer les DEUX côtés du match
        UserMatch::where(function ($q) use ($userMatch) {
            $q->where('user_id', $userMatch->user_id)
              ->where('matched_user_id', $userMatch->matched_user_id);
        })->orWhere(function ($q) use ($userMatch) {
            $q->where('user_id', $userMatch->matched_user_id)
              ->where('matched_user_id', $userMatch->user_id);
        })->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Match supprimé avec succès'
        ], 200);
    }
}
