<?php

namespace App\Services;

use App\Models\Disruption;
use App\Models\SupplyLocation;

class RiskDetectionService
{
    /**
     * Find supply locations within a certain radius of the disruption.
     * Default radius is 500 km.
     */
    public function detectAffectedLocations(Disruption $disruption, $radiusKm = 500)
    {
        $lat = $disruption->latitude;
        $lng = $disruption->longitude;

        // Haversine formula to find locations within given distance
        return SupplyLocation::query()
            ->selectRaw("id, company_id, name, latitude, longitude,
                ( 6371 * acos( cos( radians(?) ) *
                  cos( radians( latitude ) ) *
                  cos( radians( longitude ) - radians(?) ) +
                  sin( radians(?) ) *
                  sin( radians( latitude ) ) )
                ) AS distance", [$lat, $lng, $lat])
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance', 'asc')
            ->get();
    }
}
