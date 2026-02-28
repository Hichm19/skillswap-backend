<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Les IDs de mes skills
        $mySkillIds = $user->skills()->pluck('skills.id');

        // Les catégories de mes skills
        $mySkillCategories = Skill::whereIn('id', $mySkillIds)->pluck('categorie');

        // Les IDs des gens avec qui j'ai déjà un match
        $matchedIds = $user->matches()->pluck('matched_user_id')
            ->merge($user->matchedBy()->pluck('user_id'));

        // Les IDs des gens avec qui j'ai une demande en cours
        $pendingIds = \App\Models\FriendRequest::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->pluck('receiver_id')
            ->merge(
                \App\Models\FriendRequest::where('receiver_id', $user->id)
                ->pluck('sender_id')
            );

        $suggestions = User::where('id', '!=', $user->id)
            ->whereNotIn('id', $matchedIds)
            ->whereNotIn('id', $pendingIds)
            ->whereHas('skills', function($q) use ($mySkillIds, $mySkillCategories) {
                $q->whereIn('skills.id', $mySkillIds)
                  ->orWhereIn('categorie', $mySkillCategories);
            })
            ->with('skills')
            ->inRandomOrder()
            ->limit(15)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $suggestions
        ]);
    }
}