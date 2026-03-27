<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disruption;
use Illuminate\Http\Request;

class DisruptionController extends Controller
{
    /**
     * Get a paginated list of disruptions.
     */
    public function index(Request $request)
    {
        $disruptions = Disruption::latest('reported_at')->paginate(15);
        
        return response()->json($disruptions);
    }
}
