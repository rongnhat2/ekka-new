<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVar extends Model
{
    protected $table = 'product_var';

    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'material_id',
        'codeSKU',
        'prices',
        'stock',
        'minQuantity',
        'status',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'color_id' => 'integer',
        'size_id' => 'integer',
        'material_id' => 'integer',
        'prices' => 'integer',
        'stock' => 'integer',
        'minQuantity' => 'integer',
        'status' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function warehouseImportDetails()
    {
        return $this->hasMany(WarehouseImportDetail::class, 'product_var_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_var_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
