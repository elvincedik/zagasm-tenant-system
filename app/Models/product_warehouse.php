<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class product_warehouse extends Model
{
    protected $table = 'product_warehouse';

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'qte',
        'manage_stock',
        'organization_id',
    ];

    protected $casts = [
        'product_id' => 'integer',
        'warehouse_id' => 'integer',
        'manage_stock' => 'integer',
        'qte' => 'double',
    ];

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public function productVariant()
    {
        return $this->belongsTo('App\Models\ProductVariant');
    }

    // public function organization()
    // {
    //     return $this->belongsTo(Organization::class);
    // }

    // protected static function booted()
    // {
    //     static::addGlobalScope('organization', function ($builder) {
    //         if (auth()->check()) {
    //             $builder->where('organization_id', auth()->user()->organization_id);
    //         }
    //     });
    // }
}