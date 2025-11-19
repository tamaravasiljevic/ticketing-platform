<?php

namespace App\Models;

use DemeterChain\B;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'ticket_category_id',
        'quantity',
        'price_snapshot',
        'currency',
        'metadata',
        'reserved_until',
    ];

    public function cart () : BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function ticketCategory() : BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }
}
