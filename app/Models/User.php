<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';

    protected $primaryKey = 'userID';

    protected $fillable = [
        'userName', 'userPhone', 'userEmail', 'userAddress', 'userPass', 'secret_key', 'status',
    ];

    protected $hidden = ['userPass', 'secret_key'];

    protected $casts = [
        'secret_key' => 'integer',
        'status' => 'integer',
    ];

    public function getAuthPassword()
    {
        return $this->userPass;
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'userID', 'userID');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
