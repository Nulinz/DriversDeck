<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Trip;
use App\Models\TripApplied;
use Carbon\Carbon;
class TripController extends Controller
{
    // public function trip_list(){
    //     return view('admin.trip.trip_list');
    // }

public function trip_list()
{
    $trips = DB::table('trip')
        ->leftJoin('trip_applied', 'trip.id', '=', 'trip_applied.trip_id')
        ->leftJoin('driver', 'trip_applied.d_id', '=', 'driver.id')
        ->leftJoin('corporate', 'trip.c_by', '=', 'corporate.id')
        ->select(
            'trip.id as trip_id',
            'corporate.name as client_name',
            'corporate.type as created_by_type',
            'trip.st_city',
            'trip.end_city',
            'trip.st_date',
            'trip.end_date'
        )
        ->orderByDesc('trip.id')
        ->get()
        ->filter(function ($trip) {
            // Convert dates
            $stDate = Carbon::parse($trip->st_date)->format('Y-m-d');
            $endDate = Carbon::parse($trip->end_date)->format('Y-m-d');
            $today = Carbon::today()->format('Y-m-d');

            // Check if any driver already applied with Hired/Start/End status
            $alreadyApplied = TripApplied::where('trip_id', $trip->trip_id)
                ->whereIn('status', ['Hired', 'Start', 'End'])
                ->exists();

            // Skip if already applied or past trip
            if ($alreadyApplied || $stDate < $today || $endDate < $today) {
                return false;
            }

            return true;
        })
        ->map(function ($lt) {
            $tripApplied = TripApplied::where('trip_id', $lt->trip_id)
                ->whereIn('status', ['Hired', 'Start', 'End'])
                ->with('driver')
                ->first();

            $lt->driver_name = $tripApplied?->driver?->name ?? 'No Driver';
            $lt->gender = $tripApplied?->driver?->gender ?? '';
            $lt->driver_phone = $tripApplied?->driver?->phone ?? '0';

            return $lt;
        })
        ->values(); // Reset keys after filtering

    return view('admin.trip.trip_list', compact('trips'));
}

    //  public function trip_profile(){
    //     return view('admin.trip.trip_profile');
    // }

    public function trip_profile($id)
    {
        $trip = Trip::with(['hiredApplication.driver', 'corporate'])->find($id);

        // dd($trip);

        if (!$trip) {
            $trip = DB::table('trip')
                ->leftJoin('trip_applied', 'trip.id', '=', 'trip_applied.trip_id')
                ->leftJoin('driver', 'trip_applied.d_id', '=', 'driver.id')
                ->leftJoin('corporate', 'trip.c_by', '=', 'corporate.id')
                ->select(
                    'trip.*',
                    'trip_applied.crnt_loc',
                    'driver.name as driver_name',
                    'corporate.name as corporate_name'
                )
                ->where('trip.id', $id)
                ->first();
        }

        if (!$trip) {
            abort(404);
        }

        return view('admin.trip.trip_profile', compact('trip'));
    }
}
