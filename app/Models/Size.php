<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'size';

    protected $primaryKey = 'sizeID';

    protected $fillable = ['sizeValue', 'status'];

    protected $casts = ['status' => 'integer'];

    public function proVariants()
    {
        return $this->hasMany(ProVariant::class, 'sizeID', 'sizeID');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
