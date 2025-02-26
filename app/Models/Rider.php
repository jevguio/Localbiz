<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rider extends Model
{
    protected $table = 'tbl_riders';
    public $timestamps = true;

    protected $fillable = ['user_id', 'seller_id', 'document_file', 'is_approved'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id', 'id');
    }

    public function government(): HasOne
    {
        return $this->hasOne(Government::class, 'rider_id', 'id');
    }
}