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

class NewFriendshipRequestEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $user_name;

    /**
     * Create a new event instance.
     */
    public function __construct( User $sender, public $receiver_id )
    {
        $this->user_id = $sender->id;
        $this->user_name = $sender->name;
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
        return 'new-friendship-request';
    }
}
