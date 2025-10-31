<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAccount extends Model
{
    protected $fillable = [
        'user_id',
        'provider_id',
        'provider_name',
        'access_token',
        'refresh_token',
        'avatar',
    ];

    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
