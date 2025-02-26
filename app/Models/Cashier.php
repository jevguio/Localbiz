<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cashier extends Model
{
    protected $table = 'tbl_cashier';
    public $timestamps = true;

    protected $fillable = ['seller_id', 'user_id', 'document_file', 'is_approved'];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Orders::class, 'seller_id', 'id');
    }
}