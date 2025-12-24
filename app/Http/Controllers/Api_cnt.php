<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Driver;
use App\Models\Corporate;
use App\Models\Trip;
use App\Models\TripApplied;
use App\Models\SavedJobs;
use App\Models\Subscription;
use App\Http\Services\OtpService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Api_permanent;
use App\Models\Notify;
use App\Services\Fcm;
use App\Models\DriverTypeChangeRequest;






class Api_cnt extends Controller
{



    protected $apiPermanent;

    public function __construct(Api_permanent $apiPermanent)
    {
        $this->apiPermanent = $apiPermanent;
    }


// public function popup(Request $request)
// {
//     // Get user_id from request
//     $userId = $request->input('user_id');

//     // Default response
//     $response = [
//         'version' => '0.0.6',
//         'type'    => null,
//         'active_status' => null,
//     ];

//     if ($userId) {
//         // ✅ Try fetching from drivers table
//         $driver = Driver::find($userId);

//         if ($driver) {
//             $response['type'] = $driver->type;
//             $response['active_status'] = $driver->active_status;
//         } else {
//             // ✅ Try fetching from corporate table
//             $corporate = \DB::table('corporate')->where('id', $userId)->first();

//             if ($corporate) {
//                 $response['type'] = $corporate->type;
//                 $response['active_status'] = $corporate->active_status ?? 'active'; // default if null
//             } else {
//                 $response['message'] = 'User not found';
//             }
//         }
//     } else {
//         $response['message'] = 'user_id is required';
//     }

//     return response()->json($response, 200);
// }
public function popup(Request $request)
{
    // Get user_id from request
    $userId = $request->input('user_id');

    // Default response
    $response = [
        'version'       => '0.0.8',
        'type'          => null,
        'active_status' => null,
        'exp_date'      => null, // ✅ Only date
    ];

    if ($userId) {
        // ✅ Try fetching from drivers table
        $driver = Driver::find($userId);

        if ($driver) {
            $response['type'] = $driver->type;
            $response['active_status'] = $driver->active_status;

            // ✅ Fetch latest subscription for this driver
            $subscription = \DB::table('subscription')
                ->where('f_id', $userId)
                ->where('type', $driver->type)
                ->orderByDesc('id')
                ->first();

            if ($subscription) {
                $response['exp_date'] = \Carbon\Carbon::parse($subscription->exp_date)->toDateString();
            }

        } else {
            // ✅ Try fetching from corporate table
            $corporate = \DB::table('corporate')->where('id', $userId)->first();

            if ($corporate) {
                $response['type'] = $corporate->type;
                $response['active_status'] = $corporate->active_status ?? 'active';

                // ✅ Fetch latest subscription for corporate
                $subscription = \DB::table('subscription')
                    ->where('f_id', $userId)
                    ->where('type', $corporate->type)
                    ->orderByDesc('id')
                    ->first();

                if ($subscription) {
                    $response['exp_date'] = \Carbon\Carbon::parse($subscription->exp_date)->toDateString();
                }

            } else {
                $response['message'] = 'User not found';
            }
        }
    } else {
        $response['message'] = 'user_id is required';
    }

    return response()->json($response, 200);
}




    public function mobile_check(Request $req)
    {
        $mob = $req->mobile;

        // $exists = Driver::where('phone', $mob)->exists();
        $exists = Driver::where('phone', $mob)->exists() || Corporate::where('contact', $mob)->exists();


        return response()->json([
            'status' => $exists,
            'message' => $exists ? 'Mobile number already exists' : 'Mobile number not found'
        ], 200);
    }




