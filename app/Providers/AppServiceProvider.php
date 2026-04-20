<?php

namespace App\Providers;

use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register() : void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        TrimStrings::skipWhen( function ( Request $request ) {
            return $request->is( 'v1/chat/send-offer' )
                || $request->is( 'v1/chat/send-answer' );
        } );
    }
}
