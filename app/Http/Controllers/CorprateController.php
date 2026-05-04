<?php

namespace App\Http\Controllers;

use App\Models\Corporate;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\PermanentJobs;
use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\District;


class CorprateController extends Controller
{
    //   public function corprate()
    // {
    //     $corprate_list = Corporate::where('status', 'approved')->get()->map(function ($list) {
    //         $list->loc = DB::table('location_active')->where('id', $list->location)->value('location');
    //         return $list;
    //     });

    //     return view('admin.corprate.corprate_list', compact('corprate_list'));
    // }

    public function corprate(Request $request)
    {
        // Get the type from query parameter
        $type = $request->query('type');

        // Start with base query
        $query = Corporate::where('status', 'approved');

        // Apply filter if type is provided
        if ($type && in_array($type, ['corporate', 'owner'])) {
            $query->where('type', $type);
        }

        // Get the filtered list with location
        $corprate_list = $query->get()->map(function ($list) {
            $list->loc = DB::table('location_active')
                ->where('id', $list->location)
                ->value('location');
            return $list;
        });

        return view('admin.corprate.corprate_list', compact('corprate_list', 'type'));
    }

    // Add new method to toggle active status for corporate
    public function toggleActiveStatus(Request $request, $id)
    {
        try {
            $corporate = Corporate::findOrFail($id);
            $corporate->active_status = $request->active_status;
            $corporate->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully',
                'new_status' => $corporate->active_status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update active status'
            ], 500);
        }
    }

    // Optional: Add method to get corporates by active status
    public function getCorporatesByActiveStatus($status)
    {
        $corporates = Corporate::where('active_status', $status)
            ->get()->map(function ($list) {
                $list->loc = DB::table('location_active')->where('id', $list->location)->value('location');
                return $list;
            });

        return $corporates;
    }


    public function toggleStatus(Request $request, $id)
    {
        $corprate = Corporate::findOrFail($id);
        $corprate->status = $request->status;
        $corprate->save();

        return response()->json(['success' => true, 'status' => $corprate->status]);
    }

    public function corprate_profile($id)
    {
        // Get corporate details
        $corprate = Corporate::where('type', 'corporate')
            ->where('id', $id)
            ->first();

        if (!$corprate) {
            return redirect()->route('admin.corprate.corprate_list')
                ->with('error', 'Corporate profile not found');
        }

        // Get Acting Jobs (from trips table)
        $actingJobs = Trip::where('c_by', $id)
            ->where('status', 'pending')
            ->select('id', 'veh_type', 'st_loc', 'st_dest', 'st_date as start_date', 'end_date', 'status')
            ->orderBy('created_at', 'DESC')
            ->get();

        // Get Permanent Jobs
        $permanentJobs = PermanentJobs::where('c_by', $id)
            ->where('status', 'approve')
            ->select('id', 'veh_type', 'job_location', 'join_date', 'aggrement', 'min_salary', 'max_salary', 'status')
            ->orderBy('created_at', 'DESC')
            ->get();

        // Get Hired Drivers from Acting Jobs
        $hiredActingJobs = Trip::join('trip_applied', 'trip.id', '=', 'trip_applied.trip_id')
            ->join('driver', 'trip_applied.d_id', '=', 'driver.id')
            ->where('trip.c_by', $id)
            ->whereIn('trip_applied.status', ['Hired', 'Start', 'End'])
            ->select(
                'driver.name',
                'trip.veh_type as vehicle_type',
                'trip.st_loc',
                'trip.st_dest',
                'trip.st_date as st_date',
                'trip_applied.trip_code as t_code',
                DB::raw("'act' as source"),
                'trip_applied.created_at'
            )
            ->get();

        // Get Hired Drivers from Permanent Jobs
        $hiredPermanentJobs = PermanentJobs::join('sub_applied', 'permanent_jobs.id', '=', 'sub_applied.p_id')
            ->join('driver', 'sub_applied.d_id', '=', 'driver.id')
            ->where('permanent_jobs.c_by', $id)
            ->where('sub_applied.status', 'Hired')
            ->select(
                'driver.name',
                'permanent_jobs.veh_type as vehicle_type',
                'permanent_jobs.job_location',
                DB::raw("'per' as source"),
                'sub_applied.created_at'
                // 'permanent_jobs.join_date as j_date',
                // 'sub_applied.created_at as join_date'
            )
            ->get();


        $hire = $hiredActingJobs->concat($hiredPermanentJobs)->sortByDesc('created_at');

        // dd($hire);
        // Get Subscriptions
        $subscriptions = Subscription::where('type', 'corporate')
            ->where('f_id', $id)
            ->where('status', 'active')
            ->select('id', 'plan', 'paid_sts', 'created_at')
            ->get();

        return view('admin.corprate.corprate_profile', compact(
            'corprate',
            'actingJobs',
            'permanentJobs',
            'hire',
            'hiredActingJobs',
            'hiredPermanentJobs',
            'subscriptions'
        ));
    }
    public function update_corporate_profile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'c_name' => 'required|string|max:255',
            'c_num' => 'required|string|max:15',
            'a_num' => 'nullable|string|max:15',
            'c_mail' => 'required|email|max:255',
            'ad_1' => 'required|string|max:255',
            'ad_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pan' => 'nullable|string|max:10',
            'gst' => 'nullable|string|max:15',
            'no_vac' => 'required|integer|min:0',
            'no_veh' => 'required|integer|min:0',
            'no_driver' => 'required|integer|min:0',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $corprate = Corporate::where('type', 'corporate')
            ->where('id', $id)
            ->first();

        if (!$corprate) {
            return redirect()->route('admin.corprate.corprate_list')
                ->with('error', 'Corporate profile not found');
        }

        // Prepare update data
        $updateData = [
            'name' => $request->name,
            'c_name' => $request->c_name,
            'c_num' => $request->c_num,
            'a_num' => $request->a_num,
            'c_mail' => $request->c_mail,
            'ad_1' => $request->ad_1,
            'ad_2' => $request->ad_2,
            'city' => $request->city,
            'state' => $request->state,
            'pan' => $request->pan,
            'gst' => $request->gst,
            'no_vac' => $request->no_vac,
            'no_veh' => $request->no_veh,
            'no_driver' => $request->no_driver,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($corprate->logo && file_exists(public_path($corprate->logo))) {
                unlink(public_path($corprate->logo));
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('image/corporate/logo'), $logoName);
            $updateData['logo'] = 'image/corporate/logo/' . $logoName;
        }

        // Update corporate details
        $corprate->update($updateData);

        return redirect()->route('admin.corprate.corprate_profile', $id)
            ->with('success', 'Corporate profile updated successfully');
    }



    //  public function owner_profile($id){
    //   $corprate = Corporate::where('type', 'owner')
    //   ->where('id', $id)
    //   ->first();
    //   return view('admin.corprate.owner_profile', compact('corprate'));
    // }


    public function owner_profile($id)
    {
        $corprate = Corporate::where('corporate.type', 'owner')
            ->where('corporate.id', $id)
            ->leftJoin(DB::raw('
        (SELECT *
         FROM bank_withdraw bw1
         WHERE bw1.type = "owner"
         AND bw1.created_at = (
             SELECT MAX(bw2.created_at)
             FROM bank_withdraw bw2
             WHERE bw2.d_id = bw1.d_id AND bw2.type = "owner"
         )
         ) AS bank_withdraw
        '), 'corporate.id', '=', 'bank_withdraw.d_id')
            ->leftJoin('subscription', 'corporate.id', '=', 'subscription.f_id')
            ->select(
                'corporate.*',
                'bank_withdraw.type as bank_type',
                'bank_withdraw.name as holder_name',
                'bank_withdraw.bank as bank_name',
                'bank_withdraw.branch as bank_branch',
                'bank_withdraw.ifsc as bank_ifsc',
                'bank_withdraw.acc_no as bank_acc_no',
                'bank_withdraw.upi_name as bank_upi_name',
                'bank_withdraw.upi_id as bank_upi_id',
                'subscription.plan as sub_plan',
                'subscription.created_at as sub_date',
                'subscription.status as sub_status',

            )
            ->first();

        // dd($corprate->toArray());

        $subs = Subscription::where('f_id', $id)->where('type', 'owner')->get();


        $trips = Trip::where('c_by', $id)->get()->map(function ($trip) {
            $appliedDrivers = DB::table('trip_applied')
                ->where('trip_id', $trip->id)
                ->whereIn('status', ['Hired', 'Start', 'End'])
                ->value('d_id');

            $dr = Driver::find($appliedDrivers);

            if ($dr) {
                $trip->applied_drivers = $dr->name;
            } else {
                $trip->applied_drivers = 'No driver';
            }

            return $trip;
        });

        // dd($trips->toArray());



        // Fetch referrals
        $referrals = DB::table('referal')
            ->leftJoin('driver', function ($join) {
                $join->on('referal.f_id', '=', 'driver.id')
                    ->where('referal.f_type', '=', 'acting'); // use correct DB value
            })
            ->leftJoin('corporate', function ($join) {
                $join->on('referal.f_id', '=', 'corporate.id')
                    ->where('referal.f_type', '=', 'owner'); // if owner refers corporate
            })
            ->where('referal.ref_by', $id)
            ->select(
                'referal.id',
                'referal.f_type',
                'referal.created_at',
                'driver.name as driver_name',
                'driver.phone as driver_phone',
                'driver.location as driver_location',
                'corporate.name as corporate_name',
                'corporate.contact as corporate_contact',
                'corporate.location as corporate_location'
            )
            ->get();

        //  Debug log each referral
        // foreach ($referrals as $ref) {
        //     Log::info('Referral record:', (array)$ref);
        // }

        $own = Corporate::where('id', $id)->first();

        $withdraws = DB::table('bank_withdraw')
            ->where('type', $own->type)
            ->where('d_id', $own->id)
            ->orderBy('created_at', 'desc')
            ->select('created_at', 'amt', 'status', 'name', 'bank', 'branch', 'ifsc', 'acc_no', 'upi_name', 'upi_id')
            ->get();

        $data = DB::table('referal')
            ->where('code', $own->ref_code)
            ->selectRaw('COUNT(*) as count, SUM(amt) as balance')
            ->first();

        $count = $data->count;
        $balance = $data->balance;

        $with_balance = $withdraws->where('status', 'approved')->sum('amt');

        $rem = $balance - $with_balance;


        return view('admin.corprate.owner_profile', compact('corprate', 'trips', 'subs', 'referrals', 'rem'));
    }

    public function update_owner_profile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'gender' => 'required|string|max:10',
            'ad_1' => 'required|string|max:255',
            'ad_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $corporate = Corporate::findOrFail($id);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($corporate->logo && file_exists(public_path($corporate->logo))) {
                unlink(public_path($corporate->logo));
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('image/corporate/logo'), $logoName);
            $corporate->logo = 'image/corporate/logo/' . $logoName;
        }

        // Update other fields
        $corporate->name = $request->name;
        $corporate->contact = $request->contact;
        $corporate->gender = $request->gender;
        $corporate->ad_1 = $request->ad_1;
        $corporate->ad_2 = $request->ad_2;
        $corporate->city = $request->city;
        $corporate->state = $request->state;

        $corporate->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }



    // public function owner_profile($id){
    //     $corprate = Corporate::where('corporate.type', 'owner')
    //         ->where('corporate.id', $id)

    //         // Join bank_withdraw
    //         ->leftJoin('bank_withdraw', function($join) {
    //             $join->on('corporate.id', '=', 'bank_withdraw.d_id')
    //                  ->where('bank_withdraw.type', '=', 'owner');
    //         })

    //         // Join trip table (assuming trip.c_by = corporate.id)
    //         ->leftJoin('trip', 'corporate.id', '=', 'trip.c_by')

    //         // Join subscription table (assuming subscription.f_id = corporate.id)
    //         ->leftJoin('subscription', 'corporate.id', '=', 'subscription.f_id')

    //         ->select(
    //             'corporate.*',

    //             // Bank withdraw details
    //             'bank_withdraw.name as holder_name',
    //             'bank_withdraw.bank as bank_name',
    //             'bank_withdraw.branch as bank_branch',
    //             'bank_withdraw.ifsc as bank_ifsc',
    //             'bank_withdraw.acc_no as bank_acc_no',
    //             'bank_withdraw.upi_name as bank_upi_name',
    //             'bank_withdraw.upi_id as bank_upi_id',

    //             // Trip details
    //             'trip.plan as trip_plan',
    //             'trip.amount as trip_amount',
    //             'trip.paid_sts as trip_paid_status',
    //             'trip.exp_date as trip_expiry',

    //             // Subscription details
    //             'subscription.plan as sub_plan',
    //             'subscription.amount as sub_amount',
    //             'subscription.paid_sts as sub_paid_status',
    //             'subscription.exp_date as sub_expiry'
    //         )
    //         ->first();

    //     return view('admin.corprate.owner_profile', compact('corprate'));
    // }



    public function create()
    {
        // Get all active districts from location_active and join with district table to get district names
        $districts = DB::table('location_active')
            ->leftJoin('district', 'location_active.district', '=', 'district.id')
            ->where('location_active.status', 'active')
            ->select('location_active.district as district_id', 'district.district as district_name')
            ->distinct('location_active.district')
            ->orderBy('district.district')
            ->get();

        return view('admin.corprate.create', compact('districts'));
    }

    public function get_locations_by_district(Request $request)
    {
        $districtId = $request->district;

        // Get locations for this district ID
        $locations = DB::table('location_active')
            ->where('district', $districtId)
            ->where('status', 'active')
            ->select('id', 'location')
            ->orderBy('location')
            ->get();

        return response()->json($locations);
    }

    public function store(Request $request)
    {
        // Validate all fields
        $request->validate([
            // Basic Details
            'c_type' => 'required|string|max:255',
            'c_name' => 'required|string|max:255',
            'c_contact' => 'required|string|max:10|min:10|unique:corporate,contact',
            'c_email' => 'required|email|max:255',
            'c_district' => 'required',
            'c_loc' => 'required',
            'gender' => 'nullable|string|max:50',

            // Contact Person Details
            'full_name' => 'required|string|max:255',
            'full_contact' => 'required|string|max:10|min:10',
            'alt_contact' => 'nullable|string|max:10|min:10',
            'f_mail' => 'required|email|max:255',

            // Address Details
            'ad_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pin_code' => 'required|digits:6',

            // Business Details
            'pan' => 'nullable|string|max:10|min:10',
            'gst' => 'nullable|string|max:15|min:15',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Asset Details
            'no_vehicle' => 'required|integer|min:1',
            'no_drivers' => 'required|integer|min:1',
            'no_vacancies' => 'required|integer|min:0'
        ]);

        // Validate that the selected location belongs to the selected district
        $locationExists = DB::table('location_active')
            ->where('id', $request->c_loc)
            ->where('district', $request->c_district)
            ->where('status', 'active')
            ->exists();

        if (!$locationExists) {
            return redirect()->back()->withErrors(['Invalid district/location combination selected'])->withInput();
        }

        // Start database transaction
        DB::beginTransaction();

        try {
            // Prepare data for insertion
            $data = [
                'type' => 'corporate',
                'name' => $request->c_name,
                'c_type' => $request->c_type,
                'location' => $request->c_loc,
                'district' => $request->c_district, // Store the district ID
                'contact' => $request->c_contact,
                'mail' => $request->c_email,
                'gender' => $request->gender,

                // Contact Person Details
                'c_name' => $request->full_name,
                'c_num' => $request->full_contact,
                'a_num' => $request->alt_contact,
                'c_mail' => $request->f_mail,

                // Address Details
                'ad_1' => $request->ad_1,
                'city' => $request->city,
                'state' => $request->state,
                'pin' => $request->pin_code,

                // Business Details
                'pan' => strtoupper($request->pan),
                'gst' => strtoupper($request->gst),

                // Asset Details
                'no_veh' => $request->no_vehicle,
                'no_driver' => $request->no_drivers,
                'no_vac' => $request->no_vacancies,

                // Default Values
                'ref_code' => '123',
                'subscription' => 'yes',

                'c_by' => auth()->id(), // Admin who created this record
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $filename = time() . '_' . $image->getClientOriginalName();

                // Create directory if it doesn't exist
                $uploadPath = public_path('image/corporate/logo');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $image->move($uploadPath, $filename);
                $data['logo'] = 'public/image/corporate/logo/' . $filename;
            }

            // Insert the corporate data
            $corporate = Corporate::create($data);

            if ($corporate) {
                // Create default subscription for 6 months
                $subscriptionData = [
                    'f_id' => $corporate->id,
                    'type' => 'corporate',
                    'plan' => '6',
                    't_id' => null,
                    'amount' => 15000,
                    'paid_sts' => 'success',
                    'exp_date' => Carbon::now()->addMonths(6),
                    'status' => 'active',
                    'c_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $subscription = Subscription::create($subscriptionData);

                if ($subscription) {
                    // Commit the transaction
                    DB::commit();

                    Log::info("Corporate and Subscription created by admin", [
                        'corporate_id' => $corporate->id,
                        'corporate_name' => $corporate->name,
                        'subscription_id' => $subscription->id,
                        'exp_date' => $subscription->exp_date->format('Y-m-d'),
                        'created_by' => auth()->id()
                    ]);

                    return redirect()->route('admin.corprate.index')->with('success', 'Corporate added successfully with 6-month subscription!');
                } else {
                    DB::rollback();
                    return redirect()->back()->withErrors(['Failed to create subscription'])->withInput();
                }
            } else {
                DB::rollback();
                return redirect()->back()->withErrors(['Failed to save corporate details'])->withInput();
            }
        } catch (\Exception $e) {
            DB::rollback();

            Log::error("Error creating corporate and subscription", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withErrors(['An error occurred while saving corporate details. Please try again.'])
                ->withInput();
        }
    }


    public function index()
    {
        // Get all corporate records created by admin (c_by = 1) - both active and inactive
        $corporates = DB::table('corporate')
            ->where('c_by', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.corprate.index', compact('corporates'));
    }

    public function toggleStatus1($id)
    {
        try {
            $corporate = DB::table('corporate')->where('id', $id)->first();

            if (!$corporate) {
                return response()->json(['success' => false, 'message' => 'Corporate not found']);
            }

            $newStatus = ($corporate->active_status === 'active') ? 'inactive' : 'active';

            DB::table('corporate')
                ->where('id', $id)
                ->update([
                    'active_status' => $newStatus,
                    'updated_at'    => now()
                ]);

            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating status']);
        }
    }
    public function exportCorporateData(Request $request)
    {
        try {
            $type = $request->query('type');
            $status = $request->query('status', 'approved'); // approved or rejected

            // Build query - only from corporate table
            $query = DB::table('corporate')
                ->where('status', $status);

            // Apply type filter if provided (corporate or owner)
            if ($type && in_array($type, ['corporate', 'owner'])) {
                $query->where('type', $type);
            }

            // Get all corporate data
            $corporates = $query->select(
                'id',
                'type',
                'name',
                'gender',
                'location',
                'district',
                'ref_code',
                'c_type',
                'c_name',
                'contact',
                'mail',
                'c_num',
                'a_num',
                'c_mail',
                'ad_1',
                'ad_2',
                'city',
                'state',
                'pin',
                'pan',
                'gst',
                'no_veh',
                'no_driver',
                'no_vac',
                'subscription',
                'logo',
                'status',
                'active_status',
                'created_at',
                'updated_at'
            )->get()->map(function ($corporate) {
                // Get location name if location_active table exists
                $locationName = DB::table('location_active')
                    ->where('id', $corporate->location)
                    ->value('location');

                $corporate->location_name = $locationName ?? 'N/A';

                return $corporate;
            });

            return response()->json([
                'success' => true,
                'data' => $corporates
            ]);
        } catch (\Exception $e) {
            \Log::error('Export Corporate Data Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch corporate data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function corprateDelete($id)
    {

        $deleteCorp = Corporate::findOrFail($id);
        $deleteCorp->delete();

        return back()->with('success', 'Driver deleted successfully.');
    }
}
