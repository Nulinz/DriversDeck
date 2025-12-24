<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    // public function location()
    // {
    //     return view('admin.location.location');
    // }


    // public function location()
    // {
    //     $locations = DB::table('location_active')->get();
    //     $data = [];

    //     foreach ($locations as $location) {
    //         $locName = $location->location;

    //         $corporatesCount = DB::table('corporate')
    //             ->where('type', 'corporate')
    //             ->where('location', $locName)
    //             ->where('type', 'approved')
    //             ->count();

    //         $actingDrivers = DB::table('driver')
    //             ->where('type', 'acting')
    //             ->where('location', $locName)
    //             ->where('type', 'approved')
    //             ->count();

    //         $fulltimeDrivers = DB::table('driver')
    //             ->where('type', 'permanent')
    //             ->where('location', $locName)
    //             ->where('type', 'approved')
    //             ->count();

    //         $ownersCount = DB::table('corporate')
    //             ->where('type', 'owner')
    //             ->where('location', $locName)
    //             ->where('type', 'approved')
    //             ->count();

    //         $data[] = [
    //             'location' => $locName,
    //             'status' => $location->status,
    //             'corporates' => $corporatesCount,
    //             'acting_drivers' => $actingDrivers,
    //             'permanent_drivers' => $fulltimeDrivers,
    //             'owners' => $ownersCount,
    //         ];
    //     }

    //     return view('admin.location.location', compact('data'));
    // }

public function location_active(Request $request)
{
    // Fetch all active districts that have at least one active location
    $districts = DB::table('district as d')
        ->join('location_active as l', 'd.id', '=', 'l.district')
        ->where('d.status', 'active')
        ->where('l.status', 'active')
        ->select('d.id as district_id', 'd.district as district_name')
        ->distinct()
        ->orderBy('d.district')
        ->get();

    $result = [];

    foreach ($districts as $district) {
        // Fetch only active locations for this district
        $locations = DB::table('location_active')
            ->where('district', $district->district_id)
            ->where('status', 'active')
            ->select('id', 'location')
            ->orderBy('location')
            ->get();

        $result[] = [
            'district_id'   => $district->district_id,
            'district_name' => $district->district_name,
            'locations'     => $locations
        ];
    }

    return response()->json([
        'message' => 'Active districts with their active locations fetched successfully',
        'data'    => $result
    ]);
}
    public function deactivateLocation($location)
    {
        DB::table('location_active')
            ->where('location', $location)
            ->update([
                'status' => 'inactive',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Location deactivated successfully.');
    }

public function activateLocation($location)
{
    DB::table('location_active')
        ->where('location', $location)
        ->update([
            'status' => 'active',
            'updated_at' => now()
        ]);

    return redirect()->back()->with('success', 'Location activated successfully.');
}

public function location() {
    // Get locations with district names using join
    $locations = DB::table('location_active')
        ->leftJoin('district', 'location_active.district', '=', 'district.id')
        ->select(
            'location_active.*',
            'district.district as district_name'
        )
        ->get();

    $data = $locations->map(function ($location) {
        $locationId = $location->id;
        $locationName = $location->location;

        return [
            'location' => $locationName,
            'status' => $location->status,
            'district' => $location->district_name ?? 'N/A', // Show district name instead of ID
            'corporates' => DB::table('corporate')
                ->where('location', $locationId)
                ->where('type', 'corporate')
                ->count(),
            'acting_drivers' => DB::table('driver')
                ->where('location', $locationId)
                ->where('type', 'acting')
                ->count(),
            'permanent_drivers' => DB::table('driver')
                ->where('location', $locationId)
                ->where('type', 'permanent')
                ->count(),
            'owners' => DB::table('corporate')
                ->where('location', $locationId)
                ->where('type', 'owner')
                ->count(),
        ];
    });

    return view('admin.location.location', ['data' => $data]);
}
}
