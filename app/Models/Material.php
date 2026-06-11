<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'material';

    protected $primaryKey = 'mateID';

    protected $fillable = ['mateName', 'status'];

    protected $casts = ['status' => 'integer'];

    public function proVariants()
    {
        return $this->hasMany(ProVariant::class, 'mateID', 'mateID');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
