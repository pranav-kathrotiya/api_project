<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Google\Client as GoogleClient;
use Illuminate\Validation\ValidationException;

class FcmController extends Controller
{
    public function sendNotification(Request $request)
    {
        try {
            // $request->validate([
            //     'user_id' => 'required|exists:users,id',
            //     'title' => 'required|string',
            //     'body' => 'required|string',
            // ]);

            $userdata = User::get();

            foreach ($userdata as $user) {

                $fcm = $user->device_token;

                $title = 'Hello Test';
                $description = 'I am sent test notification !';
                $projectId = env('PROJECTID'); # INSERT COPIED PROJECT ID

                $credentialsFilePath = Storage::path('file.json');
                $client = new GoogleClient();
                $client->setAuthConfig($credentialsFilePath);
                $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
                $client->refreshTokenWithAssertion();
                $token = $client->getAccessToken();

                $access_token = $token['access_token'];

                $headers = [
                    "Authorization: Bearer $access_token",
                    'Content-Type: application/json'
                ];

                $data = [
                    "message" => [
                        "token" => $fcm,
                        "notification" => [
                            "title" => $title,
                            "body" => $description,
                        ],
                    ]
                ];
                $payload = json_encode($data);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send");
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable verbose output for debugging
                $response = curl_exec($ch);
                $err = curl_error($ch);
                curl_close($ch);

                if ($err) {
                    return response()->json([
                        'message' => 'Curl Error: ' . $err
                    ], 500);
                } else {
                    return response()->json([
                        'status' => true,
                        'message' => 'Notification has been sent successfully!',
                        'response' => json_decode($response, true)
                    ], 200);
                }
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            dd($e);
            return response()->json([
                'status' => 0,
                'message' => 'Failed to sent Notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
