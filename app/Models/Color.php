<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $table = 'color';

    protected $primaryKey = 'colorID';

    protected $fillable = ['colorValue', 'hex', 'status'];

    protected $casts = ['status' => 'integer'];

    public function proVariants()
    {
        return $this->hasMany(ProVariant::class, 'colorID', 'colorID');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
