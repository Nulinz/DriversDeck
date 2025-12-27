<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Corporate;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverDetail;
use App\Models\TripApplied;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HiredContrller extends Controller
{
    public function hired()
    {
        // Get the current corporate user ID
        $corporateId = auth('corporate')->user()->id;

        // Get hired drivers for this corporate only
        $drivers = DB::table('sub_applied')
            ->join('permanent_jobs', 'sub_applied.p_id', '=', 'permanent_jobs.id')
            ->join('driver', 'sub_applied.d_id', '=', 'driver.id')
            ->where('sub_applied.status', 'Hired')
            ->where('permanent_jobs.c_by', $corporateId) // Use c_by column to filter
            ->select(
                'sub_applied.created_at as joined_date',
                'driver.id as driver_id',
                'driver.name as driver_name',
                'sub_applied.status as driver_status',
                'driver.type as driver_type',
                'driver.phone as contact_number',
                'permanent_jobs.job_location'
            )
            ->orderBy('sub_applied.created_at', 'desc')
            ->get();

        // dd($drivers);

        return view('organization.hired.hired_list', compact('drivers'));
    }


    public function ft_profile($id)
    {
        $driver = DB::table('driver')
            ->leftJoin('driver_details', 'driver.id', '=', 'driver_details.d_id')
            ->leftJoin('license', 'driver.id', '=', 'license.d_id')
            ->leftJoin('bank_withdraw', 'driver.id', '=', 'bank_withdraw.type')
            ->where('driver.id', $id)
            ->select(
                // Driver table fields
                'driver.*',

                // Driver details table fields
                'driver_details.c_ad',
                'driver_details.c_city',
                'driver_details.c_state',
                'driver_details.c_pin',
                'driver_details.about',
                'driver_details.exp_year',
                'driver_details.exp_mon',
                'driver_details.p_com_name',
                'driver_details.rel_date',
                'driver_details.com_location',
                'driver_details.contact_number',
                'driver_details.current_salary',
                'driver_details.pf',
                'driver_details.expert_salary',
                'driver_details.job_loc',
                'driver_details.agreement',
                'driver_details.years',


                // License details
                'license.l_no',
                'license.cof',
                'license.l_img',
                'license.aadhaar',
                'license.aadhaar_img',
                'license.dob',
                'license.cov',
                'license.issued_rto',
                'license.date_of_issue',
                'license.v_from',
                'license.v_to',
                'license.batch_no',
                'license.batch_issue_date',
                'license.batch_issued_by',
                'license.card_serial_no',
                'license.ad_1',
                'license.ad_2',
                'license.city as license_city',
                'license.state as license_state',
                'license.status as license_status',
                'license.c_by',

                // bank details
                'bank_withdraw.type',
                'bank_withdraw.name as holder_name',
                'bank_withdraw.bank as bank_name',
                'bank_withdraw.branch as bank_branch',
                'bank_withdraw.ifsc as bank_ifsc',
                'bank_withdraw.acc_no as bank_acc_no',
                'bank_withdraw.upi_name as bank_upi_name',
                'bank_withdraw.upi_id as bank_upi_id',
            )
            ->first();

        if (!$driver) {
            abort(404, "Driver not found");
        }

        return view('organization.hired.ft_driver_profile', compact('driver'));
    }

    public function at_profile($id)
    {
        $driver = DB::table('driver')
            ->leftJoin('driver_details', 'driver.id', '=', 'driver_details.d_id')
            ->leftJoin('license', 'driver.id', '=', 'license.d_id')
            ->leftJoin('bank_withdraw', 'driver.id', '=', 'bank_withdraw.type')
            ->where('driver.id', $id)
            ->select(
                // Driver table fields
                'driver.*',

                // Driver details table fields
                'driver_details.c_ad',
                'driver_details.c_city',
                'driver_details.c_state',
                'driver_details.c_pin',
                'driver_details.about',
                'driver_details.exp_year',
                'driver_details.exp_mon',
                'driver_details.p_com_name',
                'driver_details.rel_date',
                'driver_details.com_location',
                'driver_details.contact_number',
                'driver_details.current_salary',
                'driver_details.pf',
                'driver_details.expert_salary',
                'driver_details.job_loc',
                'driver_details.agreement',
                'driver_details.years',

                // License details
                'license.l_no',
                'license.l_img',
                'license.aadhaar',
                'license.aadhaar_img',
                'license.dob',
                'license.cov',
                'license.issued_rto',
                'license.date_of_issue',
                'license.v_from',
                'license.v_to',
                'license.batch_no',
                'license.batch_issue_date',
                'license.batch_issued_by',
                'license.card_serial_no',
                'license.ad_1',
                'license.ad_2',
                'license.city as license_city',
                'license.state as license_state',
                'license.status as license_status',
                'license.c_by',

                // bank details
                'bank_withdraw.type',
                'bank_withdraw.name as holder_name',
                'bank_withdraw.bank as bank_name',
                'bank_withdraw.branch as bank_branch',
                'bank_withdraw.ifsc as bank_ifsc',
                'bank_withdraw.acc_no as bank_acc_no',
                'bank_withdraw.upi_name as bank_upi_name',
                'bank_withdraw.upi_id as bank_upi_id',
            )
            ->first();

        $feed = DB::table('feedback')->where('d_id', $id)->where('status', 'approve')->get()->map(function ($lt) {

            $lt->c_name = Corporate::where('id', $lt->c_by)->value('name');

            return $lt;
        });

        if (!$driver) {
            abort(404, "Driver not found");
        }

        return view('organization.hired.at_driver_profile', compact('driver', 'feed'));
    }


    //function to add feedback

    public function add_feedback(Request $request)
    {

        // Get the logged-in driver ID
        $owner = auth('corporate')->user()->id ?? null;

        $d_id =  TripApplied::where('trip_id', $request->trip_id)->where('status', 'End')->first();

        $updated =  DB::table('feedback')->insert([
            'd_id'       => $d_id->d_id,
            't_id'       => $request->trip_id,
            'remarks'    => $request->remarks ?? null,
            'rating'     => $request->rating,
            'c_by'       => $owner,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($updated) {
            return redirect()->back()->with('success', 'Report submitted successfully.');
        } else {
            return redirect()->back()->with('error', 'No record updated. Please check the trip ID.');
        }
    }


    public function report_add(Request $request)
    {

        $user = auth('corporate')->user(); // Owner user

        // Log::info('Owner Auth ID: ' . ($user->id ?? 'none'));

        $d_id = TripApplied::where('trip_id', $request->trip_id)
            ->where(function ($query) {
                $query->where('status', 'End')
                    ->orWhere('status', 'Start');
            })
            ->first();

        $updated = DB::table('trip_applied')
            ->where('trip_id', $request->trip_id)
            ->where('d_id', $d_id->d_id)
            ->update([
                'reason'     => $request->reason,
                'remarks'    => $request->remarks,
                'report_sts'  => 'pending',
                'updated_at' => now(),
            ]);

        if ($updated) {
            return redirect()->back()->with('success', 'Report submitted successfully.');
        } else {
            return redirect()->back()->with('error', 'No record updated. Please check the trip ID.');
        }
    }
}
