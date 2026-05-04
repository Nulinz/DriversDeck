<?php

namespace App\Http\Controllers\organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PermanentJobs;
use App\Models\Trip;
use App\Models\TripApplied;
use App\Models\SubApplied;
use App\Models\Driver;
use App\Http\Controllers\Api_owner;
use App\Jobs\Trip_notify;
use Illuminate\Support\Facades\DB;
use App\Models\Corporate;
use App\Models\Notify;
use App\Services\Fcm;
use Illuminate\Support\Facades\Log;
use Mockery\Matcher\Not;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;

class VacancyController_org extends Controller
{
    public function vacancy()
    {
        // Get the current corporate user ID
        $corporateId = auth('corporate')->user()->id;

        // Get Permanent Jobs created by the logged-in corporate user
        $permanentJobs = PermanentJobs::where('c_by', $corporateId)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(function ($job) {
                $job->type = 'Full Time';
                $job->experience = $job->min_exp . ' - ' . $job->max_exp . ' Years';
                $job->salary = $job->min_salary . ' - ' . $job->max_salary;
                $job->location = $job->job_location;
                return $job;
            });


        // Get Acting Jobs created by the same corporate user
        $actingJobs = Trip::where('c_by', $corporateId)
            // ->whereHas('corporate', function ($query) {
            //     $query->where('type', 'corporate');
            // })
            ->with('corporate')
            ->get()
            ->map(function ($trip) {
                $trip->type = 'Acting';
                $trip->job_type = "Acting";
                $trip->experience = 'N/A';
                $trip->salary = 'N/A';
                $trip->location = ' { From } -' . $trip->st_loc . ' { To } -' . $trip->st_dest;
                $trip->job_location = $trip->location;
                $trip->name = $trip->corporate->name ?? '';
                return $trip;
            });

        // Combine both job types
        // $jobs = $permanentJobs->merge($actingJobs);

        //  $jobs = $permanentJobs;

        $jobs = $permanentJobs->concat($actingJobs)->sortByDesc('created_at')->values();

        // dd($jobs->toArray());


        // Return to the view
        return view('organization.vacancy.vacancy_list', compact('jobs'));
    }



    public function add_vacancy()
    {
        return view('organization.vacancy.add_vacancy');
    }


    public function fulltime_list($id)
    {
        // Get full-time job info
        $selectedJob = PermanentJobs::with('corporate')->where('job_type', 'Full Time')->findOrFail($id);

        // Format experience and salary
        $selectedJob->experience = $selectedJob->min_exp . ' - ' . $selectedJob->max_exp . ' Years';
        $selectedJob->salary = $selectedJob->min_salary . ' - ' . $selectedJob->max_salary;

        $selectedJob->job_id = $id;

        // Check corporate subscription and plan
        $corporate = $selectedJob->corporate;
        $subscriptionLimit = false;
        $currentHiredCount = 0;

        if ($corporate && $corporate->subscription == 'yes') {
            // Get subscription details
            $subscription = DB::table('subscription')
                ->where('f_id', $corporate->id)
                ->where('plan', 6)
                ->first();

            if ($subscription) {
                // Count current hired permanent drivers for this corporate
                $currentHiredCount = DB::table('sub_applied')
                    ->join('driver', 'sub_applied.d_id', '=', 'driver.id')
                    ->where('driver.type', 'permanent')
                    ->where('sub_applied.status', 'Hired')
                    ->count();

                // Check if limit is reached (5 for plan 6)
                $subscriptionLimit = $currentHiredCount >= 5;
            }
        }

        // Get applied permanent drivers
        $appliedListFullTime = SubApplied::where('p_id', $id)
            ->whereHas('driver', function ($query) {
                $query->where('type', 'permanent');
            })
            ->with('driver.license')
            ->get()
            ->map(function ($apply) use ($subscriptionLimit) {

                $loc_name = DB::table('location_active')
                    ->where('id', $apply->driver->location)
                    ->value('location');

                $hasConflict = DB::table('sub_applied')->where('d_id', $apply->d_id)->where('status', 'Hired')->exists();

                return [
                    'id' => $apply->id,
                    'created_at' => $apply->created_at->format('d-m-Y'),
                    'driver_name' => $apply->driver->name ?? '-',
                    'image' => $apply->driver->img ?? null,
                    'experience' => $apply->driver->experience ?? '-',
                    'driver_phone' => $apply->driver->phone ?? '-',
                    'location' => $loc_name ?? '-',
                    'ap_status' => $apply->status ?? '-',
                    'license_type' => $apply->driver->license->cov ?? '-', // <-- new column
                    'conflict' => $hasConflict,
                    'subscription_limit_reached' => $subscriptionLimit && $apply->status != 'Hired',
                    'driver' => [
                        'id' => $apply->driver->id ?? null,
                    ],
                ];
            });

        return view('organization.vacancy.fulltime_list', compact(
            'selectedJob',
            'appliedListFullTime',
            'currentHiredCount',
            'subscriptionLimit'
        ));
    }


