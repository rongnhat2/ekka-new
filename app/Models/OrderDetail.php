<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'orderDetail';

    protected $primaryKey = 'ordDetailID';

    protected $fillable = [
        'ordID', 'proID', 'proVarID', 'proName', 'quantity', 'basePrice', 'salePrice', 'discount', 'suborder_status',
    ];

    protected $casts = [
        'ordID' => 'integer',
        'proID' => 'integer',
        'proVarID' => 'integer',
        'quantity' => 'integer',
        'basePrice' => 'integer',
        'salePrice' => 'integer',
        'discount' => 'integer',
        'suborder_status' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'ordID', 'ordID');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'proID', 'proID');
    }

    public function proVariant()
    {
        return $this->belongsTo(ProVariant::class, 'proVarID', 'proVarID');
    }
}
