<?php

namespace App\Services;

use Google\Client;
use Google\Service\FirebaseCloudMessaging;
use Google\Service\FirebaseCloudMessaging\Message;
use Google\Service\FirebaseCloudMessaging\AndroidConfig;
use Google\Service\FirebaseCloudMessaging\ApnsConfig;
use Google\Service\FirebaseCloudMessaging\SendMessageRequest;
use Illuminate\Support\Facades\Log;

class Fcm
{
    protected Client $client;
    protected FirebaseCloudMessaging $messaging;
    protected string $projectId;

    public function __construct()
    {
        $serviceAccountPath = storage_path('app/firebase.json');

        if (!file_exists($serviceAccountPath)) {
            throw new \Exception('firebase.json not found at: ' . $serviceAccountPath);
        }

        $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON in firebase.json: ' . json_last_error_msg());
        }

        if (empty($serviceAccount['project_id'])) {
            throw new \Exception('project_id missing in firebase.json');
        }

        $this->projectId = $serviceAccount['project_id'];

        $this->client = new Client();
        $this->client->setAuthConfig($serviceAccountPath);
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $this->messaging = new FirebaseCloudMessaging($this->client);

        Log::info('✅ FCM initialized for project: ' . $this->projectId);
    }

    public function send_notify(string $token, string $title, string $body, string $type = 'default'): array
    {
        if (empty($token)) {
            return ['status' => false, 'error' => 'No FCM token, notification skipped'];
        }

        try {
            $message = new Message([
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => [
                    'type' => $type,
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                ],
                'android' => new AndroidConfig([
                    'priority' => 'high',
                    'notification' => ['sound' => 'default'],
                ]),
                'apns' => new ApnsConfig([
                    'payload' => ['aps' => ['sound' => 'default']],
                ]),
            ]);

            $request = new SendMessageRequest(['message' => $message]);

            $this->messaging
                ->projects_messages
                ->send('projects/' . $this->projectId, $request);

            return ['status' => true, 'message' => 'Notification sent'];

        } catch (\Google\Service\Exception $e) {
            // Extract error code from the response body (it's in details array)
            $errorCode = $this->extractErrorCode($e->getMessage());

            // Don't log UNREGISTERED errors - they're handled silently
            if ($errorCode !== 'UNREGISTERED') {
                Log::error('🔥 FCM error', [
                    'title' => $title,
                    'body' => $body,
                    'token' => substr($token, 0, 20) . '...',
                    'error_code' => $errorCode,
                    'error' => $e->getMessage(),
                ]);
            }

            return ['status' => false, 'error' => $e->getMessage(), 'error_code' => $errorCode];

        } catch (\Throwable $e) {
            Log::critical('🔥 FCM unexpected error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Extract FCM error code from error message
     */
    protected function extractErrorCode(string $errorMessage): string
    {
        // Parse the JSON error message
        if (preg_match('/"errorCode":\s*"([^"]+)"/', $errorMessage, $matches)) {
            return $matches[1];
        }
        
        return 'UNKNOWN';
    }

    public function send_multicast(array $tokens, string $title, string $body, string $type = 'default')
    {
        $tokens = array_filter($tokens, fn($token) => !empty($token));

        if (empty($tokens)) {
            Log::info('No valid tokens for multicast notification');
            return;
        }

        $successCount = 0;
        $failureCount = 0;

        foreach (array_chunk($tokens, 500) as $chunk) {
            foreach ($chunk as $token) {
                $result = $this->send_notify($token, $title, $body, $type);
                
                if ($result['status']) {
                    $successCount++;
                } else {
                    $failureCount++;
                }
            }
        }

        Log::info('Multicast notification completed', [
            'total_tokens' => count($tokens),
            'success' => $successCount,
            'failed' => $failureCount
        ]);
    }
}