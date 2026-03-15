<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class disruption extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'description',
        'country',
        'latitude',
        'longitude',
        'severity',
        'source',
        'reported_at',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
    ];

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
