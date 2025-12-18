<?php

namespace App\Http\Controllers;

use App\Models\UserMatch;
use Illuminate\Http\Request;

class UserMatchController extends Controller
{
    /**
     *  Liste de tous mes matchs
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
     */
    public function show(UserMatch $userMatch, Request $request)
    {
        // Sécurité : seul le propriétaire peut voir
        if ($request->user()->id !== $userMatch->user_id) {
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
     *Supprimer un match (unfriend)
     */
    public function destroy(UserMatch $userMatch, Request $request)
    {
        $user = $request->user();

        // Sécurité
        if ($user->id !== $userMatch->user_id) {
            return response()->json([
                'message' => 'Action non autorisée'
            ], 403);
        }

        // Supprimer les deux côtés du match
        UserMatch::where('user_id', $userMatch->user_id)
            ->where('matched_user_id', $userMatch->matched_user_id)
            ->delete();

        UserMatch::where('user_id', $userMatch->matched_user_id)
            ->where('matched_user_id', $userMatch->user_id)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Match supprimé'
        ], 200);
    }
}
