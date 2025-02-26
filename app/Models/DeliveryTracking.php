<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryTracking extends Model
{
    protected $table = 'tbl_delivery_tracking';
    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'rider_id',
        'status',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class, 'rider_id', 'id');
    }
}
