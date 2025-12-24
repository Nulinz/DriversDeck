<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Corporate;
use App\Models\Driver;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $vehicleOwnersCount = Corporate::where('status', '!=', 'pending')
            ->where('type', 'owner')
            ->count();
        
        $corporateCount = Corporate::where('status', 'approved')
            ->where('type', 'corporate')
            ->count();
        
        $actingDriverCount = Driver::where('type', 'acting')
        ->whereNotIn('status', ['pending', 'rejected'])
        ->count();
        // $actingDriverCount = Driver::where('status', '!=', 'pending')
        //     ->where('type', 'acting')
        //     ->where('subscription', 'yes')
        //     ->count();


        
        $fullTimeDriverCount = Driver::where('type', 'permanent')
        ->whereNotIn('status', ['pending', 'rejected'])
        ->count();

        // Calculate Registration Amount for today
        $registrationAmount = $this->getTodayRegistrationAmount();

        // Calculate wallet amounts for today from referral table
        $fullTimeDriverWalletAmount = $this->getAllTimeReferralAmount('permanent');
        $actingDriverWalletAmount = $this->getAllTimeReferralAmount('acting');
        $vehicleOwnersWalletAmount = $this->getAllTimeReferralAmount('owner');
        
        // Create collection with counts and amounts
        $all = collect([
            (object) [
                'type' => 'vehicle_owners', 
                'total' => $vehicleOwnersCount,
                'wallet_amount' => $vehicleOwnersWalletAmount
            ],
            (object) [
                'type' => 'corporate', 
                'total' => $corporateCount,
                'registration_amount' => $registrationAmount
            ],
            (object) [
                'type' => 'acting', 
                'total' => $actingDriverCount,
                'wallet_amount' => $actingDriverWalletAmount
            ],
            (object) [
                'type' => 'full_time', 
                'total' => $fullTimeDriverCount,
                'wallet_amount' => $fullTimeDriverWalletAmount
            ],
        ]);

        // Get today's corporate registrations with subscription data
        $corp = Corporate::whereDate('created_at', Carbon::today())
            ->get()
            ->map(function ($sub) {
                $sub->pk = DB::table('subscription')
                    ->where('f_id', $sub->id)
                    ->where('type', 'corporate')
                    ->first();

                return $sub;
            });

        // Get today's driver registrations with subscription data
        $driver = Driver::whereDate('created_at', Carbon::today())
            ->get()
            ->map(function ($sub1) {
                $sub1->pk = DB::table('subscription')
                    ->where('f_id', $sub1->id)
                    ->where('type', 'acting')
                    ->first();

                return $sub1;
            });

        $pay = $corp->concat($driver);

        return view('admin.dashboard.index', [
            'all' => $all, 
            'pay' => $pay,
            'amounts' => [
                'registration' => $registrationAmount,
                'full_time_wallet' => $fullTimeDriverWalletAmount,
                'acting_wallet' => $actingDriverWalletAmount,
                'vehicle_owners_wallet' => $vehicleOwnersWalletAmount
            ]
        ]);
    }

    /**
     * Get today's registration amount from corporate users with subscription = 'yes'
     */
    private function getTodayRegistrationAmount()
    {
        // Get corporate IDs where type = 'corporate' and subscription = 'yes'
        $corporateIds = Corporate::where('type', 'corporate')
            ->where('subscription', 'yes')
            ->pluck('id')
            ->toArray();

        if (empty($corporateIds)) {
            return 0;
        }

        // Get total amount from subscription table for today's transactions
        $totalAmount = DB::table('subscription')
            ->whereIn('f_id', $corporateIds)
            ->where('type', 'corporate')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return $totalAmount ?? 0;
    }

    /**
     * Get today's referral amount by type from referral table
     */
    private function getTodayReferralAmount($type)
    {
        $totalAmount = DB::table('referal') // Note: table name might be 'referal' as shown in your images
            ->where('f_type', $type)
            ->whereDate('created_at', Carbon::today())
            ->sum('amt'); // Using 'amt' as shown in your database structure

        return $totalAmount ?? 0;
    }

     private function getAllTimeReferralAmount($type)
    {
        $totalAmount = DB::table('referal') // Note: table name is 'referal'
            ->where('f_type', $type)
            // Removed: ->whereDate('created_at', Carbon::today())
            ->sum('amt'); // Using 'amt' as shown in your database structure

        return $totalAmount ?? 0;
    }
}