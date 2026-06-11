<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProVariant extends Model
{
    protected $table = 'ProVariant';

    protected $primaryKey = 'proVarID';

    protected $fillable = [
        'proID', 'colorID', 'sizeID', 'mateID', 'codeSKU', 'price', 'stock', 'minQuantity', 'status',
    ];

    protected $casts = [
        'proID' => 'integer',
        'colorID' => 'integer',
        'sizeID' => 'integer',
        'mateID' => 'integer',
        'price' => 'integer',
        'stock' => 'integer',
        'minQuantity' => 'integer',
        'status' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'proID', 'proID');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'colorID', 'colorID');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'sizeID', 'sizeID');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'mateID', 'mateID');
    }

    public function stockImportDetails()
    {
        return $this->hasMany(StockImportDetail::class, 'proVarID', 'proVarID');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'proVarID', 'proVarID');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
