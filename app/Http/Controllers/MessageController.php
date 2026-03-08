<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\UserMatch;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(UserMatch $match, Request $request)
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

        $messages = Message::where(function ($q) use ($match) {
                $q->where('sender_id', $match->user_id)
                  ->where('receiver_id', $match->matched_user_id);
            })
            ->orWhere(function ($q) use ($match) {
                $q->where('sender_id', $match->matched_user_id)
                  ->where('receiver_id', $match->user_id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $messages
        ]);
    }

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

        $receiverId = $user->id === $match->user_id
            ? $match->matched_user_id
            : $match->user_id;

        $message = Message::create([
            'sender_id'   => $user->id,
            'receiver_id' => $receiverId,
            'content'     => $request->content
        ]);

        $message->load('sender');

        broadcast(new \App\Events\NewMessage($message));

        return response()->json([
            'status' => 'success',
            'data'   => $message
        ], 201);
    }

    public function destroy(Message $message, Request $request)
    {
        $user = $request->user();

        if ($user->id !== $message->sender_id) {
            return response()->json([
                'message' => 'Action non autorisée'
            ], 403);
        }

        $message->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Message supprimé'
        ]);
    }
}