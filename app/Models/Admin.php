<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';

    protected $primaryKey = 'adminID';

    protected $fillable = [
        'adminName', 'adminPhone', 'adminAddress', 'adminEmail', 'adminPass', 'secret_key', 'status',
    ];

    protected $hidden = ['adminPass', 'secret_key'];

    protected $casts = [
        'secret_key' => 'integer',
        'status' => 'integer',
    ];

    public function getAuthPassword()
    {
        return $this->adminPass;
    }

    public function stockImports()
    {
        return $this->hasMany(StockImport::class, 'adminID', 'adminID');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
