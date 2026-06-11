<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const STATUS_UNPAID = 1;
    public const STATUS_PAID = 2;

    protected $table = 'payment';

    protected $primaryKey = 'payID';

    protected $fillable = ['ordID', 'payDate', 'payStatus', 'amount', 'payMethod'];

    protected $casts = [
        'ordID' => 'integer',
        'payStatus' => 'integer',
        'amount' => 'integer',
        'payDate' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'ordID', 'ordID');
    }
}
