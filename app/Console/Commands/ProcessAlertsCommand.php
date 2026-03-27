<?php

namespace App\Console\Commands;

use App\Jobs\ProcessPendingAlertsJob;
use Illuminate\Console\Command;

class ProcessAlertsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alerts:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all pending alerts and send out notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Dispatching ProcessPendingAlertsJob...');
        
        // Dispatching sync for immediate execution in console 
        // without waiting on a queue daemon
        ProcessPendingAlertsJob::dispatchSync();
        
        $this->info('Pending alerts processed completely.');
    }
}
