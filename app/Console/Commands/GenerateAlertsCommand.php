<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Disruption;
use App\Models\SupplyLocation;
use App\Models\Alert;

class GenerateAlertsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate risk alerts for existing disruptions and supply locations.';

    /**
     * Execute the console command.
     */
    public function handle(\App\Services\RiskDetectionService $riskDetection)
    {
        $this->info('Starting risk alert generation for existing data...');

        $disruptions = Disruption::where('reported_at', '>=', now()->subDays(30))->get();
        $totalAlerts = 0;

        foreach ($disruptions as $disruption) {
            $affectedLocations = $riskDetection->detectAffectedLocations($disruption, 1000);

            foreach ($affectedLocations as $location) {
                $alert = Alert::firstOrCreate([
                    'company_id' => $location->company_id,
                    'disruption_id' => $disruption->id,
                ], [
                    'risk_score' => (int) max(0, 100 - ($location->distance / 10)),
                    'status' => 'pending',
                ]);

                if ($alert->wasRecentlyCreated) {
                    $totalAlerts++;
                }
            }
        }

        $this->info("Finished! Generated {$totalAlerts} new alerts.");
    }
}