    public function job_cancel($id)
    {
        // Step 1: Get the job to be cancelled
        $job = PermanentJobs::find($id);

        if (!$job) {
            return back()->with('error', 'Job not found');
        }

        // Step 2: Update the job status to 'cancelled'
        $job->status = 'cancelled';
        $job->save();

        // Step 3: Update all applications for this job to 'cancelled'
        SubApplied::where('p_id', $id)->update(['status' => 'cancelled']);

        return back()->with('success', 'Job cancelled successfully');
    }



    public function updateFullTimeStatus($id, Notify $notify)
    {
        $validated = request()->validate([
            'action' => 'required|in:Hired,Reject'
        ]);

        // Step 1: Update selected applicant
        $updated = SubApplied::where('id', $id)->update([
            'status' => $validated['action']
        ]);

        // Step 2: Get selected applicant
        $sub = SubApplied::find($id);
        $per_sub = PermanentJobs::find($sub->p_id);


        if (!$sub) {
            return back()->with('error', 'Applicant not found');
        }

        // Step 3: Reject all other applicants for the same job
        $up_sub = SubApplied::where('p_id', $sub->p_id)->where('id', '!=', $id) // ✅ Exclude current record
            ->update([
                'status' => 'Reject'
            ]);

        // Step 4: Update parent job's status
        $per_job = PermanentJobs::where('id', $sub->p_id)->update([
            'status' => $validated['action']
        ]);

        // Step 5: Update Drive status
        if ($validated['action'] == 'Hired') {

            // // Generate 4-digit random trip_code only if status is 'Hired'
            // if ($request->status === 'Hired') {
            //     $appliedData['trip_code'] = mt_rand(1000, 9999);
            // }


            $driver = Driver::find($sub->d_id);
            $driver->status = 'Hired';
            $driver->save();

            $name = $per_sub->corporate->name ?? 'Unknown';

            Notify::create([
                'type' => $driver->type,
                'f_id' => $driver->id,
                'prime_table' => $id,
                'cat' => 'job_hired',
                'title' => 'You have been hired for the job - company: ' . $name,
                'body' => 'You have been hired for the job - company: ' . $name,
                'status' => 'active',
                'c_by' => $sub->c_by, // Assuming you want to log who created this notification
            ])->save();


            if ($driver->token) {
                $fcm = new Fcm(); // ✅ Or use app(Fcm::class)
                $fcm->send_notify(
                    $driver->token,
                    'job_hired',
                    'You have been hired for the job - company: ' . $name,
                    'job_hired'
                );
            } else {
                Log::warning("Driver token missing for driver ID: {$sub->d_id}");
            }
        }


        if ($updated) {
            return back()->with('success', 'Status updated successfully');
        } else {
            return back()->with('error', 'Failed to update status');
        }
    }



