<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [ 'invoice_number', 'user_id', 'order_id', 'subtotal', 'tax_total', 'discount_total', 'total',
        'currency', 'file_path', 'billing_details'];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order() : Order
    {
        return $this->belongsTo(Order::class);
    }
}
