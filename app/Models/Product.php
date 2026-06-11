<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'images',
        'banner',
        'description',
        'detail',
        'status',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'brand_id' => 'integer',
        'status' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function productVars()
    {
        return $this->hasMany(ProductVar::class, 'product_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getImageListAttribute(): array
    {
        if (!$this->images || $this->images === '[]') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->images))));
    }

    public function getFirstImageAttribute(): ?string
    {
        $list = $this->image_list;

        return $list[0] ?? null;
    }
}
