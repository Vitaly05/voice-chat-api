<?php

namespace App\Http\Controllers\V1;

use App\Events\AcceptFriendshipRequestEvent;
use App\Events\NewFriendshipRequestEvent;
use App\Events\RemoveFriendEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddFriendRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getCurrentUserInfo()
    {
        return new UserResource( auth()->user() );
    }

    public function getAllFriends()
    {
        $current_user = auth()->user();

        return UserResource::collection( $current_user->friends()->get() );
    }

    public function addFriend( AddFriendRequest $request )
    {
        $current_user = auth()->user();

        $name = $request->name;

        if ( $current_user->name == $name ) {
            return $this->cantAddYourselfResponse();
        }

        $searched_user = User::query()->firstWhere( 'name', $name );

        if ( !$searched_user ) {
            return $this->userNotFoundResponse();
        }

        if ( $searched_user->friendshipRequests()->where( 'id', $current_user->id )->exists() ) {
            return $this->requestAlreadyExistsResponse();
        }

        if ( $current_user->hasFriend( $searched_user->id ) ) {
            return $this->userAlreadyInFriendsResponse();
        }

        if ( $current_user->friendshipRequests()->where( 'id', $searched_user->id )->exists() ) {
            $current_user->acceptFriendshipRequest( $searched_user->id );

            broadcast( new AcceptFriendshipRequestEvent( $current_user, $searched_user->id ) );

            return response()->json( [
                'success' => true,
                'status' => 'friend_added',
                'user' => UserResource::make( $searched_user ),
            ] );
        }

        $current_user->makeFriendshipRequest( $searched_user->id );

        broadcast( new NewFriendshipRequestEvent( $current_user, $searched_user->id ) );

        return response()->json( [
            'success' => true,
        ] );
    }

    function removeFriend( Request $request )
    {
        $current_user = auth()->user();

        $friend_id = $request->input( 'user_id' );

        if ( !$current_user->hasFriend( $friend_id ) ) {
            return $this->userArentYourFriendResponse();
        }

        $current_user->removeFriend( $friend_id );

        broadcast( new RemoveFriendEvent( $current_user, $friend_id ) );

        return response()->json( [
            'success' => true,
        ] );
    }

    function getFriendshipRequests()
    {
        $current_user = auth()->user();

        $requests = $current_user->friendshipRequests()->get();

        return UserResource::collection( $requests );
    }

    function acceptFriendRequest( Request $request )
    {
        $current_user = auth()->user();

        $user_id = $request->input( 'user_id' );

        $current_user->acceptFriendshipRequest( $user_id );

        broadcast( new AcceptFriendshipRequestEvent( $current_user, $user_id ) );

        return response()->json( [
            'success' => true,
        ] );
    }

    function rejectFriendRequest( Request $request )
    {
        $current_user = auth()->user();

        $user_id = $request->input( 'user_id' );

        $current_user->rejectFriendshipRequest( $user_id );

        return response()->json( [
            'success' => true,
        ] );
    }

    function getNotificationsCount()
    {
        $current_user = auth()->user();

        $notifications_count = $current_user->friendshipRequests()->count();

        return response()->json( [
            'success' => true,
            'count' => $notifications_count,
        ] );
    }

    protected function cantAddYourselfResponse() : JsonResponse
    {
        return response()->json( [
            'success' => false,
            'message' => "You can't add yourself.",
        ] );
    }

    protected function userNotFoundResponse() : JsonResponse
    {
        return response()->json( [
            'success' => false,
            'message' => 'User not found.',
        ] );
    }

    protected function requestAlreadyExistsResponse() : JsonResponse
    {
        return response()->json( [
            'success' => false,
            'message' => 'You are sent request yet.',
        ] );
    }

    protected function userAlreadyInFriendsResponse() : JsonResponse
    {
        return response()->json( [
            'success' => false,
            'message' => 'User already in your friend list.',
        ] );
    }

    protected function userArentYourFriendResponse() : JsonResponse
    {
        return response()->json( [
            'success' => false,
            'message' => 'User are not in your friend list.',
        ] );
    }
}
