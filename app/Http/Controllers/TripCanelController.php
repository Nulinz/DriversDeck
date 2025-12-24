<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripCanelController extends Controller
{
    // public function tripcanel(){
    //     return view('admin.trip_cancel.tripcanel_list');
    // }

    //   public function tripcanel()
    // {
    //     $reports = DB::table('cancel_req')
    //         ->leftJoin('trip_applied', 'cancel_req.trip_id', '=', 'trip_applied.id')
    //         ->leftJoin('driver', 'trip_applied.d_id', '=', 'driver.id')
    //         ->leftJoin('corporate', 'trip_applied.c_by', '=', 'corporate.id')
    //         ->select(
    //             'cancel_req.*',
    //             'driver.name as driver_name',
    //             'corporate.name as owner_name',
    //             'trip_applied.created_at as trip_date'
    //         )
    //         ->get();

    //     return view('admin.trip_cancel.tripcanel_list', compact('reports'));
    // }


    // public function tripcanel()
    // {
    //     $reports = DB::table('cancel_req')
    //         ->leftJoin('trip_applied', 'cancel_req.trip_id', '=', 'trip_applied.id')
    //         ->leftJoin('driver', 'trip_applied.d_id', '=', 'driver.id')
    //         ->leftJoin('corporate', 'trip_applied.c_by', '=', 'corporate.id')
    //         ->select(
    //             'cancel_req.*',
    //             'driver.name as driver_name',
    //             'corporate.name as owner_name',
    //             'trip_applied.created_at as trip_date'
    //         )
    //         ->where('cancel_req.status', 'request') // only show pending requests
    //         //   ->where('cancel_req.c_type', 'admin') // Only show entries handled by admin
    //         ->get();

    //     return view('admin.trip_cancel.tripcanel_list', compact('reports'));
    // }

    public function tripcanel()
    {
        // List of trip cancel requests that are pending (status = request)
        $reports = DB::table('cancel_req')
            ->leftJoin('trip_applied', 'cancel_req.trip_id', '=', 'trip_applied.id')
            ->leftJoin('driver', 'trip_applied.d_id', '=', 'driver.id')
            ->leftJoin('trip', function ($join) {
                $join->on('trip_applied.trip_id', '=', 'trip.id');
            })

            ->leftJoin('corporate', 'trip.c_by', '=', 'corporate.id')
            ->select(
                'cancel_req.*',
                'driver.name as driver_name',
                'corporate.name as owner_name',
                'trip_applied.created_at as trip_date'
            )
            ->where('cancel_req.status', 'request')
            ->get();

        // List of already approved/rejected by admin
        $handledReports = DB::table('cancel_req')
            ->leftJoin('trip_applied', 'cancel_req.trip_id', '=', 'trip_applied.id')
            ->leftJoin('driver', 'trip_applied.d_id', '=', 'driver.id')
            ->leftJoin('trip', function ($join) {
                $join->on('trip_applied.trip_id', '=', 'trip.id');
            })
            ->leftJoin('corporate', 'trip.c_by', '=', 'corporate.id')
            ->select(
                'cancel_req.*',
                'driver.name as driver_name',
                'corporate.name as owner_name',
                'trip_applied.created_at as trip_date'
            )
            ->where('cancel_req.c_type', 'admin') // Handled by admin
            ->whereIn('cancel_req.status', ['cancel', 'reject']) // Approved or Rejected
            ->get();

        return view('admin.trip_cancel.tripcanel_list', compact('reports', 'handledReports'));
    }



    public function handleTripCancel(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'action' => 'required|in:approve,reject',
        ]);

        $status = $request->action === 'approve' ? 'Cancel' : 'Reject';

        DB::table('cancel_req')
            ->where('id', $request->id)
            ->update([
                'status' => $status,
                'c_type' => 'admin',
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', "Trip cancel request has been {$status}ed successfully!");
    }
}
