<?php

namespace App\Models;

use DemeterChain\B;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherRedemption extends Model
{
    use HasFactory;

    public const STATUS_APPLIED = 'applied';
    public const STATUS_REFUNDED = 'refunded';
    public const STATUS_REVERTED = 'reverted';

    protected $fillable = [
        'voucher_id',
        'order_id',
        'user_id',
        'amount',
        'currency',
        'status',
    ];

    public function voucher () : BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
