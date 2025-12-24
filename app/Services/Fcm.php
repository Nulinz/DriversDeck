<?php

namespace App\Services;

use Google\Client;
use Google\Service\FirebaseCloudMessaging;
use Google\Service\FirebaseCloudMessaging\Message;
use Google\Service\FirebaseCloudMessaging\Notification;
use Google\Service\FirebaseCloudMessaging\AndroidConfig;
use Google\Service\FirebaseCloudMessaging\ApnsConfig;
use Google\Service\FirebaseCloudMessaging\SendMessageRequest;
use Illuminate\Support\Facades\Log;

class Fcm
{
    protected $client;
    protected $messaging;

    public function __construct()
    {
        // Set up the Google client with the service account credentials
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/firebase.json'));
        $this->client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);

        // Initialize the Firebase Cloud Messaging service
        $this->messaging = new FirebaseCloudMessaging($this->client);

        Log::info('Fcm service initialized');
    }

    public function send_notify($token, $title, $body)
    {
        Log::info("Preparing to send notification to token: $token");

        // Log project ID env value
        $projectId = env('FIREBASE_PROJECT_ID');
        Log::info("Firebase Project ID: $projectId");

        if (empty($projectId)) {
            Log::error('Firebase Project ID is not set in environment variables.');
            return ['error' => 'Firebase Project ID is missing'];
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
            Log::info('Sending FCM message...');
            $response = $this->messaging->projects_messages->send(
                'projects/' . $projectId,
                $sendRequest
            );
            Log::info("✅ FCM message sent successfully to token: $token, response: " . json_encode($response));

            return response()->json(['status' => 'success']);
        } catch (\Throwable $e) {
            Log::error("🔥 FCM error: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}