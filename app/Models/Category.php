<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $primaryKey = 'cateID';

    protected $fillable = ['cateName', 'slug', 'status'];

    protected $casts = ['status' => 'integer'];

    public function products()
    {
        return $this->hasMany(Product::class, 'cateID', 'cateID');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
