<?php

namespace App\Console\Commands;

use App\Jobs\IngestDisruptionsJob;
use Illuminate\Console\Command;

class IngestDisruptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'disruptions:ingest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and ingest disruptions from external sources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching IngestDisruptionsJob...');
        
        // Dispatching sync for immediate feedback in console, 
        // though in production we could dispatch to a queue.
        IngestDisruptionsJob::dispatchSync();
        
        $this->info('Disruptions ingestion complete.');
    }
}
