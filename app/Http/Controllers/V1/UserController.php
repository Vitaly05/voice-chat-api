<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getCurrentUserInfo()
    {
        return new UserResource( auth()->user() );
    }

    public function getAllFriends()
    {
        $currentUser = auth()->user();

        return UserResource::collection( $currentUser->friends()->get() );
    }
}
