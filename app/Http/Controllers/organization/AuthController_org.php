<?php

namespace App\Http\Controllers\organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Corporate; // Assuming you have a Corporate model for storing organization details
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\Subscription;
use App\Services\CashfreeService;
use App\Models\District;


class AuthController_org extends Controller
{


    public function login()
    {
        return view('organization.auth.login');
    }

       public function sendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
        ]);

        $phone = $request->mobile;

        // Get user from corporate table
        $user = Corporate::where('contact', $phone)
            ->where('type', 'corporate')
            ->first();

        if ($user) {
              if ($user->active_status !== 'active') {
            return redirect()->back()->with('alert', 'Your account is not active. Please contact support.');
        }
            // Generate OTP
            $otp = $phone === '1234567890' ? 1234 : rand(1000, 9999);

            // Save OTP to corporate table
            DB::table('corporate')->where('contact', $phone)->update(['otp' => $otp]);

            // Store OTP in session
            Session::put('otp', $otp);
            Session::put('mobile', $phone);

            $authKey = "3636736465636b35323233";
            $senderId = "DRDECK";
            $route = "2"; // Working for now
            $country = "91";
            $dltTeId = "1707175066512828187";
            $message = urlencode("Dear user, your DriversDeck registration OTP is $otp. Please do not share this with anyone. - DRDECK");

            $url = "http://promo.smso2.com/api/sendhttp.php?authkey=$authKey&mobiles=$phone&message=$message&sender=$senderId&route=2&country=91&DLT_TE_ID=$dltTeId";


            // Send SMS
            try {
                $response = file_get_contents($url);
                Log::info("SMS sent to $phone. OTP: $otp. Response: $response");
            } catch (\Exception $e) {
                Log::error("SMS sending failed: " . $e->getMessage());
            }

            return redirect()->route('auth.otp')->with('success', 'OTP sent successfully');
        }

        return redirect()->back()->withErrors(['mobile' => 'Mobile number not found']);
    }



    public function otp()
    {
        return view('organization.auth.otp');
    }

