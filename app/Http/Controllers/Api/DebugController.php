<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\IngestDisruptionsJob;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function ingest(Request $request)
    {
        IngestDisruptionsJob::dispatchSync();

        return response()->json([
            'message' => 'Disruptions ingestion job dispatched successfully (sync for debugging).'
        ]);
    }
}