    public function get_license_by_mobile(Request $req)
    {
        $license = $req->l_no;

        $driver = Driver::where('l_no', $license)->first();

        return response()->json([
            'status' => (bool) $driver,
            'message' => $driver ? 'License number found' : 'License number not found',
            'l_no' => $driver->l_no ?? null
        ], 200);
    }


public function driver_store(Request $req)
{
    // log::info('Driver store request received', $req->all());

    $validator = Validator::make($req->all(), [
        'type' => ['nullable', 'string'],
        'phone' => ['nullable', 'digits_between:10,15'],
        'location' => ['nullable', 'string'],
        'district' => ['nullable', 'string'],
        'gender' => ['required', 'string'],
        'img' => ['nullable', 'url'],
        'l_no' => ['required', 'string'],
        'name' => ['nullable', 'string'],
        'cof' => ['nullable', 'string'],
        'dob' => ['nullable', 'date_format:d-m-Y'],
        'cov' => ['nullable', 'string'],
        'issued_rto' => ['nullable', 'string'],
        'date_of_issue' => ['nullable', 'date_format:d-m-Y'],
        'v_from' => ['nullable', 'date_format:d-m-Y'],
        'v_to' => ['nullable', 'date_format:d-m-Y', 'after_or_equal:v_from'],
        'batch_issue_date' => ['nullable', 'date_format:d-m-Y'],
        'batch_issued_by' => ['nullable', 'string'],
        'ad_1' => ['nullable', 'string'],
        'city' => ['nullable', 'string'],
        'state' => ['nullable', 'string'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();

        // Generate reference code first
        if ($req->type == 'acting') {
            $count = Driver::where('type', 'acting')->count();
            $ref_code = 'DDACT' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $count = Driver::where('type', 'permanent')->count();
            $ref_code = 'DDPER' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        }

        // Create Driver first (without image)
        $driver = Driver::create([
            'name' => $req->name ?? null,
            'type' => $req->type ?? null,
            'img' => null, // Will update this after image processing
            'phone' => $req->phone ?? null,
            'gender' => $req->gender ?? null,
            'location' => $req->location,
            'district' => $req->district,
            'ref_code' => $ref_code ?? null,
            'l_no' => $req->l_no,
            'c_by' => auth('sanctum')->id(),
        ]);

        // Now handle image download with driver ID
        $storedImgPath = null;
        if ($req->img) {
            try {
                $imageContents = file_get_contents($req->img);
                if ($imageContents) {
                    // Get file extension from URL
                    $extension = pathinfo(parse_url($req->img, PHP_URL_PATH), PATHINFO_EXTENSION);
                    
                    // Slugify the name to remove spaces and symbols
                    $safeName = Str::slug($req->name);
                    
                    // Create filename with driver ID: keerthi-raj-123.jpg
                    $filename = $safeName . '-' . $driver->driver_id . '.' . $extension;
                    
                    $destinationPath = public_path('licenses');

                    if (!File::exists($destinationPath)) {
                        File::makeDirectory($destinationPath, 0755, true);
                    }

                    $fullPath = $destinationPath . '/' . $filename;
                    file_put_contents($fullPath, $imageContents);

                    // Set path to be stored in DB
                    $storedImgPath = 'licenses/' . $filename;
                    
                    // Update the driver record with the image path
                    $driver->update(['img' => $storedImgPath]);
                }
            } catch (\Exception $e) {
                Log::error("Image download failed: " . $e->getMessage());
            }
        }

        // Handle referral logic
        if ($req->ref_by_code) {
            if (Str::startsWith($req->ref_by_code, 'DDOWN')) {
                $ref_driver = Corporate::where('ref_code', $req->ref_by_code)->first();
            } else {
                $ref_driver = Driver::where('ref_code', $req->ref_by_code)->first();
            }

            // Insert referral record
            DB::table('referal')->insert([
                'code' => $req->ref_by_code ?? 'direct',
                'ref_type' => $ref_driver->type ?? 0,
                'ref_by' => $ref_driver->id ?? 0,
                'f_type' => $driver->type ?? 1,
                'f_id' => $driver->id ?? 1,
                'amt' => $ref_driver ? 10 : 0,
                'c_by' => $driver->id ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // OTP generation and SMS logic
        $id = $driver->id;
        $phone = $req->phone;
        $otp = $phone === '1234567890' ? 1234 : rand(1000, 9999);
        $type = strtolower($req->type ?? '');

        $token = $driver ? $driver->createToken('auth_token')->plainTextToken : null;

        // OTP update based on type
        if (in_array($type, ['permanent', 'acting'])) {
            DB::table('driver')->where('phone', $phone)->update(['otp' => $otp]);
        } elseif ($type === 'owner') {
            DB::table('corporate')->where('contact', $phone)->update(['otp' => $otp]);
            
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'OTP sent successfully',
                'otp' => $otp,
            ], 200);
        }

        // Send OTP using SMS
        $authKey = "your_auth_key_here";
        $senderId = "DRDECK";
        $route = "2";
        $country = "91";
        $dltTeId = "1707175066512828187";
        $message = urlencode("Dear user, your DriversDeck registration OTP is $otp. Please do not share this with anyone. - DRDECK");
        $url = "http://promo.smso2.com/api/sendhttp.php?authkey=$authKey&mobiles=$phone&message=$message&sender=$senderId&route=$route&country=$country&DLT_TE_ID=$dltTeId";

        file_get_contents($url);

        // Create Driver Details
        DB::table('driver_details')->insert([
            'd_id' => $driver->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Safe date conversion function
        $safeDateConvert = function ($date) {
            try {
                return $date ? Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d') : null;
            } catch (\Exception $e) {
                Log::error("Date conversion failed: " . $e->getMessage());
                return null;
            }
        };

        // Build license data
        $licenseData = [
            'd_id' => $driver->id,
            'dob' => $safeDateConvert($req->dob ?? null),
            'cof' => $req->cof ?? null,
            'cov' => $req->cov ?? null,
            'l_no' => $req->l_no,
            'issued_rto' => $req->issued_rto ?? null,
            'date_of_issue' => $safeDateConvert($req->date_of_issue ?? null),
            'v_from' => $safeDateConvert($req->v_from ?? null),
            'v_to' => $safeDateConvert($req->v_to ?? null),
            'batch_issue_date' => $safeDateConvert($req->batch_issue_date ?? null),
            'batch_issued_by' => $req->batch_issued_by ?? null,
            'ad_1' => $req->ad_1 ?? null,
            'city' => $req->city ?? null,
            'state' => $req->state ?? null,
            'c_by' => auth('sanctum')->user()->id ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insert License
        DB::table('license')->insert($licenseData);

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Driver created successfully',
            'type' => $req->type,
            'driver_id' => $driver->id,
            'otp' => $otp,
            'img' => $storedImgPath ? url($storedImgPath) : null,
            'phn' => $req->phone,
            'name' => $req->name,
            'ref_code' => $ref_code,
            'token' => $token,
            'gender' => $req->gender,
        ], 200);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Driver creation failed: " . $e->getMessage());

        return response()->json([
            'status' => false,
            'message' => 'Driver creation failed',
            'error' => $e->getMessage()
        ], 200);
    }
}



    public function trip_50(Request $request)
    {
        $driver = auth('sanctum')->user();

        // if ($driver->status != 'approved') {

        //     return response()->json(['message' => 'Driver is not approved.'], 403);
        // }

        if (!$driver || !isset($driver->location)) {
            return response()->json(['message' => 'Authenticated driver does not have a location_id.'], 400);
        }

        // Fetch driver's location details
        $location = DB::table('location_active')->where('id', $driver->location)->first();

        if (!$location || !$location->cord) {
            return response()->json(['message' => 'Location not found for location_id: ' . $driver->location], 400);
        }

        // Extract coordinates from location
        $locationParts = explode(',', $location->cord);
        if (count($locationParts) < 2) {
            return response()->json(['message' => 'Invalid location coordinates format: ' . $location->cord], 400);
        }

        $driverLat = (float) trim($locationParts[0]);
        $driverLon = (float) trim($locationParts[1]);

        if (!is_numeric($driverLat) || !is_numeric($driverLon)) {
            return response()->json(['message' => 'Invalid driver coordinates: ' . $location->cord], 400);
        }

        $radius = 50; // km
        $result = [];

        // Fetch all valid pending trips
        $pendingTrips = Trip::where('status', 'pending')
            ->whereNotNull('st_cord')
            ->whereNotNull('dest_cord')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($pendingTrips as $trip) {
            if (empty($trip->st_cord) || empty($trip->end_cord) || empty($trip->dest_cord)) {
                continue;
            }

            // Parse start coordinates
            $startLat = (float) trim($trip->st_cord);
            $startLng = (float) trim($trip->end_cord);

            // Parse destination coordinates
            $destParts = explode(',', $trip->dest_cord);
            if (count($destParts) < 2) {
                continue;
            }

            $destLat = (float) trim($destParts[0]);
            $destLng = (float) trim($destParts[1]);

            // Skip if any coordinate is invalid
            if (
                !is_numeric($startLat) || !is_numeric($startLng) ||
                !is_numeric($destLat) || !is_numeric($destLng) ||
                abs($startLat) > 90 || abs($destLat) > 90 ||
                abs($startLng) > 180 || abs($destLng) > 180
            ) {
                continue;
            }

            // Calculate distance from driver's location to trip start
            $distance = $this->calculateDistance($driverLat, $driverLon, $startLat, $startLng);

            // log::info("distance -" . $distance);

            if ($distance <= $radius) {
                // Skip if already applied
                $alreadyApplied = DB::table('trip_applied')
                    ->where('trip_id', $trip->id)
                    ->where('d_id', $driver->id)
                    ->exists();

                    $stDate = Carbon::parse($trip->st_date)->format('Y-m-d');
                    $endDate = Carbon::parse($trip->end_date)->format('Y-m-d');
                    $today = Carbon::today()->format('Y-m-d');

                    if ($alreadyApplied || $stDate < $today || $endDate < $today) {
                        continue;
                    }
                // Check if trip is saved
                $saved = DB::table('saved_jobs')
                    ->where('trip_id', $trip->id)
                    ->where('d_id', $driver->id)
                    ->where('status', 'saved')
                    ->exists();

                // Get readable city names
                $startCity = $this->getCityName($startLat, $startLng);
                $endCity = $this->getCityName($destLat, $destLng);

                $result[] = [
                    'trip_id' => $trip->id,
                    'title' => 'acting driver',
                    'st_city' => $trip->st_city,
                    'end_city' => $trip->end_city,
                    'st_date' => Carbon::parse($trip->st_date)->format('Y-m-d'),
                    'end_date' => Carbon::parse($trip->end_date)->format('Y-m-d'),
                    'start_time' => Carbon::parse($trip->st_time)->format('H:i'),
                    'created_at' => Carbon::parse($trip->created_at)->format('Y-m-d H:i'),
                    'saved_sts' => $saved ? 'true' : 'false',
                    'd_type' => $trip->d_type

                ];
            }
        }

        return response()->json([
            'location' => $location->location,
            'total_nearby_trips' => count($result),
            'trips' =>  $result,
        ]);
    }


    // Make sure you have this helper method for distance calculation
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }




    private function getCityName($lat, $lng)
    {
        if (!$lat || !$lng || !is_numeric($lat) || !is_numeric($lng)) {
            return null;
        }

        $apiKey = env('GOOGLE_MAPS_API_KEY');
        if (!$apiKey) {
            return null;
        }

        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$apiKey}";

        try {
            $response = Http::timeout(10)->get($url);
            $data = $response->json();

            if (isset($data['results'][0]['address_components'])) {
                foreach ($data['results'][0]['address_components'] as $component) {
                    if (in_array('locality', $component['types'])) {
                        return $component['long_name'];
                    }
                }
            }
        } catch (\Exception $e) {
            // Silent fail
        }

        return null;
    }


    public function job_profile(Request $request)
    {

        $user = auth('sanctum')->user();

        $driverId = $user->id;
        // Validate trip_id
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|integer|exists:trip,id',
        ], [
            'trip_id.required' => 'Trip ID is required.',
            'trip_id.integer'  => 'Trip ID must be a number.',
            'trip_id.exists'   => 'Trip not found.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        // Get the trip
        $trip = Trip::find($request->trip_id);

        if (!$trip) {
            return response()->json([
                'status'  => false,
                'message' => 'Trip not found.',
                'data'    => []
            ], 200);
        }

        // Get the owner
        $owner = DB::table('corporate')->where('id', $trip->c_by)->first();

        // Join trip_applied with driver to get driver_name
        $tripApplied = DB::table('trip_applied')
            ->leftJoin('driver', 'trip_applied.d_id', '=', 'driver.id')
            ->select(
                'trip_applied.salary_perday',
                'trip_applied.wait_charge',
                'trip_applied.food',
                'driver.name as driver_name',
                'trip_applied.trip_code',
                'trip_applied.created_at',

            )
            ->where('trip_applied.trip_id', $request->trip_id)
            ->where('trip_applied.d_id', $driverId)
            ->first();



        $cancelCount = DB::table('cancel_req')
            ->where('type', 'acting')
            ->where('c_by', $driverId)
            ->whereIn('status', ['Cancel', 'Request']) // counts both confirmed and requested cancellations
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Remaining attempts (max 3)
        $remaining_cancels = max(0, 3 - $cancelCount);



        // Prepare response
        $data = [
            'trip_id'    => $trip->id,
            'img'        => $owner->logo ? asset($owner->logo) : 'N/A',
            'st_loc'     => $trip->st_loc ?? 'N/A',
            'st_dest'    => $trip->st_dest ?? 'N/A',
            'st_date'    => $trip->st_date ?? 'N/A',
            'st_date'    => $trip->end_date ?? 'N/A',
            'owner_id'       => $owner->id ?? 'N/A',
            'name'       => $owner->name ?? 'N/A',
            'contact'    => $owner->contact ?? 'N/A',
            'st_date'    => $trip->st_date ? Carbon::parse($trip->st_date)->format('d/m/Y') : 'N/A',
            'end_date'   => $trip->end_date ? Carbon::parse($trip->end_date)->format('d/m/Y') : 'N/A',
            'st_time'    => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i') : 'N/A',
            'no_of_days' => $trip->no_days ?? 'N/A',
            'veh_type'   => $trip->veh_type ?? 'N/A',
            'veh_name'   => $trip->veh_name ?? 'N/A',
            'veh_number'   => $trip->veh_number,


            'salary_perday' => $tripApplied->salary_perday ?? 'N/A',
            'wait_charge'   => $tripApplied->wait_charge ?? 'N/A',
            'food'          => $tripApplied->food ?? 'N/A',
            'avg_salary'    => ($tripApplied->salary_perday ?? 0) * ($trip->no_days ?? 0),
            't_code'        => $tripApplied->trip_code ?? null,

            'applied_on' => isset($tripApplied) && $tripApplied->created_at
                ? Carbon::parse($tripApplied->created_at)->format('Y/m/d H:i:s')
                : 'N/A',


            'cancel_count' => $remaining_cancels,
            'trip_status'     => $trip->status ?? 'N/A',
        ];

        // 'st_date'    => $trip->st_date ? \Carbon\Carbon::parse($trip->st_date)->format('d/m/Y') : 'N/A',
        // 'end_date'   => $trip->end_date ? \Carbon\Carbon::parse($trip->end_date)->format('d/m/Y') : 'N/A',
        // 'st_time'    => $trip->st_time ? \Carbon\Carbon::parse($trip->st_time)->format('H:i') : 'N/A',



        // Return success response
        return response()->json([
            'status'  => true,
            'message' => 'Apply job profile retrieved successfully.',
            'data'    => $data
        ], 200);
    }

    public function location_active(Request $request)
    {
        $loc =  DB::table('location_active')->where('status', 'active')->select('id', 'location')->get();
        // Logic to handle the active location
        return response()->json(['message' => 'Location is active', 'loc' => $loc]);
    }


    public function apply_job_profile(Request $request)
    {

        $user = auth('sanctum')->user();

        $driverId = $user->id;
        // Validate trip_id
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|integer|exists:trip,id',
        ], [
            'trip_id.required' => 'Trip ID is required.',
            'trip_id.integer'  => 'Trip ID must be a number.',
            'trip_id.exists'   => 'Trip not found.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        // Get the trip
        $trip = Trip::find($request->trip_id);

        if (!$trip) {
            return response()->json([
                'status'  => false,
                'message' => 'Trip not found.',
                'data'    => []
            ], 200);
        }

        // Get the owner
        $owner = DB::table('corporate')->where('id', $trip->c_by)->first();

        // Join trip_applied with driver to get driver_name
        $tripApplied = DB::table('trip_applied')
            ->leftJoin('driver', 'trip_applied.d_id', '=', 'driver.id')
            ->select(
                'trip_applied.salary_perday',
                'trip_applied.wait_charge',
                'trip_applied.food',
                'driver.name as driver_name',
                'trip_applied.trip_code',
                'trip_applied.created_at',

            )
            ->where('trip_applied.trip_id', $request->trip_id)
            ->where('trip_applied.d_id', $driverId)
            ->first();



        $cancelCount = DB::table('cancel_req')
            ->where('type', 'acting')
            ->where('c_by', $driverId)
            ->whereIn('status', ['Cancel', 'Request']) // counts both confirmed and requested cancellations
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Remaining attempts (max 3)
        $remaining_cancels = max(0, 3 - $cancelCount);

        // $start_count = DB::table('trip_applied')->where('d_id', $driverId)->where('status', 'Start')->count();

        $existingTrips = TripApplied::with(['trip'])->where('d_id', $user->id)
            ->whereIn('status', ['Hired', 'Start'])
            ->get();


        $hasConflict = $existingTrips->contains(function ($extrips) use ($trip) {

            // log::info($trips);

            $existingStart = Carbon::parse($extrips->trip->st_date)->toDateString();
            $existingEnd = Carbon::parse($extrips->trip->end_date)->toDateString();

            $newStart = Carbon::parse($trip->st_date)->toDateString();
            $newEnd = Carbon::parse($trip->end_date)->toDateString();

            return $existingStart <= $newEnd && $existingEnd >= $newStart;


            // return (


            // ($newStart >= $existingStart && $newStart <= $existingEnd) || // New start is inside existing
            // ($newEnd >= $existingStart && $newEnd <= $existingEnd)
            // // New start date is within existing trip
            // ($application->st_date >= $existingStart && $application->st_date <= $existingEnd) ||

            // // New end date is within existing trip
            // ($application->st_date >= $existingStart && $application->st_date <= $existingEnd)
            // );
        });

        $st_city =  explode('#', $trip->st_city);
        $end_city =  explode('#', $trip->end_city);
        
                $isExpired = false;
        if ($trip->end_date) {
            $isExpired = Carbon::parse($trip->end_date)->lt(Carbon::today());
        }


        // Prepare response
        $data = [
            'trip_id'    => $trip->id,
            'img'        => $owner->logo ? asset($owner->logo) : 'N/A',
            'st_loc'     => $trip->st_loc ?? 'N/A',
            'st_dest'    => $trip->st_dest ?? 'N/A',
            'name'       => $owner->name ?? 'N/A',
            'contact'    => $trip->con_number ?? 'N/A',
            'st_date'    => $trip->st_date ? Carbon::parse($trip->st_date)->format('d/m/Y') : 'N/A',
            'end_date'   => $trip->end_date ? Carbon::parse($trip->end_date)->format('d/m/Y') : 'N/A',
            'st_time'    => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i') : 'N/A',
            'no_of_days' => $trip->no_days ?? 'N/A',
            'veh_type'   => $trip->veh_type ?? 'N/A',
            'veh_name'   => $trip->veh_name ?? 'N/A',
            'veh_number'   => $trip->veh_number,
            'trip_status'   => $trip->status ?? 'N/A',
            'st_city'   => $st_city[1] ?? 'N/A',
            'end_city'   => $end_city[1] ?? 'N/A',
            'driver_conflict' => $hasConflict,
            'expired'    => $isExpired 

        ];

        // Return success response
        return response()->json([
            'status'  => true,
            'message' => 'Apply job profile retrieved successfully.',
            'data'    => $data
        ], 200);
    }



    public function driver_trip_cancel(Request $request)
    {
        $user = auth('sanctum')->user();
        $driverId = $user->id;

        $validator = Validator::make($request->all(), [
            'trip_id'  => 'required|integer|exists:trip,id',
            'owner_id' => 'required|integer|exists:corporate,id',
            'status'   => 'required|in:Cancel',
            'reason'   => 'nullable|string',
            'remarks'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $ownerId = $request->owner_id;
        $tripId  = $request->trip_id;

        // Check if trip belongs to that owner
        $trip = DB::table('trip')
            ->where('id', $tripId)
            ->where('c_by', $ownerId)
            ->first();

        if (!$trip) {
            return response()->json([
                'status'  => false,
                'message' => 'Trip not found or owner mismatch.',
            ], 200);
        }

        // Check how many times driver has cancelled in this month
        $monthlyCancelCount = DB::table('cancel_req')
            ->where('type', 'acting')
            ->where('c_by', $driverId)
            ->where('status', 'Cancel')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        if ($monthlyCancelCount < 3) {
            // Direct cancel
            DB::table('trip')
                ->where('id', $tripId)
                ->update([
                    'status'     => $request->status,
                    'updated_at' => now()
                ]);

            DB::table('trip_applied')
                ->where('trip_id', $tripId)
                ->where('d_id', $driverId)
                ->update([
                    'status'     => $request->status,
                    'updated_at' => now()
                ]);

            DB::table('cancel_req')->insert([
                'trip_id'    => $tripId,
                'type'       => 'acting',
                'reason'    => $request->reason,
                'remarks'   => $request->remarks,
                'reason'     => $request->reason,
                'status'     => 'Cancel',
                'c_by'       => $driverId,
                'c_type'     => 'driver',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $trip_det =  Trip::find($tripId);

            $cor = Corporate::find($trip_det->c_by);

            Notify::create([
                'type' => $cor->type,
                'f_id' => $cor->id,
                'prime_table' => $tripId,
                'cat' => 'trip_' . $request->status,
                'title' => 'Your Trip Status Updated to : ' . $request->status,
                'body' => 'Your Trip Status Updated to : ' . $request->status,
                'status' => 'active',
                'c_by' => auth('sanctum')->user()->id, // Assuming you want to log who created this notification
            ])->save();




            if ($cor->token) {
                $fcm = new Fcm(); // ✅ Or use app(Fcm::class)
                $fcm->send_notify(
                    $cor->token,
                    'trip_' . $request->status,
                    'Your Trip Status Updated to : ' . $request->status,
                    'trip_update'
                );
            } else {
                // Log::warning("Driver token missing for driver ID: {$sub->d_id}");
            }

            return response()->json([
                'status'  => true,
                'message' => 'Trip cancelled successfully.',
            ], 200);
        } else {
            // Cancel request sent
            DB::table('trip')
                ->where('id', $tripId)
                ->update([
                    'status'     => 'Cancel Requested',
                    'updated_at' => now()
                ]);

            DB::table('trip_applied')
                ->where('trip_id', $tripId)
                ->where('d_id', $driverId)
                ->update([
                    'status'     => 'Cancel Requested',
                    'updated_at' => now()
                ]);

            DB::table('cancel_req')->insert([
                'trip_id'    => $tripId,
                'type'       => 'acting',
                'status'     => 'Request', // Needs admin approval
                'c_by'       => $driverId,
                'c_type'     => 'driver',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $trip_det =  Trip::find($tripId);

            $cor = Corporate::find($trip_det->c_by);

            Notify::create([
                'type' => $cor->type,
                'f_id' => $cor->id,
                'prime_table' => $tripId,
                'cat' => 'trip_' . $request->status,
                'title' => 'Your Trip Status Updated to : ' . $request->status,
                'body' => 'Your Trip Status Updated to : ' . $request->status,
                'status' => 'active',
                'c_by' => auth('sanctum')->user()->id, // Assuming you want to log who created this notification
            ])->save();




            if ($cor->token) {
                $fcm = new Fcm(); // ✅ Or use app(Fcm::class)
                $fcm->send_notify(
                    $cor->token,
                    'trip_' . $request->status,
                    'Your Trip Status Updated to : ' . $request->status,
                    'trip_update'
                );
            } else {
                // Log::warning("Driver token missing for driver ID: {$sub->d_id}");
            }

            return response()->json([
                'status'  => true,
                'message' => 'Trip cancel request sent to admin. Awaiting approval.',
            ], 200);
        }
    }




    public function trip_applied(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|exists:trip,id',
            // 'd_id' => 'required|exists:driver,id',
            'salary_perday' => 'required|string',
            'wait_charge' => 'required|string',
            'food' => 'required|string',
        ], [
            'trip_id.required' => 'Trip ID is required.',
            'trip_id.exists' => 'The given trip ID does not exist.',
            // 'd_id.required' => 'Driver ID is required.',
            // 'd_id.exists' => 'The given driver ID does not exist.',
        ]);


        $user = auth('sanctum')->user();
        $driverId = $user->id;


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $trip_ap = TripApplied::where('trip_id', $request->trip_id)->where('d_id', $driverId)->exists();

        if ($trip_ap) {
            return response()->json([
                'status' => false,
                'message' => 'Already Applied',
            ], 200);
        }

        $tripApplied = TripApplied::create([
            'trip_id' => $request->trip_id,
            'd_id' => $driverId,
            'salary_perday' => $request->salary_perday,
            'wait_charge' => $request->wait_charge,
            'food' => $request->food,
            'status' => 'Applied',
            // 'c_by' => auth('driver')->user()->id
        ]);

        $trip_det =  Trip::find($request->trip_id);

        $corporate = Corporate::where('id', $trip_det->c_by)->first();

        Notify::create([
            'type' => $corporate->type,
            'f_id' => $corporate->id,
            'prime_table' => $request->trip_id,
            'cat' => 'trip_applied',
            'title' => 'New Driver Applied',
            'body' => 'New Driver Applied for the Trip ID : ' . $request->trip_id,
            'status' => 'active',
            'c_by' => $driverId, // Assuming you want to log who created this notification
        ])->save();




        if ($corporate->token) {
            $fcm = new Fcm(); // ✅ Or use app(Fcm::class)
            $fcm->send_notify(
                $corporate->token,
                'trip_applied',
                'New Driver Applied for the Trip ID : ' . $request->trip_id,
                'trip_applied'
            );
        } else {
            // Log::warning("Driver token missing for driver ID: {$sub->d_id}");
        }

        return response()->json([
            'status' => true,
            'message' => 'Trip applied successfully.',
            'data' => [
                'trip_id' => $tripApplied->trip_id,
                'd_id' => $tripApplied->d_id,
                'salary_perday' => $tripApplied->salary_perday,
                'wait_charge' => $tripApplied->wait_charge,
                'food' => $tripApplied->food,
            ],
        ]);
    }



    public function getAppliedTripProfile(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        $driverId = $user->id;
        $tripId = $request->trip_id;

        if (!$tripId) {
            return response()->json([
                'status' => false,
                'message' => 'trip_id is required in the request.',
            ], 400);
        }

        $application = TripApplied::where('d_id', $driverId)
            ->where('trip_id', $tripId)
            ->where('status', 'Applied')
            ->first();

        if (!$application) {
            return response()->json([
                'status' => true,
                'message' => 'No applied trip found for this trip ID.',
                'data' => [],
            ]);
        }

        $trip = Trip::find($application->trip_id);

        if (!$trip) {
            return response()->json([
                'status' => false,
                'message' => 'Trip not found.',
            ], 404);
        }

        $owner = DB::table('corporate')->where('id', $trip->c_by)->first();

        $result = [
            'trip_id'       => $application->trip_id,
            'img'           => $owner && $owner->logo ? asset($owner->logo) : 'N/A',
            'st_loc'        => $trip->st_loc ?? 'N/A',
            'st_dest'       => $trip->st_dest ?? 'N/A',
            'name'          => $owner->name ?? 'N/A',
            'contact'       => $owner->contact ?? 'N/A',
            'st_date'       => $trip->st_date ? Carbon::parse($trip->st_date)->format('d/m/Y') : 'N/A',
            'end_date'      => $trip->end_date ? Carbon::parse($trip->end_date)->format('d/m/Y') : 'N/A',
            'st_time'       => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i') : 'N/A',
            'no_of_days'    => $trip->no_days ?? 'N/A',
            'veh_type'      => $trip->veh_type ?? 'N/A',
            'veh_name'      => $trip->veh_name ?? 'N/A',
            'veh_number'      => $trip->veh_number,
            'salary_perday' => $application->salary_perday ?? 'N/A',
            'wait_charge'   => $application->wait_charge ?? 'N/A',
            'food'          => $application->food ?? 'N/A',
            'status'        => $application->status ?? 'N/A',
            'applied_on'    => $application->created_at
                ? $application->created_at->format('Y-m-d H:i:s')
                : 'N/A',
        ];

        return response()->json([
            'status' => true,
            'message' => 'Applied trip fetched successfully.',
            'data' => $result,
        ]);
    }





    public function trip_applied_list(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        $driverId = $user->id;

        // $applications = TripApplied::where('d_id', $driverId)->get();

        $applications = TripApplied::where('d_id', $driverId)
            ->where('status', 'Applied')
            ->get();


        if ($applications->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No trip applications found.',
                'data' => [],
            ]);
        }

        $result = [];

        foreach ($applications as $application) {
            $trip = Trip::find($application->trip_id);

            if ((!$trip) || (Carbon::parse($trip->st_date)->format('Y-m-d') < Carbon::today()->format('Y-m-d'))) {
                continue; // Skip if trip not found or date is in the past
            }


            $savedRow = SavedJobs::where('trip_id', $application->trip_id)
                ->where('d_id', $driverId)
                ->first();

            // $savedStatus = $savedRow ? $savedRow->status : 'unsaved';
            $savedStatus = ($savedRow && $savedRow->status === 'saved') ? true : false;


            $result[] = [
                'trip_id'       => $application->trip_id,
                'title'         => $trip->title ?? 'N/A',
                'saved_status'  => $savedStatus,
                'start_city'    => $trip->st_city,
                'end_city'      => $trip->end_city,
                'start_date'    => $trip->st_date ?? null,
                'end_date'      => $trip->end_date ?? null,
                'start_time'    => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i') : null,
                'applied_status' => $application->status,
                'created_at'    => $trip->created_at ? Carbon::parse($trip->created_at)->format('Y-m-d H:i:s') : null,
            ];
        }

        return response()->json([
            'status' => true,
            'message' => 'Trip applications retrieved successfully.',
            'data' => $result,
        ]);
    }



    public function trip_saved_jobs(Request $request)
    {
        $user = auth('sanctum')->user();

        Log::info('User ID: ' . ($user?->id ?? 'Guest'));

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Please log in.',
            ], 401);
        }

        $driverId = $user->id;

        // Validation
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required',
            'issaved' => 'required|in:saved,unsaved',
        ], [
            'trip_id.required' => 'Trip ID is required.',
            'issaved.required' => 'Saved status is required.',
            'issaved.in' => 'Saved status must be either "saved" or "unsaved".',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        // Check if already exists (with any status)
        $existing = SavedJobs::where('trip_id', $request->trip_id)
            ->where('d_id', $driverId)
            ->where('type', $user->type)
            ->first();

        if ($existing) {
            $existing->status = $request->issaved;
            $existing->save();

            return response()->json([
                'status' => true,
                'message' => 'Trip status updated successfully.',
            ]);
        }

        // Create new record if not found
        SavedJobs::create([
            'type' => $user->type,
            'trip_id' => $request->trip_id,
            'd_id'    => $driverId,
            'status'  => $request->issaved,
            'c_by'    => $driverId,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Trip saved successfully.',
        ]);
    }




    public function get_saved_jobs(Request $request)
    {
        $user = auth('sanctum')->user();
        $driverId = $user->id;

        // Fetch saved trips
        $savedTripsRaw = SavedJobs::where('d_id', $driverId)
            ->where('saved_jobs.status', 'saved')
            ->join('trip', 'saved_jobs.trip_id', '=', 'trip.id')
            ->select(
                'trip.id as trip_id',
                'trip.title',
                'trip.st_city as start_city',
                'trip.end_city as end_city',
                'trip.st_date as start_date',
                'trip.end_date',
                'trip.st_time as start_time',
                'trip.created_at',
                'trip.status as trip_status',
                'saved_jobs.status as saved_status'
            )
            ->orderBy('trip.created_at', 'desc')
            ->get()->filter(function ($lt) {
                return !in_array($lt->trip_status, ['Cancel', 'Cancel Requested']);
            })
            ->values(); // reset keys if needed



        // Format created_at using Carbon
        $savedTrips = $savedTripsRaw->map(function ($trip) {

            if ($trip->start_date < Carbon::today()->toDateString()) {
                return null; // mark this for filtering out
            }

            return [
                'trip_id'     => $trip->trip_id,
                'title'       => $trip->title,
                'start_city'  => $trip->start_city,
                'end_city'    => $trip->end_city,
                'start_date'  => $trip->start_date,
                'end_date'    => $trip->end_date,
                'start_time'  => $trip->start_time,
                'status'      => $trip->status,
                'trip_status' => $trip->trip_status,
                'saved_status' => $trip->saved_status === 'saved' ? true : false,
                'created_at'  => $trip->created_at
                    ? Carbon::parse($trip->created_at)->format('Y-m-d H:i:s')
                    : 'N/A',
            ];
        })->filter();

        return response()->json([
            'status' => true,
            'message' => 'Saved jobs fetched successfully.',
            'data' => $savedTrips
        ]);
    }


   public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'phone' => 'required|numeric|digits:10',
    ], [
        'phone.required' => 'Phone number is required.',
        'phone.numeric'  => 'Phone number must be numeric.',
        'phone.digits'   => 'Phone number must be 10 digits.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => implode(', ', $validator->errors()->all()),
        ], 200);
    }

    log::info($request->all());
    $phone = $request->phone;
    $otp = in_array($phone, ['1234567891', '1234567892', '1234567890']) ? 1234 : rand(1000, 9999);


    // Try to find user in driver table
    $driver = Driver::where('phone', $phone)->whereIn('status', ['approved', 'pending', 'Hired','rejected'])->first();

    log::info($driver);

    // Try to find user in corporate table
    $corporate = Corporate::where('contact', $phone)->first();

    if (!$driver && !$corporate) {
        return response()->json([
            'status' => false,
            'message' => 'User not found in both Driver and Corporate tables.',
        ], 200);
    }

    // Send OTP and update in whichever table found
    if ($driver) {
        Driver::where('id', $driver->id)->update([
            'otp' => $otp,
        ]);
    }

    if ($corporate) {
        Corporate::where('id', $corporate->id)->update([
            'otp' => $otp,
        ]);
    }

    // Send OTP using SMS
    $authKey = "3636736465636b35323233";
    $senderId = "DRDECK";
    $route = "2";
    $country = "91";
    $dltTeId = "1707175066512828187";
    $message = urlencode("Dear user, your DriversDeck registration OTP is $otp. Please do not share this with anyone. - DRDECK");
    $url = "http://promo.smso2.com/api/sendhttp.php?authkey=$authKey&mobiles=$phone&message=$message&sender=$senderId&route=$route&country=$country&DLT_TE_ID=$dltTeId";

    file_get_contents($url);

    // Get subscription and user details
    $subscription = null;
    $expDate = null;
    $userId = null;
    $userType = null;
    $subscriptionStatus = null;
    $userName = null;
    $imgUrl = null;

    if ($driver) {
        $subscription = DB::table('subscription')
            ->where('f_id', $driver->id)
            ->where('type', $driver->type)
            ->latest('id')
            ->first();

        $userId = $driver->id;
        $userType = $driver->type;
        $userName = $driver->name;
        $gender = $driver->gender ?? 'male';
        $subscriptionStatus = $driver->subscription ?? 'no';

        // Get full image URL if exists
        // if ($driver->img) {
        //     $correctedImgPath = str_replace('license/', 'licenses/', $driver->img);
        //     $imagePath = public_path($correctedImgPath);

        //     if (file_exists($imagePath)) {
        //         $imgUrl = url($correctedImgPath);
        //     } else {
        //         Log::warning("Image not found at path: " . $imagePath);
        //     }
        // }
        if ($driver->img) {
    // Fix the path and ensure "public/" prefix exists
    $correctedImgPath = str_replace('license/', 'licenses/', $driver->img);

    if (strpos($correctedImgPath, 'public/') !== 0) {
        $correctedImgPath = 'public/' . ltrim($correctedImgPath, '/');
    }

    // Local file path for validation
    $imagePath = public_path(str_replace('public/', '', $correctedImgPath));

    if (file_exists($imagePath)) {
        $imgUrl = url($correctedImgPath);  // URL will include public/
    } else {
        Log::warning("Image not found at path: " . $imagePath);
    }
}

        
    } elseif ($corporate) {
        $subscription = DB::table('subscription')
            ->where('f_id', $corporate->id)
            ->where('type', $corporate->type)
            ->latest('id')
            ->first();

        $userId = $corporate->id;
        $userType = $corporate->type;
        $userName = $corporate->name;
        $gender = $corporate->gender ?? 'male';
        $subscriptionStatus = $corporate->subscription ?? 'no';

        // Get full image URL if exists
        if (isset($corporate->logo) && $corporate->logo) {
            $imgPath = $corporate->logo;
            $imagePath = public_path($imgPath);

            if (file_exists($imagePath)) {
                $imgUrl = url($imgPath);
            } else {
                Log::warning("Corporate logo not found at path: " . $imagePath);
            }
        }
    }

    $expDate = $subscription ? $subscription->exp_date : null;

    // Check subscription expiration
    $subscriptionExpired = false;
    $subscriptionMessage = '';

    if ($subscription && $expDate) {
        $currentDate = now()->format('Y-m-d');
        $expirationDate = \Carbon\Carbon::parse($expDate)->format('Y-m-d');
        
        if ($expirationDate < $currentDate) {
            $subscriptionExpired = true;
            $subscriptionMessage = 'Your subscription has expired on ' . \Carbon\Carbon::parse($expDate)->format('d-m-Y') . '. Please renew your subscription to continue.';
        }
    } elseif (!$subscription) {
        $subscriptionExpired = true;
        $subscriptionMessage = 'No active subscription found. Please purchase a subscription to continue.';
    }

    $ref_code = null;

    if ($driver && isset($driver->ref_code)) {
        $ref_code = $driver->ref_code;
    } elseif ($corporate && isset($corporate->ref_code)) {
        $ref_code = $corporate->ref_code;
    }

    $token = ($driver ?: $corporate)->createToken('auth_token')->plainTextToken;

    return response()->json([
        'status' => true,
        'message' => 'OTP sent successfully',
        'data' => [
            'id' => $userId,
            'name' => $userName,
            'gender' => $gender,
            'type' => $userType,
            'subscription_sts' => $subscriptionStatus,
            'subscription_expired' => $subscriptionExpired,
            'subscription_message' => $subscriptionMessage,
            'exp_date' => $expDate,
            'l_img' => $imgUrl,
            'otp' => $otp, // Remove in production
            'ref_code' => $ref_code,
            'token' => $token ?? 1,
            'active_status' => $driver ? $driver->active_status : ($corporate->active_status ?? null)// ✅ added here

        ]
    ], 200);
}



    public function resendOtp(Request $request)
    {

        // dd(auth());



        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:10',
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.numeric'  => 'Phone number must be numeric.',
            'phone.digits'   => 'Phone number must be 10 digits.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => implode(', ', $validator->errors()->all()),
            ], 422);
        }

        $phone = $request->phone;

        // Check if the driver exists
        $driver = Driver::where('phone', $phone)->first();

        if (!$driver) {
            return response()->json([
                'status' => 'error',
                'message' => 'Phone number not registered. Please sign up first.',
            ], 404);
        }

        // Generate OTP
        $otp = ($phone == '1234567891') ? 1234 : rand(1000, 9999);

        // SMS API Configuration for DriversDeck
        $authKey = "3636736465636b35323233";
        $senderId = "DRDECK";
        $route = "2";
        $country = "91";
        $dltTeId = "1707175066512828187";

        $message = urlencode("Dear user, your DriversDeck OTP is $otp. Please do not share this with anyone. - DRDECK");

        $url = "http://promo.smso2.com/api/sendhttp.php?authkey=$authKey&mobiles=$phone&message=$message&sender=$senderId&route=$route&country=$country&DLT_TE_ID=$dltTeId";

        // Send SMS
        $response = file_get_contents($url);

        // Update OTP
        $driver->update([
            'otp' => $otp,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP resent successfully',
            'data' => [
                'phone' => $phone,
                'otp_sent' => true,
                'otp' => $otp,
            ]
        ], 200);
    }





    public function TripAppliedStart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id'   => 'required|integer|exists:trip_applied,trip_id',
            'driver_id' => 'required|integer|exists:trip_applied,d_id',
            // Removed start_time and end_time from validation
            'start_loc'  => 'nullable|string',
            'end_loc'    => 'nullable|string',
            'status'     => 'required|in:Start,End',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        // Check if the application exists
        $record = DB::table('trip_applied')
            ->where('trip_id', $request->trip_id)
            ->where('d_id', $request->driver_id)
            ->first();

        if (!$record) {
            return response()->json([
                'status'  => false,
                'message' => 'No matching record found for update.',
            ], 200);
        }

        // Common fields
        $appliedUpdateData = [
            'status'     => $request->status,
            'updated_at' => now()
        ];

        // Conditionally update fields based on status
        if ($request->status === 'Start') {
            // Always store current datetime in start_time
            $appliedUpdateData['start_time'] = now()->format('Y-m-d H:i:s');

            if ($request->filled('start_loc')) {
                $appliedUpdateData['start_loc'] = $request->start_loc;
                $appliedUpdateData['crnt_loc']  = $request->start_loc; // Also update current location
            }
        }

        if ($request->status === 'End') {
            // Always store current datetime in end_time
            $appliedUpdateData['end_time'] = now()->format('Y-m-d H:i:s');

            if ($request->filled('end_loc')) {
                $appliedUpdateData['end_loc'] = $request->end_loc;
            }
        }

        // Update trip_applied table
        $tripAppliedUpdated = DB::table('trip_applied')
            ->where('trip_id', $request->trip_id)
            ->where('d_id', $request->driver_id)
            ->update($appliedUpdateData);

        // Update trip table status
        $tripUpdated = DB::table('trip')
            ->where('id', $request->trip_id)
            ->update([
                'status'     => $request->status,
                'updated_at' => now()
            ]);


        $trip_det =  Trip::find($request->trip_id);

        $cor = Corporate::find($trip_det->c_by);

        Notify::create([
            'type' => $cor->type,
            'f_id' => $cor->id,
            'prime_table' => $request->trip_id,
            'cat' => 'trip_' . $request->status,
            'title' => 'Your Trip Status Updated to : ' . $request->status,
            'body' => 'Your Trip Status Updated to : ' . $request->status,
            'status' => 'active',
            'c_by' => auth('sanctum')->user()->id, // Assuming you want to log who created this notification
        ])->save();




        if ($cor->token) {
            $fcm = new Fcm(); // ✅ Or use app(Fcm::class)
            $fcm->send_notify(
                $cor->token,
                'trip_' . $request->status,
                'Your Trip Status Updated to : ' . $request->status,
                'trip_update'
            );
        } else {
            // Log::warning("Driver token missing for driver ID: {$sub->d_id}");
        }

        if ($tripAppliedUpdated || $tripUpdated) {
            return response()->json([
                'status'  => true,
                'message' => 'Trip status updated successfully.',
            ], 200);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'No updates were made.',
            ], 200);
        }
    }



    public function trip_current_loc(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'trip_id'     => 'required|integer|exists:trip_applied,trip_id',
            'current_loc' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        // Check if trip_applied exists for this trip_id and driver
        $record = DB::table('trip_applied')
            ->where('trip_id', $request->trip_id)
            ->where('d_id', $user->id)
            ->first();

        if (!$record) {
            return response()->json([
                'status' => false,
                'message' => 'No trip_applied found for this driver and trip_id.',
            ]);
        }

        // Check the status
        if ($record->status !== 'Start') {
            return response()->json([
                'status' => false,
                'message' => "Trip exists, but status is not 'Start'. Current status: " . $record->status,
            ]);
        }

        // All clear, update current location
        DB::table('trip_applied')
            ->where('trip_id', $request->trip_id)
            ->where('d_id', $user->id)
            ->update([
                'crnt_loc'   => $request->current_loc,
                'updated_at' => now()
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Current location updated successfully.',
        ]);
    }



    public function uploadTripImage(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|integer|exists:trip_applied,trip_id',
            'image'   => 'required|image'
        ]);

        $image = $request->file('image');

        // Clean and format filename: remove spaces
        $originalName = $image->getClientOriginalName();
        $cleanedName = str_replace(' ', '_', $originalName); // replace spaces with _
        $filename = time() . '_' . $cleanedName;

        // Save image to trip_img folder
        $image->move(public_path('trip_img'), $filename);

        // Save to DB
        DB::table('trip')
            ->where('id', $request->trip_id)
            ->update([
                'trip_img'   => 'trip_img/' . $filename,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Trip image uploaded successfully.',
            'img_url' => asset('trip_img/' . $filename)
        ]);
    }



    //logout

    public function logout(Request $request)
    {
        $user = auth('sanctum')->user();

        if ($user) {
            // Revoke all tokens for the user
            $user->tokens()->delete();

            $user->token = null;

            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully.'
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'User not authenticated.'
        ], 401);
    }

    // profile edit details
    public function profile_edit_details(Request $request)
    {
        $user = auth('sanctum')->user(); // no need for 'sanctum' if using sanctum middleware
        log::info($user);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }
        $profile = Driver::find($user->id);
        if (!$profile) {
            return response()->json([
                'status' => false,
                'message' => 'Driver profile not found.',
            ], 404);
        }
        $details = DB::table('driver_details')->where('d_id', $user->id)->first();
        if (!$details) {
            return response()->json([
                'status' => false,
                'message' => 'Driver details not found.',
            ], 404);
        }

        $license = DB::table('license')->where('d_id', $user->id)->first();
        if (!$license) {
            return response()->json([
                'status' => false,
                'message' => 'Driver license not found.',
            ], 404);
        }

        $pro_det = [
            'blood_group' => $profile->b_group ?? null,
            'aadhar_number' => $profile->ad_num ?? null,
            'address' => $details->c_ad ?? null,
            'city' => $details->c_city ?? null,
            'state' => $details->c_state ?? null,
            'pincode' => ($details->c_pin == 0) ? null : $details->c_pin,
            'about' => $details->about ?? null,
            'exp_year' => $details->exp_year ?? null,
            'exp_mon' => $details->exp_mon ?? null,
            'p_com_name' => $details->p_com_name ?? null,
            'com_location' => $details->com_location ?? null,
            'contact_number' => $details->contact_number ?? null,
            'salary' => $details->current_salary ?? null,
            'pf' => $details->pf ?? null,

        ];

        if ($license->l_img) {
            // Fix the image path to use 'licenses' instead of 'license'
            // $correctedImgPath = str_replace('license/', 'licenses/', $license->l_img);
            $imagePath = public_path($license->l_img);

            if (file_exists($imagePath)) {
                $pro_det['l_img'] = asset($license->l_img);
            } else {
                Log::warning("Image not found at path: " . $imagePath);
                $pro_det['l_img'] = null; // Set to null if image not found
            }
        } else {
            $pro_det['l_img'] = null; // Set to null if no image exists
        }

        if ($license->aadhaar_img) {
            // Fix the image path to use 'licenses' instead of 'license'
            // $correctedAdImgPath = str_replace('license/', 'licenses/', $license->ad_1);
            $adImagePath = public_path($license->aadhaar_img);

            if (file_exists($adImagePath)) {
                $pro_det['ad_img'] = asset($license->aadhaar_img);
            } else {
                Log::warning("Adhar image not found at path: " . $adImagePath);
                $pro_det['ad_img'] = null; // Set to null if image not found
            }
        } else {
            $pro_det['ad_img'] = null; // Set to null if no adhar image exists
        }


        if ($profile) {
            return response()->json([
                'status' => true,
                'message' => 'Profile details retrieved successfully.',
                'data' => $pro_det
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Profile details not found.',
            ], 404);
        }
    }


    // profile step one update or insert
    public function profile_step_one(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'blood_group' => 'nullable|string|max:255',
                'aadhar_number' => 'nullable|numeric|digits:12',
                'flat' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'state' => 'nullable|string',
                'pincode' => 'nullable|numeric|digits:6',
                'about' => 'nullable|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = auth('sanctum')->user(); // no need for 'sanctum' if using sanctum middleware
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        $profile = Driver::find($user->id);

        if (!$profile) {
            return response()->json([
                'status' => false,
                'message' => 'Driver profile not found.',
            ], 404);
        }

        // Update profile
        $profile->update([
            'b_group' => $request->blood_group,
            'ad_num' => $request->aadhar_number,
        ]);

        // Update or insert into driver_details
        $up = DB::table('driver_details')->updateOrInsert(
            ['d_id' => $user->id],
            [
                'c_ad' => trim($request->flat . '/ ' . $request->address, ', '),
                'c_city' => $request->city,
                'c_state' => $request->state,
                'c_pin' => $request->pincode,
                'about' => $request->about,
                'updated_at' => now(),
            ]
        );

        if ($up) {
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully.',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update profile. Please try again.',
            ], 500);
        }
    }

    // profile update step two

    public function profile_step_two(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'exp_year' => 'nullable|string|max:255',
                'exp_mon' => 'nullable|string|max:255',
                'c_name' => 'nullable|string|max:255',
                'c_num' => 'nullable|numeric|digits:10',
                'c_loc' => 'nullable|string|max:255',
                'c_salary' => 'nullable|numeric|max:999999',
                'pf' => 'nullable|string|max:25'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = auth('sanctum')->user(); // no need for 'sanctum' if using sanctum middleware

        // Update or insert into driver_details
        $up = DB::table('driver_details')->updateOrInsert(
            ['d_id' => $user->id],
            [
                'exp_year' => $request->exp_year,
                'exp_mon' => $request->exp_mon,
                'p_com_name' => $request->c_name,
                'com_location' => $request->c_loc,
                'contact_number' => $request->c_num,
                'pf' => $request->pf,
                'current_salary' => $request->c_salary,
                'updated_at' => now(),
            ]
        );

        if ($up) {
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully.',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update profile. Please try again.',
            ], 500);
        }
    }

    // profile upate the adhard card and license image
    public function profile_step_three(Request $request)
    {
        Log::info("Uploaded Files", $request->allFiles());

        $validator = Validator::make(
            $request->allFiles(),
            [
                'ad_img' => 'nullable|mimes:jpeg,png,jpg,pdf,docx',
                'l_img' => 'nullable|mimes:jpeg,png,jpg,pdf,docx',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = auth('sanctum')->user(); // 'sanctum' is implied if middleware is used

        $aadhaar_file = null;
        $license_file = null;

        if ($request->hasFile('ad_img')) {
            $aadhaarImage = $request->file('ad_img');
            $aadhaar_file = 'licenses/ad_' . time() . '_' . uniqid() . '.' . $aadhaarImage->getClientOriginalExtension();
            $aadhaarImage->move(public_path('licenses'), $aadhaar_file);
        }

        if ($request->hasFile('l_img')) {
            $licenseImage = $request->file('l_img');
            $license_file = 'licenses/l_' . time() . '_' . uniqid() . '.' . $licenseImage->getClientOriginalExtension();
            $licenseImage->move(public_path('licenses'), $license_file);
        }
        // Update or insert into driver_details
        $up = DB::table('license')->updateOrInsert(
            ['d_id' => $user->id],
            [
                'l_img' => $license_file,
                'aadhaar_img' => $aadhaar_file,
                'updated_at' => now(),
            ]
        );

        if ($up) {
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully.',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update profile. Please try again.',
            ], 500);
        }
    }




    // public function acting_trip_list(Request $request)
    // {
    //     $user = auth('sanctum')->user();

    //     $driverId = $user->id;

    //     // dd($driverId);

    //     //  Get trips the driver applied for with status Hired or Start
    //     $appliedTrips = TripApplied::join('trip', 'trip.id', '=', 'trip_applied.trip_id')
    //         ->where('trip_applied.d_id', $user->id)
    //         ->whereIn('trip_applied.status', ['Hired', 'Start'])
    //         ->select('trip.*', 'trip_applied.d_id')
    //         ->orderBy('trip.created_at', 'desc')
    //         ->get();

    //     // $appliedTrips = TripApplied::where('trip_applied.d_id', $user->id)
    //     //     ->whereIn('trip_applied.status', ['Hired', 'Start'])
    //     //     ->select('trip_applied.*', 'trip_applied.d_id')
    //     //     ->orderBy('trip_applied.created_at', 'desc')
    //     //     ->get();


    //     // dd($appliedTrips);


    //     $currentTrips = [];
    //     $upcomingTrips = [];

    //     foreach ($appliedTrips as $trip) {

    //         if ((Carbon::parse($trip->st_date)->format('Y-m-d') < Carbon::today()->format('Y-m-d'))) {
    //             continue; // Skip if trip not found or date is in the past
    //         }

    //         $tripData = [
    //             'id'         => $trip->id,
    //             'owner_id'  => $trip->c_by,
    //             'title'      => $trip->title,
    //             'st_loc'     => $trip->st_city,
    //             'st_dest'    => $trip->end_city,
    //             'st_date'    => $trip->st_date ? Carbon::parse($trip->st_date)->format('d/m/Y') : 'N/A',
    //             'end_date'   => $trip->end_date ? Carbon::parse($trip->end_date)->format('d/m/Y') : 'N/A',
    //             'st_time'    => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i') : 'N/A',
    //             'status'     => $trip->status ?? 'N/A',
    //             'created_at' => $trip->created_at ? Carbon::parse($trip->created_at)->format('Y-m-d H:i:s') : 'N/A',
    //         ];

    //         if ($trip->status === 'Start') {
    //             $currentTrips[] = $tripData;
    //         } elseif ($trip->status === 'Hired') {
    //             $upcomingTrips[] = $tripData;
    //         }
    //     }

    //     // Count how many trips this driver completed (status = End in trip_applied)
    //     $completedCount = DB::table('trip_applied')
    //         ->where('d_id', $driverId)
    //         ->where('status', 'End')
    //         ->count();



    //     // Now call completion() using constructor
    //     $completion = $this->apiPermanent->completion();

    //     $d_status = collect([
    //         'd_status' => auth('sanctum')->user()->status,
    //         'number' => '+91 9600166427',
    //     ]);
    //     return response()->json([
    //         'status'          => true,
    //         'message'         => 'Trip list retrieved successfully.',
    //         'notification'    => 0,
    //         'completion'      => $completion->completion_percentage ?? 0,
    //         'completed_count' => $completedCount,
    //         'upcoming_count'  => count($upcomingTrips),
    //         'current_trip'    => $currentTrips,
    //         'upcoming_trip'   => $upcomingTrips,
    //         'd_status'   => $d_status,
    //         'subscription_sts' => $user->subscription,
    //     ], 200);
    // }
