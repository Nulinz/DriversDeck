<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\Driver;

class FcmService
{
    protected Fcm $fcm;

    public function __construct(Fcm $fcm)
    {
        $this->fcm = $fcm;
    }

    public function send(string $token, string $title, string $body, string $type, ?int $driverId = null)
    {
        if (empty($token)) {
            return;
        }

        $result = $this->fcm->send_notify($token, $title, $body, $type);

        // Handle UNREGISTERED tokens silently
        if (!$result['status'] && isset($result['error_code']) && $result['error_code'] === 'UNREGISTERED') {
            if ($driverId) {
                $this->removeInvalidToken($driverId);
            }
            return; // Don't log anything for UNREGISTERED
        }

        // Log other failures (but not UNREGISTERED)
        if (!$result['status']) {
            Log::warning('FCM notification failed', [
                'driver_id' => $driverId,
                'type' => $type,
                'error_code' => $result['error_code'] ?? 'UNKNOWN',
            ]);
        }
    }

    /**
     * Remove invalid token from driver record
     */
    protected function removeInvalidToken(int $driverId): void
    {
        try {
            Driver::where('id', $driverId)->update(['token' => null]);
            
            // Optional: Only log in debug mode
            // Log::debug('Removed invalid FCM token', ['driver_id' => $driverId]);
        } catch (\Throwable $e) {
            Log::error('Failed to remove invalid token', [
                'driver_id' => $driverId,
                'error' => $e->getMessage()
            ]);
        }
    }
}