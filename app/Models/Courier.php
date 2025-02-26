<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courier extends Model
{
    protected $table = 'tbl_couriers';
    public $timestamps = true;

    protected $fillable = [
       'name',
    ];

    public function payments(): HasMany
    {
        return $this->hasMany(Payments::class, 'courier_id', 'id');
    }
}

