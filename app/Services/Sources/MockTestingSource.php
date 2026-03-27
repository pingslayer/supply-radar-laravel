<?php

namespace App\Services\Sources;

use App\Contracts\DisruptionSource;

class MockTestingSource implements DisruptionSource
{
    public function fetch(): array
    {
        return [
            [
                'title' => 'Port Closure due to Strike',
                'type' => 'Port Closure',
                'description' => 'A major dockworker strike has closed operations at the main regional port.',
                'country' => 'US',
                'latitude' => 34.0522, // e.g. LA Port area
                'longitude' => -118.2437,
                'severity' => 'high',
                'source' => 'Mock Data',
                'reported_at' => now(),
            ],
            [
                'title' => 'Typhoon Warning in Pacific',
                'type' => 'Weather Disaster',
                'description' => 'Severe typhoon approaching the coastline, disrupting shipping lanes.',
                'country' => 'JP',
                'latitude' => 35.6762,
                'longitude' => 139.6503,
                'severity' => 'critical',
                'source' => 'Mock Data',
                'reported_at' => now()->subHours(2),
            ],
            [
                'title' => 'Magnitude 7.2 Earthquake',
                'type' => 'Natural Disaster',
                'description' => 'A massive earthquake hit the greater Tokyo area, halting warehouse operations.',
                'country' => 'JP',
                'latitude' => 35.6800, // Just a few kilometers from the Tokyo Warehouse
                'longitude' => 139.6900,
                'severity' => 'critical',
                'source' => 'Mock Data',
                'reported_at' => now(),
            ]
        ];
    }
}
