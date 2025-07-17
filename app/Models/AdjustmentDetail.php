<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdjustmentDetail extends Model
{

    protected $fillable = [
        'id',
        'product_id',
        'adjustment_id',
        'quantity',
        'type',
        'product_variant_id',
        'organization_id',
    ];

    protected $casts = [
        'adjustment_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'double',
        'product_variant_id' => 'integer',
    ];

    public function adjustment()
    {
        return $this->belongsTo('App\Models\Adjustment');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('organization', function ($builder) {
            if (auth()->check()) {
                $builder->where('organization_id', auth()->user()->organization_id);
            }
        });
    }
}