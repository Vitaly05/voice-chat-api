<?php

namespace App\Broadcasting;

use App\Models\User;

class ChatChannel
{
    /**
     * Create a new channel instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join( User $user, $user_id ) : array|bool
    {
        return $user->id === (int) $user_id;
    }
}
