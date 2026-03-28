<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\SupplyLocation;
use Illuminate\Http\Request;

class SupplyLocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = SupplyLocation::where('company_id', $request->user()->company_id)
            ->latest()
            ->paginate(15);
            
        return response()->json($locations);
    }

    public function show(Request $request, $id)
    {
        $location = SupplyLocation::where('company_id', $request->user()->company_id)
            ->findOrFail($id);
            
        return response()->json($location);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:3',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'industry' => 'nullable|string|max:255',
        ]);

        $location = SupplyLocation::create([
            'company_id' => $request->user()->company_id,
            'name' => $request->name,
            'country' => $request->country,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'industry' => $request->industry,
        ]);

        return response()->json($location, 201);
    }

    public function update(Request $request, $id)
    {
        $location = SupplyLocation::where('company_id', $request->user()->company_id)
            ->findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'country' => 'nullable|string|max:3',
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric',
            'industry' => 'nullable|string|max:255',
        ]);

        $location->update($request->only([
            'name', 'country', 'latitude', 'longitude', 'industry'
        ]));

        return response()->json($location);
    }

    public function destroy(Request $request, $id)
    {
        $location = SupplyLocation::where('company_id', $request->user()->company_id)
            ->findOrFail($id);
            
        $location->delete();

        return response()->json(['message' => 'Supply location deleted successfully']);
    }
}
