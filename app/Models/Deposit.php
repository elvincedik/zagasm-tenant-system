<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'account_id',
        'deposit_category_id',
        'amount',
        'date',
        'deposit_ref',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
        'organization_id',
    ];

    protected $casts = [
        'user_id'  => 'integer',
        'account_id'  => 'integer',
        'deposit_category_id'  => 'integer',
        'amount' => 'double',
    ];


    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function account()
    {
        return $this->hasOne('App\Models\Account', 'id', 'account_id');
    }

    public function deposit_category()
    {
        return $this->hasOne('App\Models\DepositCategory', 'id', 'deposit_category_id');
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