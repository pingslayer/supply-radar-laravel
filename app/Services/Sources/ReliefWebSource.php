<?php

namespace App\Services\Sources;

use App\Contracts\DisruptionSource;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReliefWebSource implements DisruptionSource
{
    private const API_URL = 'https://api.reliefweb.int/v1/disasters?appname=supply-radar&limit=20&preset=latest';

    public function fetch(): array
    {
        try {
            $response = Http::get(self::API_URL);

            if (!$response->successful()) {
                Log::error("Failed to fetch ReliefWeb API disasters: " . $response->status());
                return [];
            }

            $data = $response->json();
            $disruptions = [];

            foreach ($data['data'] as $entry) {
                // Fetch the detailed description and country details
                $detailResponse = Http::get($entry['href']);
                if ($detailResponse->successful()) {
                    $detailData = $detailResponse->json()['data'][0];
                    $fields = $detailData['fields'];

                    $disruptions[] = [
                        'title' => (string) $fields['name'],
                        'type' => (string) $fields['primary_type']['name'],
                        'description' => (string) ($fields['description'] ?? 'No description available'),
                        'country' => (string) $fields['primary_country']['iso3'] ?? (string) $fields['primary_country']['name'],
                        'latitude' => (float) ($fields['primary_country']['location']['lat'] ?? 0),
                        'longitude' => (float) ($fields['primary_country']['location']['lon'] ?? 0),
                        'severity' => $this->mapSeverity((string) $fields['status']),
                        'source' => 'ReliefWeb',
                        'reported_at' => Carbon::parse((string) $fields['date']['created']),
                    ];
                }
            }

            return $disruptions;

        } catch (\Exception $e) {
            Log::error("Error in ReliefWebSource: " . $e->getMessage());
            return [];
        }
    }

    private function mapSeverity(string $status): string
    {
        return match (strtolower($status)) {
            'alert' => 'critical',
            'current' => 'high',
            'past' => 'low',
            default => 'medium',
        };
    }
}
