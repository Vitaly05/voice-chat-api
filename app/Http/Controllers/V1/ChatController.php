<?php

namespace App\Http\Controllers\V1;

use App\Events\WebRTCSignal;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function signal( Request $request )
    {
        broadcast( new WebRTCSignal( $request->receiverId, $request->data ) )
            ->toOthers();
    }
}
