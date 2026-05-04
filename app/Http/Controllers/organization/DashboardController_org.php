<?php

namespace App\Http\Controllers\organization;

use App\Http\Controllers\Controller;
use App\Models\PermanentJobs;
use App\Models\Trip;
use Illuminate\Http\Request;

class DashboardController_org extends Controller
{
    public function index()
    {
        $user = auth('corporate')->user();

        $per = PermanentJobs::with('subApplies')->where('c_by', $user->id)
            ->where('status', '!=', 'pending')
            ->orderBy('created_at', 'DESC')
            ->get()->map(function ($job) {

                $job->applied_count = $job->subApplies->count();

                return $job;
            });

        $act = Trip::with('appliedDrivers')->where('c_by', $user->id)
            ->where('status', '!=', 'pending')
            ->orderBy('created_at', 'DESC')
            ->get()->map(function ($trip) {

                $trip->applied_count = $trip->appliedDrivers->count();
                return $trip;
            });

        // $applied = $per->sum(function ($job) {
        //     return $job->subApplies->count();
        // });

        //  dd($act->toArray());
        return view('organization.dashboard.index', ['status' => 'active', 'per_jobs' => $per, 'trips' => $act]);
    }
}
