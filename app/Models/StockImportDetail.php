<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockImportDetail extends Model
{
    protected $table = 'stock_import_detail';

    protected $primaryKey = 'importDetailID';

    protected $fillable = ['importID', 'proVarID', 'Quantity', 'unitPrice'];

    protected $casts = [
        'importID' => 'integer',
        'proVarID' => 'integer',
        'Quantity' => 'integer',
        'unitPrice' => 'integer',
    ];

    public function stockImport()
    {
        return $this->belongsTo(StockImport::class, 'importID', 'importID');
    }

    public function proVariant()
    {
        return $this->belongsTo(ProVariant::class, 'proVarID', 'proVarID');
    }
}
