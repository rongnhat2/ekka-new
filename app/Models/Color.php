<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'color';

    protected $fillable = [
        'name',
        'hex',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function productVars()
    {
        return $this->hasMany(ProductVar::class, 'color_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
