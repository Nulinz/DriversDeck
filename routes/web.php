<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CorprateController;
use App\Http\Controllers\CustomerReport;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Organization\AuthController_org;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\TripCanelController;
use App\Http\Controllers\ChangeTypeController;
use App\Http\Controllers\Api_owner;
use App\Http\Controllers\Landing\LandingController;
use App\Http\Controllers\Organization\VacancyController_org;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CashfreepaymentController;



//for 
Route::get('/payment', [CashfreepaymentController::class, 'createPayment'])->name('payment.create');
Route::get('/pays', [CashfreepaymentController::class, 'Pay'])->name('payment.pay');
Route::get('/cashfree/callback', [CashfreepaymentController::class, 'callback'])->name('payment.callback');


//payment integration

// routes/web.php

Route::get('/pay', [PaymentController::class, 'initiatePayment']);
Route::get('/mail', [PaymentController::class, 'sendTestEmail']);
Route::get('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'handleCallback'])->name('cashfree.callback');
Route::post('/payment/webhook', [\App\Http\Controllers\PaymentController::class, 'handleWebhook'])->name('cashfree.webhook');


// admin route
// Auth
Route::get('/admin/login', [AuthController::class, 'login'])->name('admin.auth.login');
Route::post('/admin/login_check', [AuthController::class, 'login_check'])->name('admin.auth.check');
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('admin.auth.logout');
Route::get('/admin/forgot_password', [AuthController::class, 'forgotpass'])->name('admin.auth.forgotpass');
Route::get('/admin/get_otp', [AuthController::class, 'otp'])->name('admin.auth.otp');
Route::get('/admin/change_password', [AuthController::class, 'changepass'])->name('admin.auth.change_pass');
// dashboard

Route::middleware(['auth:web', 'cook.auth.corp'])->group(function () {

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
    // candidate (Driver)
    Route::get('/admin/candidate_list', [CandidateController::class, 'candidate'])->name('admin.candidate.index');
    Route::get('/admin/candidate/profile/{id}', [CandidateController::class, 'profile'])->name('admin.candidate.profile');
    Route::post('/admin/corporate/status-toggle/{id}', [CandidateController::class, 'toggleStatus']);
        Route::put('/admin/candidate/update-profile/{id}', [CandidateController::class, 'updateProfile'])->name('admin.candidate.update_profile');

    // Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
// Add this route to your web.php file
Route::post('/admin/candidate/active-status-toggle/{id}', [CandidateController::class, 'toggleActiveStatus'])->name('admin.candidate.active-status-toggle');

// Your existing route
Route::get('/admin/candidate_list', [CandidateController::class, 'candidate'])->name('admin.candidate.index');
// Driver type change request routes
Route::get('/admin/driver-change-requests', [CandidateController::class, 'showTypeChangeRequests'])
    ->name('admin.driverChangeRequests');

Route::post('/admin/driver-change-requests/{requestId}', [CandidateController::class, 'approveDriverTypeChange'])
    ->name('admin.approveDriverTypeChange');
Route::get('/admin/candidate/export-data', [CandidateController::class, 'exportDriverData'])->name('admin.candidate.export.data');

// Optional: API routes for mobile app or AJAX requests
Route::post('/api/driver/type-change-request', [CandidateController::class, 'createTypeChangeRequest'])
    ->name('api.driver.typeChangeRequest');
Route::get('/admin/corprate', [CorprateController::class, 'index'])->name('admin.corprate.index');

// Corporate status toggle route
Route::post('/admin/corporates1/status-toggle/{id}', [CorprateController::class, 'toggleStatus1'])->name('admin.corporate.toggle-status');

Route::get('/admin/corprate_create', [CorprateController::class, 'create'])->name('admin.corprate.corprate_create');
Route::post('/admin/corprate_store', [CorprateController::class, 'store'])->name('admin.corporate.store');
Route::post('/admin/get-locations-by-district', [CorprateController::class, 'get_locations_by_district'])->name('admin.get_locations_by_district');


Route::get('/api/driver/{driverId}/type-change-history', [CandidateController::class, 'getDriverTypeChangeHistory'])
    ->name('api.driver.typeChangeHistory');
    // corprate
    Route::get('/admin/corprate_list', [CorprateController::class, 'corprate'])->name('admin.corprate.corprate_list');
    // Add this route to your web.php file
Route::post('/admin/corporate/active-status-toggle/{id}', [CorprateController::class, 'toggleActiveStatus'])->name('admin.corporate.active-status-toggle');
Route::get('/admin/corporate/export-data', [CorprateController::class, 'exportCorporateData'])->name('admin.corporate.export.data');

// Your existing route
    Route::get('/admin/corprate_profile/{id}', [CorprateController::class, 'corprate_profile'])->name('admin.corprate.corprate_profile');
    Route::get('/admin/owner_profile/{id}', [CorprateController::class, 'owner_profile'])->name('admin.corprate.owner_profile');
    Route::post('/admin/corporate/status-toggle/{id}', [CorprateController::class, 'toggleStatus']);
   Route::put('corprate/update-profile/{id}', [CorprateController::class, 'update_corporate_profile'])
        ->name('admin.corprate.update_profile');
        Route::put('/admin/owner_profile/{id}', [CorprateController::class, 'update_owner_profile'])->name('admin.corprate.update_owner_profile');

    // cutomer report
    Route::get('/admin/customer_report', [CustomerReport::class, 'customer_report'])->name('admin.customer.customer_report');
    //new
    Route::post('/admin/customer/approve', [CustomerReport::class, 'handleCustomerReportAction'])->name('admin.customer.approve');
    Route::post('/admin/customer/feedback', [CustomerReport::class, 'handleCustomerFeedbackAction'])->name('admin.customer.feedback');
    Route::get('/vacancy/create', [App\Http\Controllers\VacancyController::class, 'create'])->name('admin.vacancy.create');
        Route::post('/vacancy/store', [App\Http\Controllers\VacancyController::class, 'store'])->name('admin.vacancy.store');
    Route::get('vacancy/{vacancy}/applied-details', [App\Http\Controllers\VacancyController::class, 'appliedDetails'])->name('admin.vacancy.applied-details');

    Route::get('vacancy/{vacancy}/applied-users', [App\Http\Controllers\VacancyController::class, 'getAppliedUsers'])->name('admin.vacancy.applied-users');
      Route::put('admin/vacancy/{id}/update-status', [App\Http\Controllers\VacancyController::class, 'updateStatus'])->name('admin.vacancy.update-status');
Route::put('admin/vacancys/{id}/update-status', 
    [App\Http\Controllers\VacancyController::class, 'update_Status']
)->name('admin.vacancys.update-status');
Route::post('admin/handle-approval-reason', [ApprovalController::class, 'handleApprovalWithReason'])
    ->name('admin.handle.approval.reason');
Route::post('/admin/subscription-approval/{type}/{id}/{action}', [ApprovalController::class, 'handleSubscriptionApproval'])->name('admin.handle.subscription');

    // approval list all regirstered
    Route::get('/admin/approval_list', [ApprovalController::class, 'approval'])->name('admin.approval.approval_list');
    Route::post('/admin/approval/{type}/{id}/{action}', [ApprovalController::class, 'handleApproval'])->name('admin.handle.approval');

    // settings
    Route::get('/admin/settings', [SettingsController::class, 'settings'])->name('admin.settings.settings');
    Route::get('/admin/add_user', [SettingsController::class, 'addUser'])->name('admin.settings.add_user');
    Route::post('/admin/user/store', [SettingsController::class, 'storeUser'])->name('admin.settings.user.store');
    Route::get('/admin/add_permission', [SettingsController::class, 'addPermission'])->name('admin.settings.add_permission');
    Route::get('/admin/edit_permission', [SettingsController::class, 'editPermission'])->name('admin.settings.edit_permission');
    Route::get('/admin/location', [SettingsController::class, 'settings'])->name('admin.settings.location');
    Route::post('/admin/location/store', [SettingsController::class, 'storeLocation'])->name('admin.settings.location.store');
    Route::post('/admin/user/status-toggle/{id}', [SettingsController::class, 'toggleStatus']);

Route::get('/admin/settings/location/{location}/deactivate', [SettingsController::class, 'deactivateLocation'])->name('location.deactivate');
Route::get('/admin/settings/location/{location}/activate', [SettingsController::class, 'activateLocation'])->name('location.activate');


    // request
    // Route::get('/admin/request', [RequestController::class, 'request'])->name('admin.wallet.request');
    // reports
    Route::get('/admin/reports', [ReportsController::class, 'reports'])->name('admin.reports.report');
    // trip
    Route::get('/admin/trip', [TripController::class, 'trip_list'])->name('admin.trip.trip_list');
    Route::get('/admin/trip_profile', [TripController::class, 'trip_profile'])->name('admin.trip.trip_profile');
    // vacancy
    Route::get('/admin/vacancy', [VacancyController::class, 'vacancy'])->name('admin.vacancy.vacancy_approvel');

    // new Vacancy Routes
    // Route::get('/admin/vacancy', [VacancyController::class, 'vacancy'])->name('admin.vacancy.vacancy_approvel');
    // Route::post('/admin/vacancy/approval', [VacancyController::class, 'handleApproval'])->name('admin.vacancy.approval');

 Route::get('/location/activate/{location}', [LocationController::class, 'activateLocation'])
        ->name('admin.location.activate');
    // location
    Route::get('/admin.location', [LocationController::class, 'location'])->name('admin.location.location');
    //new
    Route::get('/admin/location/deactivate/{location}', [LocationController::class, 'deactivateLocation'])->name('admin.location.deactivate');

    // trip cancel
    Route::get('/admin/trip_canel', [TripCanelController::class, 'tripcanel'])->name('admin.trip_cancel.tripcanel_list');

    //new
    Route::post('admin/trip-cancel/handle', [TripCanelController::class, 'handleTripCancel'])->name('admin.trip_cancel.handle');

    // change type
    Route::get('/admin/change_type', [ChangeTypeController::class, 'changetype'])->name('admin.change_type.change_type');
});

// request
Route::get('/admin/request', [RequestController::class, 'request'])->name('admin.wallet.request');

//new
Route::post('/admin/wallet/approve', [RequestController::class, 'handleApproval'])->name('admin.wallet.approve');

// reports
Route::get('/admin/reports', [ReportsController::class, 'reports'])->name('admin.reports.report');
// trip
Route::get('/admin/trip', [TripController::class, 'trip_list'])->name('admin.trip.trip_list');
// Route::get('/admin/trip_profile', [TripController::class, 'trip_profile'])->name('admin.trip.trip_profile');

// new

Route::get('/admin/trip_profile/{id}', [TripController::class, 'trip_profile'])->name('admin.trip.trip_profile');
// vacancy
Route::get('/admin/vacancy', [VacancyController::class, 'vacancy'])->name('admin.vacancy.vacancy_approvel');
// Correct POST route
Route::post('/admin/vacancy/approval', [VacancyController::class, 'handleApproval'])->name('admin.vacancy.approval');
// In routes/web.php
// GET route for approve/reject via link
// Route::get('/admin/vacancy/approval/{type}/{id}/{action}', [VacancyController::class, 'handleApprovalReq'])
//     ->name('admin.vacancy.action');

// location
Route::get('/admin.location', [LocationController::class, 'location'])->name('admin.location.location');
// trip cancel
// Route::get('/admin/trip_canel', [TripCanelController::class, 'tripcanel'])->name('admin.trip_cancel.tripcanel_list');
// change type
Route::get('/admin/change_type', [ChangeTypeController::class, 'changetype'])->name('admin.change_type.change_type');

//org Middleware....
Route::get('/organization/login', [App\Http\Controllers\Organization\AuthController_org::class, 'login'])->name('auth.login.org');


Route::get('/subscription/payment-details/{plan}', [AuthController_org::class, 'payment_details'])->name('auth.payment_details');
Route::post('/subscription/submit-payment', [AuthController_org::class, 'submit_payment'])->name('auth.submit_payment');
Route::get('/subscription/transaction-form/{plan}', [AuthController_org::class, 'transaction_form'])->name('auth.transaction_form');


//new


Route::post('/organization/send-otp', [AuthController_org::class, 'sendOtp'])->name('auth.send_otp');

Route::get('/organization/otp', [AuthController_org::class, 'otp'])->name('auth.otp');
Route::post('/organization/verify-otp', [AuthController_org::class, 'verifyOtp'])->name('auth.verify_otp');


Route::post('organization/login_check', [App\Http\Controllers\Organization\AuthController_org::class, 'login_check'])->name('auth.login_check');



// Route::post('/organization/send-otp', [AuthController_org::class, 'sendOtp'])->name('auth.send_otp');

// Route::get('/organization/otp', [AuthController_org::class, 'otp'])->name('auth.otp');
// Route::post('/organization/verify-otp', [AuthController_org::class, 'verifyOtp'])->name('auth.verify_otp');


// Route::post('organization/login_check', [App\Http\Controllers\Organization\AuthController_org::class, 'login_check'])->name('auth.login_check');

// forgot password
Route::get('organization/forgot_pass', [App\Http\Controllers\Organization\AuthController_org::class, 'forgotpass'])->name('auth.forgot_pass');
// get opt
Route::get('organization/get_opt', [App\Http\Controllers\Organization\AuthController_org::class, 'otp'])->name('auth.otp');
// change password
Route::get('organization/change_pass', [App\Http\Controllers\Organization\AuthController_org::class, 'changepass'])->name('auth.change_pass');

// register details

// trip cancel from owner...

Route::post('/trip-cancel', [App\Http\Controllers\Api_owner::class, 'trip_cancel'])->name('act_trip_cancel_owner');

Route::get('/organization/get-locations/{district}', [AuthController_org::class, 'get_locations_by_district'])
     ->name('organization.getLocations');
Route::get('organization/register_details', [AuthController_org::class, 'register_basic'])->name('auth.register_details');
Route::post('organization/register_details_store', [AuthController_org::class, 'register_basic_store'])->name('auth.register_details_store');

Route::get('organization/register_contact', [AuthController_org::class, 'register_contact'])->name('auth.register_contact');
Route::post('organization/register_contact_store', [AuthController_org::class, 'register_contact_store'])->name('auth.register_contact_store');
Route::get('organization/register_address', [AuthController_org::class, 'register_address'])->name('auth.register_address');
Route::post('organization/register_address_store', [AuthController_org::class, 'register_address_store'])->name('auth.register_address_store');
Route::get('organization/register_business', [AuthController_org::class, 'register_business'])->name('auth.register_business');
Route::post('organization/register_business_store', [AuthController_org::class, 'register_business_store'])->name('auth.register_business_store');
Route::get('organization/register_asset', [AuthController_org::class, 'register_asset'])->name('auth.register_asset');
Route::post('organization/register_asset_store', [AuthController_org::class, 'register_asset_store'])->name('auth.register_asset_store');

// Updated subscription routes
Route::get('organization/register_subscription', [AuthController_org::class, 'subscription'])->name('auth.register_subscription');
Route::get('organization/register_subscription_store', [AuthController_org::class, 'subscription_store'])->name('auth.register_subscription_store');

// New payment routes
Route::post('organization/initiate_payment', [AuthController_org::class, 'initiate_payment'])->name('auth.initiate_payment');
Route::get('organization/payment_callback', [AuthController_org::class, 'payment_callback'])->name('auth.payment_callback');// organization group routes
Route::prefix('organization')->name('organization.')->namespace('App\Http\Controllers\Organization')->middleware(['auth:corporate', 'cook.auth.corp'])->group(function () {
    // auth
    // Add this near your other auth routes
    // Route::get('/login', [App\Http\Controllers\Organization\AuthController_org::class, 'login'])->name('login');

    // Route::get('/login', 'AuthController_org@login')->name('auth.login');
    Route::get('/logout', 'AuthController_org@logout')->name('auth.logout');





    // dashboard
    Route::get('/dashboard', 'DashboardController_org@index')->name('dashboard.index');
    // hired list
    // Route::get('/hired_list', 'HiredContrller@hired')->name('hired.hired_list');
    // // fulltime driver profile
    // Route::get('/ft_driver_profile/{id}', 'HiredContrller@ft_profile')->name('hired.ft_driver_profile');
    // // acting driver profile
    // Route::get('/at_driver_profile/{id}', 'HiredContrller@at_profile')->name('hired.at_driver_profile');

    Route::get('/hired_list', 'HiredContrller@hired')->name('hired.hired_list');
    // fulltime driver profile
    Route::get('/ft_driver_profile/{id}', 'HiredContrller@ft_profile')->name('hired.ft_driver_profile');
    // acting driver profile
    Route::get('/at_driver_profile/{id}', 'HiredContrller@at_profile')->name('hired.at_driver_profile');
    // vacancy list
    Route::get('/vacancy_list', 'VacancyController_org@vacancy')->name('vacancy.vacancy_list');
    // add vacancy
    Route::get('/add_vacancy', 'VacancyController_org@add_vacancy')->name('vacancy.add_vacancy');
    Route::post('/add_vacancy', 'VacancyController_org@add_vacancy_store')->name('add_vacancy');

    // vacancy fulltime
    Route::get('/fulltime_list/{id}', 'VacancyController_org@fulltime_list')->name('vacancy.fulltime_list');
    Route::post('/fulltime/status/update/{id?}', 'VacancyController_org@updateFullTimeStatus')->name('fulltime.status.update');
    Route::get('/fulltime/status/cancel/{id}', 'VacancyController_org@job_cancel')->name('fulltime.status.cancel');


    // feedback and report url

    Route::post('/feedback_add', 'HiredContrller@add_feedback')->name('feedback.add');
    Route::post('/report_add', 'HiredContrller@report_add')->name('report.add');

    // vacancy acting
    Route::get('/acting_list/{id}', 'VacancyController_org@acting_list')->name('vacancy.acting_list');
    Route::post('/acting/status/update/{id}', 'VacancyController_org@updateActingStatus')->name('acting.status.update');


    // trip list
    Route::get('/trip', 'OrgTripController@org_trip_list')->name('trip.trip_list');
    // trip profile
    Route::get('/trips/{trip}', 'OrgTripController@org_trip_profile')->name('trip.profile');


    //new for current profile
    Route::get('/trip/{id}/current-location', 'OrgTripController@getCurrentLocation');
    Route::post('/api/trips/{id}/update-location', 'OrgTripController@updateCurrentLocation');

    // settings
    Route::get('/settings', 'SettingsController_org@settings')->name('settings.settings');
    Route::post('/settings_details_store', 'SettingsController_org@update_basic_store')->name('update_details_store');
    Route::post('/settings_contact_store', 'SettingsController_org@update_contact_store')->name('update_contact_store');
    Route::post('/settings_address_store', 'SettingsController_org@update_address_store')->name('update_address_store');
    Route::post('/settings_business_store', 'SettingsController_org@update_business_store')->name('update_business_store');
    Route::post('/settings_asset_store', 'SettingsController_org@update_asset_store')->name('update_asset_store');

    //payment integraation

    // routes/web.php

    // Route::get('/pay', 'PaymentController@initiatePayment')->name('cashfree.pay');
    // Route::get('/payment/callback', 'PaymentController@handleCallback')->name('cashfree.callback');
    // Route::post('/payment/webhook', 'PaymentController@handleWebhook')->name('cashfree.webhook');
});

// landing routes
Route::get('/', [LandingController::class, 'landing'])->name('landing.index');

Route::name('landing.')->namespace('App\Http\Controllers\Landing')->group(function () {

    // about
    Route::get('/about', 'LandingController@about')->name('landing.about');
    // contact
    Route::get('/contact', 'LandingController@contact')->name('landing.contact');
    Route::get('/corporate', 'LandingController@corprate')->name('landing.corporate');
    Route::get('/owners', 'LandingController@owners')->name('landing.owners');
    Route::get('/drivers', 'LandingController@drivers')->name('landing.drivers');
    Route::get('/terms', 'LandingController@terms')->name('landing.terms');
    Route::get('/acceptance', 'LandingController@acceptane')->name('landing.acceptance');
    Route::get('/cancel', 'LandingController@cancel')->name('landing.cancel');
    Route::get('/refund', 'LandingController@refund')->name('landing.refund');
});

use Illuminate\Http\Request;

Route::get('/refferral', function (Request $request) {
    $id = $request->query('id'); // e.g. DDPER001

    // Build Google Play Store URL
    $playUrl = "https://play.google.com/store/apps/details?id=com.driversdeck.app&referrer=id%".$id;

    return redirect()->away($playUrl);
});