public function verifyOtp(Request $request) 
{
    $request->validate([
        'otp' => 'required|digits:4',
    ]);

    $enteredOtp = $request->otp;
    $storedOtp = Session::get('otp');
    $mobile = Session::get('mobile');

    if ($enteredOtp == $storedOtp) {
        $user = Corporate::where('contact', $mobile)
            ->where('type', 'corporate')
            ->first();

        if ($user) {
            Auth::guard('corporate')->login($user);

            // Case 1: User has no subscription yet
            if ($user->subscription == 'no') {
                return redirect()->route('auth.register_subscription')
                    ->with('success', 'Login successful, please complete your registration');
            }

            // Case 2: User subscription == yes → check subscription table
            $subscription = DB::table('subscription')
                ->where('f_id', $user->id)  // f_id = corporate id
                ->where('status', 'active') // ensure only active ones
                ->orderByDesc('id')
                ->first();

            if ($subscription && $subscription->exp_date >= now()->toDateString()) {
                // Subscription valid → allow login
                Cookie::queue(
                    cookie('cook_auth_corp', encrypt($user->id), 60 * 24 * 30) // 30 days
                );

                Session::forget('otp');
                Session::forget('mobile');

                return redirect()->route('organization.dashboard.index')
                    ->with('success', 'Logged in!');
            } else {
                // Subscription expired → redirect to subscription page instead of logout
                Session::forget('otp');
                Session::forget('mobile');
                
                return redirect()->route('auth.register_subscription')
                    ->with('error', '');
            }
        } else {
            return redirect()->route('auth.login.org')
                ->withErrors(['mobile' => 'User not found']);
        }
    } else {
        return redirect()->back()->withErrors(['otp' => 'Invalid OTP']);
    }
}
    // public function login()
    // {

    //     return view('organization.auth.login');
    // }

    public function login_check(Request $request)
    {
        // dd('hello');

        // Get the user by contact and type = corporate
        $user = Corporate::where('contact', $request->mobile)
            ->where('type', 'corporate')
            ->first();


        // Check plain text password
        if ($user) {

            // 🔐 Store encrypted user ID in cookie for 30 days
            Cookie::queue(
                cookie('cook_auth_corp', encrypt($user->id), 60 * 24 * 30) // 30 days
            );

            Auth::guard('corporate')->login($user);

            // ✅ Authenticated user is now available
            Log::info('User logged in: ' . auth('corporate')->user()->name);


            // dd(Auth::guard('web')->user());

            return redirect()->route('organization.dashboard.index')->with('success', 'Login successful');
        } else {
            // Log the failed login attempt
            Log::warning('Failed login attempt for contact: ' . $request->contact);
            return redirect()->back()->withErrors(['Invalid credentials']);
        }
    }

    public function logout()
    {
        Auth::guard('corporate')->logout();

        // ❌ Clear custom auth cookie
        Cookie::queue(Cookie::forget('cook_auth_corp'));

        // return view('organization.auth.login.org');
        return redirect()->route('auth.login.org');
    }

    public function forgotpass()
    {
        return view('organization.auth.forgot_pass');
    }
    // public function otp()
    // {
    //     return view('organization.auth.otp');
    // }
    public function changepass()
    {
        return view('organization.auth.change_pass');
    }
    // public function register_basic()
    // {
    //     $loc = DB::table('location_active')->where('status', 'active')->select('id', 'location')->get();
    //     return view('organization.auth.register_details', ['loc' => $loc]);
    // }
    // public function register_basic_store(Request $request)
    // {

    //     // dd($request);
    //     // Validate the request data
    //     $request->validate([
    //         'c_type' => 'required|string|max:255',
    //         'c_name' => 'required|string|max:255',
    //         'c_contact' => 'required|string|max:10|min:10|unique:corporate,contact',
    //         'c_email' => 'required|email|max:255',
    //         'c_loc' => 'required'
    //     ]);

    //     // if ($request->fails()) {
    //     //     return redirect()->back()->withErrors($request->errors());
    //     // }

    //     // dd($request->fails());

    //     $ins = Corporate::create([
    //         'type' => 'corporate',
    //         'name' => $request->c_name,
    //         'c_type' => $request->c_type,
    //         'location' => $request->c_loc,
    //         'contact' => $request->c_contact,
    //         'mail' => $request->c_email,
    //         'gender' => $request->gender ?? null,

    //         // 'password' => bcrypt($request->password), // Uncomment if you want to handle password
    //     ]);
    //     if ($ins) {
    //         Auth::guard('corporate')->login($ins);

    //         Log::info("message", ['User logged in: ' . auth('corporate')->user()->name]);
    //         return redirect()->route('auth.register_contact')->with('success', 'Login successful');
    //     } else {
    //         return redirect()->back()->withErrors(['Failed to save details']);
    //     }

    //     // Store the data in the database or perform any other logic here

    //     return redirect()->route('auth.register_contact')->with('success', 'Details saved successfully');
    // }
    public function register_basic() {
    // Get all active districts from location_active and join with district table to get district names
    $districts = DB::table('location_active')
        ->leftJoin('district', 'location_active.district', '=', 'district.id')
        ->where('location_active.status', 'active')
        ->select('location_active.district as district_id', 'district.district as district_name')
        ->distinct('location_active.district')
        ->orderBy('district.district')
        ->get();
    
    return view('organization.auth.register_details', ['districts' => $districts]);
}

public function get_locations_by_district(Request $request) {
    $districtId = $request->district;
    
    // Get locations for this district ID
    $locations = DB::table('location_active')
        ->where('district', $districtId) // assuming district column stores district ID
        ->where('status', 'active')
        ->select('id', 'location')
        ->get();
    
    return response()->json($locations);
}

