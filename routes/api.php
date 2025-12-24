<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api_cnt;
use App\Http\Controllers\Api_owner;
use App\Http\Controllers\LocationController;

// Example protected route
// Route::middleware('auth:sanctum')->get('/', function (Request $request) {
//     Log::info('Authenticated User ID: ' . ($request->user() ?? 'none'));
//     return $request->user();
// });

// Example public route
// Route::get('/hello', function () {
//     return response()->json(['message' => 'Hello from API!']);
// });


//common
Route::post('/login', [Api_cnt::class, 'login']);
Route::post('/location_active', [Api_cnt::class, 'location_active'])->name('location.active');
Route::post('/Acting/mobile', [Api_cnt::class, 'mobile_check'])->name('mobile_check');
Route::post('/license_no', [Api_cnt::class, 'get_license_by_mobile']);
Route::post('/driver_store', [Api_cnt::class, 'driver_store'])->name('driver.store');
// Route::post('/driver_store', [Api_cnt::class, 'driver_store'])->name('driver.store');


Route::post('/checkTransactionId', [Api_owner::class, 'checkTransactionId']);
Route::post('/popup', [Api_cnt::class, 'popup']);


Route::post('/checkTransactionId', [Api_owner::class, 'checkTransactionId']);
Route::post('/get-subscription-info', [Api_owner::class, 'getSubscriptionInfo']);
Route::post('/add-subscription', [Api_owner::class, 'add_subscription']);
Route::post('/owner_login', [Api_owner::class, 'owner_login']);
Route::post('/device-token', [Api_owner::class, 'device_token']);
Route::post('/get-subscription-info', [Api_owner::class, 'getSubscriptionInfo']);
Route::post('/add-subscription', [Api_owner::class, 'add_subscription']);
Route::post('/owner_login', [Api_owner::class, 'owner_login']);
Route::post('/device-token', [Api_owner::class, 'device_token']);
Route::post('/resend-owner-otp', [Api_owner::class, 'resendOwnerOtp']);

Route::post('/license_no', [Api_cnt::class, 'get_license_by_mobile']);
Route::post('/driver_store', [Api_cnt::class, 'driver_store']);

Route::post('/available-districts', [Api_cnt::class, 'available_districts']);


//owner
Route::post('/owner_register', [Api_owner::class, 'owner_register']);


Route::post('/location_active', [LocationController::class, 'location_active']);


// Example route with a controller

