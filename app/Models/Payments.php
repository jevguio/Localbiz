<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payments extends Model
{
    protected $table = 'tbl_payments';
    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'customer_id',
        'courier_id',
        'payment_method',
        'payment_amount',
        'receipt_file',
        'payment_date',
        'message',
        'status',
        'paid_at',
        'pickup_date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class, 'courier_id', 'id');
    }
}
