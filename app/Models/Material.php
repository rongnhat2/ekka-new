<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'material';

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function productVars()
    {
        return $this->hasMany(ProductVar::class, 'material_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
