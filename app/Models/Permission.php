<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = ['id'];
    protected $fillable = array('name', 'label', 'description', 'organization_id');

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    /**
     * Determine if the permission belongs to the role.
     *
     * @param  mixed $role
     * @return boolean
     */
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