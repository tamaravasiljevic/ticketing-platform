<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventChangeLog extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'field_name',
        'old_value',
        'new_value',
        'description',
    ];

    public function event() : BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
