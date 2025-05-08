<?php

namespace App\Http\Controllers;

use App\Models\GiveawayBanner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GiveawayBannerController extends ApiController
{
    public function addGiveawayBanner(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'sub_title' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/giveaway_banners'), $imageName);

                $imagePath = url('uploads/giveaway_banners/' . $imageName);
            }

            $banner = GiveawayBanner::create([
                'title' => $request->title,
                'sub_title' => $request->sub_title,
                'image' => $imagePath
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'Giveaway banner added successfully!',
                'banner' => $banner
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to add giveaway banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editGiveawayBanner(Request $request)
    {
        try {
            // Validate all required fields including banner ID
            $request->validate([
                'giveaway_banner_id' => 'required|integer|exists:giveaway_banners,id',
                'title' => 'required|string',
                'sub_title' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $banner = GiveawayBanner::find($request->giveaway_banner_id);

            // Update image if provided
            if ($request->hasFile('image')) {
                // Delete old image from folder
                $oldImagePath = public_path('uploads/giveaway_banners/' . basename($banner->image));
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/giveaway_banners'), $imageName);

                $banner->image = $imageName;
            }

            // Update title and description
            $banner->title = $request->title;
            $banner->sub_title = $request->sub_title;
            $banner->save();

            $banner->image = url('uploads/giveaway_banners/' . $banner->image);

            return response()->json([
                'status' => 1,
                'message' => 'Giveaway banner updated successfully!',
                'banner' => $banner
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to update giveaway banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getGiveawayBanner()
    {
        try {
            $banners = GiveawayBanner::all()->map(function ($banner) {
                $banner->image = url($banner->image);
                return $banner;
            });

            return response()->json([
                'status' => 1,
                'message' => 'Giveaway banners fetched successfully!',
                'banners' => $banners
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch offer banners',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteGiveawayBanner(Request $request)
    {
        try {

            $request->validate([
                'giveaway_banner_id' => 'required',
            ]);

            $banner = GiveawayBanner::find($request->giveaway_banner_id);

            if (!$banner) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Giveaway banner not found!'
                ], 404);
            }

            // Delete image from storage
            $imagePath = public_path(str_replace(url('/uploads/giveaway_banners'), '', $banner->image));
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete banner record
            $banner->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Giveaway banner deleted successfully!'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to delete giveaway banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
