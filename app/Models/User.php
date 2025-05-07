<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tbl_users';
    public $timestamps = true;

    protected $fillable = ['fname', 'lname', 'email', 'password', 'address', 'phone', 'role', 'is_active', 'avatar', 'gcash_number', 'bank_name', 'bank_account_number', 'last_login'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class, 'user_id', 'id');
    }

    public function rider(): HasOne
    {
        return $this->hasOne(Rider::class, 'user_id', 'id');
    }

    public function cashier(): HasOne
    {
        return $this->hasOne(Cashier::class, 'user_id', 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Orders::class, 'user_id', 'id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItems::class, 'user_id', 'id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payments::class, 'customer_id', 'id');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'user_id', 'id');
    }
}
