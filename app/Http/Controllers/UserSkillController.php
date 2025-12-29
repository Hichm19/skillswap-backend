<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;

class UserSkillController extends Controller
{
    /**
     * Lister les compétences d’un utilisateur
     */
    public function index($userId)
    {
        $user = User::with('skills')->findOrFail($userId);

        return response()->json([
            'user' => $user->name,
            'skills' => $user->skills
        ]);
    }

    /**
     * Ajouter une compétence à un utilisateur
     */
    public function store(Request $request, $userId)
    {
        $request->validate([
            'skill_id' => 'required|exists:skills,id'
        ]);

        $user = User::findOrFail($userId);

        // évite les doublons 
        $user->skills()->syncWithoutDetaching($request->skill_id);

        return response()->json([
            'message' => 'Compétence ajoutée avec succès'
        ], 201);
    }

    /**
     * Supprimer une compétence d’un utilisateur
     */
    public function destroy($userId, $skillId)
    {
        $user = User::findOrFail($userId);
        $skill = Skill::findOrFail($skillId);

        $user->skills()->detach($skill->id);

        return response()->json([
            'message' => 'Compétence supprimée'
        ]);
    }
}
