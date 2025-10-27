<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function user () : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
