<?php

namespace App\Http\Controllers;

use App\Models\OfferBanner;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OfferBannerController extends ApiController
{
    public function addOfferBanner(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/offer_banners'), $imageName);

                $imagePath = url('uploads/offer_banners/' . $imageName);
            }

            $banner = OfferBanner::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $imagePath
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'Offer banner added successfully!',
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
                'message' => 'Failed to add offer banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function editOfferBanner(Request $request)
    {
        try {
            // Validate all required fields including banner ID
            $request->validate([
                'offer_banner_id' => 'required|integer|exists:offer_banners,id',
                'title' => 'required|string',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $banner = OfferBanner::find($request->offer_banner_id);

            // Update image if provided
            if ($request->hasFile('image')) {
                // Delete old image from folder
                $oldImagePath = public_path('uploads/offer_banners/' . basename($banner->image));
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/offer_banners'), $imageName);

                $banner->image = $imageName;
            }

            // Update title and description
            $banner->title = $request->title;
            $banner->description = $request->description;
            $banner->save();

            $banner->image = url('uploads/offer_banners/' . $banner->image);

            return response()->json([
                'status' => 1,
                'message' => 'Offer banner updated successfully!',
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
                'message' => 'Failed to update offer banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getOfferBanner()
    {
        try {
            $banners = OfferBanner::all()->map(function ($banner) {
                $banner->image = url($banner->image);
                return $banner;
            });

            return response()->json([
                'status' => 1,
                'message' => 'Offer banners fetched successfully!',
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

    public function deleteOfferBanner(Request $request)
    {
        try {

            $request->validate([
                'offer_banner_id' => 'required',
            ]);

            $banner = OfferBanner::find($request->offer_banner_id);

            if (!$banner) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Offer banner not found!'
                ], 404);
            }

            // Delete image from storage
            $imagePath = public_path(str_replace(url('/'), '', $banner->image));
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete banner record
            $banner->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Offer banner deleted successfully!'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to delete offer banner',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
