<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Get a paginated list of alerts, optionally filtered by company ID.
     */
    public function index(Request $request)
    {
        $query = Alert::with(['disruption', 'company'])
            ->where('company_id', $request->user()->company_id)
            ->latest();

        $alerts = $query->paginate(15);
        
        return response()->json($alerts);
    }
}
