<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Driver;
use App\Models\Corporate;
use App\Jobs\Trip_notify;
use App\Models\PermanentJobs;
use App\Models\Vacancy;
use App\Models\VacancyApplied;
use App\Models\Trip;


class VacancyController extends Controller
{
    // public function vacancy(){
    //     return view('admin.vacancy.vacancy_approvel');
    // }


    //  public function vacancy()
    // {
    //     $vacancies = DB::table('permanent_jobs')
    //         ->leftJoin('sub_applied', 'permanent_jobs.id', '=', 'sub_applied.p_id')
    //         ->leftJoin('driver', 'sub_applied.d_id', '=', 'driver.id')
    //         ->join('corporate', 'permanent_jobs.c_by', '=', 'corporate.id')
    //         ->select(
    //             'sub_applied.id as sub_id',
    //             'corporate.name as corporate_name',
    //             'permanent_jobs.job_type',
    //             'driver.name as driver_name',
    //             'driver.phone',
    //             'corporate.mail',
    //             'permanent_jobs.job_location',
    //             'sub_applied.status'
    //         )
    //         ->orderByDesc('permanent_jobs.id')
    //         ->get();

    //     return view('admin.vacancy.vacancy_approvel', compact('vacancies'));
    // }


    // public function vacancy()
    // {
    //     // Get pending permanent jobs
    //     $permanentJobs = DB::table('permanent_jobs')->where('permanent_jobs.status', 'pending')
    //         // ->leftJoin('sub_applied', 'permanent_jobs.id', '=', 'sub_applied.p_id')
    //         // ->leftJoin('driver', 'sub_applied.d_id', '=', 'driver.id')
    //         ->join('corporate', 'permanent_jobs.c_by', '=', 'corporate.id')
    //         ->select(
    //             'corporate.id as corp_id',
    //             'permanent_jobs.id as job_id',
    //             'corporate.name as corporate_name',
    //             'permanent_jobs.job_type',
    //             // 'driver.name as driver_name',
    //             // 'driver.phone',
    //             'corporate.contact',
    //             'permanent_jobs.job_location',
    //             'permanent_jobs.status',
    //             'permanent_jobs.created_at'
    //         )
    //         ->orderBy('permanent_jobs.created_at', 'DESC')
    //         ->get()
    //         ->map(function ($item) {
    //             $item->job_category = 'permanent';
    //             return $item;
    //         });

    //     // $vacancies = $permanentJobs->sortByDesc('created_at');

    //     // dd($vacancies);


    //     return view('admin.vacancy.vacancy_approvel', ['vacancies' => $permanentJobs]);
    // }

    public function vacancy()
    {
        /* ======================
         * Permanent Jobs
         * ====================== */
        $permanentJobs = DB::table('permanent_jobs')
            ->join('corporate', 'permanent_jobs.c_by', '=', 'corporate.id')
            ->where('permanent_jobs.status', 'pending')
            ->select(
                'permanent_jobs.id as job_id',
                'corporate.id as corp_id',
                'corporate.name as corporate_name',
                'permanent_jobs.job_type',
                'corporate.contact',
                'permanent_jobs.job_location as location',
                'permanent_jobs.status',
                'permanent_jobs.created_at',
                DB::raw("'permanent' as job_category")
            );


        /* ======================
         * Acting Jobs (Trips)
         * ====================== */
        $actingJobs = DB::table('trip')
            ->join('corporate', 'trip.c_by', '=', 'corporate.id')
            ->where('trip.status', 'pending')
            ->select(
                'trip.id as job_id',
                'corporate.id as corp_id',
                'corporate.name as corporate_name',
                DB::raw("'Acting' as job_type"),
                'corporate.contact',
                DB::raw("CONCAT(trip.st_loc, ' → ', trip.st_dest) as location"),
                'trip.status',
                'trip.created_at',
                DB::raw("'acting' as job_category")
            );


        /* ======================
         * Merge both
        /compiler
         * ====================== */
        $vacancies = $permanentJobs
            ->unionAll($actingJobs)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin.vacancy.vacancy_approvel', compact('vacancies'));
    }

    // public function handleApproval(Request $request)
    // {
    //     $request->validate([

    //         'action' => 'required|in:approve,reject',
    //     ]);

    //     $status = $request->action === 'approve' ? 'approve' : 'rejected';

    //     // dd($request->all());

    //     try {
    //         DB::table('permanent_jobs')
    //             ->where('id', $request->id)
    //             ->update([
    //                 'status' => $status,
    //                 'updated_at' => now()
    //             ]);

    //         // $per_job =  DB::table('permanent_jobs')->where('id', $request->id)->first();

    //         // $location = DB::table('location_active')->where('id', $per_job->corporate->location)->first();

    //         $per_job = PermanentJobs::with('corporate')->find($request->id);

    //         // if (!$per_job || !$per_job->corporate || !$per_job->corporate->location) {
    //         //     return response()->json(['status' => false, 'message' => 'Corporate or location not found'], 404);
    //         // }

    //         // $location = DB::table('location_active')->where('id', $per_job->corporate->location)->first();

    //         // if (!$location || !$location->cord) {
    //         //     return response()->json(['status' => false, 'message' => 'Base location not found'], 404);
    //         // }

    //         // if (!$location || !$location->cord) {
    //         //     return response()->json(['status' => false, 'message' => 'Base location not found'], 404);
    //         // }

    //         // $locationParts = explode(',', $location->cord);
    //         // $lat1 = trim($locationParts[0]);
    //         // $lon1 = trim($locationParts[1]);
    //         // $radius = 50;

    //         // $all_loc = DB::table('location_active')
    //         //     ->where('status', 'active')
    //         //     ->select('id', 'location', 'cord', 'status')
    //         //     ->get();