Route::namespace('App\Http\Controllers')->middleware('auth:sanctum')->group(function () {


    // logout 

    Route::post('/logout', 'Api_cnt@logout');
    Route::post('/delete_driver', 'Api_cnt@delete_account');



    Route::post('/resend-otp', 'Api_cnt@resendOtp');


    //Acting driver


    // Route::post('/Acting/mobile', 'Api_cnt@mobile_check')->name('mobile_check');

    //current trip
    Route::post('/acting_trip_list', 'Api_cnt@acting_trip_list');  //dashboard
    Route::post('/current_trip_profile', 'Api_cnt@current_trip_profile');   //current trip profile
    Route::post('/trip_current_loc', 'Api_cnt@trip_current_loc');   //current trip location
    Route::post('/upload-trip-image', 'Api_cnt@uploadTripImage');

    Route::post('/trip_applied_start', 'Api_cnt@TripAppliedStart');   //start end trip
    Route::post('/driver_trip_cancel', 'Api_cnt@driver_trip_cancel');
    Route::post('/feedback_list', 'Api_cnt@feedback_list');

    //upcomming trip
    Route::post('/upcoming_job_profile', 'Api_cnt@job_profile'); //job profile

    //jobs
    Route::post('/trip_50', 'Api_cnt@trip_50')->name('trip_50');
    Route::post('/apply_job_profile', 'Api_cnt@apply_job_profile');  //apply job profile
    Route::post('/trip_applied', 'Api_cnt@trip_applied'); // apply salary details post

    // applied
    Route::post('/trip_applied_list', 'Api_cnt@trip_applied_list');
    Route::post('/applied_job_profile', 'Api_cnt@getAppliedTripProfile');   // get applied trip profile (applied jobs)

    // saved jobs
    Route::post('/trip_saved_jobs', 'Api_cnt@trip_saved_jobs'); //post
    Route::post('/get_saved_jobs', 'Api_cnt@get_saved_jobs'); // get


    Route::post('/driver_summary', 'Api_cnt@getDriverSummary');
    Route::post('/switch-driver-type', 'Api_cnt@switchDriverType');


    Route::post('/notify_update', 'Api_cnt@notify_update');


    //owner


    Route::post('/trip-create', 'Api_owner@trip_create');
    Route::post('/trip-list', 'Api_owner@owner_trip_list');        //dashboard
    Route::post('/trip-current-details', 'Api_owner@trip_current_details');
    Route::post('/upload-trip-image', 'Api_owner@uploadTripImage');
    Route::post('/get-owner-address', 'Api_owner@getOwnerAddress');
    Route::post('/owner_edit_profile', 'Api_owner@owner_edit_profile');
    Route::post('/update_owner_logo', 'Api_owner@update_owner_logo');
    Route::post('/trip-applied-driver-list', 'Api_owner@trip_applied_driver_list');
    Route::post('/update-trip-remarks', 'Api_owner@updateTripRemarks');
    Route::post('/driver-profile', 'Api_owner@driver_profile');    //planned trips
    Route::post('/upcoming-driver-profile', 'Api_owner@upcomming_driver_profile');    // upcomming trips
    Route::post('/update-trip-status', 'Api_owner@update_Trip_Application_Status');
    Route::post('/completed-trips', 'Api_owner@completed_trips');
    Route::post('/trip-profile', 'Api_owner@trip_profile');
    Route::post('/trip-cancel', 'Api_owner@trip_cancel');
    Route::post('/feedback', 'Api_owner@feedback');
    Route::post('/report', 'Api_owner@report');



    //Permanent table
    Route::post('/fulltime_applied', 'Api_permanent@fulltime_applied');




    Route::post('/send-otp', 'Api_cnt@sendOtp');
    Route::post('/verify-otp', 'Api_cnt@verifyOtp');


    // hari api new permanent drivers

    // Route
    Route::post('/profile_edit', 'Api_cnt@profile_edit_details');
    Route::post('/profile_step_1', 'Api_cnt@profile_step_one');
    Route::post('/profile_step_2', 'Api_cnt@profile_step_two');
    Route::post('/profile_step_3', 'Api_cnt@profile_step_three');

    Route::post('/profile_complete', 'Api_permanent@completion');
    Route::post('/vacancy/details', 'Api_permanent@vacancyDetails');

    Route::post('/job', 'Api_permanent@job');
   Route::post('/vacancy/latest', 'Api_permanent@latest');
    Route::post('/vacancy/apply', 'Api_permanent@apply');
    Route::post('/permanent_dashboard', 'Api_permanent@permanent_dashboard');
    Route::post('/permanent_jobs', 'Api_permanent@permanent_dashboard');
    Route::post('/permanent_jobs_applied', 'Api_permanent@permanent_jobs_applied');

    Route::post('/permanent_job_id', 'Api_permanent@permanent_job_id');

    Route::post('/permanent_job_apply', 'Api_permanent@permanent_job_apply');

    Route::post('/driver_withdraw', 'Api_permanent@driver_withdraw');

    Route::post('/driver_withdraw_list', 'Api_permanent@driver_withdraw_list');

    Route::post('/permanent_job_saved', 'Api_permanent@permanent_job_saved');

    Route::post('/help_support', 'Api_permanent@help_support');


    Route::post('/notify_list', 'Api_cnt@notify_list');







    // Route?


    // Route::get('/dashboard', function () {
    //     return response()->json(['message' => 'Welcome to your dashboard!']);
    // });

    // Add more protected routes here
});
