<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brand';

    protected $primaryKey = 'brandID';

    protected $fillable = ['brandName', 'brandDesc', 'status'];

    protected $casts = ['status' => 'integer'];

    public function products()
    {
        return $this->hasMany(Product::class, 'brandID', 'brandID');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
