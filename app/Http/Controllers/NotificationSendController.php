<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Notification;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;

class NotificationSendController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        try {
            $request->user()->update(['fcm_token' => $request->fcm_token]);
            return response()->json([
                'success' => true
            ]);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'success' => false
            ], 500);
        }
    }

    public function sendNotification(Request $request)
    {
        $fcm_token = auth()->user()->fcm_token;

        $credential = new ServiceAccountCredentials(
            "https://www.googleapis.com/auth/firebase.messaging",
            json_decode(file_get_contents("pvKey.json"), true)
        );

        $token = $credential->fetchAuthToken(HttpHandlerFactory::build());

        $ch = curl_init("https://fcm.googleapis.com/v1/projects/booket-d4fd5/messages:send");

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token['access_token']
        ]);

        $payload = json_encode([
            "message" => [
                "token" => $fcm_token,
                "notification" => [
                    "title" => "Test Notification",
                    "body" => "Test Test",
                ],
                "webpush" => [
                    "fcm_options" => [
                        "link" => "https://google.com"
                    ]
                ]
            ]
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $response = curl_exec($ch);

        curl_close($ch);

        echo $response;
    }
}
