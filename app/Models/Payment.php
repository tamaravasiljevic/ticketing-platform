<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    public const STATUS_INITIATED = 'initiated';

    public const STATUS_PAID = 'paid';

    public const STATUS_FAILED = 'failed';

    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'order_id',
        'provider',
        'provider_payment_id',
        'status',
        'amount',
        'currency',
        'idempotency_key',
        'metadata',
        'provider_response',
    ];

    public function order () : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
