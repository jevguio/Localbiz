<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    //
    
    protected $table = 'tbl_product_images';
    protected $fillable = ['product_id', 'filename'];
    
    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

}
