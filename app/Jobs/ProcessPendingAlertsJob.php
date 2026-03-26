<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Notifications\AlertNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ProcessPendingAlertsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $alerts = Alert::with('company', 'disruption')
            ->where('status', 'pending')
            ->get();

        foreach ($alerts as $alert) {
            // Find users in the company to notify if available
            // Assuming relationship exists on Company
            if (method_exists($alert->company, 'users') && $alert->company->users->isNotEmpty()) {
                Notification::send($alert->company->users, new AlertNotification($alert));
            }

            Log::info("Sent alert for Disruption: {$alert->disruption->title} to Company: {$alert->company->name}");
            
            $alert->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        }
    }
}
