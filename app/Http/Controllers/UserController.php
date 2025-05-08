<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Wishlist;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends ApiController
{
    public function getUserById(Request $request)
    {
        try {
            // Validate that 'user_id' is required and must be an integer
            $request->validate([
                'user_id' => 'required|integer'
            ]);

            // Get user_id from request
            $userId = $request->input('user_id');

            // Find user by ID
            $user = User::find($userId);

            // If user not found
            if (!$user) {
                return response()->json([
                    'status' => 0,
                    'message' => 'User not found!'
                ], 404);
            }

            // Return success response
            return response()->json([
                'status' => 1,
                'message' => 'User fetched successfully!',
                'user' => $user
            ], 200);
        } catch (ValidationException $e) {
            // Return validation error
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editUser(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'user_id' => 'required|integer',
                'name' => 'required|string|max:255',
                'phone_number' => 'required|numeric',
                'email' => 'required|email|unique:users,email,' . $request->user_id
            ]);

            // Fetch the user ID from request
            $userId = $request->input('user_id');

            // Find the user
            $user = User::find($userId);

            // If user is not found
            if (!$user) {
                return response()->json([
                    'status' => 0,
                    'message' => 'User not found!'
                ], 404);
            }

            // Update user details
            $user->name = $request->input('name');
            $user->phone_number = $request->input('phone_number');
            $user->email = $request->input('email');
            $user->save();

            // Return success response
            return response()->json([
                'status' => 1,
                'message' => 'User updated successfully!',
                'user' => $user
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteAccount(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'reason' => 'required|string'
            ]);

            $user = User::find($request->input('user_id'));

            if (!$user) {
                return response()->json([
                    'status' => 0,
                    'message' => 'User not found!'
                ], 404);
            }

            // Log the reason before deleting
            \Log::info('User Deletion:', [
                'user_id' => $user->id,
                'reason' => $request->input('reason')
            ]);

            // Delete user-related data
            Wishlist::where('user_id', $user->id)->delete();
            Cart::where('user_id', $user->id)->delete();

            // Delete user account
            $user->delete();

            return response()->json([
                'status' => 1,
                'message' => 'User account deleted successfully!'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to delete account',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllUser()
    {
        try {
            $users = User::all();

            if ($users->isEmpty()) {
                return response()->json([
                    'status' => 1,
                    'message' => 'No users found.',
                    'users' => []
                ], 200);
            }

            return response()->json([
                'status' => 1,
                'message' => 'Users fetched successfully!',
                'users' => $users
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
