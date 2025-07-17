<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'date',
        'Ref',
        'sale_id',
        'delivered_to',
        'shipping_address',
        'status',
        'shipping_details',
        'organization_id',

    ];

    protected $casts = [
        'user_id' => 'integer',
        'sale_id' => 'integer',
    ];


    public function sale()
    {
        return $this->belongsTo('App\Models\Sale');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
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