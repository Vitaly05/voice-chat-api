<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptFriendshipRequestEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $user_name;

    /**
     * Create a new event instance.
     */
    public function __construct( User $acceptor, public $receiver_id )
    {
        $this->user_id = $acceptor->id;
        $this->user_name = $acceptor->name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn() : array
    {
        return [
            new PrivateChannel( 'Chat.' . $this->receiver_id ),
        ];
    }

    public function broadcastAs() : string
    {
        return 'accept-friendship-request';
    }
}
