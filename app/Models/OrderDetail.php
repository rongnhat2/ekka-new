<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_detail';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_var_id',
        'product_name',
        'quantity',
        'price',
        'discount',
        'total_price',
        'suborder_status',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'product_id' => 'integer',
        'product_var_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'integer',
        'discount' => 'integer',
        'total_price' => 'integer',
        'suborder_status' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productVar()
    {
        return $this->belongsTo(ProductVar::class, 'product_var_id');
    }
}
