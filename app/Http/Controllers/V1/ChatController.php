<?php

namespace App\Http\Controllers\V1;

use App\Events\AcceptCallEvent;
use App\Events\StartCallEvent;
use App\Events\WebRTCSignal;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\AcceptCallRequest;
use App\Http\Requests\Chat\StartCallRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function signal( Request $request )
    {
        broadcast( new WebRTCSignal( $request->receiverId, $request->data ) );
    }

    public function startCall( StartCallRequest $request )
    {
        $current_user = auth()->user();
        $recipient_id = $request->recipient_id;

        if ( !$current_user->hasFriend( $recipient_id ) ) {
            return $this->notAFriendResponse();
        }

        broadcast( new StartCallEvent( $current_user->id, $recipient_id ) );

        return response()->json( ['success' => true] );
    }

    public function acceptCall( AcceptCallRequest $request )
    {
        $current_user_id = auth()->id();
        $sender_id = $request->sender_id;

        broadcast( new AcceptCallEvent( $sender_id, $current_user_id ) );

        return response()->json( ['success' => true] );
    }

    public function notAFriendResponse() : JsonResponse
    {
        return response()->json( [
            'success' => false,
            'message' => __( 'This user is not your friend. You can\'t call him.' ),
        ], 400 );
    }
}
