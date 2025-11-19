<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_CHECKED_OUT = 'checked_out';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'session_id',
        'status',
        'expires_at',
        'subtotal',
        'discount',
        'total',
        'currency',
        'idempotency_key',
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
