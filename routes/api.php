<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ChatController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::controller( AuthController::class )
    ->prefix( 'auth' )
    ->group( function () {
        Route::post( 'register', 'register' );
        Route::post( 'login-name', 'loginByName' );
    } );

Route::controller( ChatController::class )
    ->prefix( 'chat' )
    ->group( function () {
        Route::post( 'signal', 'signal' );
        Route::post( 'new-ice-candidate', 'newIceCandidate' );
        Route::post( 'send-offer', 'sendOffer' );
        Route::post( 'send-answer', 'sendAnswer' );
    } );
