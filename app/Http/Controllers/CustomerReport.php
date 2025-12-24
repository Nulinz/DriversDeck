<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Corporate;
use App\Models\Trip;

class CustomerReport extends Controller
{
    // public function customer_report(){
    //     return view('admin.customer.customer_report');
    // }

    public function customer_report()
    {
        // Trip Reports with report_sts = 'pending'
        $tripReports = DB::table('trip_applied')
            ->leftJoin('driver', 'trip_applied.d_id', '=', 'driver.id')
            ->leftJoin('corporate', 'trip_applied.c_by', '=', 'corporate.id')
            ->select(
                'trip_applied.*',
                'driver.name as driver_name',
                'driver.type as driver_type',
                'corporate.name as owner_name'
            )
            // ->whereNotNull('trip_applied.reason')
            // ->where('trip_applied.reason', '!=', '')
            // ->whereNotNull('trip_applied.remarks')
            // ->where('trip_applied.remarks', '!=', '')
            ->where('trip_applied.report_sts', 'pending')
            ->get()->map(function ($lt) {

                $corporateName = Corporate::where('id', Trip::where('id', $lt->trip_id)->value('c_by'))->value('name');

                $lt->owner_name = $corporateName;

                return $lt;
            });

        // dd($tripReports->toArray());
        // Feedback Reports with report_sts = 'pending'
        $feedbackReports = DB::table('feedback')
            ->leftJoin('driver', 'feedback.d_id', '=', 'driver.id')
            ->leftJoin('corporate', 'feedback.c_by', '=', 'corporate.id')
            ->select(
                'feedback.*',
                'driver.name as driver_name',
                'corporate.name as owner_name'
            )
            ->where('feedback.status', 'active')
            ->get();

        // dd($feedbackReports->toArray());

        // Return both to view
        return view('admin.customer.customer_report', compact('tripReports', 'feedbackReports'));
    }


    public function handleCustomerReportAction(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'action' => 'required|in:approve,reject',
        ]);

        DB::table('trip_applied')
            ->where('id', $request->id)
            ->update([
                'report_sts' => $request->action,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Report ' . $request->action . 'd successfully.');
    }


    public function handleCustomerFeedbackAction(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'action' => 'required|in:approve,reject',
        ]);

        DB::table('feedback')
            ->where('id', $request->id)
            ->update([
                'status' => $request->action,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Feedback ' . $request->action . 'd successfully.');
    }
}
