<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        try {
            // ✅ Stop at First Validation Error
            $validated = $request->validate([
                // 'guest_id' => 'required|string',
                // 'device_id' => 'required|string',
                // 'device_token' => 'required|string',
                'name' => 'required|string|max:255',
                'phone_number' => 'required|numeric|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // User Create Karein
            $user = new User();
            // $user->guest_id = $validated['guest_id'];
            // $user->device_id = $validated['device_id'];
            // $user->device_token = $validated['device_token'];
            $user->name = $validated['name'];
            $user->phone_number = $validated['phone_number'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->save();

            // Token Generate Karein
            $token = $user->createToken('auth_token')->plainTextToken;

            // Success Response
            return $this->sendSuccess('User registered successfully!', [
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $loginWith = filter_var($request->emailphone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
            $validated = $request->validate(
                [
                    'emailphone' => [
                        'required',
                        $loginWith == 'email' ? 'email' : 'digits:10',
                        Rule::exists('users', $loginWith),
                    ],
                    'password' => 'required|string|min:6',
                ],
                [
                    'emailphone.exists' => 'This email or phone number is not registered.',

                ]
            );
            if ($loginWith == 'email') {
                $user = User::where('email', $validated['emailphone'])->first();
            } else {
                $user = User::where('phone_number', $validated['emailphone'])->first();
            }

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Invalid password!'
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            // Success Response
            return $this->sendSuccess('Login successfully!', [
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function change_password(Request $request)
    {
        try {
            $loginWith = filter_var($request->emailphone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
            $request->validate(
                [
                    'emailphone' => [
                        'required',
                        $loginWith == 'email' ? 'email' : 'digits:10',
                        Rule::exists('users', $loginWith),
                    ],
                    'password' => 'required|string|min:6|confirmed',
                ],
                [
                    'emailphone.exists' => 'This email or phone number is not registered.',

                ]
            );
            if ($loginWith == 'email') {
                $updatepass = User::where('email', $request->emailphone)->first();
            } else {
                $updatepass = User::where('phone_number', $request->emailphone)->first();
            }
            $updatepass->password = Hash::make($request->password);
            $updatepass->save();


            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully!'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to changed password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // public function sendOtp(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|string|email|exists:users,email',
    //         ]);

    //         $otp = rand(100000, 999999); // Generate 6-digit OTP
    //         $email = $request->email;

    //         // Store OTP in cache for 5 minutes
    //         Cache::put('otp_' . $email, $otp, now()->addMinutes(5));

    //         // Send OTP via email
    //         Mail::raw("Your OTP for login is: $otp", function ($message) use ($email) {
    //             $message->to($email)
    //                 ->subject("Login OTP");
    //         });

    //         return response()->json([
    //             'status' => 1,
    //             'message' => 'OTP sent to your email!'
    //         ], 200);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'status' => 0,
    //             'message' => $e->validator->errors()->first()
    //         ], 422);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => 0,
    //             'message' => 'Failed to send OTP',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    // /**
    //  * Verify OTP and Login
    //  */
    // public function loginWithOtp(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'email' => 'required|string|email|exists:users,email',
    //             'otp' => 'required|digits:6',
    //         ]);

    //         $email = $request->email;
    //         $otp = $request->otp;

    //         // Check OTP from cache
    //         $storedOtp = Cache::get('otp_' . $email);

    //         if (!$storedOtp || $storedOtp != $otp) {
    //             return response()->json([
    //                 'status' => 0,
    //                 'message' => 'Invalid or expired OTP!'
    //             ], 401);
    //         }

    //         // OTP verified, login the user
    //         $user = User::where('email', $email)->first();
    //         $token = $user->createToken('auth_token')->plainTextToken;

    //         // Remove OTP after successful login
    //         Cache::forget('otp_' . $email);

    //         return response()->json([
    //             'status' => 1,
    //             'message' => 'Login successful!',
    //             'user' => $user,
    //             'token' => $token
    //         ], 200);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'status' => 0,
    //             'message' => $e->validator->errors()->first()
    //         ], 422);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => 0,
    //             'message' => 'Login failed',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
