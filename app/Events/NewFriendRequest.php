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

class NewFriendRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public FriendRequest $friendRequest)
    {
        //
    }

    public function broadcastOn(){
        return new Channel('user.'. $this->friendRequest->receiver_id);
    }

    public function broadcastAs(){
        return 'new-friend-request';
    }
    public function broadcastWith()
{
    // 🔍 LOG 2 : Vérifie ce qui arrive dans l'événement
    \Log::info('NewFriendRequest broadcastWith:', [
        'friendRequest_id' => $this->friendRequest->id,
        'sender_loaded' => $this->friendRequest->relationLoaded('sender'),
        'sender' => $this->friendRequest->sender,
        'sender_name' => $this->friendRequest->sender?->name
    ]);

    // Force le chargement si nécessaire
    if (!$this->friendRequest->relationLoaded('sender')) {
        $this->friendRequest->load('sender');
        \Log::info('Relation chargée dans broadcastWith');
    }
    
    return [
        'friendRequest' => [
            'id' => $this->friendRequest->id,
            'sender_id' => $this->friendRequest->sender_id,
            'sender_name' => $this->friendRequest->sender?->name ?? 'Utilisateur',
        ]
    ];
}

}
