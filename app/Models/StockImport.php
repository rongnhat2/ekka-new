<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockImport extends Model
{
    protected $table = 'stock_import';

    protected $primaryKey = 'importID';

    protected $fillable = ['adminID', 'improtDate', 'totalQuantity', 'note'];

    protected $casts = [
        'adminID' => 'integer',
        'totalQuantity' => 'integer',
        'improtDate' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'adminID', 'adminID');
    }

    public function details()
    {
        return $this->hasMany(StockImportDetail::class, 'importID', 'importID');
    }
}
