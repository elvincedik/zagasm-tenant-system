<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'code',
        'adresse',
        'phone',
        'country',
        'email',
        'city',
        'tax_number',
        'organization_id',
    ];

    protected $casts = [
        'code' => 'integer',
    ];

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