<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        "department",
        "department_head",
        'company_id',
        'organization_id',
    ];

    protected $casts = [
        'department_head' => 'integer',
        'company_id' => 'integer',
    ];


    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'department_head');
    }

    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
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