    public function acting_list($id)
    {
        dd('Method called!', 'ID: ' . $id); // ← Add this as THE FIRST LINE

        $selectedJob = Trip::with('corporate')->findOrFail($id);

        $selectedJob->t_code = TripApplied::where('trip_id', $selectedJob->id)
            ->whereNotNull('trip_code')
            ->value('trip_code');

        $appliedListActing = TripApplied::where('trip_id', $id)
            ->whereHas('driver', function ($query) {
                $query->where('type', 'acting');
            })
            ->with(['trip', 'driver.license'])
            ->get()
            ->map(function ($apply) use ($selectedJob) {

                try {
                    $loc = DB::table('location_active')->where('id', $apply->driver->location)->value('location');

                    $existingTrips = TripApplied::with(['trip'])->where('d_id', $apply->d_id)
                        ->whereIn('status', ['Hired', 'Start'])
                        ->get();

                    $hasConflict = $existingTrips->contains(function ($extrips) use ($selectedJob) {

                        $existingStart = Carbon::parse($extrips->trip->st_date)->toDateString();
                        $existingEnd = Carbon::parse($extrips->trip->end_date)->toDateString();

                        $newStart = Carbon::parse($selectedJob->st_date)->toDateString();
                        $newEnd = Carbon::parse($selectedJob->end_date)->toDateString();

                        return $existingStart <= $newEnd && $existingEnd >= $newStart;
                    });

                    return [
                        'id' => $apply->id,
                        'created_at' => $apply->created_at->format('d-m-Y'),
                        'driver_name' => $apply->driver->name ?? '-',
                        'driver_phone' => $apply->driver->phone ?? '-',
                        'license_type' => $apply->driver->license->cov ?? '-',
                        'location' => $loc ?? '-',
                        'salary_per_day' => $apply->salary_perday ?? '-',
                        'wait_charge' => $apply->wait_charge ?? '-',
                        'act_status' => $apply->status ?? '-',
                        'food' => $apply->food ?? '-',
                        'driver_conflict' => $hasConflict,
                        'driver' => [
                            'id' => $apply->driver->id ?? null,
                        ],
                    ];
                } catch (\Exception $e) {
                    \Log::error('Error in acting_list map: ' . $e->getMessage());
                    \Log::error('Apply ID: ' . $apply->id);
                    dd('Error in map function', $e->getMessage(), $apply);
                }
            });

        dd('After map', $appliedListActing->toArray()); // Check data after mapping

        return view('organization.vacancy.acting_list', compact('selectedJob', 'appliedListActing'));
    }





    // public function acting_list($id)
    // {
    //     dd('Method called!', 'ID: ' . $id); // ← Add this as THE FIRST LINE
    //     $selectedJob = Trip::with('corporate')->findOrFail($id);
    //     dd('Step 1: selectedJob loaded', $selectedJob->toArray());

    //     $selectedJob->t_code = TripApplied::where('trip_id', $selectedJob->id)
    //         ->whereNotNull('trip_code')
    //         ->value('trip_code');

    //     $rawData = TripApplied::where('trip_id', $id)
    //         ->whereHas('driver', function ($query) {
    //             $query->where('type', 'acting');
    //         })
    //         ->with(['trip', 'driver.license'])
    //         ->get();

    //     dd('Step 2: Raw data retrieved', $rawData->toArray());

