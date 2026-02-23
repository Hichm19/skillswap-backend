<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\UserMatch;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Lister les messages d’un match
     * GET /api/matches/{match}/messages
     */
    public fonction index(UserMatch $match, Request $request)
    {
        $user = $request->user();

        if (
            $user->id !== $match->user_id &&
            $user->id !== $match->matched_user_id
        ){
            return return response()->json([
                "message"=>"Action non autaorisée"
            ], 403);
        }

        $message= Message::where("user_match_id", $match ->id)
        ->with('user:id,name')
        ->orderBy('created_at','asc')
        ->get();
    }

    /**
     * Envoyer un message
     * POST /api/matches/{match}/messages
     */
    public function store(UserMatch $match, Request $request)
    {
        
        $user = $request->user();

        
        if (
            $user->id !== $match->user_id &&
            $user->id !== $match->matched_user_id
        ) {
            return response()->json([
                'message' => 'Action non autorisée'
            ], 403);
        }

        
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

       
        $message = Message::create([
            'user_id' => $user->id,
            'user_match_id' => $match->id,
            'content' => $request->content
        ]);

        
        return response()->json([
            'status' => 'success',
            'data' => $message->load('user:id,name')
        ], 201);
    }

    /**
     * Supprimer un message (optionnel)
     * DELETE /api/messages/{id}
     */
    public function destroy(Message $message, Request $request)
    {
        $user = $request->user();

        // Seul l’auteur du message peut supprimer
        if ($user->id !== $message->user_id) {
            return response()->json([
                'message' => 'Action non autorisée'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Message supprimé'
        ], 200);
    }
}
