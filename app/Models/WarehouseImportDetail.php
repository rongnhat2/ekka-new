<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseImportDetail extends Model
{
    protected $table = 'warehouse_import_detail';

    protected $fillable = [
        'import_id',
        'product_var_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'import_id' => 'integer',
        'product_var_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'integer',
    ];

    public function warehouseImport()
    {
        return $this->belongsTo(WarehouseImport::class, 'import_id');
    }

    public function productVar()
    {
        return $this->belongsTo(ProductVar::class, 'product_var_id');
    }

    public function getLineTotalAttribute(): int
    {
        return $this->quantity * $this->price;
    }
}
