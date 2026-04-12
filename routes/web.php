<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get( '/', function () {
    return response()->json();
} );

Route::post( '/signal', function ( Request $request ) {
    return broadcast( new \App\Events\WebRTCSignal( $request->receiverId, $request->data ) )
        ->toOthers();
} );
