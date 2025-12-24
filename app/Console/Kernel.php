<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\Trip_notify;
use App\Models\Trip;
use App\Models\Driver;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        /**
         * Example: check for new trips every 5 minutes
         * and notify nearby drivers dynamically.
         */
        $schedule->call(function () {
            // ✅ Fetch trips created in the last 5 minutes (you can adjust logic)
            $trips = Trip::where('created_at', '>=', now()->subMinutes(5))->get();

            foreach ($trips as $trip) {
                // ✅ Get all active drivers for this trip
                // (You can filter by location, availability, etc.)
                $drivers = Driver::where('status', 'active')->pluck('id')->toArray();

                if (!empty($drivers)) {
                    // Dispatch job dynamically
                    Trip_notify::dispatch($drivers, $trip->id, 'trip_posted', $trip->created_by);
                }
            }
        })->everyFiveMinutes();

        /**
         * Example: Send job posting notifications hourly
         */
        $schedule->call(function () {
            // fetch job-posted trips (or corporate jobs)
            $jobs = Trip::where('type', 'job')
                        ->where('created_at', '>=', now()->subHour())
                        ->get();

            foreach ($jobs as $job) {
                $drivers = Driver::where('status', 'active')->pluck('id')->toArray();

                if (!empty($drivers)) {
                    Trip_notify::dispatch($drivers, $job->id, 'job_posted', $job->created_by);
                }
            }
        })->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
