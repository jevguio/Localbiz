<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Orders extends Model
{
    protected $table = 'tbl_orders';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'user_id',
        'total_amount',
        'order_number',
        'proof_of_delivery',
        'receipt_file',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'order_id', 'id');
    }

    public function payments(): HasOne
    {
        return $this->hasOne(Payments::class, 'order_id', 'id');
    }

    public function deliveryTracking(): HasMany
    {
        return $this->hasMany(DeliveryTracking::class, 'order_id', 'id');
    }

    public function receipt(): HasOne
    {
        return $this->hasOne(Receipt::class, 'order_id', 'id');
    }
}
