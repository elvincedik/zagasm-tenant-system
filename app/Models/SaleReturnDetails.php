<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleReturnDetails extends Model
{

    protected $fillable = [
        'id',
        'product_id',
        'sale_return_id',
        'sale_unit_id',
        'total',
        'quantity',
        'product_variant_id',
        'price',
        'TaxNet',
        'discount',
        'discount_method',
        'tax_method',
        'organization_id',
    ];

    protected $casts = [
        'total' => 'double',
        'quantity' => 'double',
        'sale_return_id' => 'integer',
        'product_id' => 'integer',
        'sale_unit_id' => 'integer',
        'product_variant_id' => 'integer',
        'price' => 'double',
        'TaxNet' => 'double',
        'discount' => 'double',
    ];

    public function SaleReturn()
    {
        return $this->belongsTo('App\Models\SaleReturn');
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