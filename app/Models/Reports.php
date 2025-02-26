<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    protected $table = 'tbl_reports';
    protected $fillable = [
        'seller_id',
        'report_name',
        'report_type',
        'content',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id')->nullable();
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id')->nullable();
    }
}
