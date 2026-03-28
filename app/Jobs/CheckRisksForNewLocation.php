<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckRisksForNewLocation implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected \App\Models\SupplyLocation $location)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\RiskDetectionService $riskDetection): void
    {
        $disruptions = $riskDetection->detectDisruptionsForLocation($this->location, 1000);

        foreach ($disruptions as $disruption) {
            \App\Models\Alert::firstOrCreate([
                'company_id' => $this->location->company_id,
                'disruption_id' => $disruption->id,
            ], [
                'risk_score' => (int) max(0, 100 - ($disruption->distance / 10)),
                'status' => 'pending',
            ]);
        }
    }
}
