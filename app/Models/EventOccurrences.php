<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventOccurrences extends Model
{
    protected $fillable = [
        'event_id',
        'start_time',
        'end_time',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
