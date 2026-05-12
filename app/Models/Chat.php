<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable( ['status', 'creator_id'] )]
class Chat extends Model
{
    public function users() : BelongsToMany
    {
        return $this->belongsToMany( User::class );
    }
}
