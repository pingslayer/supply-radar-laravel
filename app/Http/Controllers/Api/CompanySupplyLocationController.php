<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanySupplyLocationController extends Controller
{
    /**
     * Get a paginated list of supply locations for a specific company.
     */
    public function index(Request $request, Company $company)
    {
        $locations = $company->supplyLocations()->latest()->paginate(15);
        
        return response()->json($locations);
    }
}