    //     // The rest will execute after you comment out the dd above
    // }
    public function updateActingStatus($id)
    {
        // Log::info('Updating status for ID: ' . $id, request()->all());

        $validated = request()->validate([
            'action' => 'required|in:Hired,Reject'
        ]);

        $trip_apply = TripApplied::find($id);

        if ($validated['action'] == 'Hired') {
            $trip_apply->trip_code = mt_rand(1000, 9999);
        }

        $trip_apply->status = $validated['action'];
        $trip_apply->save();

        // Step 3: Reject all other applicants for the same job
        $up_sub = TripApplied::where('trip_id', $trip_apply->trip_id)->where('id', '!=', $id) // ✅ Exclude current record
            ->update([
                'status' => 'Reject'
            ]);

        Trip::where('id', $trip_apply->trip_id)->update([
            'status' => $validated['action']
        ]);

        // Step 5: Update Drive status
        if ($validated['action'] == 'Hired') {

            $driver = Driver::find($trip_apply->d_id);
            $driver->status = 'Hired';
            $driver->save();

            $trip_det = Trip::find($trip_apply->trip_id);

            $cor = Corporate::find($trip_det->c_by);

            $name = $cor->name ?? 'Unknown';

            Notify::create([
                'type' => $driver->type,
                'f_id' => $driver->id,
                'prime_table' => $trip_det->id,
                'cat' => 'trip_Hired',
                'title' => 'You have been hired for the Trip - company: ' . $name,
                'body' => 'You have been hired for the Trip - company: ' . $name,
                'status' => 'active',
                'c_by' => $trip_det->c_by, // Assuming you want to log who created this notification
            ])->save();


            if ($driver->token) {
                $fcm = new Fcm(); // ✅ Or use app(Fcm::class)
                $fcm->send_notify(
                    $driver->token,
                    'trip_Hired',
                    'You have been hired for the Trip - company: ' . $name,
                    'trip_Hired'
                );
            } else {
                Log::warning("Driver token missing for driver ID: {$trip_apply->d_id}");
            }
        }




        if ($trip_apply) {
            return back()->with('success', 'Status updated successfully');
        } else {
            return back()->with('error', 'Failed to update status');
        }
    }

