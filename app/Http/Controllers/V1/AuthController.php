<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginByNameRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register( RegistrationRequest $request )
    {
        $name = trim( $request->input( 'name' ) );
        $password = trim( $request->input( 'password' ) );

        $user = User::query()->create( [
            'name' => $name,
            'password' => Hash::make( $password ),
        ] );

        return $this->tokenResponse( $user );
    }

    public function loginByName( LoginByNameRequest $request )
    {
        $name = trim( $request->input( 'name' ) );
        $password = trim( $request->input( 'password' ) );

        $user = User::query()->where( 'name', $name )->first();

        if ( !$user || !Hash::check( $password, $user->password ) ) {
            return $this->invalidCredentialsResponse();
        }

        return $this->tokenResponse( $user );
    }

    protected function tokenResponse( User $user ) : JsonResponse
    {
        $token = $user->createToken( 'api-token' )->plainTextToken;

        return response()->json( [
            'success' => true,
            'access_token' => $token,
        ] );
    }

    protected function invalidCredentialsResponse() : JsonResponse
    {
        return response()->json( [
            'success' => false,
            'message' => __( 'Invalid credentials.' ),
        ] );
    }
}
