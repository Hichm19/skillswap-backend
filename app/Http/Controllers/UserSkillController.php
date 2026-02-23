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
    public function index(Request $request)
{
    $user = $request->user();
    return response()->json([
        'user' => $user->name,
        'skills' => $user->skills
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'skill_id' => 'required|exists:skills,id',
        'type' => 'required|in:teach,learn'
    ]);

    $user = $request->user();

    $user->skills()->syncWithoutDetaching([
        $request->skill_id => ['type' => $request->type]
    ]);

    return response()->json(['message' => 'Compétence ajoutée'], 201);
}

public function destroy(Request $request, $skillId)
{
    $user = $request->user();
    $user->skills()->detach($skillId);
    return response()->json(['message' => 'Compétence supprimée']);
}
}
