<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Products extends Model
{
    protected $table = 'tbl_products';
    public $timestamps = true;

    protected $fillable = ['seller_id', 'category_id', 'name', 'description', 'price', 'stock', 'image', 'location_id', 'is_active','best_before_date'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'product_id', 'id');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'product_id', 'id');
    }
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
    public function reports(): HasMany
    {
        return $this->hasMany(Reports::class, 'product_id', 'id');
    }
}
