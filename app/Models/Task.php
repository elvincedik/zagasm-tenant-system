<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'project_id',
        'start_date',
        'end_date',
        'company_id',
        'description',
        'status',
        'organization_id',

    ];

    protected $casts = [
        'project_id'  => 'integer',
        'company_id'  => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }

    public function company()
    {
        return $this->hasOne('App\Models\Company', 'id', 'company_id');
    }

    public function assignedEmployees()
    {
        return $this->belongsToMany('App\Models\Employee');
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