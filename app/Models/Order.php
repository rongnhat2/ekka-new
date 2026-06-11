<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const STATUS_PENDING = 0;
    public const STATUS_INCOMPLETE = 1;
    public const STATUS_COMPLETED = 2;
    public const STATUS_DELIVERED = 3;
    public const STATUS_RETURNED = 4;

    public const PAYMENT_UNPAID = 1;
    public const PAYMENT_PAID = 2;

    public const TYPE_ONLINE = 0;
    public const TYPE_OFFLINE = 1;

    protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'subtotal',
        'discount',
        'total',
        'order_status',
        'payment_status',
        'order_type',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'subtotal' => 'integer',
        'discount' => 'integer',
        'total' => 'integer',
        'order_status' => 'integer',
        'payment_status' => 'integer',
        'order_type' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function scopeStatus($query, int $status)
    {
        return $query->where('order_status', $status);
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', self::STATUS_DELIVERED);
    }

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING => 'Chờ xử lí',
            self::STATUS_INCOMPLETE => 'Chưa hoàn thiện',
            self::STATUS_COMPLETED => 'Đã hoàn thiện',
            self::STATUS_DELIVERED => 'Đã giao hàng',
            self::STATUS_RETURNED => 'Hoàn trả',
        ];
    }

    public static function paymentLabels(): array
    {
        return [
            0 => '',
            self::PAYMENT_UNPAID => 'Chưa thanh toán',
            self::PAYMENT_PAID => 'Đã thanh toán',
        ];
    }
}