// Alternative approach: Store the location_active ID instead of district ID
public function register_basic_store(Request $request) {
    // Validate the request data
    $request->validate([
        'c_type' => 'required|string|max:255',
        'c_name' => 'required|string|max:255',
        'c_contact' => 'required|string|max:10|min:10|unique:corporate,contact',
        'c_email' => 'required|email|max:255',
        'c_district' => 'required', // This is actually location_active.district (district ID)
        'c_loc' => 'required' // Location ID
    ]);
    
    // Get the location_active record to get the actual district ID
    $locationActive = DB::table('location_active')
        ->where('district', $request->c_district)
        ->where('status', 'active')
        ->first();
    
    if (!$locationActive) {
        return redirect()->back()->withErrors(['Invalid district/location selected']);
    }
    
    // Create the corporate record
    $ins = Corporate::create([
        'type' => 'corporate',
        'name' => $request->c_name,
        'c_type' => $request->c_type,
        'location' => $request->c_loc,
        'district' => $request->c_district, // Store the district ID from location_active
        'contact' => $request->c_contact,
        'mail' => $request->c_email,
        'gender' => $request->gender ?? null,
    ]);
    
    if ($ins) {
        Auth::guard('corporate')->login($ins);
        Log::info("User registered and logged in: " . $ins->name);
        return redirect()->route('auth.register_contact')->with('success', 'Registration successful');
    } else {
        return redirect()->back()->withErrors(['Failed to save details']);
    }
}

    public function register_contact(Request $req)
    {
        return view('organization.auth.register_contact');
    }
    public function register_contact_store(Request $req)
    {
        $req->validate([
            'full_name' => 'required|string|max:255',
            'full_contact' => 'required|string|max:10|min:10',
            'alt_contact' => 'nullable|string|max:10|min:0',
            'f_mail' => 'required|email|max:255',
        ]);

        $up =  Corporate::where('id', auth('corporate')->user()->id)->update([
            'c_name' => $req->full_name,
            'c_num' => $req->full_contact,
            'a_num' => $req->alt_contact,
            'c_mail' => $req->f_mail,
            'c_by' => auth('corporate')->user()->id // Assuming you want to store the ID of the user who created this entry
        ]);

        if ($up) {
            Log::info("message", ['User contact details updated: ' . auth('corporate')->user()->name]);
            return redirect()->route('auth.register_address')->with('success', 'Contact details updated successfully');
        } else {
            return redirect()->back()->withErrors(['Failed to update contact details']);
        }

        // return view('organization.auth.register_contact');
    }
    public function register_address()
    {
        return view('organization.auth.register_address');
    }
    public function register_address_store(Request $req)
    {
        $req->validate([
            'ad_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pin_code' => 'required|digits:6',
        ]);

        $up = Corporate::where('id', auth('corporate')->user()->id)->update([
            'ad_1' => $req->ad_1,
            'city' => $req->city,
            'state' => $req->state,
            'pin' => $req->pin_code,
        ]);

        if ($up) {
            Log::info("message", ['User contact details updated: ' . auth('corporate')->user()->name]);
            return redirect()->route('auth.register_business')->with('success', 'Address details updated successfully');
        } else {
            return redirect()->back()->withErrors(['Failed to update address details']);
        }
    }

    public function register_business()
    {
        return view('organization.auth.register_business');
    }
    public function register_business_store(Request $req)
    {
        $req->validate([
            'pan' => 'nullable|string|max:10|min:10',
            'gst' => 'nullable|string|max:15|min:15',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Optional logo upload
        ]);

        $data = [
            'pan' => $req->pan,
            'gst' => $req->gst,
        ];

        if ($req->hasFile('logo')) {
            $image = $req->file('logo');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('image/corporate/logo'), $filename);
            $data['logo'] = 'public/image/corporate/logo/' . $filename;
        }

        $up = Corporate::where('id', auth('corporate')->user()->id)->update($data);

        if ($up) {
            Log::info("message", ['User business details updated: ' . auth('corporate')->user()->name]);
            return redirect()->route('auth.register_asset')->with('success', 'Business details updated successfully');
        } else {
            return redirect()->back()->withErrors(['Failed to update business details']);
        }
    }

    public function register_asset()
    {
        return view('organization.auth.register_asset');
    }
    public function register_asset_store(Request $req)
    {
        $req->validate([
            'no_vehicle' => 'required|integer|min:1',
            'no_drivers' => 'required|integer|min:1',
            'no_vacancies' => 'required|integer|min:0',
        ]);

        $up = Corporate::where('id', auth('corporate')->user()->id)->update([
            'no_veh' => $req->no_vehicle,
            'no_driver' => $req->no_drivers,
            'no_vac' => $req->no_vacancies,
        ]);

        if ($up) {
            Log::info("message", ['User asset details updated: ' . auth('corporate')->user()->name]);
            return redirect()->route('auth.register_subscription')->with('success', 'Asset details updated successfully');
        } else {
            return redirect()->back()->withErrors(['Failed to update asset details']);
        }
    }

    public function subscription()
    {
        return view('organization.auth.register_subscription');
    }

// public function initiate_payment(Request $request, CashfreeService $cashfree)
// {
//     try {
//         $request->validate([
//             'plan' => 'required|in:basic,premium',
//         ]);

//         $user = auth('corporate')->user();
        
//         if (!$user) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'User not authenticated'
//             ], 401);
//         }

//         // Define plan details
//         $plans = [
//             'basic' => [
//                 'amount' => 15000,
//                 'name' => 'Basic Plan - 6 Months',
//                 'description' => 'Permanent Drivers, 6 Months, Acting Drivers, Verified & Experienced'
//             ],
//             'premium' => [
//                 'amount' => 22000,
//                 'name' => 'Premium Plan - 1 Year',
//                 'description' => 'Unlimited Permanent Drivers, 1 year, Acting Drivers, Verified & Experienced'
//             ]
//         ];

