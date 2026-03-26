<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyLocation extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'country',
        'latitude',
        'longitude',
        'industry',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
