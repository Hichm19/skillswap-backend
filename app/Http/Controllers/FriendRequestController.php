<?php

namespace App\Http\Controllers;

use App\Models\FriendRequest;
use Illuminate\Http\Request;
use App\Models\UserMatch;
use App\Events\NewFriendRequest;
use App\Events\FriendRequestAccepted;

class FriendRequestController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $requests = FriendRequest::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->with('sender')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $requests
        ], 200);
    }

    public function friendAsk(Request $request)
    {
        $user = $request->user();
        $sentRequests = FriendRequest::where('sender_id', $user->id)
            ->where('status', 'pending')
            ->with('receiver')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $sentRequests
        ], 200);
    }

    public function store(Request $request)
{
    $request->validate([
        'receiver_id' => 'required|exists:users,id'
    ]);

    $sender = $request->user();

    if ($sender->id === $request->receiver_id) {
        return response()->json([
            'message' => 'Impossible de s\'envoyer une demande à soi-même'
        ], 400);
    }

    $exists = FriendRequest::where('sender_id', $sender->id)
        ->where('receiver_id', $request->receiver_id)
        ->where('status', 'pending')
        ->exists();

    if ($exists) {
        return response()->json([
            'message' => 'Demande déjà envoyée'
        ], 409);
    }

    $friendRequest = FriendRequest::create([
        'sender_id' => $sender->id,
        'receiver_id' => $request->receiver_id,
        'status' => 'pending'
    ]);

    // 🔍 LOG 1 : Vérifie que le sender est bien chargé
    $friendRequest->load('sender');
    \Log::info('FriendRequest après load:', [
        'id' => $friendRequest->id,
        'sender_id' => $friendRequest->sender_id,
        'sender' => $friendRequest->sender,
        'sender_name' => $friendRequest->sender?->name
    ]);

    broadcast(new NewFriendRequest($friendRequest));

    return response()->json([
        'status' => 'success',
        'data' => $friendRequest
    ], 201);
}

    public function show(FriendRequest $friendRequest, Request $request)
    {
        $user = $request->user();

        if (
            $user->id !== $friendRequest->sender_id &&
            $user->id !== $friendRequest->receiver_id
        ) {
            return response()->json([
                'message' => 'Accès non autorisé'
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $friendRequest
        ], 200);
    }

    public function update(Request $request, FriendRequest $friendRequest)
    {
        $request->validate([
            'status' => 'required|in:accepted,refused'
        ]);

        if ($request->user()->id !== $friendRequest->receiver_id) {
            return response()->json([
                'message' => 'Action non autorisée'
            ], 403);
        }

        if ($friendRequest->status !== 'pending') {
            return response()->json([
                'message' => 'Cette demande a déjà été traitée'
            ], 409);
        }

        $friendRequest->update([
            'status' => $request->status
        ]);

        if ($request->status === 'accepted') {
            // Prévient l'expéditeur que sa demande a été acceptée
            broadcast(new FriendRequestAccepted($friendRequest));

            $exists = UserMatch::where(function ($q) use ($friendRequest) {
                $q->where('user_id', $friendRequest->sender_id)
                  ->where('matched_user_id', $friendRequest->receiver_id);
            })->orWhere(function ($q) use ($friendRequest) {
                $q->where('user_id', $friendRequest->receiver_id)
                  ->where('matched_user_id', $friendRequest->sender_id);
            })->exists();

            if (!$exists) {
                UserMatch::create([
                    'user_id' => $friendRequest->sender_id,
                    'matched_user_id' => $friendRequest->receiver_id,
                    'score' => null
                ]);

                UserMatch::create([
                    'user_id' => $friendRequest->receiver_id,
                    'matched_user_id' => $friendRequest->sender_id,
                    'score' => null
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $friendRequest
        ], 200);
    }

    public function destroy(FriendRequest $friendRequest, Request $request)
    {
        $user = $request->user();

        if (
            $user->id !== $friendRequest->sender_id &&
            $user->id !== $friendRequest->receiver_id
        ) {
            return response()->json([
                'message' => 'Action non autorisée'
            ], 403);
        }

        $friendRequest->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Demande supprimée'
        ], 200);
    }
}