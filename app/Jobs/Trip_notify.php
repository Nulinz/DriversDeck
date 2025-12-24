<?php

namespace App\Jobs;

use App\Models\Corporate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Fcm;
use App\Models\Notify;
use App\Models\Trip;
use Illuminate\Support\Facades\Log;


use App\Models\Driver;

class Trip_notify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public  $drivers;
    public  $type; // 'posted', 'applied', etc.
    public  $trip; // 'posted', 'applied', etc.
    public  $fcm;
    public  $c_by;

    public function __construct($drivers, $trip, $type, $c_by = null)
    {
        $this->drivers = $drivers;
        $this->type = $type;
        $this->trip = $trip;
        $this->c_by = $c_by;
        // $this->fcm = new Fcm_obj();

        // dd($this->drivers, $type);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {

        // $fcm = new Fcm_obj(); // ✅ Safe to do here
        $drivers_loop = Driver::whereIn('id', $this->drivers)->get(); // ✅ only if $this->drivers is a flat array
        Log::info('Driver IDs in job:', $this->drivers);

        foreach ($drivers_loop as $driver) {
            switch ($this->type) {
                case 'trip_posted':
                    // Log::info("message: Trip posted notification for driver ID: " . $driver->id . "Trip-ID: " . $this->trip);
                    $this->sendTripPosted($driver);
                    break;

                case 'job_posted':
                    // Log::info("message: Trip applied notification for driver ID: " . $driver->id);
                    $this->sendjobPosted($driver);
                    break;
            }
        }
    }

    protected function sendTripPosted($driver)
    {
        $fcm = new Fcm(); // ✅ Safe to do here

        $trip = Trip::find($this->trip);

        if (!$trip) {
            Log::warning("Trip not found for ID {$this->trip}");
            return;
        }

        $title = "New Trip Posted ";
        $body = "A new trip has been posted near you. Trip ID: {$trip->id}";
        Notify::create([
            'type' => $driver->type,
            'f_id' => $driver->id,
            'prime_table' => $trip->id,
            'cat' => 'trip_posted',
            'title' => $title,
            'body' => $body,
            'status' => 'active',
            'c_by' =>  $this->c_by, // Assuming you want to log who created this notification
        ]);

        $fcm->send_notify($driver->token, $title, $body);
    }

    protected function sendjobPosted($driver)
    {
        $fcm = new Fcm(); // ✅ Safe to do here

        $per_drivers = Corporate::find($this->c_by);

        Log::info("trip new ID --" . $this->trip);

        $title = "New Job Posted ";
        $body = "A Job has been posted near you. By: " . $per_drivers->name;

        // Log::info("Sending notification to Job ID: " . $per_drivers->name);

        Notify::create([
            'type' => $driver->type,
            'f_id' => $driver->id,
            'prime_table' => $this->trip,
            'cat' => 'job_posted',
            'title' => $title,
            'body' => $body,
            'status' => 'active',
            'c_by' =>  $this->c_by, // Assuming you want to log who created this notification
        ]);

        $fcm->send_notify($driver->token, $title, $body);
    }
}
