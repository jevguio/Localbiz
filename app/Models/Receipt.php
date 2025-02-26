<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'tbl_receipts';
    protected $fillable = [
        'order_id',
        'receipt_file',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
}
