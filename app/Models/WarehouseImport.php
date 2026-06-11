<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseImport extends Model
{
    protected $table = 'warehouse_import';

    protected $fillable = [
        'admin_id',
    ];

    protected $casts = [
        'admin_id' => 'integer',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function details()
    {
        return $this->hasMany(WarehouseImportDetail::class, 'import_id');
    }

    public function getTotalQuantityAttribute(): int
    {
        return (int) $this->details->sum('quantity');
    }

    public function getTotalPriceAttribute(): int
    {
        return (int) $this->details->sum(function (WarehouseImportDetail $detail) {
            return $detail->quantity * $detail->price;
        });
    }
}
