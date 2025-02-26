<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seller extends Model
{
    protected $table = 'tbl_sellers';
    public $timestamps = true;

    protected $fillable = ['user_id', 'logo', 'document_file', 'is_approved'];

    public function cashiers(): HasMany
    {
        return $this->hasMany(Cashier::class, 'seller_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function riders(): HasMany
    {
        return $this->hasMany(Rider::class, 'seller_id', 'id');
    }

    public function government(): HasOne
    {
        return $this->hasOne(Government::class, 'seller_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Products::class, 'seller_id', 'id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Reports::class, 'seller_id', 'id');
    }
}