<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'date',
        'Ref',
        'provider_id',
        'warehouse_id',
        'GrandTotal',
        'time',
        'discount',
        'shipping',
        'statut',
        'notes',
        'TaxNet',
        'tax_rate',
        'paid_amount',
        'payment_statut',
        'created_at',
        'updated_at',
        'deleted_at',
        'organization_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'provider_id' => 'integer',
        'warehouse_id' => 'integer',
        'GrandTotal' => 'double',
        'discount' => 'double',
        'shipping' => 'double',
        'TaxNet' => 'double',
        'tax_rate' => 'double',
        'paid_amount' => 'double',
    ];

    public function details()
    {
        return $this->hasMany('App\Models\PurchaseDetail');
    }

    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }

    public function facture()
    {
        return $this->hasMany('App\Models\PaymentPurchase');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
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