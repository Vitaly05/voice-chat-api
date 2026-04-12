<?php

use App\Http\Controllers\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller( AuthController::class )
    ->prefix( 'auth' )
    ->group( function () {
        Route::post( 'register', 'register' );
        Route::post( 'login-name', 'loginByName' );
    } );
