<?php

namespace App\Models;

use DemeterChain\B;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ticket_category_id',
        'description',
        'quantity',
        'unit_price',
        'total_price',
        'metadata',
    ];

    public function order () : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketCategory() : BelongsTo
    {
        return $this->belongsTo(TicketCategory::class);
    }
}
