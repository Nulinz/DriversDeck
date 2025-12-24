<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Driver;
use App\Models\Corporate;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class Api_trip extends Controller
{


   public function getNearbyTrips(Request $request)
{
    $request->validate([
        'location_id' => 'required|exists:location_active,id',
    ]);

    // Get selected location coordinates
    $location = DB::table('location_active')->where('id', $request->location_id)->first();

    if (!$location || !$location->cord || !str_contains($location->cord, ',')) {
        return response()->json(['message' => 'Invalid or missing coordinates'], 400);
    }

    [$lat1, $lon1] = explode(',', $location->cord);
    $radius = 40; // in km

    // Nearby by start location
    $nearbyStart = Trip::where('status', 'pending')
        ->whereNotNull('st_cord')
        ->whereRaw("st_cord LIKE '%,%'")
        ->select('*')
        ->selectRaw("(
            6371 * acos(
                cos(radians(?)) *
                cos(radians(SUBSTRING_INDEX(st_cord, ',', 1))) *
                cos(radians(SUBSTRING_INDEX(st_cord, ',', -1)) - radians(?)) +
                sin(radians(?)) *
                sin(radians(SUBSTRING_INDEX(st_cord, ',', 1)))
            )
        ) AS distance", [$lat1, $lon1, $lat1])
        ->having('distance', '<=', $radius)
        ->get();

    // Nearby by destination location
    $nearbyEnd = Trip::where('status', 'pending')
        ->whereNotNull('dest_cord')
        ->whereRaw("dest_cord LIKE '%,%'")
        ->select('*')
        ->selectRaw("(
            6371 * acos(
                cos(radians(?)) *
                cos(radians(SUBSTRING_INDEX(dest_cord, ',', 1))) *
                cos(radians(SUBSTRING_INDEX(dest_cord, ',', -1)) - radians(?)) +
                sin(radians(?)) *
                sin(radians(SUBSTRING_INDEX(dest_cord, ',', 1)))
            )
        ) AS distance", [$lat1, $lon1, $lat1])
        ->having('distance', '<=', $radius)
        ->get();

    // Merge both collections and remove duplicates
    $nearbyTrips = $nearbyStart->merge($nearbyEnd)->unique('id')->values();

    return response()->json([
        'location' => $location->location,
        'total_nearby_trips' => count($nearbyTrips),
        'trips' => $nearbyTrips
    ]);
}


}