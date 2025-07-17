<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{

    protected $fillable = [
        'user_id',
        'description',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
        'organization_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function expense()
    {
        return $this->belongsTo('App\Models\Expense');
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