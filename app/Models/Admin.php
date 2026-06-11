<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';

    protected $fillable = [
        'secret_key',
        'email',
        'password',
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

    public function warehouseImports()
    {
        return $this->hasMany(WarehouseImport::class, 'admin_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
