<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalkinOrders extends Model
{
    //

    use HasFactory;
    protected $table="walkin_orders";
    protected $fillable = [
        'customer_name',
        'items',
        'delivery_method',
        'payment_method',
        'subtotal',
        'delivery_fee',
        'total',
        'status',
    ];

    // Cast JSON columns to arrays or objects for easy manipulation
    protected $casts = [
        'items' => 'array',
    ];
}
