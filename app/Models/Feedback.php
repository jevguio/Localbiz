<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $table = 'tbl_feedback';
    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];

    public function orderItems(): BelongsTo
    {
        return $this->belongsTo(OrderItems::class, 'order_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
