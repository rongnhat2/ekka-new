<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $table = 'customer';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'password',
        'secret_key',
        'status',
    ];

    protected $hidden = [
        'password',
        'secret_key',
    ];

    protected $casts = [
        'secret_key' => 'integer',
        'status' => 'integer',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
