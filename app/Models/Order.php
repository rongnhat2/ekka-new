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

    protected $table = 'order';

    protected $primaryKey = 'ordID';

    protected $fillable = [
        'userID', 'ordDate', 'ordPhone', 'ordReceiver', 'ordAddress',
        'totalPrice', 'staValue', 'subtotal', 'discount', 'order_type',
    ];

    protected $casts = [
        'userID' => 'integer',
        'totalPrice' => 'integer',
        'staValue' => 'integer',
        'subtotal' => 'integer',
        'discount' => 'integer',
        'order_type' => 'integer',
        'ordDate' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'ordID', 'ordID');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'ordID', 'ordID');
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class, 'ordID', 'ordID')->latestOfMany('payID');
    }

    public function scopeDelivered($query)
    {
        return $query->where('staValue', self::STATUS_DELIVERED);
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
}
