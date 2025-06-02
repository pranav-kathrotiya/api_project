<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WishlistController extends ApiController
{
    public function addToWishlist(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'product_id' => 'required|integer'
            ]);

            $wishlist = Wishlist::create([
                'user_id' => $request->user_id,
                'product_id' => $request->product_id
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'Item added to wishlist!',
                'wishlist' => $wishlist
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to add item to wishlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeFromWishlist(Request $request)
    {
        try {
            $request->validate([
                'wishlist_id' => 'required|integer'
            ]);

            $deleted = Wishlist::where('id', $request->wishlist_id)->delete();

            if ($deleted) {
                return response()->json([
                    'status' => 1,
                    'message' => 'Item removed from wishlist!'
                ], 200);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Item not found in wishlist!'
                ], 404);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to remove item from wishlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getWishlist(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer'
            ]);

            $wishlist = Wishlist::join('products', 'products.id', '=', 'wishlists.product_id')->where('user_id', $request->user_id)->get();

            return response()->json([
                'status' => 1,
                'message' => 'Wishlist fetched successfully!',
                'wishlist' => $wishlist
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch wishlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
