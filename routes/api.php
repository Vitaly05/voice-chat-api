<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ChatController;
use App\Http\Controllers\V1\UserController;
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

        Route::middleware( 'auth:sanctum' )->group( function () {
            Route::post( 'start-call', 'startCall' );
            Route::post( 'accept-call', 'acceptCall' );
            Route::post( 'reject-call', 'rejectCall' );
            Route::post( 'cancel-call', 'cancelCall' );
        } );
    } );

Route::controller( UserController::class )
    ->prefix( 'user' )
    ->middleware( 'auth:sanctum' )
    ->group( function () {
        Route::get( 'get-info', 'getCurrentUserInfo' );
        Route::get( 'get-all-friends', 'getAllFriends' );
    } );
