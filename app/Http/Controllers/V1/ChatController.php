<?php

namespace App\Http\Controllers\V1;

use App\Events\NewICECandidateEvent;
use App\Events\SendedAnswerEvent;
use App\Events\SendedOfferEvent;
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

    public function newIceCandidate( Request $request )
    {
        broadcast( new NewICECandidateEvent( $request->candidate ) )
            ->toOthers();
    }

    public function sendOffer( Request $request )
    {
        broadcast( new SendedOfferEvent( $request->offer ) )
            ->toOthers();
    }

    public function sendAnswer( Request $request )
    {
        broadcast( new SendedAnswerEvent( $request->answer ) )
            ->toOthers();
    }
}
