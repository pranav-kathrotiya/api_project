<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AddressController extends ApiController
{
    public function address(Request $request)
    {
        try {
            $validated = $request->validate([
                'address_id' => 'nullable|exists:addresses,id',
                'user_id' => 'required|exists:users,id',
                'full_name' => 'required|string|max:255',
                'phone_number' => 'required|numeric|digits:10',
                'alternate_phone_number' => 'nullable|numeric|digits:10',
                'pin_code' => 'required|numeric|digits:6',
                'latitude' => 'nullable',
                'longitude' => 'nullable',
                'state' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'house_and_building' => 'required|string|max:255',
                'area_address' => 'required|string|max:255',
                'landmark' => 'nullable|string|max:255',
            ]);

            if (!empty($validated['address_id'])) {
                $address = Address::find($validated['address_id']);
                if (!$address) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Address not found!'
                    ], 404);
                }
                $message = 'Address updated successfully!';
            } else {
                $address = new Address();
                $message = 'Address added successfully!';
            }

            $address->user_id = $validated['user_id'];
            $address->full_name = $validated['full_name'];
            $address->phone_number = $validated['phone_number'];
            $address->alternate_phone_number = $validated['alternate_phone_number'] ?? null;
            $address->pin_code = $validated['pin_code'];
            $address->latitude = $validated['latitude'] ?? null;
            $address->longitude = $validated['longitude'] ?? null;
            $address->state = $validated['state'];
            $address->city = $validated['city'];
            $address->house_and_building = $validated['house_and_building'];
            $address->area_address = $validated['area_address'];
            $address->landmark = $validated['landmark'] ?? null;
            $address->save();

            return $this->sendSuccess($message, [
                'address' => $address,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Operation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserAddresses(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $user_id = $validated['user_id'];

            $user = User::find($user_id);

            $addresses = Address::where('user_id', $user_id)->get();

            if ($addresses->isEmpty()) {
                return response()->json([
                    'status' => 1,
                    'message' => 'No addresses found for this user.',
                    'addresses' => []
                ], 200);
            }

            return response()->json([
                'status' => 1,
                'message' => 'User addresses retrieved successfully!',
                'user' => $user,
                'addresses' => $addresses
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch addresses',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
