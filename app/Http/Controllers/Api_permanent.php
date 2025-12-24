<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\SubApplied;
use App\Models\Corporate;
use App\Http\Services\OtpService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Jobs\Trip_notify;
use App\Models\Driver;
use App\Models\Vacancy;
use App\Models\VacancyApplied;


class Api_permanent extends Controller
{
public function vacancyDetails(Request $request)
{
    // ✅ Validate request
    $request->validate([
        'vacancy_id' => 'required|integer|exists:vacancy,id',
    ]);

    // ✅ Get the authenticated user
    $user = auth()->user();
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 401);
    }

    $userId = $user->id;
    $vacancyId = $request->vacancy_id;

    // ✅ Get vacancy
    $vacancy = Vacancy::where('id', $vacancyId)
        ->where('status', 'active')
        ->first();

    if (!$vacancy) {
        return response()->json([
            'success' => false,
            'message' => 'Vacancy not found or inactive.'
        ], 404);
    }

    // ✅ Check if user has applied & fetch status if applied
    $appliedRecord = \App\Models\VacancyApplied::where('user_id', $userId)
        ->where('vacancy_id', $vacancy->id)
        ->first();

    $vacancy->applied = (bool) $appliedRecord;
    $vacancy->application_status = $appliedRecord ? $appliedRecord->status : null;

    // ✅ Prepare response data
    $responseData = $vacancy->toArray();

    if ($appliedRecord && $appliedRecord->status === 'Rejected') {
        $responseData['rejection_reason'] = $appliedRecord->rejection_reason;
    }

    return response()->json([
        'success' => true,
        'data'    => $responseData,
    ], 200);
}
    
    public function latest(Request $request)
{
    // ✅ Get the authenticated user from Bearer token
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 401);
    }

    $userId = $user->id; // driver id from token

    $vacancies = Vacancy::where('status', 'active')->get();

    if ($vacancies->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No active vacancy found.'
        ], 404);
    }

    // Attach "applied" status for each vacancy
    $vacancies = $vacancies->map(function ($vacancy) use ($userId) {
        $vacancy->applied = \App\Models\VacancyApplied::where('user_id', $userId)
            ->where('vacancy_id', $vacancy->id)
            ->exists();
        return $vacancy;
    });

    return response()->json([
        'success' => true,
        'data'    => $vacancies,
    ], 200);
}
public function apply(Request $request)
{
    // ✅ Get authenticated driver from Bearer token
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized',
        ], 401);
    }

    // Only vacancy_id is required from request, user_id comes from token
    $validated = $request->validate([
        'vacancy_id' => 'required|exists:vacancy,id',
    ]);

    $userId = $user->id;  // driver id from token

    // ✅ Check if already hired in SubApplied
    $alreadyHired = \App\Models\SubApplied::where('d_id', $userId)
        ->where('status', 'Hired')
        ->exists();

    if ($alreadyHired) {
        return response()->json([
            'success' => false,
            'message' => 'Already Hired',
        ], 200);
    }

    // ✅ prevent duplicate applications
    $exists = VacancyApplied::where('user_id', $userId)
        ->where('vacancy_id', $validated['vacancy_id'])
        ->first();

    if ($exists) {
        return response()->json([
            'success' => true,
            'message' => 'You have already applied for this vacancy.'
        ], 200);
    }

    // ✅ Create application with token-based user_id
    $applied = VacancyApplied::create([
        'user_id'    => $userId,
        'vacancy_id' => $validated['vacancy_id'],
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Applied to vacancy successfully!',
        'data'    => $applied
    ], 200);
}

    public function job()
    {

        $driverIds = Driver::where('status', 'pendingg')->pluck('id')->toArray();

        $trip = 16;

        Trip_notify::dispatch($driverIds, $trip, 'trip_posted');
    }


    public function fulltime_applied(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'p_id' => 'required|exists:permanent_jobs,id',
            'd_id' => 'required|exists:driver,id',
        ], [
            'p_id.required' => 'permanent_jobs ID is required.',
            'p_id.exists' => 'The given permanent_jobs ID does not exist.',
            'd_id.required' => 'Driver ID is required.',
            'd_id.exists' => 'The given driver ID does not exist.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $tripApplied = SubApplied::create([
            'p_id' => $request->p_id,
            'd_id' => $request->d_id,
            'status' => 'Applied',
            // 'c_by' => auth('driver')->user()->id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Trip applied successfully.',
            'data' => [
                'p_id' => $tripApplied->p_id,
                'd_id' => $tripApplied->d_id,

            ],
        ]);
    }


    // dashboard funtion

    // public function permanent_dashboard(Request $req)
    // {

    //     $user = auth('sanctum')->user();

    //     $page = $req->page; // Accept ?page=dashboard from request

    //     // $user->location = $user->location ?? 2; // Default to 1 if location is not set

    //     // $location = DB::table('location_active')->where('id', $user->location)->first();

    //     // if (!$location || !$location->cord) {
    //     //     return response()->json(['status' => false, 'message' => 'Base location not found'], 404);
    //     // }

    //     // $locationParts = explode(',', $location->cord);
    //     // $lat1 = trim($locationParts[0]);
    //     // $lon1 = trim($locationParts[1]);
    //     // $radius = 50;

    //     // $all_loc = DB::table('location_active')
    //     //     ->where('status', 'active')
    //     //     ->select('id', 'location', 'cord', 'status')
    //     //     ->get();

    //     // $nearbyLocations = [];

    //     // foreach ($all_loc as $loc) {
    //     //     // if (!$loc->cord) {
    //     //     //     continue;
    //     //     // }

    //     //     $cordParts = explode(',', $loc->cord);
    //     //     if (count($cordParts) !== 2) {
    //     //         continue;
    //     //     }

    //     //     $lat2 = trim($cordParts[0]);
    //     //     $lon2 = trim($cordParts[1]);

    //     //     $distance = $this->calculateDistance($lat1, $lon1, $lat2, $lon2);

    //     //     if ($distance <= $radius) {
    //     //         $loc->distance = round($distance, 2); // optional: show how far it is
    //     //         $nearbyLocations[] = $loc;
    //     //     }
    //     // }

    //     // $search_loc =  collect($nearbyLocations)->pluck('id')->toArray();

    //     // $corporate = Corporate::where('type', 'corporate')->whereIn('location', $search_loc)->pluck('id')->toArray();

    //     $threeDaysAgo = Carbon::now()->subDays(3)->toDateString();


    //     $jobQuery  = DB::table('permanent_jobs')
    //         // ->whereIn('c_by', $corporate)
    //         ->where('status', 'approve');
    //     // ðŸ‘‡ Only filter by last 3 days if page is 'dashboard'
    //     if ($page === 'dashboard') {
    //         $jobQuery->whereDate('created_at', '>=', $threeDaysAgo);
    //     }

    //     $jobs = $jobQuery->orderBy('created_at', 'desc')
    //         ->select('id', 'veh_type', 'join_date', 'min_exp', 'max_exp', 'job_location', 'min_salary', 'max_salary', 'created_at', 'c_by')
    //         ->get()->map(function ($job) {

    //             $job->c_on = Carbon::parse($job->created_at)->format('Y-m-d H:i:s');

    //             $job->saved = DB::table('saved_jobs')->where('type', 'permanent')
    //                 ->where('trip_id', $job->id)
    //                 ->where('status', 'saved')
    //                 ->where('d_id', auth('sanctum')->user()->id)
    //                 ->exists() ?? false;

    //             $job->applied  = DB::table('sub_applied')
    //                 ->where('p_id', $job->id)
    //                 ->where('d_id', auth('sanctum')->user()->id)
    //                 ->where('status', 'Applied')->exists();
    //             return $job;
    //         })->reject(function ($job) {
    //             return $job->applied;  // remove jobs where saved = true
    //         })
    //         ->values();  // reset keys after reject




    //     $completion = $this->completion();

    //     // dd($completion);
    //     // dd($jobs, $nearbyLocations, $corporate, $threeDaysAgo);

    //     $d_status = collect([
    //         'd_status' => auth('sanctum')->user()->status,
    //         'number' => '123456789',
    //     ]);

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Nearby locations Jobs found',
    //         'data' =>  $jobs,
    //         'completion' => $completion->completion_percentage ?? 0,
    //         'd_status' => $d_status
    //     ]);
    // }

