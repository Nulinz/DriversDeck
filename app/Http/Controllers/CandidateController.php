<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\DriverDetail;
use Illuminate\Support\Facades\DB;
use App\Models\DriverTypeChangeRequest;

class CandidateController extends Controller
{

    public function candidate(Request $request)
    {
        // Get the type from query parameter
        $type = $request->query('type');

        // Build approved drivers query
        $approvedQuery = DB::table('driver as d')
            ->leftJoin('driver_details as dd', 'd.id', '=', 'dd.d_id')
            ->leftJoin('license as l', 'd.id', '=', 'l.d_id')
            ->where('d.status', 'approved');

        // Apply type filter if provided
        if ($type && in_array($type, ['acting', 'permanent'])) {
            $approvedQuery->where('d.type', $type);
        }

        // Get approved drivers with proper aliasing
        $approvedDrivers = $approvedQuery
            ->select(
                'd.id as driver_id',
                'd.name',
                'd.phone',
                'd.type',
                'd.active_status',
                'd.location',
                'd.created_at',
                'dd.*',
                'l.cov'
            )
            ->get()
            ->map(function ($list) {
                // Use driver_id instead of id
                $list->id = $list->driver_id;
                $list->loc = DB::table('location_active')->where('id', $list->location)->value('location');
                return $list;
            });
        // Build rejected drivers query
        $rejectedQuery = DB::table('driver as d')
            ->leftJoin('driver_details as dd', 'd.id', '=', 'dd.d_id')
            ->leftJoin('license as l', 'd.id', '=', 'l.d_id')
            ->where('d.status', 'rejected');

        // Apply type filter if provided
        if ($type && in_array($type, ['acting', 'permanent'])) {
            $rejectedQuery->where('d.type', $type);
        }

        // Get rejected drivers with proper aliasing
        $rejectedDrivers = $rejectedQuery
            ->select(
                'd.id as driver_id',
                'd.name',
                'd.phone',
                'd.type',
                'd.active_status',
                'd.location',
                'd.created_at',
                'dd.*',
                'l.cov'
            )
            ->get()
            ->map(function ($list) {
                // Use driver_id instead of id
                $list->id = $list->driver_id;
                $list->loc = DB::table('location_active')->where('id', $list->location)->value('location');
                return $list;
            });

        $pendingQuery = DB::table('driver as d')
            ->leftJoin('driver_details as dd', 'd.id', '=', 'dd.d_id')
            ->leftJoin('license as l', 'd.id', '=', 'l.d_id')
            ->where('d.status', 'pending');

        // Keep type filter if needed
        if ($type && in_array($type, ['acting', 'permanent'])) {
            $pendingQuery->where('d.type', $type)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('approval_reasons as ar')
                        ->whereColumn('ar.user_id', 'd.id')
                        ->where('ar.action', 'pending');
                });
        }

        $pendingDrivers = $pendingQuery
            ->select(
                'd.id as driver_id',
                'd.name',
                'd.phone',
                'd.type',
                'd.active_status',
                'd.location',
                'd.created_at',
                'dd.*',
                'l.cov'
            )
            ->get()
            ->map(function ($list) {
                $list->id = $list->driver_id;
                $list->loc = DB::table('location_active')->where('id', $list->location)->value('location');
                return $list;
            });

        // Get unique COV values
        $covValues = DB::table('license')
            ->select('cov')
            ->whereNotNull('cov')
            ->pluck('cov')
            ->toArray();
        // Flatten comma-separated values into array
        $allCov = [];
        foreach ($covValues as $cov) {
            $items = array_map('trim', explode(',', $cov));
            $allCov = array_merge($allCov, $items);
        }
        $allCov = array_unique($allCov);
        return view('admin.candidate.index', compact(
            'approvedDrivers',
            'rejectedDrivers',
            'pendingDrivers',
            'allCov',
            'type'
        ));
    }


    // Add new method to toggle active status
    public function toggleActiveStatus(Request $request, $id)
    {
        try {
            $driver = Driver::findOrFail($id);
            $driver->active_status = $request->active_status;
            $driver->save();

            return response()->json([
                'success' => true,
                'message' => 'Active status updated successfully',
                'new_status' => $driver->active_status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update active status'
            ], 500);
        }
    }

    // Optional: Add method to get drivers by active status
    public function getDriversByActiveStatus($status)
    {
        $drivers = Driver::where('active_status', $status)
            ->with('details')
            ->get()->map(function ($list) {
                $list->loc = DB::table('location_active')->where('id', $list->location)->value('location');
                return $list;
            });

        return $drivers;
    }

    public function toggleStatus(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->status = $request->status;
        $driver->save();

        return response()->json(['success' => true, 'status' => $driver->status]);
    }



    public function profile($id)
    {

        $dr = Driver::find($id);

        // $latestWithdraw = DB::table('bank_withdraw')
        //     ->where('type', $dr->type)
        //     ->where('d_id', $dr->id)
        //     ->orderByDesc('id') // or 'created_at'
        //     ->limit(1);


        $driver = DB::table('driver')
            ->leftJoin('driver_details', 'driver.id', '=', 'driver_details.d_id')
            ->leftJoin('license', 'driver.id', '=', 'license.d_id')
            // ->leftJoin('bank_withdraw', 'driver.id', '=', 'bank_withdraw.type')
            ->leftJoin('bank_withdraw', function ($join) {
                $join->on('driver.id', '=', 'bank_withdraw.d_id')
                    ->on('driver.type', '=', 'bank_withdraw.type');
            })

            ->where('driver.id', $id)
            // ->where('driver.status', 'active')
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
                'license.dob',
                'license.aadhaar_img',
                'license.cov',
                'license.cof',
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

        // dd($driver);
        if (!$driver) {
            abort(404, "Active Driver not found");
        }


        //  Fetch all trips this driver is hired for
        $hiredTrips = DB::table('trip_applied')
            ->join('trip', 'trip_applied.trip_id', '=', 'trip.id')
            ->leftJoin('corporate', 'trip.c_by', '=', 'corporate.id')
            ->where('trip_applied.d_id', $id)
            ->whereIn('trip_applied.status', ['Hired', 'Start', 'End'])
            ->select(
                'trip.id as trip_id',
                'corporate.name as client_name',
                'trip.st_city',
                'trip.end_city',
                'trip.st_date',
                'trip.end_date'
            )
            ->get();


        $feedbacks = DB::table('feedback')
            ->leftJoin('corporate', 'feedback.c_by', '=', 'corporate.id')
            ->where('feedback.d_id', $id)
            ->select(
                'corporate.name as client_name',
                'feedback.remarks',
                'feedback.rating',
                'feedback.created_at'
            )
            ->get();


        $subscriptions = DB::table('subscription')
            ->where('f_id', $id) // Use correct column name here
            ->select('plan', 'created_at', 'status')
            ->get();



        // $referrals = DB::table('referal')
        //     ->leftJoin('driver', function ($join) {
        //         $join->on('referal.f_id', '=', 'driver.id')
        //             ->where('referal.f_type', '=', 'acting'); // Ensure value matches your DB
        //     })
        //     ->leftJoin('corporate', function ($join) {
        //         $join->on('referal.f_id', '=', 'corporate.id')
        //             ->where('referal.f_type', '=', 'owner'); // adjust as needed
        //     })
        //     ->where('referal.code', $dr->ref_code)
        //     ->select(
        //         'referal.id',
        //         'referal.f_type',
        //         'referal.created_at',
        //         'driver.name as driver_name',
        //         'driver.phone as driver_phone',
        //         'driver.location as driver_location',
        //         'corporate.name as corporate_name',
        //         'corporate.contact as corporate_contact',
        //         'corporate.location as corporate_location'
        //     )
        //     ->get();

        $referrals = DB::table('referal')->where('code', $dr->ref_code)->get()->map(function ($referral) {
            if ($referral->f_type === 'acting') {
                $driver = DB::table('driver')
                    ->where('id', $referral->f_id)
                    ->select('name as driver_name', 'phone as driver_phone', 'type as driver_type')
                    ->first();

                $referral->collect = $driver;
            } else {
                $driver = null;
            }

            if ($referral->f_type === 'owner') {
                $corporate = DB::table('corporate')
                    ->where('id', $referral->f_id)
                    ->select('name as driver_name', 'contact as driver_phone', 'type as driver_type')
                    ->first();

                $referral->collect = $corporate;
            } else {
                $corporate = null;
            }

            return $referral;
        });

        $withdraws = DB::table('bank_withdraw')
            ->where('type', $dr->type)
            ->where('d_id', $dr->id)
            ->orderBy('created_at', 'desc')
            ->select('created_at', 'amt', 'status', 'name', 'bank', 'branch', 'ifsc', 'acc_no', 'upi_name', 'upi_id')
            ->get();

        $data = DB::table('referal')
            ->where('code', $dr->ref_code)
            ->selectRaw('COUNT(*) as count, SUM(amt) as balance')
            ->first();

        $count = $data->count;
        $balance = $data->balance;

        $with_balance = $withdraws->where('status', 'approved')->sum('amt');

        $rem = $balance - $with_balance;

        // dd($referrals);



        return view('admin.candidate.profile', compact('driver', 'hiredTrips', 'feedbacks', 'subscriptions', 'referrals', 'rem', 'dr'));
    }
    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'gender' => 'required|in:Male,Female,Other',
            'b_group' => 'required|string',
            'c_ad' => 'required|string|max:255',
            'c_city' => 'required|string|max:100',
            'c_state' => 'required|string|max:100',
            'c_pin' => 'nullable|string|max:10',
            'about' => 'nullable|string|max:500',
            'exp_year' => 'nullable|integer|min:0',
            'exp_mon' => 'nullable|integer|min:0|max:11',
            'p_com_name' => 'nullable|string|max:255',
            'rel_date' => 'nullable|date',
            'com_location' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:15',
            'current_salary' => 'nullable|numeric',
            'pf' => 'nullable|string|max:100',
            'expert_salary' => 'nullable|numeric',
            'job_loc' => 'nullable|string|max:255',
            'agreement' => 'nullable|string|max:255',
            'years' => 'nullable|integer'
        ]);

        $driver = Driver::findOrFail($id);

        // Update driver data (only editable fields)
        $driver->update([
            'gender' => $request->gender,
            'b_group' => $request->b_group
        ]);

        // Update driver_details table
        DB::table('driver_details')
            ->where('d_id', $id)
            ->update([
                'c_ad' => $request->c_ad,
                'c_city' => $request->c_city,
                'c_state' => $request->c_state,
                'c_pin' => $request->c_pin,
                'about' => $request->about,
                'exp_year' => $request->exp_year,
                'exp_mon' => $request->exp_mon,
                'p_com_name' => $request->p_com_name,
                'rel_date' => $request->rel_date,
                'com_location' => $request->com_location,
                'contact_number' => $request->contact_number,
                'current_salary' => $request->current_salary,
                'pf' => $request->pf,
                'expert_salary' => $request->expert_salary,
                'job_loc' => $request->job_loc,
                'agreement' => $request->agreement,
                'years' => $request->years,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }


    // Method to show type change requests
    public function showTypeChangeRequests()
    {
        // Fetch all requests with driver information
        $requests = DriverTypeChangeRequest::with('driver')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.driver_change_requests', compact('requests'));
    }

    // Method to approve/reject driver type change
    public function approveDriverTypeChange($requestId, Request $request)
    {
        $changeRequest = DriverTypeChangeRequest::find($requestId);

        if (!$changeRequest) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        if ($changeRequest->request_status !== 'pending') {
            return redirect()->back()->with('error', 'No pending request found.');
        }

        $driver = $changeRequest->driver;

        if (!$driver) {
            return redirect()->back()->with('error', 'Driver not found.');
        }

        if ($request->action === 'approve') {
            // Approve request → update driver type and mark request as approved
            $driver->type = $changeRequest->change_type_to;
            $driver->save();

            $changeRequest->request_status = 'approved';
            $changeRequest->save();

            return redirect()->back()->with('success', 'Driver type updated successfully.');
        } elseif ($request->action === 'reject') {
            // Reject request → mark request as rejected
            $changeRequest->request_status = 'rejected';
            $changeRequest->save();

            return redirect()->back()->with('success', 'Driver type change request rejected.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }

    // Method to create a new type change request (for API or form submission)
    public function createTypeChangeRequest(Request $request)
    {
        $driverId = $request->driver_id;
        $newType = $request->change_type_to;

        // Validate input
        $request->validate([
            'driver_id' => 'required|exists:driver,id',
            'change_type_to' => 'required|string',
        ]);

        $driver = Driver::find($driverId);

        if (!$driver) {
            return response()->json(['error' => 'Driver not found'], 404);
        }

        // Check if driver already has a pending request
        if ($driver->hasPendingTypeChangeRequest()) {
            return response()->json(['error' => 'Driver already has a pending type change request'], 400);
        }

        // Create new type change request
        $changeRequest = DriverTypeChangeRequest::create([
            'driver_id' => $driverId,
            'previous_type' => $driver->type,
            'change_type_to' => $newType,
            'request_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Type change request submitted successfully',
            'request_id' => $changeRequest->id
        ]);
    }

    // Method to get driver's type change history
    public function getDriverTypeChangeHistory($driverId)
    {
        $driver = Driver::find($driverId);

        if (!$driver) {
            return response()->json(['error' => 'Driver not found'], 404);
        }

        $history = $driver->typeChangeRequests()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'driver' => $driver,
            'history' => $history
        ]);
    }
    public function exportDriverData(Request $request)
    {
        try {
            $type = $request->query('type');
            $status = $request->query('status', 'approved'); // approved or rejected

            // Build query - only from driver table
            $query = DB::table('driver')
                ->where('status', $status)
                ->where(function ($query) {
                    $query->where('type', 'permanent')
                        ->orWhere(function ($q) {
                            $q->where('type', 'acting');
                        });
                });

            // Apply type filter if provided
            if ($type && in_array($type, ['acting', 'permanent'])) {
                $query->where('type', $type);
            }

            // Get all driver data from driver table only
            $drivers = $query->select(
                'id',
                'name',
                'phone',
                'type',
                'gender',
                'marital_status',
                'b_group',
                'location',
                'district',
                'l_no',
                'ad_num',
                'ref_code',
                'subscription',
                'active_status',
                'status',
                'created_at',
                'updated_at'
            )->get()->map(function ($driver) {
                // Get location name if location_active table exists
                $locationName = DB::table('location_active')
                    ->where('id', $driver->location)
                    ->value('location');

                $driver->location_name = $locationName ?? 'N/A';

                return $driver;
            });

            return response()->json([
                'success' => true,
                'data' => $drivers
            ]);
        } catch (\Exception $e) {
            \Log::error('Export Driver Data Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch driver data',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function deleteCandidate($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return back()->with('success', 'Driver deleted successfully.');
    }
}