//         $selectedPlan = $plans[$request->plan];
//         $orderId = 'SUB_' . $user->id . '_' . time();

//         // Log the attempt
//         Log::info('Payment initiation started', [
//             'user_id' => $user->id,
//             'plan' => $request->plan,
//             'amount' => $selectedPlan['amount'],
//             'order_id' => $orderId,
//             'environment' => env('CASHFREE_ENVIRONMENT')
//         ]);

//         // Create payment order with Cashfree
//         $response = $cashfree->createOrder(
//             $orderId,
//             $selectedPlan['amount'],
//             $user->name,
//             $user->mail,
//             $user->contact,
//             $user->id
//         );

//         Log::info('Cashfree response received', $response);

//         if (isset($response['payment_session_id'])) {
//             // Store pending subscription record
//             $subscription = Subscription::create([
//                 'f_id'     => $user->id,
//                 'type'     => 'corporate',
//                 'plan'     => $request->plan === 'basic' ? '6' : '12',
//                 't_id'     => $orderId,
//                 'amount'   => $selectedPlan['amount'],
//                 'paid_sts' => 'pending',
//                 'c_by'     => $user->id,
//                 'status'   => 'active'
//             ]);

//             Log::info('Subscription record created', [
//                 'subscription_id' => $subscription->id,
//                 'environment' => env('CASHFREE_ENVIRONMENT')
//             ]);

//             return response()->json([
//                 'success' => true,
//                 'payment_session_id' => $response['payment_session_id'],
//                 'order_id' => $orderId
//             ]);
//         } else {
//             Log::error('Payment session ID not found in response', $response);
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Failed to create payment session: ' . ($response['message'] ?? 'Unknown error')
//             ], 400);
//         }
//     } catch (\Exception $e) {
//         Log::error('Payment initiation failed: ' . $e->getMessage(), [
//             'file' => $e->getFile(),
//             'line' => $e->getLine(),
//             'trace' => $e->getTraceAsString(),
//             'environment' => env('CASHFREE_ENVIRONMENT')
//         ]);
//         return response()->json([
//             'success' => false,
//             'message' => 'Payment initiation failed: ' . $e->getMessage()
//         ], 500);
//     }
// }

// public function payment_callback(Request $request)
// {
//     $orderId = $request->query('order_id');
//     $paymentStatus = $request->input('order_status', 'PAID');
    
//     try {
//         Log::info('Payment callback received', [
//             'order_id' => $orderId,
//             'payment_status' => $paymentStatus,
//             'environment' => env('CASHFREE_ENVIRONMENT'),
//             'request_data' => $request->all()
//         ]);

//         // Find the subscription record
//         $subscription = Subscription::where('t_id', $orderId)->first();
        
//         if (!$subscription) {
//             Log::error('Subscription not found for order: ' . $orderId);
//             return redirect()->route('auth.register_subscription')
//                 ->with('error', 'Payment verification failed');
//         }

//         if ($paymentStatus === 'PAID') {
//             // Update subscription status
//             $expiryDate = $subscription->plan === '6' ? 
//                 now()->addMonths(6)->format('Y-m-d') : 
//                 now()->addYear()->format('Y-m-d');

//             $subscription->update([
//                 'paid_sts' => 'success',
//                 'exp_date' => $expiryDate,
//                 'c_by' => $subscription->f_id
//             ]);

//             // Update corporate table
//             Corporate::where('id', $subscription->f_id)->update([
//                 'subscription' => 'yes'
//             ]);

//             Log::info('Payment successful for order: ' . $orderId, [
//                 'subscription_id' => $subscription->id,
//                 'expiry_date' => $expiryDate,
//                 'environment' => env('CASHFREE_ENVIRONMENT')
//             ]);
            
//             return redirect()->route('organization.dashboard.index')
//                 ->with('success', 'Payment successful! Your subscription is now active.');
                
//         } else {
//             // Payment failed
//             $subscription->update([
//                 'paid_sts' => 'failed'
//             ]);

//             Log::info('Payment failed for order: ' . $orderId, [
//                 'environment' => env('CASHFREE_ENVIRONMENT')
//             ]);
            
//             return redirect()->route('landing.index')
//                 ->with('error', 'Payment was not successful. Please try again.');
//         }
        
