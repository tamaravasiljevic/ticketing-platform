<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'original_user_id', 'ticket_category_id', 'code'];

    protected static function booted(): void
    {
        static::creating(function ($ticket) {
            $ticket->code = Str::uuid();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function originalOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'original_user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'ticket_category_id');
    }

    /**
     * Indirect relationship to Event through TicketCategory.
     */
    public function event()
    {
        return $this->hasOneThrough(
            Event::class,            // The final model we want to reach (Event).
            TicketCategory::class,   // The intermediate model (TicketCategory).
            'id',                    // Foreign key on TicketCategory (to Ticket).
            'id',                    // Foreign key on Event (to TicketCategory).
            'ticket_category_id',    // Local key on Ticket (to TicketCategory).
            'event_id'               // Local key on TicketCategory (to Event).
        );
    }
}
