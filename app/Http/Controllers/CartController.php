<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends ApiController
{
    public function addToCart(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'product_id' => 'required|integer',
            ]);

            $productdata = Product::with('images')->where('id', $request->product_id)->first();
            if (!empty($productdata)) {
                $cart = new Cart();
                $cart->user_id = $request->user_id;
                $cart->product_id = $request->product_id;
                $cart->product_name = $productdata->name;
                $cart->product_detail = $productdata->detail;
                // $cart->product_image = $productdata['images'][0]->image;
                $cart->qty = 1;
                $cart->product_price = $productdata->price;
                $cart->save();
                return response()->json([
                    'status' => 1,
                    'message' => 'Item added to cart!',
                    'cart' => $cart
                ], 200);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Item not found!',
                ], 200);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to add item to cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeFromCart(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'product_id' => 'required|integer'
            ]);

            $deleted = Cart::where('user_id', $request->user_id)
                ->where('product_id', $request->product_id)
                ->delete();

            if ($deleted) {
                return response()->json([
                    'status' => 1,
                    'message' => 'Item removed from cart!'
                ], 200);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Item not found in cart!'
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
                'message' => 'Failed to remove item from cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCart(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer'
            ]);

            $cart = Cart::where('user_id', $request->user_id)->get();

            return response()->json([
                'status' => 1,
                'message' => 'Cart fetched successfully!',
                'cart' => $cart
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
