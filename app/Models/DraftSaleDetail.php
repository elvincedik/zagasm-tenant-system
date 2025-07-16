<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DraftSaleDetail extends Model
{

    protected $fillable = [
        'id',
        'date',
        'draft_sale_id',
        'sale_unit_id',
        'quantity',
        'product_id',
        'total',
        'product_variant_id',
        'price',
        'TaxNet',
        'discount',
        'discount_method',
        'tax_method',
        'organization_id',
    ];

    protected $casts = [
        'id' => 'integer',
        'total' => 'double',
        'quantity' => 'double',
        'draft_sale_id' => 'integer',
        'sale_unit_id' => 'integer',
        'product_id' => 'integer',
        'product_variant_id' => 'integer',
        'price' => 'double',
        'TaxNet' => 'double',
        'discount' => 'double',
    ];

    public function draftsale()
    {
        return $this->belongsTo('App\Models\DraftSale');
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