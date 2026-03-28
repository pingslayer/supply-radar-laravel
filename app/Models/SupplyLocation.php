<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyLocation extends Model
{
    protected static function booted()
    {
        static::created(function ($location) {
            \App\Jobs\CheckRisksForNewLocation::dispatch($location);
        });
    }

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
