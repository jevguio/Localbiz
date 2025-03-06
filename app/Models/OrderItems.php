<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItems extends Model
{
    protected $table = 'tbl_order_items';
    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'is_checked',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class, 'order_id', 'id');
    }
}
