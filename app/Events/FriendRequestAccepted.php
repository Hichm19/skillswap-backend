<?php

namespace App\Events;
use App\Models\FriendRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestAccepted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public FriendRequest $friendRequest) {}

    public function broadcastOn()
    {
        return new Channel('user.' . $this->friendRequest->sender_id);
    }

    public function broadcastAs()
    {
        return 'friend-request-accepted';
    }
    public function broadcastWith()
{
    // Charger les deux relations
    $this->friendRequest->load(['sender', 'receiver']);
    
    return [
        'friendRequest' => [
            'id' => $this->friendRequest->id,
            'sender_id' => $this->friendRequest->sender_id,
            'sender_name' => $this->friendRequest->sender?->name ?? 'Utilisateur',
            'receiver_id' => $this->friendRequest->receiver_id,
            'receiver_name' => $this->friendRequest->receiver?->name ?? 'Utilisateur',
        ]
    ];
}
}