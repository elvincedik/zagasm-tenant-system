<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'account_num',
        'account_name',
        'initial_balance',
        'balance',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
        'organization_id',
    ];

    protected $casts = [
        'initial_balance' => 'double',
        'balance' => 'double',
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