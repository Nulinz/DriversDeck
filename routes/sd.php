<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CorprateController;
use App\Http\Controllers\CustomerReport;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\organization\AuthController_org;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\TripCanelController;
use App\Http\Controllers\ChangeTypeController;
use App\Http\Controllers\landing\LandingController;
use Illuminate\Support\Facades\Route;

// admin route
// Auth
Route::get('/admin/login', [AuthController::class, 'login'])->name('admin.auth.login');
Route::post('/admin/login_check', [AuthController::class, 'login_check'])->name('admin.auth.check');
Route::get('/admin/logout', [AuthController::class, 'logout'])->name('admin.auth.logout');
Route::get('/admin/forgot_password', [AuthController::class, 'forgotpass'])->name('admin.auth.forgotpass');
Route::get('/admin/get_otp', [AuthController::class, 'otp'])->name('admin.auth.otp');
Route::get('/admin/change_password', [AuthController::class, 'changepass'])->name('admin.auth.change_pass');
// dashboard
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
// candidate (Driver)
Route::get('/admin/candidate_list', [CandidateController::class, 'candidate'])->name('admin.candidate.index');
Route::get('/admin.candidate.profile/{id}', [CandidateController::class, 'profile'])->name('admin.candidate.profile');
// corprate
Route::get('/admin/corprate_list', [CorprateController::class, 'corprate'])->name('admin.corprate.corprate_list');
Route::get('/admin/corprate_profile/{id}', [CorprateController::class, 'corprate_profile'])->name('admin.corprate.corprate_profile');
Route::get('/admin/owner_profile/{id}', [CorprateController::class, 'owner_profile'])->name('admin.corprate.owner_profile');
// cutomer report
Route::get('/admin/customer_report', [CustomerReport::class, 'customer_report'])->name('admin.customer.customer_report');
// approval list all regirstered
Route::get('/admin/approval_list', [ApprovalController::class, 'approval'])->name('admin.approval.approval_list');
// settings
Route::get('/admin/settings', [SettingsController::class, 'settings'])->name('admin.settings.settings');
Route::get('/admin/add_user', [SettingsController::class, 'addUser'])->name('admin.settings.add_user');
Route::post('/admin/user/store', [SettingsController::class, 'storeUser'])->name('admin.settings.user.store');
Route::get('/admin/add_permission', [SettingsController::class, 'addPermission'])->name('admin.settings.add_permission');
Route::get('/admin/edit_permission', [SettingsController::class, 'editPermission'])->name('admin.settings.edit_permission');
Route::get('/admin/location', [SettingsController::class, 'settings'])->name('admin.settings.location');
Route::post('/admin/location/store', [SettingsController::class, 'storeLocation'])->name('admin.settings.location.store');



// request
Route::get('/admin/request', [RequestController::class, 'request'])->name('admin.wallet.request');
// reports
Route::get('/admin/reports', [ReportsController::class, 'reports'])->name('admin.reports.report');
// trip
Route::get('/admin/trip', [TripController::class, 'trip_list'])->name('admin.trip.trip_list');
Route::get('/admin/trip_profile', [TripController::class, 'trip_profile'])->name('admin.trip.trip_profile');
// vacancy
Route::get('/admin/vacancy', [VacancyController::class, 'vacancy'])->name('admin.vacancy.vacancy_approvel');
// location
Route::get('/admin.location', [LocationController::class, 'location'])->name('admin.location.location');
// trip cancel
Route::get('/admin/trip_canel', [TripCanelController::class, 'tripcanel'])->name('admin.trip_cancel.tripcanel_list');
// change type
Route::get('/admin/change_type', [ChangeTypeController::class, 'changetype'])->name('admin.change_type.change_type');

//org Middleware....
Route::get('/organization/login', [App\Http\Controllers\organization\AuthController_org::class, 'login'])->name('auth.login.org');

// organization group routes
Route::prefix('organization')->name('organization.')->namespace('App\Http\Controllers\organization')->middleware(['auth:corporate'])->group(function () {
    // auth

    Route::post('/login_check', 'AuthController_org@login_check')->name('auth.login_check');
    Route::get('/logout', 'AuthController_org@logout')->name('auth.logout');
    // forgot password
    Route::get('/forgot_pass', 'AuthController_org@forgotpass')->name('auth.forgot_pass');
    // get opt
    Route::get('/get_opt', 'AuthController_org@otp')->name('auth.otp');
    // change password
    Route::get('/change_pass', 'AuthController_org@changepass')->name('auth.change_pass');

    // register
    Route::get('/register_details', 'AuthController_org@register_basic')->name('auth.register_details');
    Route::post('/register_details_store', 'AuthController_org@register_basic_store')->name('auth.register_details_store');
    Route::get('/register_contact', 'AuthController_org@register_contact')->name('auth.register_contact');
    Route::post('/register_contact_store', 'AuthController_org@register_contact_store')->name('auth.register_contact_store');
    Route::get('/register_address', 'AuthController_org@register_address')->name('auth.register_address');
    Route::post('/register_address_store', 'AuthController_org@register_address_store')->name('auth.register_address_store');
    Route::get('/register_business', 'AuthController_org@register_business')->name('auth.register_business');
    Route::post('/register_business_store', 'AuthController_org@register_business_store')->name('auth.register_business_store');
    Route::get('/register_asset', 'AuthController_org@register_asset')->name('auth.register_asset');
    Route::post('/register_asset_store', 'AuthController_org@register_asset_store')->name('auth.register_asset_store');
    Route::get('/register_subscription', 'AuthController_org@subscription')->name('auth.register_subscription');

    // dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
    // hired list
    Route::get('/hired_list', 'HiredContrller@hired')->name('hired.hired_list');
    // fulltime driver profile
    Route::get('/ft_driver_profile', 'HiredContrller@ft_profile')->name('hired.ft_driver_profile');
    // acting driver profile
    Route::get('/at_driver_profile', 'HiredContrller@at_profile')->name('hired.at_driver_profile');
    // vacancy list
    Route::get('/vacancy_list', 'VacancyController@vacancy')->name('vacancy.vacancy_list');
    // add vacancy
    Route::get('/add_vacancy', 'VacancyController@add_vacancy')->name('vacancy.add_vacancy');
    // vacancy fulltime
    Route::get('/fulltime_list', 'VacancyController@fulltime_list')->name('vacancy.fulltime_list');
    // vacancy acting
    Route::get('/acting_list', 'VacancyController@acting_list')->name('vacancy.acting_list');

    // settings
    Route::get('/settings', 'SettingsController@settings')->name('settings.settings');
});

// landing routes
Route::get('/', [LandingController::class, 'landing'])->name('landing.index');

Route::name('landing.')->namespace('App\Http\Controllers\landing')->group(function () {

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
