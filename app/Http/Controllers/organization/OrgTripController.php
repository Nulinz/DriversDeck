<?php

namespace App\Http\Controllers\organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trip;
use Exception;

class OrgTripController extends Controller
{
    // public function org_trip_list()
    // {
    //     $trips = Trip::get();

    //         $trips = Trip::with(['hiredApplication.driver'])->get();

    //     return view('organization.trip.trip_list', compact('trips'));
    // }


    public function org_trip_list()
    {
        $corporateId = auth('corporate')->user()->id;

        $trips = Trip::where('c_by', $corporateId) // filter on trip table
            ->with(['hiredApplication.driver'])
            ->get();

        return view('organization.trip.trip_list', compact('trips'));
    }


    
    public function org_trip_profile($id)
    {
        $trip = Trip::with('hiredApplication')->findOrFail($id);
        return view('organization.trip.trip_profile', compact('trip'));
    }

    // API endpoint for updating current location (add this to your API routes)
    public function updateCurrentLocation(Request $request, $id)
    {
        try {
            $trip = Trip::findOrFail($id);

            $request->validate([
                'current_lat' => 'required|numeric',
                'current_lng' => 'required|numeric'
            ]);

            $currentLocation = $request->current_lat . ',' . $request->current_lng;

            $trip->update([
                'crnt_loc' => $currentLocation,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Current location updated successfully',
                'data' => [
                    'current_lat' => $request->current_lat,
                    'current_lng' => $request->current_lng,
                    'updated_at' => $trip->updated_at->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update location: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get current location endpoint
    public function getCurrentLocation($id)
    {
        $trip = Trip::find($id);

        if (!$trip || !$trip->crnt_loc) {
            return response()->json(['success' => false]);
        }

        $coords = explode(',', $trip->crnt_loc);

        return response()->json([
            'success' => true,
            'current_lat' => trim($coords[0]),
            'current_lng' => trim($coords[1])
        ]);
    }
}