    //         // $nearbyLocations = [];

    //         // foreach ($all_loc as $loc) {
    //         //     // if (!$loc->cord) {
    //         //     //     continue;
    //         //     // }

    //         //     $cordParts = explode(',', $loc->cord);
    //         //     if (count($cordParts) !== 2) {
    //         //         continue;
    //         //     }

    //         //     $lat2 = trim($cordParts[0]);
    //         //     $lon2 = trim($cordParts[1]);

    //         //     $distance = $this->calculateDistance($lat1, $lon1, $lat2, $lon2);

    //         //     if ($distance <= $radius) {
    //         //         $loc->distance = round($distance, 2); // optional: show how far it is
    //         //         $nearbyLocations[] = $loc;
    //         //     }
    //         // }

    //         // $search_loc =  collect($nearbyLocations)->pluck('id')->toArray();

    //         // log::info('Location : -', $search_loc);


    //         $per_driver = Driver::where('type', 'permanent')->where('status', 'approved')->pluck('id')->toArray();

    //         // log::info('Driver : -', $per_driver);

    //         if (count($per_driver) > 0) {
    //             Trip_notify::dispatch($per_driver, $request->id, 'job_posted', $per_job->c_by);
    //         }


    //         return redirect()->back()->with('success', 'Permanent job ' . $status . ' successfully!');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Failed to update status: ' . $e->getMessage());
    //     }

    //     // dd($request->all());

    // }

    // public function handleApproval(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required',
    //         'type' => 'required|in:permanent,acting',
    //         'action' => 'required|in:approve,reject',
    //     ]);

    //     $status = $request->action === 'approve' ? 'approved' : 'rejected';

    //     try {

    //         if ($request->type === 'permanent') {

    //             PermanentJobs::where('id', $request->id)->update([
    //                 'status' => $status,
    //                 'updated_at' => now()
    //             ]);

    //             $job = PermanentJobs::with('corporate')->findOrFail($request->id);

    //             $drivers = Driver::where('type', 'permanent')
    //                 ->where('status', 'approved')
    //                 ->pluck('id')
    //                 ->toArray();

    //         } else { // acting job

    //             Trip::where('id', $request->id)->update([
    //                 'status' => $status,
    //                 'updated_at' => now()
    //             ]);

    //             $job = Trip::with('corporate')->findOrFail($request->id);

    //             $drivers = Driver::where('type', 'acting')
    //                 ->where('status', 'approved')
    //                 ->pluck('id')
    //                 ->toArray();
    //         }

    //         if (!empty($drivers)) {
    //             Trip_notify::dispatch($drivers, $job->id, 'job_posted', $job->c_by);
    //         }

    //         return redirect()
    //             ->route('admin.vacancy')
    //             ->with('success', ucfirst($request->type) . ' job ' . $status . ' successfully!');

    //     } catch (\Exception $e) {
    //         return back()->with('error', $e->getMessage());
    //     }
    // }


    public function handleApproval(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');
        $action = $request->input('action');

        if (($type === 'acting') || ($type === 'permanent')) {
            $model = \App\Models\Driver::class;
        } else {
            $model = \App\Models\Corporate::class;
        }

        $record = $model::findOrFail($id);

        if ($action === 'approve') {
            $record->status = 'approved';
        } elseif ($action === 'reject') {
            $record->status = 'rejected';
        }

        $record->save();

        return redirect()->back()->with('success', 'Action performed successfully.');
    }





    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }


    // Updated Controller Methods
// public function create()
// {
//     // Get all vacancies with their applied users count
//     $vacancies = Vacancy::withCount('vacancyApplied')->get();

    //     return view('admin.vacancy.create', compact('vacancies'));
// }

    // public function store(Request $request)
// {
//     $request->validate([
//         'location' => 'required|string|max:255',
//     ]);

    //     Vacancy::create([
//         'location' => $request->location,
//         'contact_number' => '9876543210',
//     ]);

    //     return redirect()->back()->with('success', 'Vacancy created successfully!');
// }
    public function create()
    {
        // Get all vacancies with their applied users count     
        $vacancies = Vacancy::withCount('vacancyApplied')->get();

        return view('admin.vacancy.create', compact('vacancies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        Vacancy::create([
            'location' => $request->location,
            'description' => $request->description,
            'contact_number' => '+91 9600166427',
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Vacancy created successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $vacancy = Vacancy::findOrFail($id);
        $vacancy->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Vacancy status updated successfully!');
    }

    // New method to show applied details in table format
    public function appliedDetails($vacancyId)
    {
        $vacancy = Vacancy::with(['vacancyApplied.driver'])->findOrFail($vacancyId);

        $appliedUsers = $vacancy->vacancyApplied()->with('driver')->paginate(10);

        return view('admin.vacancy.applied-users', compact('vacancy', 'appliedUsers'));
    }

    // Keep this method for AJAX calls if still needed
    public function getAppliedUsers($vacancyId)
    {
        $vacancy = Vacancy::with(['vacancyApplied.driver'])->findOrFail($vacancyId);

        $appliedUsers = $vacancy->vacancyApplied()->with('driver')->get();

        return response()->json([
            'vacancy' => $vacancy,
            'applied_users' => $appliedUsers
        ]);
    }
    public function update_Status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|nullable|string|max:500'
        ]);

        $application = VacancyApplied::findOrFail($id);
        $application->status = $request->status;

        if ($request->status === 'rejected') {
            $application->rejection_reason = $request->rejection_reason;
        }

        $application->save();

        return response()->json([
            'success' => true,
            'message' => 'Application status updated successfully.',
            'status' => $application->status
        ]);
    }


}
