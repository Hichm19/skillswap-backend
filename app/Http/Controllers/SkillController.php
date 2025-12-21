<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Afficher la liste des skills
     */
    public function index()
    {
        $skills = Skill::all();

        return response()->json($skills);
    }

    /**
     * Créer un nouveau skill
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:skills,name',
            'categorie' => 'required|string|max:255',
        ]);

        $skill = Skill::create([
            'name' => $request->name,
            'categorie' => $request->categorie,
        ]);

        return response()->json([
            'message' => 'Skill créé avec succès',
            'skill' => $skill
        ], 201);
    }

    /**
     * Afficher un skill précis
     */
    public function show($id)
    {
        $skill = Skill::findOrFail($id);

        return response()->json($skill);
    }

    /**
     * Mettre à jour un skill
     */
    public function update(Request $request, $id)
    {
        $skill = Skill::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:skills,name,' . $skill->id,
            'categorie' => 'required|string|max:255',
        ]);

        $skill->update([
            'name' => $request->name,
            'categorie' => $request->categorie,
        ]);

        return response()->json([
            'message' => 'Skill mis à jour avec succès',
            'skill' => $skill
        ]);
    }

    /**
     * Supprimer un skill
     */
    public function destroy($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json([
            'message' => 'Skill supprimé avec succès'
        ]);
    }
}
