<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Driver;
use App\Models\Corporate;
use App\Jobs\Trip_notify;
use App\Models\Trip;
use App\Models\PermanentJobs;
use App\Models\Vacancy;
use App\Models\VacancyApplied;


class VacancyController extends Controller
{
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

        // Merge with unionAll
        $vacancies = DB::query()
            ->fromSub(function ($query) use ($permanentJobs, $actingJobs) {
                $query->fromSub($permanentJobs, 'p')
                    ->unionAll($actingJobs);
            }, 'vacancies')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.vacancy.vacancy_approvel', compact('vacancies'));
    }
    //     public function handleApproval(Request $request)
// {

    //         $type = $request->type;
//     $id = $request->id;
//     $action = $request->action;

    //         $model = in_array($type, ['acting', 'permanent'])
//         ? Driver::class
//         : Corporate::class;

    //         dd("hello");


    //         $record = $model::findOrFail($id);
//     $record->status = $action === 'approve' ? 'approved' : 'rejected';
//     $record->save();



    //         return back()->with('success', 'Vacancy updated successfully');
// }

    public function handleApproval(Request $request)
    {
        $type = $request->type;     // 'acting' or 'permanent'
        $id = $request->id;
        $action = $request->action; // 'approve' or 'reject'

        Log::info('Vacancy approval request', $request->all());

        try {
            if ($type === 'acting') {
                // Acting jobs stored in Trip table
                $trip = Trip::findOrFail($id);

                $trip->status = ($action === 'approve') ? 'approved' : 'rejected';
                $trip->updated_at = now();
                $trip->save();

                Log::info('Acting job rows affected', ['count' => 1]);

                if ($action === 'approve') {
                    // Get trip coordinates to find nearby drivers
                    $startCoords = [$trip->st_cord, $trip->end_cord]; // lat, lng
                    $lat1 = $startCoords[0];
                    $lon1 = $startCoords[1];
                    $radius = 50;

                    // Find nearby locations
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
                            $nearbyLocations[] = $loc->id;
                        }
                    }

                    Log::info('Nearby locations found', ['count' => count($nearbyLocations)]);

                    // Get acting drivers in nearby locations
                    $d_type = $trip->d_type; // 'male', 'female', or 'both'

                    $driverQuery = Driver::where('type', 'acting')
                        ->where('status', '!=', 'pending') // approved or active drivers
                        ->whereNotNull('token')
                        ->where('token', '!=', '')
                        ->whereIn('location', $nearbyLocations);

                    // Filter by gender if not 'both'
                    if ($d_type !== 'both') {
                        $driverQuery->where('gender', $d_type);
                    }

                    $drivers = $driverQuery->pluck('id')->toArray();

                    Log::info('Acting drivers found for notification', [
                        'count' => count($drivers),
                        'driver_type' => $d_type,
                        'nearby_locations' => count($nearbyLocations)
                    ]);

                    if (!empty($drivers)) {
                        Trip_notify::dispatch($drivers, $trip->id, 'trip_posted', $trip->c_by);
                        Log::info('Trip_notify job dispatched', ['drivers_count' => count($drivers)]);
                    } else {
                        Log::warning('No drivers found to notify for trip', ['trip_id' => $trip->id]);
                    }
                }

            } elseif ($type === 'permanent') {
                // Permanent jobs stored in permanent_jobs table
                $affected = DB::table('permanent_jobs')
                    ->where('id', $id)
                    ->update([
                        'status' => ($action === 'approve') ? 'approved' : 'rejected',
                        'updated_at' => now()
                    ]);

                Log::info('Permanent job rows affected', ['count' => $affected]);

                if ($action === 'approve') {
                    // Fetch job details
                    $record = DB::table('permanent_jobs')
                        ->select('id', 'c_by', 'job_location')
                        ->where('id', $id)
                        ->first();

                    if ($record) {
                        $drivers = Driver::where('type', 'permanent')
                            ->where('status', '!=', 'pending') // approved drivers
                            ->whereNotNull('token')
                            ->where('token', '!=', '')
                            ->pluck('id')
                            ->toArray();

                        Log::info('Permanent drivers found for notification', ['count' => count($drivers)]);

                        if (!empty($drivers)) {
                            Trip_notify::dispatch($drivers, $record->id, 'job_posted', $record->c_by ?? null);
                            Log::info('Trip_notify job dispatched', ['drivers_count' => count($drivers)]);
                        } else {
                            Log::warning('No drivers found to notify for permanent job', ['job_id' => $record->id]);
                        }
                    }
                }

            } else {
                Log::warning('Unknown vacancy type', ['type' => $type]);
                return redirect()->back()->with('error', 'Unknown vacancy type');
            }

            // Instant response to user
            return redirect()->back()->with('success', 'Vacancy ' . $action . 'd successfully!');

        } catch (\Throwable $e) {
            Log::error('Approval failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
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
