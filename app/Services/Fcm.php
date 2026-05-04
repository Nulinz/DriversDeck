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

        // Verify projects_messages property exists
        if (!isset($this->messaging->projects_messages)) {
            Log::error('projects_messages property is not set on FirebaseCloudMessaging client.');
            return ['error' => 'Firebase messaging client misconfigured'];
        }

        // $imageurl = 'https://driversdeck.in/assets/images/logo/Turuck_1.jpg'; 

        // Build notification payload
        $notification = new Notification([
            'title' => $title,
            'body' => $body,
            // 'image' => $imageurl,
        ]);

        // ✅ UPDATED ANDROID CONFIG
        $androidConfig = new AndroidConfig([
            'priority' => 'high',
            'notification' => [
                // 'image' => $imageurl,
                'sound' => 'sound.wav', // 🆕 added custom sound
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK', // 🆕 added click action for background notification
            ],
        ]);

        // ✅ UPDATED APNS CONFIG
        $apnsConfig = new ApnsConfig([
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $body,
                        // 'image' => $imageurl,
                    ],
                    'sound' => 'sound',
                    'content-available' => 1,
                ],
            ],
        ]);

        // ✅ ADDED DATA PAYLOAD (needed for tap action on background notifications)
        $dataPayload = [ // 🆕 added
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'screen' => 'home', // 🆕 example custom data (you can change or remove)
        ];

        $message = new Message([
            'token' => $token,
            'notification' => $notification,
            'data' => $dataPayload, // 🆕 added
            'android' => $androidConfig,
            'apns' => $apnsConfig,
        ]);

        $sendRequest = new SendMessageRequest([
            'message' => $message,
        ]);

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
}


//////////////////////////



// <?php

// namespace App\Services;

// use Google\Client;
// use Google\Service\FirebaseCloudMessaging;
// use Google\Service\FirebaseCloudMessaging\Message;
// use Google\Service\FirebaseCloudMessaging\Notification;
// use Google\Service\FirebaseCloudMessaging\AndroidConfig;
// use Google\Service\FirebaseCloudMessaging\ApnsConfig;
// use Google\Service\FirebaseCloudMessaging\SendMessageRequest;
// use Illuminate\Support\Facades\Log;

// class Fcm
// {
//     protected Client $client;
//     protected FirebaseCloudMessaging $messaging;
//     protected string $projectId;

//     public function __construct()
//     {
//         $serviceAccountPath = storage_path('app/firebase.json');

//         if (!file_exists($serviceAccountPath)) {
//             throw new \Exception('firebase.json not found');
//         }

//         $serviceAccount = json_decode(
//             file_get_contents($serviceAccountPath),
//             true
//         );

//         if (empty($serviceAccount['project_id'])) {
//             throw new \Exception('project_id missing in firebase.json');
//         }

//         $this->projectId = $serviceAccount['project_id'];

//         // Google Client
//         $this->client = new Client();
//         $this->client->setAuthConfig($serviceAccountPath);

//         // âœ… CORRECT SCOPE (MOST IMPORTANT)
//         $this->client->addScope(
//             'https://www.googleapis.com/auth/firebase.messaging'
//         );

//         $this->messaging = new FirebaseCloudMessaging($this->client);

//         Log::info('âœ… FCM service initialized for project: ' . $this->projectId);
//     }

//     public function send_notify(string $token, string $title, string $body): array
//     {
//         try {
//             Log::info("ðŸ“¨ Sending FCM notification", [
//                 'token' => $token,
//                 'title' => $title
//             ]);

//             // Notification
//             $notification = new Notification([
//                 'title' => $title,
//                 'body'  => $body,
//             ]);

//             // Android config
//             $androidConfig = new AndroidConfig([
//                 'priority' => 'high',
//                 'notification' => [
//                     'sound' => 'default',
//                     'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//                 ],
//             ]);

//             // iOS config
//             $apnsConfig = new ApnsConfig([
//                 'payload' => [
//                     'aps' => [
//                         'alert' => [
//                             'title' => $title,
//                             'body'  => $body,
//                         ],
//                         'sound' => 'default',
//                         'content-available' => 1,
//                     ],
//                 ],
//             ]);

//             // Data payload (important for background click)
//             $dataPayload = [
//                 'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//                 'screen' => 'home',
//             ];

//             // Message
//             $message = new Message([
//                 'token' => $token,
//                 'notification' => $notification,
//                 'data' => $dataPayload,
//                 'android' => $androidConfig,
//                 'apns' => $apnsConfig,
//             ]);

//             $request = new SendMessageRequest([
//                 'message' => $message,
//             ]);

//             // Send
//             $response = $this->messaging
//                 ->projects_messages
//                 ->send(
//                     'projects/' . $this->projectId,
//                     $request
//                 );

//             Log::info('âœ… FCM sent successfully', [
//                 'response' => $response
//             ]);

//             return [
//                 'status' => true,
//                 'message' => 'Notification sent'
//             ];
//         } catch (\Throwable $e) {
//             Log::error('ðŸ”¥ FCM error', [
//                 'error' => $e->getMessage()
//             ]);

//             return [
//                 'status' => false,
//                 'error' => $e->getMessage()
//             ];
//         }
//     }
// }
