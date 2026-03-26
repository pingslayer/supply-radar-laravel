<?php

namespace App\Events;

use App\Models\Disruption;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DisruptionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Disruption $disruption)
    {
    }
}
