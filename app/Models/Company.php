<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plan',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function supplyLocations()
    {
        return $this->hasMany(SupplyLocation::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
