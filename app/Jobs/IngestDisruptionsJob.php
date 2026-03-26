<?php

namespace App\Jobs;

use App\Contracts\DisruptionSource;
use App\Models\Disruption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IngestDisruptionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(DisruptionSource $source): void
    {
        $disruptions = $source->fetch();

        foreach ($disruptions as $data) {
            // Using title and source as a naive unique identifier for now
            Disruption::updateOrCreate(
                [
                    'title' => $data['title'],
                    'source' => $data['source'],
                ],
                $data
            );
        }
    }
}
