<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

// use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'organization_id',
        'code',
        'Type_barcode',
        'name',
        'cost',
        'price',
        'unit_id',
        'unit_sale_id',
        'unit_purchase_id',
        'stock_alert',
        'category_id',
        'sub_category_id',
        'is_variant',
        'is_imei',
        'tax_method',
        'image',
        'brand_id',
        'is_active',
        'note',
        'type',
        'warranty_period',
        'warranty_unit',
        'warranty_terms',
        'has_guarantee',
        'guarantee_period',
        'guarantee_unit',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'unit_id' => 'integer',
        'unit_sale_id' => 'integer',
        'unit_purchase_id' => 'integer',
        'is_variant' => 'integer',
        'is_imei' => 'integer',
        'brand_id' => 'integer',
        'is_active' => 'integer',
        'cost' => 'double',
        'price' => 'double',
        'stock_alert' => 'double',
        'TaxNet' => 'double',
        'has_guarantee'   => 'boolean',
    ];

    public function ProductVariant()
    {
        return $this->belongsTo('App\Models\ProductVariant');
    }

    public function PurchaseDetail()
    {
        return $this->belongsTo('App\Models\PurchaseDetail');
    }

    public function SaleDetail()
    {
        return $this->belongsTo('App\Models\SaleDetail');
    }

    public function QuotationDetail()
    {
        return $this->belongsTo('App\Models\QuotationDetail');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit', 'unit_id');
    }

    public function unitPurchase()
    {
        return $this->belongsTo('App\Models\Unit', 'unit_purchase_id');
    }

    public function unitSale()
    {
        return $this->belongsTo('App\Models\Unit', 'unit_sale_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    // Relationship for products that are combined in a combo
    public function combinedProducts()
    {
        return $this->belongsToMany(Product::class, 'combined_products', 'product_id', 'combined_product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('organization', function ($query) {
            if (Auth::check()) {
                $query->where('organization_id', Auth::user()->organization_id);
            }
        });
    }
}