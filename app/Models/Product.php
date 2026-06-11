<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    protected $primaryKey = 'proID';

    protected $fillable = [
        'cateID', 'brandID', 'proName', 'proDesc', 'IMG', 'slug', 'banner', 'detail', 'status',
    ];

    protected $casts = [
        'cateID' => 'integer',
        'brandID' => 'integer',
        'status' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'cateID', 'cateID');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brandID', 'brandID');
    }

    public function proVariants()
    {
        return $this->hasMany(ProVariant::class, 'proID', 'proID');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'proID', 'proID');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getImageListAttribute(): array
    {
        if (!$this->IMG || $this->IMG === '[]') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->IMG))));
    }
}
