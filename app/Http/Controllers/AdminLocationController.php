<?php

namespace App\Http\Controllers;

use App\Models\AdminLocation;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class AdminLocationController extends Controller
{
    // Add or Update Location
    public function addOrUpdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'id' => 'nullable|integer',
            ]);

            if (!empty($validated['id'])) {
                $location = AdminLocation::find($validated['id']);
                if (!$location) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Location not found!'
                    ], 404);
                }
                $message = 'Location updated successfully!';
            } else {
                $location = new AdminLocation();
                $message = 'Location added successfully!';
            }

            $location->latitude = $validated['latitude'];
            $location->longitude = $validated['longitude'];
            $location->save();

            return response()->json([
                'status' => 1,
                'message' => $message,
                'location' => $location
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

    // Delete Location
    public function delete(Request $request)
    {
        try {

            $request->validate([
                'admin_location_id' => 'required',
            ]);

            $location = AdminLocation::find($request->admin_location_id);
            if (!$location) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Location not found!'
                ], 404);
            }

            $location->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Location deleted successfully!'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to delete location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get all Locations
    public function list()
    {
        try {
            $locations = AdminLocation::all();

            return response()->json([
                'status' => 1,
                'message' => 'Locations fetched successfully!',
                'locations' => $locations
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch locations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
