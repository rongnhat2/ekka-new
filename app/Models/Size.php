<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'size';

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function productVars()
    {
        return $this->hasMany(ProductVar::class, 'size_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