public function permanent_dashboard(Request $req)
{
    $user = auth('sanctum')->user();
    $page = $req->page;

    $threeDaysAgo = Carbon::now()->subDays(3)->toDateString();

    $jobQuery = DB::table('permanent_jobs')
        ->where('status', 'approve');

    // Only filter by last 3 days if page = 'dashboard'
    if ($page === 'dashboard') {
        $jobQuery->whereDate('created_at', '>=', $threeDaysAgo);
    }

    $jobs = $jobQuery->orderBy('created_at', 'desc')
        ->select('id', 'veh_type', 'join_date', 'min_exp', 'max_exp', 'job_location', 'min_salary', 'max_salary', 'created_at', 'c_by')
        ->get()
        ->map(function ($job) {
            $job->c_on = Carbon::parse($job->created_at)->format('Y-m-d H:i:s');

            $job->saved = DB::table('saved_jobs')
                ->where('type', 'permanent')
                ->where('trip_id', $job->id)
                ->where('status', 'saved')
                ->where('d_id', auth('sanctum')->user()->id)
                ->exists() ?? false;

            $job->applied = DB::table('sub_applied')
                ->where('p_id', $job->id)
                ->where('d_id', auth('sanctum')->user()->id)
                ->where('status', 'Applied')
                ->exists();

            return $job;
        })
        ->reject(function ($job) {
            return $job->applied;
        })
        ->values();

    // Get profile completion data
    $completion = $this->completion();

    // Handle driver status and possible rejection/pending reason
    $driverStatus = $user->status;
    $reason = null;

    if (in_array($driverStatus, ['rejected', 'pending'])) {
        // Normalize status to match DB values
        $action = $driverStatus === 'rejected' ? 'reject' : 'pending';

        $latestReason = \App\Models\ApprovalReason::where('user_id', $user->id)
            ->where('user_type', $user->type)
            ->where('action', $action)
            ->latest()
            ->first();

        $reason = $latestReason ? $latestReason->reason : null;
    }

    $d_status = [
        'd_status' => $driverStatus,
        'number'   => '123456789',
        'reason'   => $reason,
    ];

    return response()->json([
        'status'      => true,
        'message'     => 'Nearby locations Jobs found',
        'data'        => $jobs,
        'completion'  => $completion->completion_percentage ?? 0,
        'd_status'    => $d_status,
    ]);
}
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }


    public function completion()
    {

        $driverIds = Driver::where('status', 'pending')->pluck('id')->toArray();

        // Trip_notify::dispatch($driverIds, 'posted');

        // dd('Job dispatched to the queue successfully.', $driverIds);

        $user = auth('sanctum')->user();

        $completion = DB::table('driver_details as dd')
            ->leftJoin('driver as d', 'd.id', '=', 'dd.d_id')
            ->leftJoin('license as l', 'l.d_id', '=', 'dd.d_id')
            ->selectRaw("
                ROUND((
                    (dd.c_ad IS NOT NULL AND dd.c_ad != '') +
                    (dd.c_city IS NOT NULL AND dd.c_city != '') +
                    (dd.c_pin IS NOT NULL AND dd.c_pin != '') +
                    (dd.about IS NOT NULL AND dd.about != '') +
                    (dd.exp_year IS NOT NULL AND dd.exp_year != '') +
                    (dd.exp_mon IS NOT NULL AND dd.exp_mon != '') +
                    (dd.p_com_name IS NOT NULL AND dd.p_com_name != '') +
                    (dd.com_location IS NOT NULL AND dd.com_location != '') +
                    (dd.contact_number IS NOT NULL AND dd.contact_number != '') +
                    (dd.current_salary IS NOT NULL AND dd.current_salary != '') +
                    (dd.pf IS NOT NULL AND dd.pf != '') +
                    (d.b_group IS NOT NULL AND d.b_group != '') +
                    (d.ad_num IS NOT NULL AND d.ad_num != '') +
                    (l.l_img IS NOT NULL AND l.l_img != '') +
                    (l.ad_1 IS NOT NULL AND l.ad_1 != '')
                ) / 15 * 100, 0) as completion_percentage
            ")
            ->where('dd.d_id', $user->id)
            ->first();

        return $completion;
    }

    public function permanent_jobs_applied(Request $req)
    {

        $user = auth('sanctum')->user();

        $appliedJobs = DB::table('sub_applied as sa')
            ->leftJoin('permanent_jobs as pj', 'sa.p_id', '=', 'pj.id')
            ->where('sa.d_id', $user->id)
            ->where('sa.status', 'Applied')
            ->select('sa.p_id', 'sa.status as ap_status', 'pj.created_at as ap_date', 'pj.*')
            ->get()
            ->map(function ($job) {
                $job->created_at = Carbon::parse($job->ap_date)->format('Y-m-d H:i:s');
                $job->saved = DB::table('saved_jobs')->where('type', 'permanent')
                    ->where('trip_id', $job->p_id)
                    ->where('status', 'saved')
                    ->where('d_id', auth('sanctum')->user()->id)
                    ->exists() ?? false;
                unset($job->created_at, $job->updated_at);
                return $job;
            });

        // dd($appliedJobs->toArray());

        if ($appliedJobs->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No applied jobs found.',
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Applied jobs found.',
                'data' => $appliedJobs
            ]);
        }
    }


    public function permanent_job_id(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'job_id' => 'required|exists:permanent_jobs,id',
        ], [
            'job_id.required' => 'Permanent job ID is required.',
            'job_id.exists' => 'The given permanent job ID does not exist.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $job = DB::table('permanent_jobs')->where('permanent_jobs.id', $req->job_id)
            ->leftJoin('corporate', 'permanent_jobs.c_by', '=', 'corporate.id')
            ->select('permanent_jobs.*', 'permanent_jobs.id as job_id', 'corporate.name as corporate_name', 'corporate.contact as corporate_contact', 'corporate.a_num as corporate_alter')
            ->first();

        $job->job_status = DB::table('sub_applied')
            ->where('p_id', $job->id)
            ->where('d_id', auth('sanctum')->user()->id)
            ->value('status') ?? 'Not Applied';

        // dd($job);

        if (!$job) {
            return response()->json([
                'status' => false,
                'message' => 'Permanent job not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Permanent job found.',
            'data' => $job
        ]);
    }


    public function permanent_job_apply(Request $req)
    {

        // log::info($req->all());
        $validator = Validator::make($req->all(), [
            'job_id' => 'required|exists:permanent_jobs,id',
        ], [
            'job_id.required' => 'Permanent job ID is required.',
            'job_id.exists' => 'The given permanent job ID does not exist.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $user = auth('sanctum')->user();

        $exists =  SubApplied::where('d_id', $user->id)->where('status', 'Hired')->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Already Hired',
            ]);
        }

        $applied = SubApplied::updateOrCreate(
            ['p_id' => $req->job_id, 'd_id' => $user->id, 'c_by' => $user->id],
            ['status' => 'Applied']
        );

        return response()->json([
            'status' => true,
            'message' => 'Job application submitted successfully.',
            'data' => [
                'p_id' => $applied->p_id,
                'd_id' => $applied->d_id,
                'status' => $applied->status,
            ],
        ]);
    }

    public function driver_withdraw(Request $req)
    {

        // dd($req->all());

        log::info('Withdraw Request -- ', $req->all());

        $validator = Validator::make($req->all(), [

            'd_id' => 'required',
            'amt' => 'required|numeric|min:501',
            'name' => 'required|string',
            'bank' => 'required|string',
            'branch' => 'required|string',
            'ifsc' => 'required|string',
            'acc_no' => 'required|string',
            'upi_name' => 'nullable|string',
            'upi_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $user = auth('sanctum')->user();

        $ins = DB::table('bank_withdraw')->insert([
            'type' => $user->type,
            'd_id' => $req->d_id,
            'amt' => $req->amt,
            'name' => $req->name,
            'bank' => $req->bank,
            'branch' => $req->branch,
            'ifsc' => $req->ifsc,
            'acc_no' => $req->acc_no,
            'upi_name' => $req->upi_name,
            'upi_id' => $req->upi_id,
            'status' => 'pending',
            'c_by' => $user->id, // Assuming the user is authenticated
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($ins) {
            return response()->json([
                'status' => true,
                'message' => 'withdraw Requested successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to withdraw.',
            ], 500);
        }
    }

    public function driver_withdraw_list(Request $req)
    {
        $user = auth('sanctum')->user();

        $withdraws = DB::table('bank_withdraw')
            ->where('type', $user->type)
            ->where('d_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->select('created_at', 'amt', 'status', 'name', 'bank', 'branch', 'ifsc', 'acc_no', 'upi_name', 'upi_id')
            ->get();

        $with_bank = $withdraws->first() ?? 0;  // returns the first item or null

        $data = DB::table('referal')
            ->where('code', $user->ref_code)
            ->selectRaw('COUNT(*) as count, SUM(amt) as balance')
            ->first();

        $count = $data->count;
        $balance = $data->balance;

        // $count = DB::table('referal')->where('code', $user->ref_code)->count();

        // $balance = DB::table('referal')->where('code', $user->ref_code)->sum('amt');

        $with_balance = $withdraws->where('status', 'approved')->sum('amt');

        $rem = $balance - $with_balance;



        if ($withdraws->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No withdraw requests found.',
                'count' => $count,
                'rem' => $rem,
                'bank' => $with_bank ?? null,
                'data' => $withdraws
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Withdraw requests found.',
                'count' => $count,
                'rem' => $rem,
                'bank' => $with_bank ?? null,
                'data' => $withdraws,
            ]);
        }
    }

    public function permanent_job_saved(Request $req)
    {

        $user = auth('sanctum')->user();

        $saved = DB::table('saved_jobs')->where('type', 'permanent')->where('status', 'saved')->where('d_id', $user->id)->pluck('trip_id');

        $jobs = DB::table('permanent_jobs')
            ->whereIn('id', $saved)
            ->select('permanent_jobs.*')
            ->get()->map(function ($job) {

                $job->saved = DB::table('saved_jobs')->where('type', 'permanent')
                    ->where('trip_id', $job->id)
                    ->where('d_id', auth('sanctum')->user()->id)
                    ->where('status', 'saved')
                    ->exists() ?? false;

                $job->applied  = DB::table('sub_applied')
                    ->where('p_id', $job->id)
                    ->where('d_id', auth('sanctum')->user()->id)
                    ->where('status', 'Applied')->exists();

                return $job;
            });

        if ($saved->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No saved jobs found.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Job saved successfully.',
            'data' => $jobs,
        ]);
    }


    //help and support
public function help_support(Request $req)
{
    $type = auth('sanctum')->user()->type; 

    $permanentHelp = [
        [
            'title' => [
                'en' => 'How to edit my profile',
                'ta' => 'என் சுயவிவரத்தை எப்படி திருத்துவது'
            ],
            'description' => [
                'en' => 'To edit your profile, go to settings and tap "Edit Profile". Make necessary changes and save.',
                'ta' => 'உங்கள் சுயவிவரத்தை திருத்த, அமைப்புகளுக்குச் செல்லவும். "சுயவிவரத்தை திருத்து" என்பதைத் தேர்வு செய்து, தேவையான மாற்றங்களைச் செய்து சேமிக்கவும்.'
            ]
        ],
        [
            'title' => [
                'en' => 'How to change my password',
                'ta' => 'என் கடவுச்சொல்லை எப்படி மாற்றுவது'
            ],
            'description' => [
                'en' => 'Navigate to Settings > Security > Change Password. Enter your current and new password.',
                'ta' => 'அமைப்புகள் > பாதுகாப்பு > கடவுச்சொல் மாற்று சென்று, தற்போதைய மற்றும் புதிய கடவுச்சொல்லை உள்ளிடவும்.'
            ]
        ],
        [
            'title' => [
                'en' => 'How to book a ride',
                'ta' => 'ஒரு பயணத்தை எப்படி முன்பதிவு செய்வது'
            ],
            'description' => [
                'en' => 'Open the rides tab, choose a ride, and tap "Book Now". Confirm the details and submit.',
                'ta' => 'பயணங்கள் பகுதியில் சென்று, ஒரு பயணத்தைத் தேர்ந்தெடுத்து "இப்போது முன்பதிவு செய்" என்பதைக் கிளிக் செய்யவும். விவரங்களை உறுதிப்படுத்தி சமர்ப்பிக்கவும்.'
            ]
        ]
    ];

    $owner = [
        [
            'title' => [
                'en' => 'How to edit my profile',
                'ta' => 'என் சுயவிவரத்தை எப்படி திருத்துவது'
            ],
            'description' => [
                'en' => 'Tap the menu bar, go to "Edit Profile", make the necessary changes, and tap "Save".',
                'ta' => 'மெனு பட்டியைத் தட்டவும், "சுயவிவரத்தை திருத்து" என்பதிற்குச் செல்லவும். தேவையான மாற்றங்களைச் செய்து "சேமி" என்பதைத் தட்டவும்.'
            ]
        ],
        [
            'title' => [
                'en' => 'How to contact my driver',
                'ta' => 'என் ஓட்டுநரை எப்படி தொடர்பு கொள்வது'
            ],
            'description' => [
                'en' => 'Tap the "Call" button on the Driver Profile screen to directly contact your driver via phone.',
                'ta' => 'ஓட்டுநர்  சுயவிவரத் திரையில் உள்ள "அழை" பொத்தானை அழுத்தி, டிரைவரை நேரடியாக தொலைபேசியில் தொடர்பு கொள்ளவும்.'
            ]
        ],
        [
            'title' => [
                'en' => 'Can I cancel a ride that is already started?',
                'ta' => 'ஏற்கனவே தொடங்கிய பயணத்தை நான் ரத்து செய்ய முடியுமா?'
            ],
            'description' => [
                'en' => 'Tap the "Cancel Trip" button on the Driver Profile screen to cancel your trip.',
                'ta' => 'ஓட்டுநர்  சுயவிவரத் திரையில் உள்ள "பயணத்தை ரத்து செய்" பொத்தானை அழுத்தி, உங்கள் பயணத்தை ரத்து செய்யவும்.'
            ]
        ],
        [
            'title' => [
                'en' => 'How to check when the trip was last updated',
                'ta' => 'பயணம் கடைசியாக எப்போது புதுப்பிக்கப்பட்டது என்பதை எப்படி பார்க்கலாம்?'
            ],
            'description' => [
                'en' => 'The "Last Updated" field on the Dashboard screen shows the most recent driver location update time.',
                'ta' => 'கட்டுப்பாட்டு பலகை திரையில் உள்ள "கடைசியாக புதுப்பிக்கப்பட்டது" புலம், டிரைவரின் சமீபத்திய இருப்பிடப் புதுப்பிப்பு நேரத்தைக் காட்டும்.'
            ]
        ]
    ];

    // ✅ Choose help content based on type
    $response = in_array($type, ['permanent', 'acting']) ? $permanentHelp : $owner;

    return response()->json([
        'status' => true,
        'type'   => $type,
        'data'   => $response
    ]);
}

}