public function acting_trip_list(Request $request)
{
    $user = auth('sanctum')->user();
    $driverId = $user->id;

    // Get trips the driver applied for with status Hired or Start
    $appliedTrips = TripApplied::join('trip', 'trip.id', '=', 'trip_applied.trip_id')
        ->where('trip_applied.d_id', $driverId)
        ->whereIn('trip_applied.status', ['Hired', 'Start'])
        ->select('trip.*', 'trip_applied.d_id')
        ->orderBy('trip.created_at', 'desc')
        ->get();

    $currentTrips = [];
    $upcomingTrips = [];

    foreach ($appliedTrips as $trip) {
        if ((Carbon::parse($trip->st_date)->format('Y-m-d') < Carbon::today()->format('Y-m-d'))) {
            continue; // Skip if trip date is in the past
        }

        $tripData = [
            'id'         => $trip->id,
            'owner_id'   => $trip->c_by,
            'title'      => $trip->title,
            'st_loc'     => $trip->st_city,
            'st_dest'    => $trip->end_city,
            'st_date'    => $trip->st_date ? Carbon::parse($trip->st_date)->format('d/m/Y') : 'N/A',
            'end_date'   => $trip->end_date ? Carbon::parse($trip->end_date)->format('d/m/Y') : 'N/A',
            'st_time'    => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i') : 'N/A',
            'status'     => $trip->status ?? 'N/A',
            'created_at' => $trip->created_at ? Carbon::parse($trip->created_at)->format('Y-m-d H:i:s') : 'N/A',
        ];

        if ($trip->status === 'Start') {
            $currentTrips[] = $tripData;
        } elseif ($trip->status === 'Hired') {
            $upcomingTrips[] = $tripData;
        }
    }

    // Count how many trips this driver completed (status = End in trip_applied)
    $completedCount = DB::table('trip_applied')
        ->where('d_id', $driverId)
        ->where('status', 'End')
        ->count();

    // Get completion percentage
    $completion = $this->apiPermanent->completion();

    // Get driver status and reason if rejected or pending
    $driverStatus = $user->status;
    $reason = null;

    if (in_array($driverStatus, ['rejected', 'pending'])) {
        $action = $driverStatus === 'rejected' ? 'reject' : 'pending';

        $latestReason = \App\Models\ApprovalReason::where('user_id', $driverId)
            ->where('user_type', $user->type)
            ->whereRaw('LOWER(action) = ?', [strtolower($action)]) // ✅ case-insensitive match
            ->latest()
            ->first();

        $reason = $latestReason ? $latestReason->reason : null;
    }

    $d_status = [
        'd_status' => $driverStatus,
        'number'   => '+91 9600166427',
        'reason'   => $reason,
    ];

    return response()->json([
        'status'          => true,
        'message'         => 'Trip list retrieved successfully.',
        'notification'    => 0,
        'completion'      => $completion->completion_percentage ?? 0,
        'completed_count' => $completedCount,
        'upcoming_count'  => count($upcomingTrips),
        'current_trip'    => $currentTrips,
        'upcoming_trip'   => $upcomingTrips,
        'd_status'        => $d_status,
        'subscription_sts'=> $user->subscription,
    ], 200);
}




    public function current_trip_profile(Request $request)
    {
        $user = auth('sanctum')->user();
        $driverId = $user->id;

        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|integer|exists:trip,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $trip = Trip::find($request->trip_id);

        if (!$trip) {
            return response()->json([
                'status'  => false,
                'message' => 'Trip not found.',
                'data'    => []
            ], 200);
        }

        $owner = DB::table('corporate')->where('id', $trip->c_by)->first();

        $tripApplied = DB::table('trip_applied')
            ->where('trip_id', $request->trip_id)
            ->where('d_id', $driverId)
            ->first();

        $data = [
            'trip_id' => $trip->id,
            'img'     => $owner->logo ? asset($owner->logo) : 'N/A',
            'name'    => $owner->name ?? 'N/A',
            'contact' => $owner->contact ?? 'N/A',
            't_code'  => $tripApplied->trip_code ?? 'N/A',
            'st_loc'  => $tripApplied->start_loc ?? 'N/A',
            'end_loc' => $trip->dest_cord ?? 'N/A',
            'trip_img' => $trip->trip_img ? asset($trip->trip_img) : 'null',
        ];

        return response()->json([
            'status'  => true,
            'message' => 'Basic trip details retrieved successfully.',
            'data'    => $data
        ], 200);
    }



    public function feedback_list(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'driver_id' => 'required|integer|exists:driver,id',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }


        $user = auth('sanctum')->user();
        $driverId = $user->id;

        // $driverId = $request->driver_id;

        $feedbacks = DB::table('feedback')
            ->join('trip', 'feedback.t_id', '=', 'trip.id')
            ->join('corporate', 'trip.c_by', '=', 'corporate.id')
            ->where('feedback.d_id', $driverId)
            ->where('feedback.status', 'approve')
            ->select(
                'corporate.name as owner_name',
                'trip.st_city',
                'trip.end_city',
                'feedback.remarks',
                'feedback.rating',
                'feedback.created_at'
            )
            ->orderBy('feedback.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Driver feedback summary retrieved successfully.',
            'feedbacks' => $feedbacks,
        ]);
    }




    public function getDriverSummary(Request $request)
{
    // Get authenticated user
    $user = auth('sanctum')->user();

    if (!$user) {
        return response()->json([
            'status'  => false,
            'message' => 'Unauthorized access.',
        ], 200);
    }

    $driverId = $user->id;

    // Fetch feedback statistics for this driver
    $feedbackStats = DB::table('feedback')
        ->where('d_id', $driverId)
        ->where('status', 'approve')
        ->selectRaw('COUNT(*) as total_ratings, AVG(rating) as average_rating')
        ->first();

    // Get completion percentage from service/class
    $completion = $this->apiPermanent->completion();

    $updatedCount = Notify::where('f_id', $user->id)->where('type', $user->type)->where('seen', 0)->count();

    $driver = Driver::find($driverId);
    
    // Check if driver has pending type change request
    $hasPendingTypeChangeRequest = false;
    if ($driver) {
        $hasPendingTypeChangeRequest = $driver->hasPendingTypeChangeRequest();
    }

    return response()->json([
        'status'        => true,
        'message'       => 'Driver summary retrieved successfully.',
        'd_id'          => $driverId,
        'notification'  => $updatedCount,
        'completion'    => $completion->completion_percentage ?? 0,
        'ratings'       => $feedbackStats->total_ratings ?? 0,
        'avg_rating'    => round($feedbackStats->average_rating, 2) ?? 0,
        'pending_status' => $hasPendingTypeChangeRequest,
    ]);
}


