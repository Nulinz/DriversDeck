<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Corporate;
use App\Models\Subscription;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\TripApplied;
use App\Jobs\Trip_notify;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Notify;
use App\Services\Fcm;
use Illuminate\Support\Str;

class Api_owner extends Controller
{

    public function owner_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'location' => 'required|string',
            'district' => 'required|string',
            'gender' => 'required|string',
            // 'ref_code' => 'nullable|string',
            'ad_1' => 'required|string',
            'ad_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'pin' => 'required|digits:6',
            'contact' => 'required|digits:10|unique:corporate,contact',
        ], [
            'name.required' => 'Full name is required.',
            'location.required' => 'Location is required.',
            'district.required' => 'District is required.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be male, female, or other.',
            'ad_1.required' => 'Address Line 1 is required.',
            'city.required' => 'City is required.',
            'state.required' => 'State is required.',
            'pin.required' => 'Pincode is required.',
            'pin.digits' => 'Pincode must be 6 digits.',
            'contact.required' => 'Mobile number is required.',
            'contact.digits' => 'Mobile number must be 10 digits.',
            'contact.unique' => 'This contact number is already registered.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        // Generate OTP
        $otp = $request->contact === '1234567890' ? 1234 : rand(1000, 9999);
        $phone = $request->contact; // Using contact as phone number

        $count = Corporate::where('type', 'owner')->count();
        $ref_code = 'DDOWN' . str_pad($count + 1, 3, '0', STR_PAD_LEFT); // e.g., DDOWN001




        // Insert the new user and get the ID first
        $data = [
            'name' => $request->name,
            'location' => $request->location,
            'district' => $request->district,
            'gender' => $request->gender,
            'ref_code' => $ref_code,
            'ad_1' => $request->ad_1,
            'ad_2' => $request->ad_2,
            'city' => $request->city,
            'state' => $request->state,
            'pin' => $request->pin,
            'contact' => $phone,
            'type' => 'owner',
            'subscription' => 'no',
            'otp' => $otp,
            'c_by' => auth('sanctum')->user(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Insert the new user
        $id = DB::table('corporate')->insertGetId(array_merge($data, ['c_by' => null]));


        // Fetch the newly created corporate record
        $corporate = Corporate::find($id);


        if ($request->ref_by_code) {

            if (Str::startsWith($request->ref_by_code, 'DDOWN')) {
                // Handle Corporate model logic
                $ref_driver = Corporate::where('ref_code', $request->ref_by_code)->first();
                // Do something with $corporate
            } else {
                // Handle Driver model logic
                $ref_driver = Driver::where('ref_code', $request->ref_by_code)->first();
                // Do something with $driver
            }


            // $ref_driver = Driver::where('ref_code', $req->ref_by_code)->exists();

            // if ($ref_driver) {
            // Insert referral record
            DB::table('referal')->insert([
                'code' => $request->ref_by_code ?? 'direct',
                'ref_type' => $ref_driver->type ?? 0,     // Use 0 if not found
                'ref_by' => $ref_driver->id ?? 0,         // Use 0 if not found
                'f_type' => $corporate->type ?? 1,
                'f_id' => $corporate->id ?? 1,
                'amt' => $ref_driver ? 10 : 0, // Give 10 only if ref_driver exists
                'c_by' => $corporate->id ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // }
        }

        $token = $corporate ? $corporate->createToken('auth_token')->plainTextToken : null;

        // Optionally, update the 'c_by' field with the correct ID if needed
        DB::table('corporate')->where('id', $id)->update(['c_by' => $id]);
        $data['c_by'] = $id;


        // Send OTP via SMS
        $authKey = "3636736465636b35323233";
        $senderId = "DRDECK";
        $route = "2";
        $country = "91";
        $dltTeId = "1707175066512828187";
        $message = urlencode("Dear user, your DriversDeck registration OTP is $otp. Please do not share this with anyone. - DRDECK");

        $url = "http://promo.smso2.com/api/sendhttp.php?authkey=$authKey&mobiles=$phone&message=$message&sender=$senderId&route=$route&country=$country&DLT_TE_ID=$dltTeId";

        // Send SMS (you might want to queue this in production)
        $smsResponse = file_get_contents($url);

        return response()->json([
            'status' => true,
            'message' => 'Owner registered successfully. OTP sent to your mobile.',
            'data' => $data,
            'token' => $token,
        ], 200);
    }

    public function getSubscriptionInfo(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
        ]);

        $plans = [];

        if ($request->type === 'acting') {
            $plans = [
                ['plan' => 3, 'amount' => '400'],
                ['plan' => 6, 'amount' => '700'],
                // ['plan' => 12,  'amount' => '1200'],
                ['plan' => 12, 'amount' => '1200'],
            ];
        } elseif ($request->type === 'permanent') {
            // permanent user 
            $plans = [];
        } elseif ($request->type === 'owner') {
            // owner user
            $plans = [];
        } else {
            // any other types – return empty plans
            $plans = [];
        }

        return response()->json([
            'status' => true,
            'message' => 'Subscription plan info',
            'data' => [
                'type' => $request->type,
                'plans' => $plans
            ]
        ], 200);
    }

    public function device_token(Request $request)
    {

        $user = auth('sanctum')->user(); // or $request->user()

        // Log::info('Authenticated User ID: ' . ($user ?? 'none'));


        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:acting,permanent,owner,corporate',
            'token' => 'required|string',
            'otp_sts' => 'required|string',

        ], [
            'id.required' => 'user is required.',
            'id.integer' => 'User ID must be a number.',
            'type.required' => 'Type is required.',
            'token.required' => 'Token is required.',
            'otp_sts.required' => 'OTP status is required.',
        ]);

        if ($request->type === 'owner' || $request->type === 'corporate') {
            $corporateExists = DB::table('corporate')
                ->where('id', $request->id)
                ->exists();

            if ($corporateExists) {
                DB::table('corporate')
                    ->where('id', $request->id)
                    ->update([
                        'token' => $request->token,
                    ]);
            } else {
                // Log::info("{$request->id} not found in corporate table.");
                return response()->json([
                    'status' => false,
                    'message' => "Corporate ID {$request->id} not found."
                ], 404);
            }
        }

        if ($request->type === 'acting' || $request->type === 'permanent') {
            $driverExists = DB::table('driver')
                ->where('id', $request->id)
                ->exists();

            if ($driverExists) {
                DB::table('driver')
                    ->where('id', $request->id)
                    ->update([
                        'token' => $request->token,
                    ]);
            } else {
                Log::warning("Driver ID {$request->id} not found in driver table.");
                return response()->json([
                    'status' => false,
                    'message' => "Driver ID {$request->id} not found."
                ], 404);
            }
        }


        return response()->json([
            'status' => true,
            'message' => 'Device token updated successfully',

        ], 200);
    }






    public function add_subscription(Request $request)
    {
        $request->merge([
            'plan' => (string) $request->plan
        ]);

        $request->validate([
            'f_id' => 'required|integer',
            'type' => 'required|in:owner,corporate,acting,permanent',
            'plan' => 'required|in:3,6,9,12',
            't_id' => 'required|string',
            'amount' => 'required|string',
            'payment_screenshot' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'f_id.required' => 'Foreign ID (f_id) is required.',
            'f_id.integer' => 'Foreign ID must be a number.',
            'type.required' => 'Type is required.',
            'type.in' => 'Type must be one of owner, corporate, acting, or permanent.',
            'plan.required' => 'Plan is required.',
            'plan.in' => 'Plan must be one of: 3, 6, 9, or 12 months.',
            't_id.string' => 'Transaction ID must be a string.',
            'amount.string' => 'Amount must be a string.',
        ]);

        $planMonths = (int) $request->plan;
        // $expDate = now()->addMonths($planMonths)->format('Y-m-d');

        // Handle file upload
        $screenshotPath = null;
        if ($request->hasFile('payment_screenshot')) {
            $file = $request->file('payment_screenshot');
            $filename = 'payment_' . $request->f_id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Move directly to public/payment_screenshots
            $file->move(public_path('payment_screenshots'), $filename);

            // Save path
            $screenshotPath = 'payment_screenshots/' . $filename;
        }

        $subscription = Subscription::create([
            'f_id' => $request->f_id,
            'type' => $request->type,
            'plan' => (string) $planMonths,
            't_id' => $request->t_id,
            'amount' => $request->amount,
            'paid_sts' => 'success',
            'status' => 'active',
            'payment_screenshot' => $screenshotPath,
            'c_by' => auth('sanctum')->user()->id ?? 1,
        ]);

        // Update corporate table for 'owner' and 'corporate' types
        if ($request->type === 'owner' || $request->type === 'corporate') {
            $corporateExists = DB::table('corporate')
                ->where('id', $request->f_id)
                ->exists();

            if ($corporateExists) {
                DB::table('corporate')
                    ->where('id', $request->f_id)
                    ->update([
                        'subscription' => 'yes',
                    ]);
            } else {
                Log::warning("Corporate ID {$request->f_id} not found in corporate table.");
            }
        }

        // Update driver table for 'acting' and 'permanent' types
        if ($request->type === 'acting' || $request->type === 'permanent') {
            $driverExists = DB::table('driver')
                ->where('id', $request->f_id)
                ->exists();

            if ($driverExists) {
                DB::table('driver')
                    ->where('id', $request->f_id)
                    ->update(['subscription' => 'progress']);
            } else {
                Log::warning("Driver ID {$request->f_id} not found in driver table.");
            }
        }

        // Fetch the actual subscription status from the corresponding table
        $subscriptionStatus = null;

        if ($request->type === 'owner' || $request->type === 'corporate') {
            $subscriptionStatus = DB::table('corporate')
                ->where('id', $request->f_id)
                ->value('subscription');
        } elseif ($request->type === 'acting' || $request->type === 'permanent') {
            $subscriptionStatus = DB::table('driver')
                ->where('id', $request->f_id)
                ->value('subscription');
        }

        return response()->json([
            'status' => true,
            'message' => 'Subscription added successfully',
            'data' => [
                'f_id' => (string) $subscription->f_id,
                'type' => $subscription->type,
                'plan' => (string) $subscription->plan,
                't_id' => $subscription->t_id,
                'amount' => $subscription->amount,
                'paid_sts' => $subscription->paid_sts,
                'status' => $subscriptionStatus ?? 'N/A',
                'exp_date' => $subscription->exp_date,
                'payment_screenshot' => $subscription->payment_screenshot,
                'number' => '123456789',
            ]
        ], 200);
    }

    public function checkTransactionId(Request $request)
    {
        $request->validate([
            't_id' => 'required|string',
        ]);
        $existingTransaction = Subscription::where('t_id', $request->t_id)->first();

        if ($existingTransaction) {
            return response()->json([
                'status' => true,
                'message' => 'This transaction ID has already been used.',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Transaction ID is available.',
        ], 200);
    }

    public function owner_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:10',
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.numeric' => 'Phone number must be numeric.',
            'phone.digits' => 'Phone number must be 10 digits.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => implode(', ', $validator->errors()->all()),
            ], 200);
        }

        $phone = $request->phone;
        $otp = $phone === '1234567890' ? 1234 : rand(1000, 9999);

        // Check if corporate user exists
        $corporate = DB::table('corporate')->where('contact', $phone)->first();

        if (!$corporate) {
            return response()->json([
                'status' => false,
                'message' => 'Corporate user not found',
            ], 200);
        }

        // Update OTP in corporate table
        DB::table('corporate')->where('id', $corporate->id)->update([
            'otp' => $otp,
        ]);

        // Send OTP using SMS 
        $authKey = "3636736465636b35323233";
        $senderId = "DRDECK";
        $route = "2";
        $country = "91";
        $dltTeId = "1707175066512828187";
        $message = urlencode("Dear user, your DriversDeck registration OTP is $otp. Please do not share this with anyone. - DRDECK");
        $url = "http://promo.smso2.com/api/sendhttp.php?authkey=$authKey&mobiles=$phone&message=$message&sender=$senderId&route=$route&country=$country&DLT_TE_ID=$dltTeId";

        // Send the SMS request
        file_get_contents($url);

        // Get latest subscription details for corporate
        $subscription = DB::table('subscription')
            ->where('f_id', $corporate->id)
            ->where('type', 'owner')
            ->latest('id')
            ->first();


        $expDate = $subscription ? $subscription->exp_date : null;

        // Log::info('Fetched Subscription Expiry Date: ' . $expDate);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully',
            'data' => [
                'user_id' => $corporate->id,
                'subscription_sts' => $corporate->subscription,
                'exp_date' => $expDate,
                'otp' => $otp, // remove in prod for security
            ]
        ], 200);
    }


    public function resendOwnerOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits:10',
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.numeric' => 'Phone number must be numeric.',
            'phone.digits' => 'Phone number must be 10 digits.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => implode(', ', $validator->errors()->all()),
            ], 200);
        }

        $phone = $request->phone;

        // Check if corporate user exists
        $corporate = DB::table('corporate')->where('contact', $phone)->first();

        if (!$corporate) {
            return response()->json([
                'status' => false,
                'message' => 'Corporate user not found',
            ], 200);
        }

        // Generate OTP
        $otp = $phone === '1234567890' ? 1234 : rand(1000, 9999);

        // Update OTP
        DB::table('corporate')->where('id', $corporate->id)->update([
            'otp' => $otp,
        ]);

        // Send OTP via SMS
        $authKey = "3636736465636b35323233";
        $senderId = "DRDECK";
        $route = "2";
        $country = "91";
        $dltTeId = "1707175066512828187";

        $message = urlencode("Dear user, your DriversDeck OTP is $otp. Please do not share this with anyone. - DRDECK");

        $url = "http://promo.smso2.com/api/sendhttp.php?authkey=$authKey&mobiles=$phone&message=$message&sender=$senderId&route=$route&country=$country&DLT_TE_ID=$dltTeId";

        // Send SMS
        file_get_contents($url);

        // Fetch latest subscription info
        $subscription = DB::table('subscription')
            ->where('f_id', $corporate->id)
            ->where('type', 'owner')
            ->latest('id')
            ->first();

        $expDate = $subscription ? $subscription->exp_date : null;

        return response()->json([
            'status' => true,
            'message' => 'OTP resent successfully',
            'data' => [
                'user_id' => $corporate->id,
                'otp' => $otp, //  remove in production
            ]
        ], 200);
    }




    // public function trip_create(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'from_address' => 'required|string',
    //         'to_address' => 'required|string',
    //         'start_city' => 'required|string',
    //         'end_city' => 'required|string',
    //         'start_lat' => 'required|numeric',
    //         'start_lng' => 'required|numeric',
    //         'end_lat' => 'required|numeric',
    //         'end_lng' => 'required|numeric',
    //         'veh_type' => 'required|string',
    //         'veh_name' => 'nullable|string',
    //         'veh_number' => 'required|string|max:20',
    //         'contact_number' => 'required|digits:10',
    //         'alternate_number' => 'nullable|digits:10',
    //         'start_date' => 'required|date',
    //         'end_date' => 'required|date',
    //         'start_time' => 'required',
    //         'no_of_days' => 'required|integer',
    //         'd_type' => 'required|string',

    //     ], [
    //         'from_address.required' => 'From address is required.',
    //         'to_address.required' => 'To address is required.',
    //         'start_city.required' => 'Start city is required.',
    //         'end_city.required' => 'End city is required.',
    //         'start_lat.required' => 'Start latitude is required.',
    //         'start_lng.required' => 'Start longitude is required.',
    //         'end_lat.required' => 'End latitude is required.',
    //         'end_lng.required' => 'End longitude is required.',
    //         'veh_type.required' => 'Vehicle type is required.',
    //         'veh_name.required' => 'Vehicle name is required.',
    //         'veh_number.required' => 'Vehicle number is required.',
    //         'contact_number.required' => 'Contact number is required.',
    //         'contact_number.digits' => 'Contact number must be 10 digits.',
    //         'start_date.required' => 'Start date is required.',
    //         'end_date.required' => 'End date is required.',
    //         'start_time.required' => 'Start time is required.',
    //         'no_of_days.required' => 'Number of days is required.',
    //         'd_type.required' => 'Driver type is required.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 200);
    //     }

    //     $data = [
    //         'st_loc' => $request->from_address,
    //         'st_dest' => $request->to_address,
    //         'st_city' => $request->start_city,
    //         'end_city' => $request->end_city,
    //         'st_cord' => $request->start_lat,
    //         'start_lat' => $request->start_lat,
    //         'start_lng' => $request->start_lng,
    //         'end_cord' => $request->start_lng,
    //         'dest_cord' => $request->end_lat . ',' . $request->end_lng,
    //         'title' => 'Acting Driver Job',
    //         'con_number' => $request->contact_number,
    //         'alter_number' => $request->alternate_number,
    //         // 'st_date'      => Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d'),
    //         // 'end_date'     => Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d'),

    //         'st_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
    //         'end_date' => Carbon::parse($request->end_date)->format('Y-m-d'),

    //         'st_time' => $request->start_time,
    //         'no_days' => $request->no_of_days,
    //         'veh_type' => $request->veh_type,
    //         'veh_name' => $request->veh_name,
    //         'veh_number' => $request->veh_number,
    //         'd_type' => $request->d_type,
    //         'status' => 'pending',
    //         'c_by' => auth('sanctum')->user()->id ?? 1,
    //     ];

    //     // Log::info($request->all());

    //     $trip = Trip::create($data);

    //     if ($trip) {

    //         // $locationParts = explode(',', $location->cord);
    //         $lat1 = $request->start_lat;
    //         $lon1 = $request->start_lng;
    //         $radius = 50;

    //         $all_loc = DB::table('location_active')
    //             ->where('status', 'active')
    //             ->select('id', 'location', 'cord', 'status')
    //             ->get();

    //         $nearbyLocations = [];

    //         foreach ($all_loc as $loc) {
    //             // if (!$loc->cord) {
    //             //     continue;
    //             // }

    //             $cordParts = explode(',', $loc->cord);
    //             if (count($cordParts) !== 2) {
    //                 continue;
    //             }

    //             $lat2 = trim($cordParts[0]);
    //             $lon2 = trim($cordParts[1]);

    //             $distance = $this->calculateDistance($lat1, $lon1, $lat2, $lon2);

    //             if ($distance <= $radius) {
    //                 $loc->distance = round($distance, 2); // optional: show how far it is
    //                 $nearbyLocations[] = $loc;
    //             }
    //         }

    //         // Log::info('Nearby Locations:', ['locations' => $nearbyLocations]);

    //         $search_loc = collect($nearbyLocations)->pluck('id')->toArray();

    //         $d_type = $trip->d_type; // could be 'male', 'female', or 'both'

    //         $driverQuery = Driver::where('type', 'acting')
    //             ->where('status', '!=', 'pending')
    //             ->whereIn('location', $search_loc);

    //         if ($d_type !== 'both') {
    //             $driverQuery->where('gender', $d_type); // Replace 'gender' with the correct column
    //         }

    //         $driver = $driverQuery->pluck('id')->toArray();

    //         // $d_type = $trip->d_type=='male' ? 


    //         // $driver = Driver::where('type', 'acting')->where('status', '!=', 'pending')->whereIn('location', $search_loc)->pluck('id')->toArray();

    //         if (count($driver) != 0) {
    //             Trip_notify::dispatch($driver, $trip->id, 'trip_posted', auth('sanctum')->user()->id);
    //         }
    //     }



    //     return response()->json([
    //         'message' => 'Trip created successfully',
    //         'trip_id' => $trip->id
    //     ]);
    // }



    public function trip_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_address' => 'required|string',
            'to_address' => 'required|string',
            'start_city' => 'required|string',
            'end_city' => 'required|string',
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
            'veh_type' => 'required|string',
            'veh_name' => 'nullable|string',
            'veh_number' => 'required|string|max:20',
            'contact_number' => 'required|digits:10',
            'alternate_number' => 'nullable|digits:10',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'no_of_days' => 'required|integer',
            'd_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $trip = Trip::create([
            'st_loc' => $request->from_address,
            'st_dest' => $request->to_address,
            'st_city' => $request->start_city,
            'end_city' => $request->end_city,
            'st_cord' => $request->start_lat,
            'start_lat' => $request->start_lat,
            'start_lng' => $request->start_lng,
            'end_cord' => $request->start_lng,
            'dest_cord' => $request->end_lat . ',' . $request->end_lng,
            'title' => 'Acting Driver Job',
            'con_number' => $request->contact_number,
            'alter_number' => $request->alternate_number,
            'st_date' => Carbon::parse($request->start_date)->format('Y-m-d'),
            'end_date' => Carbon::parse($request->end_date)->format('Y-m-d'),
            'st_time' => $request->start_time,
            'no_days' => $request->no_of_days,
            'veh_type' => $request->veh_type,
            'veh_name' => $request->veh_name,
            'veh_number' => $request->veh_number,
            'd_type' => $request->d_type,
            'status' => 'pending', // 🔒 always pending
            'c_by' => auth('sanctum')->user()->id ?? 1,
        ]);

        return response()->json([
            'message' => 'Trip submitted successfully. Waiting for admin approval.',
            'trip_id' => $trip->id
        ]);
    }


    public function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Cast all inputs to float
        $lat1 = (float) $lat1;
        $lon1 = (float) $lon1;
        $lat2 = (float) $lat2;
        $lon2 = (float) $lon2;

        $earthRadius = 6371; // Earth radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // distance in km
    }

    // public function owner_trip_list(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'owner_id' => 'required|integer|exists:corporate,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 200);
    //     }

    //     $trips = Trip::where('c_by', $request->owner_id)
    //         ->whereIn('status', ['pending', 'Hired', 'Cancel Requested', 'Start'])
    //         ->orderBy('created_at', 'desc')
    //         ->get();

    //     $currentTrips   = [];
    //     $upcomingTrips  = [];
    //     $plannedTrips   = [];

    //     foreach ($trips as $trip) {
    //         $applied = TripApplied::where('trip_id', $trip->id)
    //             ->whereIn('status', ['Hired', 'Start', 'Cancel Requested']) // stricter for proper matches
    //             ->orderBy('id', 'desc')
    //             ->first();

    //         $driver = $applied && $applied->d_id
    //             ? Driver::find($applied->d_id)
    //             : null;

    //         // For debugging
    //         // Log::info('Trip ID: ' . $trip->id, [
    //         //     'Applied' => $applied,
    //         //     'Driver'  => $driver,
    //         // ]);

    //         $tripData = [
    //             'id'          => $trip->id,
    //             'driver_id'   => $driver ? $driver->id : 'N/A',
    //             'driver_name' => $driver ? $driver->name : 'N/A',
    //             'title'       => $trip->title ?? 'N/A',
    //             'st_loc'      => $trip->st_city ?? 'N/A',
    //             'st_dest'     => $trip->end_city ?? 'N/A',
    //             'st_date'     => $trip->st_date ? Carbon::parse($trip->st_date)->format('d/m/Y') : 'N/A',
    //             'end_date'    => $trip->end_date ? Carbon::parse($trip->end_date)->format('d/m/Y') : 'N/A',
    //             'st_time'     => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i') : 'N/A',
    //             'status'      => $trip->status ?? 'N/A',
    //             'created_at'  => $trip->created_at ? Carbon::parse($trip->created_at)->format('Y-m-d H:i:s') : 'N/A',
    //         ];

    //         switch ($trip->status) {
    //             case 'Start':
    //                 $currentTrips[] = $tripData;
    //                 break;
    //             case 'Hired':
    //             case 'Cancel Requested':
    //                 $upcomingTrips[] = $tripData;
    //                 break;
    //             case 'pending':
    //                 $plannedTrips[] = $tripData;
    //                 break;
    //         }
    //     }

    //     return response()->json([
    //         'status'         => true,
    //         'message'        => 'Trip list retrieved successfully.',
    //         'notification'   => 0,
    //         'current_trip'   => $currentTrips,
    //         'upcoming_trip'  => $upcomingTrips,
    //         'planned_trip'   => $plannedTrips,
    //         'owner_status' => auth('sanctum')->user()->status
    //     ]);
    // }

    public function owner_trip_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'owner_id' => 'required|integer|exists:corporate,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        // Explicitly exclude 'Cancel' status trips
        $trips = Trip::where('c_by', $request->owner_id)
            ->whereIn('status', ['pending', 'Hired', 'Cancel Requested', 'Start'])
            ->where('status', '!=', 'Cancel') // Extra safety to exclude Cancel status
            ->orderBy('created_at', 'desc')
            ->get();

        $currentTrips = [];
        $upcomingTrips = [];
        $plannedTrips = [];

        foreach ($trips as $trip) {
            // Additional check to ensure we never process Cancel status trips
            if ($trip->status === 'Cancel') {
                continue;
            }

            $applied = TripApplied::where('trip_id', $trip->id)
                ->whereIn('status', ['Hired', 'Start', 'Cancel Requested']) // stricter for proper matches
                ->orderBy('id', 'desc')
                ->first();

            $driver = $applied && $applied->d_id
                ? Driver::find($applied->d_id)
                : null;

            $tripData = [
                'id' => $trip->id,
                'driver_id' => $driver ? $driver->id : 'N/A',
                'driver_name' => $driver ? $driver->name : 'N/A',
                'title' => $trip->title ?? 'N/A',
                'st_loc' => $trip->st_city ?? 'N/A',
                'st_dest' => $trip->end_city ?? 'N/A',
                'st_date' => $trip->st_date ? Carbon::parse($trip->st_date)->format('d/m/Y') : 'N/A',
                'end_date' => $trip->end_date ? Carbon::parse($trip->end_date)->format('d/m/Y') : 'N/A',
                'st_time' => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i') : 'N/A',
                'status' => $trip->status ?? 'N/A',
                'created_at' => $trip->created_at ? Carbon::parse($trip->created_at)->format('Y-m-d H:i:s') : 'N/A',
            ];

            switch ($trip->status) {
                case 'Start':
                    $currentTrips[] = $tripData;
                    break;
                case 'Hired':
                case 'Cancel Requested':
                    $upcomingTrips[] = $tripData;
                    break;
                case 'pending':
                    $plannedTrips[] = $tripData;
                    break;
                // Note: 'Cancel' case is intentionally omitted and filtered out above
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Trip list retrieved successfully.',
            'notification' => 0,
            'current_trip' => $currentTrips,
            'upcoming_trip' => $upcomingTrips,
            'planned_trip' => $plannedTrips,
            'owner_status' => auth('sanctum')->user()->status,
            'number' => '123456789'
        ]);
    }





    public function trip_current_details(Request $request)
    {
        $user = auth('sanctum')->user();

        // Log::info('Authenticated User: ' . json_encode($user));

        // Only owners can access this
        if ($user->type !== 'owner') {
            return response()->json([
                'status' => false,
                'message' => 'Only owners can access this trip detail.',
                'data' => []
            ], 403);
        }

        // Step 1: Find an active trip created by this owner
        $trip = DB::table('trip')->where('id', $request->trip_id)
            // ->where('c_by', $user->id)
            ->where('status', 'Start') // or any appropriate status check
            ->first();

        if (!$trip) {
            return response()->json([
                'status' => false,
                'message' => 'No current trip found for owner.',
                'data' => []
            ], 200);
        }

        // Step 2: Get the applied driver who has started the trip
        $tripApplied = DB::table('trip_applied')
            ->where('trip_id', $trip->id)
            ->where('status', 'Start')
            ->first();

        if (!$tripApplied) {
            return response()->json([
                'status' => false,
                'message' => 'No driver found for this trip.',
                'data' => []
            ], 200);
        }

        // Step 3: Fetch the driver details
        $driver = DB::table('driver')
            ->where('id', $tripApplied->d_id)
            ->select('name', 'phone', 'img')
            ->first();

        // Step 4: Determine if report is required
        $report = !empty($tripApplied->remarks) || !empty($tripApplied->reason);

        return response()->json([
            'status' => true,
            'message' => 'Owner trip details fetched successfully.',
            'data' => [
                'driver_id' => $tripApplied->d_id,
                'trip_id' => $trip->id,
                'start_loc' => $trip->st_cord . ', ' . $trip->end_cord,
                'end_loc' => $trip->dest_cord ?? 'N/A',
                'crnt_loc' => $tripApplied->crnt_loc ?? 'N/A',
                'last_updated' => $tripApplied->updated_at ? Carbon::parse($tripApplied->updated_at)->format('Y-m-d H:i:s') : 'N/A',
                'driver_name' => $driver->name ?? 'N/A',
                'contact' => $driver->phone ?? 'N/A',
                'driver_img' => $driver->img ? asset($driver->img) : 'N/A',
                'report' => $report
            ]
        ], 200);
    }



    public function uploadTripImage(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|integer|exists:trip_applied,trip_id',
            'image' => 'required|image'
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
                'trip_img' => 'trip_img/' . $filename,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Trip image uploaded successfully.',
            'img_url' => asset('trip_img/' . $filename)
        ]);
    }






    public function trip_applied_driver_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|integer|exists:trip,id',
        ], [
            'trip_id.required' => 'Trip ID is required.',
            'trip_id.integer' => 'Trip ID must be a number.',
            'trip_id.exists' => 'Trip not found.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $city1 = Trip::where('id', $request->trip_id)->select('st_city', 'end_city')->first();

        $applications = TripApplied::with(['driver.details', 'trip'])
            ->where('trip_id', $request->trip_id)
            ->get()
            ->map(function ($application) {
                $driver = $application->driver;

                $city = $application->trip;

                if (!$driver)
                    return null;

                // Step 1: Get existing trips for driver with 'hire' or 'start' status
                $existingTrips = TripApplied::with(['trip'])->where('d_id', $driver->id)
                    ->whereIn('status', ['Hired', 'Start'])
                    ->get();


                $hasConflict = $existingTrips->contains(function ($trips) use ($city) {

                    // log::info($trips);
    
                    $existingStart = Carbon::parse($trips->trip->st_date);
                    $existingEnd = Carbon::parse($trips->trip->end_date);

                    $newStart = Carbon::parse($city->st_date);
                    $newEnd = Carbon::parse($city->end_date);

                    // log::info($existingStart . ' - ' . $existingEnd);
                    // log::info($newStart . ' - ' . $newEnd);
    
                    return $existingStart <= $newEnd && $existingEnd >= $newStart;

                    // return (
                    //     ($newStart >= $existingStart && $newStart <= $existingEnd) || // New start is inside existing
                    //     ($newEnd >= $existingStart && $newEnd <= $existingEnd)
                    //     // // New start date is within existing trip
                    //     // ($application->st_date >= $existingStart && $application->st_date <= $existingEnd) ||
    
                    //     // // New end date is within existing trip
                    //     // ($application->st_date >= $existingStart && $application->st_date <= $existingEnd)
                    // );
                });

                $details = $driver->details;

                $avgRating = DB::table('feedback')
                    ->where('d_id', $driver->id)
                    ->where('status', 'approve')
                    ->avg('rating');

                //  Fetch location name from location_active table
                $locationName = 'N/A';
                if ($driver->location) {
                    $loc = DB::table('location_active')->where('id', $driver->location)->value('location');
                    $locationName = $loc ?? 'N/A';
                }

                return [
                    'd_id' => $driver->id,
                    'name' => $driver->name,
                    'img' => $driver->img ? asset($driver->img) : 'N/A',
                    'st_loc' => $city->st_city,
                    'end_loc' => $city->end_city,
                    'experience' => $details->exp_year ?? 0,
                    'rating' => $avgRating ? round($avgRating, 1) : 0,
                    'location' => $locationName,
                    'salary' => $application->salary_perday ?? 0,
                    'about' => $details->about ?? 0,
                    'c_on' => $application->created_at ? Carbon::parse($application->created_at)->format('Y-m-d H:i:s') : 'N/A',
                    'status' => $application->status ?? 'N/A',
                    'driver_conflict' => $hasConflict
                ];
            })
            ->filter()
            ->values() // Reset keys
            ->all();   // Convert collection to plain array

        if (empty($applications)) {
            return response()->json([
                'status' => false,
                'message' => 'No drivers applied for this trip.',
                'data' => []
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Applied driver list retrieved successfully.',
            'data' => $applications,
            'city' => $city1
        ], 200);
    }



    public function updateTripRemarks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|integer|exists:driver,id',
            'trip_id' => 'required|integer|exists:trip_applied,trip_id',
            'reason' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // Update the record
        $updated = DB::table('trip_applied')
            ->where('d_id', $request->driver_id)
            ->where('trip_id', $request->trip_id)
            ->update([
                'reason' => $request->reason,
                'remarks' => $request->remarks,
                'updated_at' => now()
            ]);

        if ($updated) {
            return response()->json([
                'status' => true,
                'message' => 'Trip remarks updated successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Trip not found or update failed.'
            ]);
        }
    }





    public function driver_profile(Request $request)
    {
        $driverId = $request->driver_id;
        $tripId = $request->trip_id;

        // Validate driver
        $driver = DB::table('driver')->where('id', $driverId)->first();

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found',
            ], 200);
        }

        // Validate license
        $license = DB::table('license')->where('d_id', $driverId)->first();

        // Badge holder logic
        $badge_holder = ($license && ($license->batch_no || $license->batch_issue_date || $license->batch_issued_by)) ? 'yes' : 'no';

        // Fetch trip + application only for given driver_id & trip_id
        $trip = DB::table('trip_applied')
            ->where('trip_applied.d_id', $driverId)
            ->where('trip_applied.trip_id', $tripId)
            ->join('trip', 'trip.id', '=', 'trip_applied.trip_id')
            ->select(
                'trip.id as trip_id',
                'trip.c_by',
                'trip.st_loc',
                'trip.st_dest',
                'trip.st_date',
                'trip.end_date',
                'trip.st_time',
                'trip.no_days',
                'trip.veh_type',
                'trip.veh_name',
                'trip.veh_number',
                'trip_applied.salary_perday',
                'trip_applied.wait_charge',
                'trip_applied.food'
            )
            ->first();

        $details = [];
        if ($trip) {

            $avgSalary = ($trip->salary_perday ?? 0) * ($trip->no_days ?? 0);


            $details[] = [
                'name' => $license->cof ?? 'N/A',
                'contact' => $driver->phone,
                // 'start_date'    => \Carbon\Carbon::parse($trip->st_date)->format('d/m/Y'),
                // 'end_date'      => \Carbon\Carbon::parse($trip->end_date)->format('d/m/Y'),
                'start_date' => Carbon::parse($trip->st_date)->format('d-m-Y'),
                'end_date' => Carbon::parse($trip->end_date)->format('d-m-Y'),
                'start_time' => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i:s') : null,
                'no_of_days' => $trip->no_days,
                'veh_type' => $trip->veh_type,
                'veh_name' => $trip->veh_name,
                'veh_number' => $trip->veh_number,
                'about' => $driver->about ?? '',
                'salary_perday' => $trip->salary_perday,
                'wait_charge' => $trip->wait_charge,
                'food' => $trip->food,
                'avg_salary' => $avgSalary,
            ];
        }

        // Fetch feedback with corporate and trip info
        $feedbackData = DB::table('feedback')
            ->where('feedback.d_id', $driverId)
            ->where('feedback.status', 'approve')
            ->join('trip', 'trip.id', '=', 'feedback.t_id')
            ->join('corporate', 'corporate.id', '=', 'trip.c_by')
            ->select(
                'corporate.name as owner_name',
                'trip.st_city',
                'trip.end_city',
                'feedback.remarks',
                'feedback.rating',
                'feedback.created_at'
            )
            ->get();

        $feedback = [];
        foreach ($feedbackData as $fb) {

            // if ($fb->status !== 'approved') {
            //     continue;
            // }

            $feedback[] = [
                'name' => $fb->owner_name,
                'start_loc' => $fb->st_city,
                'end_loc' => $fb->end_city,
                'remarks' => $fb->remarks ?? '',
                'ratings' => $fb->rating,
                // 'date'      => $fb->created_at ? \Carbon\Carbon::parse($fb->created_at)->format('d/m/Y') : null,
                'date' => $fb->created_at ? Carbon::parse($fb->created_at)->format('d-m-Y') : null,
                'img' => $driver->img ? asset($driver->img) : 'N/A',


            ];
        }

        // Calculate average rating
        $avgRating = $feedbackData->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : null;

        return response()->json([
            'status' => true,
            'message' => 'Driver profile fetched successfully',
            'data' => [
                'name' => $driver->name,
                'img' => $driver->img ? asset($driver->img) : 'N/A',
                'ratings' => $avgRating,
                'cov' => $license->cov ?? 'N/A',
                'badge_holder' => $badge_holder,
                'details' => $details,
                'feedback' => $feedback,
            ]
        ]);
    }



    public function upcomming_driver_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|integer|exists:driver,id',
            'trip_id' => 'required|integer|exists:trip,id',
            'owner_id' => 'required|integer|exists:corporate,id',
        ], [
            'driver_id.required' => 'Driver ID is required.',
            'trip_id.required' => 'Trip ID is required.',
            'owner_id.required' => 'Owner ID is required.',
            'driver_id.exists' => 'Driver not found.',
            'trip_id.exists' => 'Trip not found.',
            'owner_id.exists' => 'Owner not found.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $driverId = $request->driver_id;
        $tripId = $request->trip_id;
        $ownerId = $request->owner_id;

        $driver = DB::table('driver')->where('id', $driverId)->first();
        $license = DB::table('license')->where('d_id', $driverId)->first();

        $trip = DB::table('trip_applied')
            ->where('trip_applied.d_id', $driverId)
            ->where('trip_applied.trip_id', $tripId)
            ->join('trip', 'trip.id', '=', 'trip_applied.trip_id')
            ->select(
                'trip_applied.trip_code',
                'trip.st_date',
                'trip.end_date',
                'trip.st_time',
                'trip.no_days',
                'trip.veh_type',
                'trip.veh_name',
                'trip.veh_number',
                'trip_applied.salary_perday',
                'trip_applied.wait_charge',
                'trip_applied.food'
            )
            ->first();

        if (!$trip) {
            return response()->json([
                'status' => false,
                'message' => 'Trip data not found for driver.',
                'data' => []
            ], 200);
        }

        $badge_holder = ($license && ($license->batch_no || $license->batch_issue_date || $license->batch_issued_by)) ? 'yes' : 'no';

        $avgSalary = ($trip->salary_perday ?? 0) * ($trip->no_days ?? 0);

        $avgRating = DB::table('feedback')->where('d_id', $driverId)->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : null;

        $cancelCount = DB::table('cancel_req')
            ->where('type', 'owner')
            ->where('c_by', $ownerId)
            ->whereIn('status', ['Cancel', 'Request']) // counts both confirmed and requested cancellations
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Remaining attempts (max 3)
        $remaining_cancels = max(0, 3 - $cancelCount);


        $data = [
            'name' => $driver->name ?? 'N/A',
            'img' => $driver->img ? asset($driver->img) : 'N/A',
            'ratings' => $avgRating,
            'cov' => $license->cov ?? 'N/A',
            'badge_holder' => $badge_holder,
            'start_date' => $trip->st_date ? Carbon::parse($trip->st_date)->format('d-m-Y') : 'N/A',
            'end_date' => $trip->end_date ? Carbon::parse($trip->end_date)->format('d-m-Y') : 'N/A',
            'start_time' => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i:s') : 'N/A',
            'no_of_days' => $trip->no_days,
            'veh_type' => $trip->veh_type,
            'veh_name' => $trip->veh_name,
            'veh_number' => $trip->veh_number,
            'about' => $driver->about ?? '',
            'salary_perday' => $trip->salary_perday,
            'wait_charge' => $trip->wait_charge,
            'food' => $trip->food,
            'avg_salary' => $avgSalary,
            'trip_code' => $trip->trip_code ?? 'N/A',
            'contact' => $driver->phone ?? 'N/A',
            'cancel_count' => $remaining_cancels


        ];

        return response()->json([
            'status' => true,
            'message' => 'Driver basic details fetched successfully.',
            'data' => $data
        ], 200);
    }




    public function update_Trip_Application_Status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|integer|exists:trip_applied,trip_id',
            'driver_id' => 'required|integer|exists:trip_applied,d_id',
            'status' => 'required|string|in:Hired,Reject',
        ], [
            'trip_id.required' => 'Trip ID is required.',
            'trip_id.exists' => 'Trip not found.',
            'driver_id.required' => 'Driver ID is required.',
            'driver_id.exists' => 'Driver not found.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either "Hired" or "Reject".',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 200);
        }

        // Initialize data array for update
        $appliedData = [
            'status' => $request->status,
            'updated_at' => now(),
        ];

        // Generate 4-digit random trip_code only if status is 'Hired'
        if ($request->status === 'Hired') {
            $appliedData['trip_code'] = mt_rand(1000, 9999);
        }

        // Update trip_applied table
        $appliedUpdated = DB::table('trip_applied')
            ->where('trip_id', $request->trip_id)
            ->where('d_id', $request->driver_id)
            ->update($appliedData);

        // Update trip table
        $tripUpdated = DB::table('trip')
            ->where('id', $request->trip_id)
            ->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

        $dr = Driver::find($request->driver_id);

        if ($request->status == 'Hired') {

            Notify::create([
                'type' => $dr->type,
                'f_id' => $dr->id,
                'prime_table' => $request->trip_id,
                'cat' => 'trip_' . $request->status,
                'title' => 'Your Trip Status Updated to : ' . $request->status,
                'body' => 'Your Trip Status Updated to : ' . $request->status,
                'status' => 'active',
                'c_by' => auth('sanctum')->user()->id, // Assuming you want to log who created this notification
            ])->save();
        }




        if ($dr->token) {
            $fcm = new Fcm(); // ✅ Or use app(Fcm::class)
            $fcm->send_notify(
                $dr->token,
                'trip_' . $request->status,
                'Your Trip Status Updated to : ' . $request->status,
                'trip_update'
            );
        } else {
            // Log::warning("Driver token missing for driver ID: {$sub->d_id}");
        }




        if ($appliedUpdated || $tripUpdated) {
            return response()->json([
                'status' => true,
                'message' => 'Trip and application status updated successfully.',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No matching record found or no change made.',
            ], 200);
        }
    }


    public function completed_trips(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'owner_id' => 'required|integer|exists:corporate,id',
        ], [
            'owner_id.required' => 'Owner ID is required.',
            'owner_id.integer' => 'Owner ID must be a number.',
            'owner_id.exists' => 'Owner not found.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $trips = Trip::where('c_by', $request->owner_id)
            ->where('status', 'End')
            ->select('id', 'title', 'st_city', 'end_city', 'st_date', 'end_date', 'st_time', 'status', 'created_at', 'no_days')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($trip) {

                $tripApplied = \App\Models\TripApplied::where('trip_id', $trip->id)->first();
                return [
                    'id' => $trip->id,
                    'title' => $trip->title,
                    'st_loc' => $trip->st_city,
                    'st_dest' => $trip->end_city,

                    // 'st_date'    => $trip->st_date ? \Carbon\Carbon::parse($trip->st_date)->format('d/m/Y') : 'N/A',
                    // 'end_date'   => $trip->end_date ? \Carbon\Carbon::parse($trip->end_date)->format('d/m/Y') : 'N/A',
                    // 'st_time'    => $trip->st_time ? \Carbon\Carbon::parse($trip->st_time)->format('H:i') : 'N/A',
                    'st_date' => $trip->st_date ? Carbon::parse($trip->st_date)->format('d-m-Y') : 'N/A',
                    'end_date' => $trip->end_date ? Carbon::parse($trip->end_date)->format('d-m-Y') : 'N/A',
                    'st_time' => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i') : 'N/A',
                    'avg_salary' => ($tripApplied->salary_perday) ? ($tripApplied->salary_perday) : 'N/A',
                    'status' => $trip->status ?? 'N/A',
                    // 'created_at' => $trip->created_at ? \Carbon\Carbon::parse($trip->created_at)->diffForHumans() : 'N/A',
                    'created_at' => $trip->created_at ? Carbon::parse($trip->created_at)->format('Y-m-d H:i:s') : 'N/A',

                ];
            });

        if ($trips->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No completed trips found for this owner.',
                'data' => []
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => 'Completed trips retrieved successfully.',
            'data' => $trips
        ], 200);
    }




    public function trip_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'owner_id' => 'required|integer|exists:corporate,id',
            'trip_id' => 'required|integer|exists:trip,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        // Get the trip info with matching owner
        $trip = DB::table('trip')
            ->where('id', $request->trip_id)
            ->where('c_by', $request->owner_id)
            ->first();

        if (!$trip) {
            return response()->json([
                'status' => false,
                'message' => 'Trip not found for the given owner.',
            ], 200);
        }

        // Join trip_applied with driver to get driver_name
        $tripApplied = DB::table('trip_applied')
            ->leftJoin('driver', 'trip_applied.d_id', '=', 'driver.id')
            ->select(
                'trip_applied.salary_perday',
                'trip_applied.wait_charge',
                'trip_applied.food',
                'driver.name as driver_name',
                'trip_applied.trip_code'
            )
            ->where('trip_applied.trip_id', $request->trip_id)
            ->whereIn('trip_applied.status', ['End'])
            ->first();



        $data = [

            'start_loc' => $trip->st_loc,
            'end_loc' => $trip->st_dest,
            'driver_name' => $tripApplied->driver_name ?? 'N/A',
            'start_date' => $trip->st_date ? Carbon::parse($trip->st_date)->format('d-m-Y') : 'N/A',
            'end_date' => $trip->end_date ? Carbon::parse($trip->end_date)->format('d-m-Y') : 'N/A',
            'start_time' => $trip->st_time ? Carbon::parse($trip->st_time)->format('H:i') : 'N/A',
            'no_days' => $trip->no_days ?? 'N/A',
            'veh_type' => $trip->veh_type ?? 'N/A',
            'veh_name' => $trip->veh_name ?? 'N/A',
            'veh_number' => $trip->veh_number,
            'salary_perday' => $tripApplied->salary_perday ?? 'N/A',
            'wait_charge' => $tripApplied->wait_charge ?? 'N/A',
            'food' => $tripApplied->food ?? 'N/A',
            'avg_salary' => ($tripApplied->salary_perday ?? 0) * ($trip->no_days ?? 0),
            't_code' => $tripApplied->trip_code ?? 'N/A'

        ];

        return response()->json([
            'status' => true,
            'message' => 'Trip profile fetched successfully.',
            'data' => $data
        ], 200);
    }



    public function trip_cancel(Request $request)
    {

        // dd($trip_id);

        if ($request->hasHeader('Authorization')) {
            $validator = Validator::make($request->all(), [
                'owner_id' => 'required|integer|exists:corporate,id',
                'trip_id' => 'required|integer|exists:trip,id',
                'status' => 'required|in:Cancel',
                'remarks' => 'nullable|string',
                'reason' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 200);
            }

            $ownerId = $request->owner_id;
        }

        $tripId = $request->trip_id;

        if ($request->hasHeader('Authorization')) {
            $user = auth('sanctum')->user();
        } else {
            $user = auth('corporate')->user();
        }

        //  Check trip belongs to owner
        $trip = DB::table('trip')
            ->where('id', $tripId)
            // ->where('c_by', $ownerId)
            ->first();

        // if (!$trip) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => 'Trip not found for this owner.',
        //     ], 200);
        // }

        $dr = DB::table('trip_applied')
            ->where('trip_id', $tripId)
            ->where('status', 'Hired')
            ->first();

        //  Count this month's cancellations by owner
        $monthlyCancelCount = DB::table('cancel_req')
            ->where('type', $user->type)
            ->where('c_by', $user->id)
            ->where('status', 'Cancel')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // if ($monthlyCancelCount < 3) {
        // Cancel directly

        $trip_st = ($monthlyCancelCount < 3) ? $request->status : 'Cancel Requested';

        $trip_tab_st = ($monthlyCancelCount < 3) ? 'Cancel' : 'Request';

        DB::table('trip')
            ->where('id', $tripId)
            ->update([
                'status' => $trip_st,
                'updated_at' => now()
            ]);

        DB::table('trip_applied')
            ->where('trip_id', $tripId)
            ->update([
                'status' => $trip_st,
                'updated_at' => now()
            ]);

        DB::table('cancel_req')->insert([
            'trip_id' => $tripId,
            'type' => $user->type,
            'remarks' => $request->remarks,
            'reason' => $request->reason,
            'status' => $trip_tab_st,
            'c_by' => $user->id,
            'c_type' => $user->type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // $trip_det =  Trip::find($tripId);



        if (!$dr) {
            Log::warning("No hired driver found for trip ID: {$tripId}");
            return response()->json([
                'status' => false,
                'message' => 'No hired driver found for this trip.',
            ], 404);
        }

        $dr_det = Driver::find($dr->d_id);

        Notify::create([
            'type' => $dr_det->type,
            'f_id' => $dr_det->id,
            'prime_table' => $tripId,
            'cat' => 'trip_' . $request->status,
            'title' => 'Your Trip Status Updated to : ' . $request->status,
            'body' => 'Your Trip Status Updated to : ' . $request->status,
            'status' => 'active',
            'c_by' => $user->id, // Assuming you want to log who created this notification
        ])->save();


        if ($dr_det->token) {
            $fcm = new Fcm(); // ✅ Or use app(Fcm::class)
            $fcm->send_notify(
                $dr_det->token,
                'trip_' . $request->status,
                'Your Trip Status Updated to : ' . $request->status,
                'trip_update'
            );
        }

        if ($request->hasHeader('Authorization')) {
            return response()->json([
                'status' => true,
                'message' => 'Trip cancelled successfully.',
            ]);
        } else {

            return redirect()->route('organization.vacancy.acting_list', ['id' => $tripId])
                ->with('success', 'Trip cancelled successfully.');
        }
        // }
        // else {
        //     // From 4th – Cancel Requested
        //     DB::table('trip')
        //         ->where('id', $tripId)
        //         ->update([
        //             'status'     => 'Cancel Requested',
        //             'updated_at' => now()
        //         ]);

        //     DB::table('trip_applied')
        //         ->where('trip_id', $tripId)
        //         ->update([
        //             'status'     => 'Cancel Requested',
        //             'updated_at' => now()
        //         ]);

        //     DB::table('cancel_req')->insert([
        //         'trip_id'    => $tripId,
        //         'type'       => 'owner',
        //         'remarks' => $request->remarks,
        //         'reason' => $request->reason,
        //         'status'     => 'Request', // for admin to approve
        //         'c_by'       => $ownerId,
        //         'c_type'    => 'owner',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);


        //     if (!$dr) {
        //         Log::warning("No hired driver found for trip ID: {$tripId}");
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'No hired driver found for this trip.',
        //         ], 404);
        //     }

        //     $dr_det = Driver::find($dr->d_id);

        //     Notify::create([
        //         'type' => $dr_det->type,
        //         'f_id' => $dr_det->id,
        //         'prime_table' => $tripId,
        //         'cat' => 'trip_' . $request->status,
        //         'title' => 'Your Trip Status Updated to : ' . $request->status,
        //         'body' => 'Your Trip Status Updated to : ' . $request->status,
        //         'status' => 'active',
        //         'c_by' => auth('sanctum')->user()->id, // Assuming you want to log who created this notification
        //     ])->save();


        //     if ($dr_det->token) {
        //         $fcm = new Fcm(); // ✅ Or use app(Fcm::class)
        //         $fcm->send_notify(
        //             $dr_det->token,
        //             'trip_' . $request->status,
        //             'Your Trip Status Updated to : ' . $request->status,
        //             'trip_update'
        //         );
        //     } else {
        //         // Log::warning("Driver token missing for driver ID: {$sub->d_id}");
        //     }

        //     return response()->json([
        //         'status'  => true,
        //         'message' => 'Trip cancel request sent to admin. Awaiting approval.',
        //     ], 200);
        // }
    }



    public function getOwnerAddress(Request $request)
    {
        $ownerId = $request->owner_id;

        if (!$ownerId) {
            return response()->json([
                'status' => false,
                'message' => 'Owner ID is required.'
            ], 400);
        }

        $corporate = DB::table('corporate')->where('id', $ownerId)->first();

        if (!$corporate) {
            return response()->json([
                'status' => false,
                'message' => 'Owner not found.'
            ], 200);
        }

        // Combine full address with all parts
        $addressParts = [
            $corporate->ad_1,
            $corporate->ad_2,
            $corporate->city,
            $corporate->state,
            $corporate->pin
        ];

        // Filter null or empty values and join with comma
        $address = implode(', ', array_filter($addressParts));

        return response()->json([
            'status' => true,
            'message' => 'Owner address fetched successfully.',
            'address' => $address
        ], 200);
    }





    public function owner_edit_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:corporate,id',
            'name' => 'required|string',
            'ad_1' => 'required|string',
            'ad_2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'pin' => 'required|digits:6',
        ]); {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:corporate,id',
                'name' => 'required|string',
                'ad_1' => 'required|string',
                'ad_2' => 'nullable|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'pin' => 'required|digits:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 200);
            }
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 200);
            }

            $updateData = [
                'name' => $request->name,
                'ad_1' => $request->ad_1,
                'ad_2' => $request->ad_2,
                'city' => $request->city,
                'state' => $request->state,
                'pin' => $request->pin,
            ];
            $updateData = [
                'name' => $request->name,
                'ad_1' => $request->ad_1,
                'ad_2' => $request->ad_2,
                'city' => $request->city,
                'state' => $request->state,
                'pin' => $request->pin,
            ];

            DB::table('corporate')->where('id', $request->id)->update($updateData);
            DB::table('corporate')->where('id', $request->id)->update($updateData);

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully.',
            ], 200);
        }
        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.',
        ], 200);
    }




    public function update_owner_logo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:corporate,id',
            'logo' => 'required|image',
        ]); {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer|exists:corporate,id',
                'logo' => 'required|image',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 200);
            }
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 200);
            }

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');

                // Sanitize the file name: remove spaces, special chars
                $originalName = str_replace(' ', '-', $logo->getClientOriginalName());
                $filename = time() . '_' . $originalName;

                // Move to public path
                $logo->move(public_path('image/corporate/logo'), $filename);

                // Store relative path in DB
                $relativePath = 'public/image/corporate/logo/' . $filename;

                DB::table('corporate')
                    ->where('id', $request->id)
                    ->update(['logo' => $relativePath]);

                // Generate full URL path
                $fullUrl = asset($relativePath);  // asset() gives full http://your-domain.com/path

                return response()->json([
                    'status' => true,
                    'message' => 'Logo updated successfully.',
                    'path' => $fullUrl,
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Logo file is missing.',
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => 'Logo file is missing.',
        ], 200);
    }



    public function feedback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|integer|exists:trip,id',
            'remarks' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ], [
            'trip_id.required' => 'Trip ID is required.',
            'rating.required' => 'Rating is required.',
            'rating.min' => 'Minimum rating is 1.',
            'rating.max' => 'Maximum rating is 5.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        // Get the logged-in driver ID
        $owner = auth('sanctum')->user()->id ?? null;

        $d_id = TripApplied::where('trip_id', $request->trip_id)->where('status', 'End')->first();

        // if (!$owner) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Unauthorized. Driver not logged in.'
        //     ], 401);
        // }

        // Store feedback
        DB::table('feedback')->insert([
            'd_id' => $d_id->d_id,
            't_id' => $request->trip_id,
            'remarks' => $request->remarks ?? null,
            'rating' => $request->rating,
            'c_by' => $owner,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Feedback submitted successfully.'
        ], 200);
    }


    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trip_id' => 'required|integer|exists:trip_applied,trip_id',
            'driver_id' => 'required|integer',
            'reason' => 'nullable|string',
            'remarks' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $user = auth('sanctum')->user(); // Owner user
        // Log::info('Owner Auth ID: ' . ($user->id ?? 'none'));

        $updated = DB::table('trip_applied')
            ->where('trip_id', $request->trip_id)
            ->where('d_id', $request->driver_id)  // Add d_id condition
            // ->where('c_by', $user->id)  
            ->update([
                'reason' => $request->reason,
                'remarks' => $request->remarks,
                'report_sts' => 'pending',
                'updated_at' => now(),
            ]);

        if ($updated) {
            return response()->json([
                'status' => true,
                'message' => 'Report submitted successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No matching record found or nothing to update.',
            ]);
        }
    }
}
