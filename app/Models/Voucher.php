<?php

namespace App\Models;

use DemeterChain\B;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'purchased_by',
        'order_id',
        'initial_value',
        'remaining_value',
        'currency',
        'expires_at',
        'is_active',
        'metadata',
    ];

    public function order () : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function purchasedBy () : BelongsTo
    {
        return $this->belongsTo(User::class, 'purchased_by');
    }
}