//     } catch (\Exception $e) {
//         Log::error('Payment callback error: ' . $e->getMessage(), [
//             'order_id' => $orderId,
//             'environment' => env('CASHFREE_ENVIRONMENT')
//         ]);
//         return redirect()->route('auth.register_subscription')
//             ->with('error', 'Payment verification failed');
//     }
// }

//     public function subscription_store(Request $req)
//     {
//         $user = auth('corporate')->user();

//         $updated = Corporate::where('id', $user->id)->update([
//             'subscription' => 'no',
//         ]);

//         if ($updated) {
//             return redirect()->route('organization.dashboard.index')->with('success', 'Registration completed without subscription.');
//         } else {
//             return redirect()->back()->withErrors(['Failed to save subscription.']);
//         }
//     }
public function payment_details($plan)
{
    $user = auth('corporate')->user();
        
if (!$user) {
    return redirect()->back()->with('error', 'User not authenticated');
}

    
    // Define plan details
    $plans = [
        'basic' => [
            'amount' => 15000,
            'name' => 'Basic Plan',
            'duration' => '6 Months',
            'drivers' => '5 Permanent Drivers',
        ],
        'premium' => [
            'amount' => 22000,
            'name' => 'Premium Plan',
            'duration' => '1 Year',
            'drivers' => 'Unlimited Permanent Drivers',
        ]
    ];

    if (!isset($plans[$plan])) {
        return redirect()->route('auth.register_subscription')
            ->with('error', 'Invalid plan selected');
    }

    $selectedPlan = $plans[$plan];
    
    return view('organization.auth.payment_details', compact('selectedPlan', 'plan'));
}

public function transaction_form($plan)
{
    $user = auth('corporate')->user();
    
    // Define plan details
    $plans = [
        'basic' => [
            'amount' => 15000,
            'name' => 'Basic Plan',
            'duration' => '6 Months',
        ],
        'premium' => [
            'amount' => 22000,
            'name' => 'Premium Plan',
            'duration' => '1 Year',
        ]
    ];

    if (!isset($plans[$plan])) {
        return redirect()->route('auth.register_subscription')
            ->with('error', 'Invalid plan selected');
    }

    $selectedPlan = $plans[$plan];
    
    return view('organization.auth.transaction_form', compact('selectedPlan', 'plan'));
}

public function submit_payment(Request $request)
{
    try {
        $request->validate([
            'plan' => 'required|in:basic,premium',
            'transaction_id' => 'required|string|max:255',
            'payment_screenshot' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ]);

        $user = auth('corporate')->user();
        
        if (!$user) {
            return redirect()->route('auth.register_subscription')
                ->with('error', 'User not authenticated');
        }

        // Check if transaction ID already exists
        $existingTransaction = Subscription::where('t_id', $request->transaction_id)->first();
        if ($existingTransaction) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['transaction_id' => 'This transaction ID has already been used']);
        }

        // Handle file upload
        $screenshotPath = null;
        if ($request->hasFile('payment_screenshot')) {
            $file = $request->file('payment_screenshot');
            $filename = 'payment_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Move directly to public/payment_screenshots
            $file->move(public_path('payment_screenshots'), $filename);

            // Save path if needed
            $screenshotPath = 'payment_screenshots/' . $filename;
        }


        // Calculate expiry date
        $expiryDate = $request->plan === 'basic' ? 
            now()->addMonths(6)->format('Y-m-d') : 
            now()->addYear()->format('Y-m-d');

        // Create subscription record
        $subscription = Subscription::create([
            'f_id'     => $user->id,
            'type'     => 'corporate',
            'plan'     => $request->plan === 'basic' ? '6' : '12',
            't_id'     => $request->transaction_id,
            'amount'   => $request->plan === 'basic' ? 15000 : 22000,
            'paid_sts' => 'success', // Admin will verify and update this
            'exp_date' => $expiryDate,
            'payment_screenshot' => $screenshotPath,
            'c_by'     => $user->id,
            'status'   => 'active'
        ]);

        Log::info('Payment details submitted', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'transaction_id' => $request->transaction_id,
            'plan' => $request->plan
        ]);

        // Update corporate table to pending subscription verification
        Corporate::where('id', $user->id)->update([
            'subscription' => 'yes'
        ]);

        return redirect()->route('organization.dashboard.index')
            ->with('success', 'Payment details submitted successfully. Your subscription will be activated after admin verification.');
            
    } catch (\Exception $e) {
        Log::error('Payment submission error: ' . $e->getMessage(), [
            'user_id' => auth('corporate')->user()->id ?? null,
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to submit payment details. Please try again.');
    }
}


}
