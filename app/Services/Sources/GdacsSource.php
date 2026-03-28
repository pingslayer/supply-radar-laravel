<?php

namespace App\Services\Sources;

use App\Contracts\DisruptionSource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GdacsSource implements DisruptionSource
{
    private const FEED_URL = 'https://www.gdacs.org/xml/rss.xml';

    public function fetch(): array
    {
        try {
            $response = Http::get(self::FEED_URL);

            if (!$response->successful()) {
                Log::error("Failed to fetch GDACS RSS feed: " . $response->status());
                return [];
            }

            $xml = simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);

            if (!$xml) {
                Log::error("Failed to parse GDACS RSS feed XML.");
                return [];
            }

            $disruptions = [];

            foreach ($xml->channel->item as $item) {
                // GDACS custom fields
                $gdacsMeta = $item->children('http://www.gdacs.org');
                
                // Geo positioning fields (W3C Geo vocabulary)
                $geoMeta = $item->children('http://www.w3.org/2003/01/geo/wgs84_pos#');
                $point = $geoMeta->Point;
                $iso3 = (string) $gdacsMeta->iso3;
                $fallback = $iso3 ? \App\Services\CountryCoordinateProvider::getCoordinates($iso3) : null;

                $disruptions[] = [
                    'title' => (string) $item->title,
                    'type' => $this->mapEventType((string) $gdacsMeta->eventtype),
                    'description' => (string) $item->description,
                    'country' => (string) ($gdacsMeta->country ?? $iso3),
                    'latitude' => (float) ($point->lat ?? $fallback['lat'] ?? 0),
                    'longitude' => (float) ($point->long ?? $fallback['lng'] ?? 0),
                    'severity' => $this->mapSeverity((string) $gdacsMeta->severity),
                    'source' => 'GDACS',
                    'reported_at' => Carbon::parse((string) $item->pubDate),
                ];
            }

            return $disruptions;

        } catch (\Exception $e) {
            Log::error("Error in GdacsSource: " . $e->getMessage());
            return [];
        }
    }

    private function mapEventType(string $type): string
    {
        return match (strtoupper($type)) {
            'EQ' => 'Earthquake',
            'TC' => 'Tropical Cyclone',
            'FL' => 'Flood',
            'VU' => 'Volcano',
            'DR' => 'Drought',
            default => 'Disaster: ' . $type,
        };
    }

    private function mapSeverity(string $severity): string
    {
        // GDACS uses colors for severity
        return match (strtolower($severity)) {
            'red' => 'critical',
            'orange' => 'high',
            'green' => 'low',
            default => 'medium',
        };
    }
}
