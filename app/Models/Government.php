<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Government extends Model
{
    protected $table = 'tbl_government';
    public $timestamps = true;

    protected $fillable = [
        'seller_id',
        'rider_id',
        'is_approved',
        'approved_at',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(Rider::class, 'rider_id', 'id');
    }

}
