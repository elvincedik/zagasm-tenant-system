<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $fillable = [
        'currency_id',
        'email',
        'CompanyName',
        'CompanyPhone',
        'CompanyAdress',
        'quotation_with_stock',
        'logo',
        'footer',
        'developed_by',
        'client_id',
        'warehouse_id',
        'default_language',
        'show_language',
        'is_invoice_footer',
        'invoice_footer',
        'app_name',
        'favicon',
        'page_title_suffix',
        'organization_id',
    ];

    protected $casts = [
        'currency_id' => 'integer',
        'client_id' => 'integer',
        'quotation_with_stock' => 'integer',
        'show_language' => 'integer',
        'is_invoice_footer' => 'integer',
        'warehouse_id' => 'integer',
    ];

    public function Currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    public function Client()
    {
        return $this->belongsTo('App\Models\Client');
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