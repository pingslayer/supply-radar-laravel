<?php

namespace App\Listeners;

use App\Events\DisruptionCreated;
use App\Models\Alert;
use App\Services\RiskDetectionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DetectRisksForDisruption implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(protected RiskDetectionService $riskDetection)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(DisruptionCreated $event): void
    {
        $disruption = $event->disruption;
        $affectedLocations = $this->riskDetection->detectAffectedLocations($disruption, 1000);

        foreach ($affectedLocations as $location) {
            // Create an alert. We check by company_id and disruption_id 
            // to avoid sending duplicate alerts to the same company for the same event
            // even if they have multiple supply locations affected by it.
            // You can change this to be per location if preferred.
            Alert::firstOrCreate([
                'company_id' => $location->company_id,
                'disruption_id' => $disruption->id,
            ], [
                // Assign a risk score based on distance (closer = higher risk score)
                // 1000km = 0, 0km = 100
                'risk_score' => (int) max(0, 100 - ($location->distance / 10)),
                'status' => 'pending',
            ]);
        }
    }
}
