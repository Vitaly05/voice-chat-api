<?php

namespace App\Models;

use App\Enums\ChatStatuses;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable( ['name', 'email', 'password'] )]
#[Hidden( ['password', 'remember_token'] )]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts() : array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function chats() : BelongsToMany
    {
        return $this->belongsToMany( Chat::class );
    }

    public function friends()
    {
        $current_user_id = $this->id;

        return User::query()->whereHas( 'chats', function ( $query ) use ( $current_user_id ) {
            $query->where( 'status', ChatStatuses::ACCEPTED )
                ->whereHas( 'users', function ( $query ) use ( $current_user_id ) {
                    $query->where( 'id', $current_user_id );
                } );
        } )->where( 'id', '!=', $current_user_id );
    }

    public function hasFriend( $user_id )
    {
        return $this->friends()->where( 'id', $user_id )->exists();
    }

    public function friendshipRequests()
    {
        $current_user_id = $this->id;

        return User::query()->whereHas( 'chats', function ( $query ) use ( $current_user_id ) {
            $query->where( 'status', ChatStatuses::REQUESTED )
                ->where( 'creator_id', '!=', $current_user_id )
                ->whereHas( 'users', function ( $query ) use ( $current_user_id ) {
                    $query->where( 'id', $current_user_id );
                } );
        } )->where( 'id', '!=', $current_user_id );
    }
}
