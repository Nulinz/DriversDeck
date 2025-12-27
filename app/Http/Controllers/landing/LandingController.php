<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Corporate;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;
use App\Models\Vacancy;
class LandingController extends Controller
{
public function landing()
{
    // Get total drivers (acting + permanent)
    $totalDrivers = Driver::whereIn('type', ['acting', 'permanent'])
        ->count();
    
    // Get total corporates (type = 'corporate')
    $totalCorporates = Corporate::where('type', 'corporate')
        ->count();
    
    // Get total owners (type = 'owner')
    $totalOwners = Corporate::where('type', 'owner')
        ->count();
    
    // Get happy customers
    $happyCustomers = DB::table('corporate')
        ->where('corporate.type', 'client')
        ->where('corporate.status', 'approved')
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('trip')
                ->join('trip_applied', 'trip.id', '=', 'trip_applied.trip_id')
                ->whereColumn('trip.c_by', 'corporate.id');
        })
        ->count();
    
    // Get vacancies
    $vacancies = Vacancy::where('status', 'active')
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
    
   // Get trusted companies with logos (only corporates)
    $trustedCompanies = Corporate::whereNotNull('logo')
        ->where('logo', '!=', '')
        ->where('type', 'corporate') // Add this line to filter only corporates
        ->where('status', 'approved')
        ->where('active_status', 'active')
        ->select('id', 'name', 'logo', 'c_type')
        ->orderBy('created_at', 'desc')   
        ->get();
        
        return view('landing.index', compact(
            'totalDrivers', 
            'totalCorporates', 
            'totalOwners', 
            'happyCustomers', 
            'vacancies',
            'trustedCompanies'
        ));
    }


    public function about()
    {
        return view('landing.about');
    }
    public function contact()
    {
        return view('landing.contact');
    }
    public function corprate()
    {
        return view('landing.corporate');
    }
    public function owners()
    {
        return view('landing.owners');
    }
    public function drivers()
    {
        return view('landing.drivers');
    }
    
    public function terms()
    {
        return view('landing.terms');
    }
    public function acceptane()
    {
        return view('landing.acceptance');
    }
    public function cancel()
    {
        return view('landing.cancel');
    }
    public function refund()
    {
        return view('landing.refund');
    }
}
