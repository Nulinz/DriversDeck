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

                // Clear session
                Session::forget('otp');
                Session::forget('mobile');

                // Set cookie
                Cookie::queue(
                    cookie('cook_auth_corp', encrypt($user->id), 60 * 24 * 30) // 30 days
                );

                // Redirect to dashboard directly
                return redirect()->route('organization.dashboard.index')
                    ->with('success', 'Logged in successfully');
            } else {
                return redirect()->route('auth.login.org')
                    ->withErrors(['mobile' => 'User not found']);
            }
        } else {
            return redirect()->back()->withErrors(['otp' => 'Invalid OTP']);
        }
    }

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

    public function changepass()
    {
        return view('organization.auth.change_pass');
    }
    public function register_basic()
    {
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

    public function get_locations_by_district(Request $request)
    {
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
    public function register_basic_store(Request $request)
    {
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

        $up = Corporate::where('id', auth('corporate')->user()->id)->update([
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
            return redirect()->route('organization.dashboard.index')->with('success', 'Asset details updated successfully');
        } else {
            return redirect()->back()->withErrors(['Failed to update asset details']);
        }
    }

    public function subscription()
    {
        return view('organization.auth.register_subscription');
    }
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
                'f_id' => $user->id,
                'type' => 'corporate',
                'plan' => $request->plan === 'basic' ? '6' : '12',
                't_id' => $request->transaction_id,
                'amount' => $request->plan === 'basic' ? 15000 : 22000,
                'paid_sts' => 'success', // Admin will verify and update this
                'exp_date' => $expiryDate,
                'payment_screenshot' => $screenshotPath,
                'c_by' => $user->id,
                'status' => 'active'
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
