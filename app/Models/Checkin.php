<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    public const TYPE_IN = 'in';
    public const TYPE_OUT = 'out';

    protected $fillable = ['ticket_id', 'type', 'checked_at', 'user_id', 'location'];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}
