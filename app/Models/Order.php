<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'user_id',
        'cart_id',
        'status',
        'subtotal',
        'discount_total',
        'tax_total',
        'total',
        'currency',
        'payment_method',
        'reference',
    ];

    public function user () : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cart() : BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}