    public function add_vacancy_store(Request $request)
    {
        if ($request->job_type === 'Full Time') {
            // Validation for Full Time jobs
            $request->validate([
                'job_type' => 'required',
                'veh_type' => 'required',
                'veh_name' => 'nullable',
                'min_exp' => 'required|numeric',
                'max_exp' => 'required|numeric',
                'job_location' => 'required',
                'join_date' => 'required|date',
                'min_salary' => 'required|numeric',
                'max_salary' => 'required|numeric',
                'accommodation' => 'required',
                'aggrement' => 'required',
                'food' => 'required',
                'description' => 'required'
            ]);

            // Store in PermanentJobs table
            $per_job = PermanentJobs::create([
                'job_type' => $request->job_type,
                'veh_type' => $request->veh_type,
                'veh_name' => $request->veh_name,
                'min_exp' => $request->min_exp,
                'max_exp' => $request->max_exp,
                'job_location' => $request->job_location,
                'join_date' => $request->join_date,
                'min_salary' => $request->min_salary,
                'max_salary' => $request->max_salary,
                'accommodation' => $request->accommodation,
                'food' => $request->food,
                'aggrement' => $request->aggrement,
                'a_years' => $request->a_years ?? 0,
                'status' => 'pending',
                'description' => $request->description,
                'c_by' => auth('corporate')->user()->id
            ]);

            // Notification will be sent after admin approval in handleApproval() method

            return redirect()->route('organization.vacancy.vacancy_list')
                ->with('success', 'Full-time job created successfully and sent for admin approval');

        } elseif ($request->job_type === 'Acting') {

            try {
                $validated = $request->validate([
                    'start_coordinates' => 'required|string',
                    'end_coordinates' => 'required|string',
                    'from_address' => 'required',
                    'to_address' => 'required',
                    'alternate_number' => 'required',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'start_time' => 'required',
                    'contact_number' => 'required',
                    'no_of_days' => 'required|numeric|min:1',
                    'veh_type' => 'required',
                    'veh_name' => 'nullable',
                    'veh_number' => 'required|string',
                    'd_type' => 'required',
                    'st_dist' => 'required',
                    'end_dist' => 'required',
                ]);
            } catch (ValidationException $e) {
                Log::error('Validation failed', [
                    'errors' => $e->errors(),
                    'input' => $request->all(),
                ]);
                throw $e;
            }

            try {
                $startCoords = explode(',', $validated['start_coordinates']);
                $endCoords = explode(',', $validated['end_coordinates']);

                // Validate coordinate arrays have proper elements
                if (count($startCoords) < 2 || count($endCoords) < 2) {
                    throw new \Exception('Invalid coordinates format');
                }

                Log::info('Creating trip with data:', [
                    'coordinates' => [
                        'start' => $startCoords,
                        'end' => $endCoords
                    ],
                    'd_type' => $validated['d_type'],
                    'other_data' => $validated
                ]);

                // Create trip with proper coordinate separation for start location only
                $trip = Trip::create([
                    'st_loc' => $validated['from_address'],
                    'st_dest' => $validated['to_address'],
                    'st_city' => '#' . $validated['st_dist'],
                    'end_city' => '#' . $validated['end_dist'],
                    'st_cord' => $startCoords[0], // Start latitude only (separate)
                    'end_cord' => $startCoords[1], // Start longitude only (separate)  
                    'dest_cord' => $validated['end_coordinates'], // End coordinates combined (lat,lng)
                    'title' => 'Acting Driver Job',
                    'con_number' => $validated['contact_number'],
                    'alter_number' => $validated['alternate_number'],
                    'st_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'st_time' => $validated['start_time'],
                    'no_days' => $validated['no_of_days'],
                    'veh_type' => $validated['veh_type'],
                    'veh_name' => $validated['veh_name'],
                    'veh_number' => $validated['veh_number'],
                    'd_type' => $validated['d_type'],
                    'status' => 'pending',
                    'c_by' => auth('corporate')->user()->id ?? auth('sanctum')->user()->id
                ]);

                // Store nearby locations for later use when admin approves
                if ($trip) {
                    $lat1 = $startCoords[0];
                    $lon1 = $startCoords[1];
                    $radius = 50;

                    $all_loc = DB::table('location_active')
                        ->where('status', 'active')
                        ->select('id', 'location', 'cord', 'status')
                        ->get();

                    $nearbyLocations = [];

                    foreach ($all_loc as $loc) {
                        $cordParts = explode(',', $loc->cord);
                        if (count($cordParts) !== 2) {
                            continue;
                        }

                        $lat2 = trim($cordParts[0]);
                        $lon2 = trim($cordParts[1]);

                        $distance = (new Api_owner)->calculateDistance($lat1, $lon1, $lat2, $lon2);

                        if ($distance <= $radius) {
                            $loc->distance = round($distance, 2);
                            $nearbyLocations[] = $loc;
                        }
                    }

                    // Store nearby location IDs for notification dispatch after approval
                    $search_loc = collect($nearbyLocations)->pluck('id')->toArray();

                    // Optional: Store this in a separate table or in trip metadata
                    // so handleApproval() can use it later
                    // For now, we'll recalculate it in handleApproval()

                    Log::info('Trip created successfully with ID: ' . $trip->id . ' and d_type: ' . $trip->d_type);
                    Log::info('Nearby locations found: ' . count($search_loc) . ' locations within ' . $radius . 'km radius');

                    // ✅ REMOVED: Notification dispatch - will happen after admin approval
                }

                return redirect()->route('organization.vacancy.vacancy_list')
                    ->with('success', 'Acting job created successfully and sent for admin approval');

            } catch (\Exception $e) {
                Log::error('Trip creation failed: ' . $e->getMessage());
                Log::error('Stack trace: ' . $e->getTraceAsString());
                return back()->with('error', 'Error creating trip: ' . $e->getMessage())
                    ->withInput();
            }
        }

        return back()->with('error', 'Invalid job type selected')->withInput();
    }
}
