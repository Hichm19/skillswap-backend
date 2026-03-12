<?php

namespace App\Events;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public Message $message) {}

    public function broadcastOn()
    {
        return new Channel('user.' . $this->message->receiver_id);
    }

    public function broadcastAs()
    {
        return 'new-message';
    }
    public function broadcastWith()
{
    
    if (!$this->message->relationLoaded('sender')) {
        $this->message->load('sender');
    }
    
    return [
        'message' => [
            'id' => $this->message->id,
            'content' => $this->message->content,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender?->name ?? 'Utilisateur',
        ]
    ];
}
}