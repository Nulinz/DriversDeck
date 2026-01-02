<?php

namespace App\Jobs;

use App\Models\Corporate;
use App\Models\Notify;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\PermanentJobs;
use App\Services\FcmService;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Trip_notify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $drivers; 
    public $type;    
    public $trip;    
    public $c_by;    

    public function __construct(array $drivers, int $trip, string $type, ?int $c_by = null)
    {
        $this->drivers = $drivers;
        $this->trip = $trip;
        $this->type = $type;
        $this->c_by = $c_by;
    }

    public function handle(FcmService $fcmService)
    {
        // Fetch drivers with valid tokens in one query
        $drivers = Driver::whereIn('id', $this->drivers)
            ->whereNotNull('token')
            ->where('token', '!=', '')
            ->get(['id', 'type', 'token']); // Only fetch needed columns

        if ($drivers->isEmpty()) {
            Log::info('No drivers with valid tokens found for notification', [
                'type' => $this->type,
                'trip_id' => $this->trip
            ]);
            return;
        }

        // Batch prepare notification data to reduce queries
        $notificationsData = [];
        $fcmPayloads = [];

        foreach ($drivers as $driver) {
            $result = match ($this->type) {
                'trip_posted' => $this->sendTripPosted($driver),
                'job_posted'  => $this->sendJobPosted($driver),
                default => null,
            };

            if ($result) {
                $notificationsData[] = $result['notification'];
                $fcmPayloads[] = [
                    'token' => $driver->token,
                    'title' => $result['title'],
                    'body' => $result['body'],
                    'type' => $this->type,
                    'driver_id' => $driver->id,
                ];
            }
        }

        // Batch insert notifications
        if (!empty($notificationsData)) {
            Notify::insert($notificationsData);
        }

        // Send FCM notifications
        foreach ($fcmPayloads as $payload) {
            $fcmService->send(
                $payload['token'],
                $payload['title'],
                $payload['body'],
                $payload['type'],
                $payload['driver_id']
            );
        }

        Log::info('Notifications processed', [
            'type' => $this->type,
            'trip_id' => $this->trip,
            'drivers_notified' => count($fcmPayloads)
        ]);
    }

    protected function sendTripPosted(Driver $driver): ?array
    {
        static $trip = null;

        // Cache trip object to avoid multiple DB queries
        if ($trip === null) {
            $trip = Trip::find($this->trip);
        }

        if (!$trip) {
            Log::warning('Trip not found for notification', ['trip_id' => $this->trip]);
            return null;
        }

        $title = "New Trip Posted";
        $body  = "A new trip has been posted near you. Trip ID: {$trip->id}";

        return [
            'title' => $title,
            'body' => $body,
            'notification' => [
                'type' => $driver->type,
                'f_id' => $driver->id,
                'prime_table' => $trip->id,
                'cat' => 'trip_posted',
                'title' => $title,
                'body' => $body,
                'status' => 'active',
                'c_by' => $this->c_by,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
    }

    protected function sendJobPosted(Driver $driver): ?array
    {
        static $corporate = null;

        // Cache corporate object to avoid multiple DB queries
        if ($corporate === null) {
            $corporate = Corporate::find($this->c_by);
        }

        if (!$corporate) {
            Log::warning('Corporate not found for notification', ['corporate_id' => $this->c_by]);
            return null;
        }

        $title = "New Job Posted";
        // $body  = "A job has been posted near you. By: {$corporate->name}";
        $body  = "A job has been posted near you.";

        return [
            'title' => $title,
            'body' => $body,
            'notification' => [
                'type' => $driver->type,
                'f_id' => $driver->id,
                'prime_table' => $this->trip,
                'cat' => 'job_posted',
                'title' => $title,
                'body' => $body,
                'status' => 'active',
                'c_by' => $this->c_by,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
    }
}