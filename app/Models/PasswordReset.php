<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{

    protected $fillable = [
        'email',
        'token',
        'created_at',
        'updated_at',
        'organization_id',
    ];


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