public function switchDriverType(Request $request)
{
    $user = auth('sanctum')->user();
    if (!$user) {
        return response()->json([
            'status'  => false,
            'message' => 'Unauthorized access.',
        ], 200);
    }

    $driver = Driver::find($user->id);
    if (!$driver) {
        return response()->json([
            'status'  => false,
            'message' => 'Driver not found.',
        ], 200);
    }

    // Check if driver already has a pending request
    if ($driver->hasPendingTypeChangeRequest()) {
        $pendingRequest = $driver->pendingTypeChangeRequest;
        return response()->json([
            'status'  => false,
            'message' => 'You already have a pending type change request.',
            'pending_request' => [
                'request_id' => $pendingRequest->id,
                'current_type' => $pendingRequest->previous_type,
                'requested_to' => $pendingRequest->change_type_to,
                'request_date' => $pendingRequest->created_at->format('d-m-Y'),
            ]
        ], 200);
    }

    // Determine target type
    $targetType = null;
    if ($driver->type === 'acting') {
        $targetType = 'permanent';
    } elseif ($driver->type === 'permanent') {
        $targetType = 'acting';
    } else {
        return response()->json([
            'status'  => false,
            'message' => 'Invalid driver type. Only acting and permanent drivers can switch types.',
        ], 200);
    }

    // Create new type change request
    try {
        $changeRequest = DriverTypeChangeRequest::create([
            'driver_id' => $driver->id,
            'previous_type' => $driver->type,
            'change_type_to' => $targetType,
            'request_status' => 'pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Type change request submitted successfully. Waiting for admin approval.',
            'request_details' => [
                'request_id' => $changeRequest->id,
                'driver_id' => $driver->id,
                'driver_name' => $driver->name,
                'current_type' => $driver->type,
                'requested_to' => $changeRequest->change_type_to,
                'request_status' => $changeRequest->request_status,
                'request_date' => $changeRequest->created_at->format('d-m-Y H:i:s'),
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to submit type change request. Please try again.',
            'error' => $e->getMessage()
        ], 500);
    }
}





    public function delete_account(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|min:5',
        ]);


        $user = auth('sanctum')->user();
        $userId = $user->id;


        $type = $user->type ?? (new \ReflectionClass($user))->getShortName();
        // Determine table name based on user type
        if ($type === 'owner' || $type === 'corporate') {
            $table = 'corporate';
        } else {
            $table = 'driver';
        }


        DB::table('delete_acc')->insert([
            'type'       => $type,
            'reason'     => $request->reason,
            'status'     => 'delete',
            'c_by'       => $userId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table($table)
            ->where('id', $userId)
            ->update(['status' => 'delete']);


        return response()->json([
            'status' => true,
            'message' => 'Account delete status updated successfully.'
        ]);
    }


    // public function for  notify_list

    public function notify_list()
    {

        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        }


        $list = Notify::where('f_id', $user->id)->where('type', $user->type)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $list
        ]);
    }


    // notify update

    public function notify_update(Request $req)
    {
        // log::info(auth('sanctum')->user()->id);
        // log::info(auth('sanctum')->user()->type);
        // log::info($req->prime_table);

        $up = Notify::where('f_id', auth('sanctum')->user()->id)->where('type', auth('sanctum')->user()->type)->where('prime_table', $req->prime_table)->where('seen', 0)->update(['seen' => 1]);

        return response()->json([
            'status' => true,
            'data' => 'Updated Successfully'
        ]);
    }
    public function available_districts() {
    // Fetch distinct districts from location_active (only active ones)
    $districts = DB::table('district as d')
        ->join('location_active as l', 'd.id', '=', 'l.district')
        ->where('d.status', 'active')
        ->where('l.status', 'active')
        ->select('d.id as district_id', 'd.district as district_name')
        ->distinct()
        ->orderBy('d.district')
        ->get();

    return response()->json([
        'message' => 'Available active districts fetched successfully',
        'data' => $districts
    ]);
}

}
