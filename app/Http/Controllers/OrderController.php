<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\AdminLocation;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    //New Order Created
    public function placeOrder(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'address_id' => 'required|integer',
            ]);

            $order_number = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 10)), 0, 20);

            $adminlocation = AdminLocation::first();
            $cartdata = Cart::where('user_id', $request->user_id)->get();
            
            // $addressdata = Address::select('*', DB::raw("round(6371 * acos(cos(radians(" . $adminlocation->latitude . ")) * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $adminlocation->longitude . ")) + sin(radians(" . $adminlocation->latitude . ")) * sin(radians(latitude))), 2) AS distance"))
            //     ->where('id', $request->address_id)
            //     ->first();

            $addressdata = Address::select('*', DB::raw("ST_Distance_Sphere(POINT($adminlocation->longitude, $adminlocation->latitude), POINT(longitude, latitude)) / 1000 AS distance"))
                ->where('id', $request->address_id)
                ->first();
            if (count($cartdata) > 0) {
                $grand_total = $request->sub_total + round($addressdata->distance * 3, 2) + $request->cod_delivery_charge - $request->discount;

                $order = new Order();
                $order->user_id = $request->user_id;
                $order->order_number = $order_number;
                $order->sub_total = $request->sub_total;
                $order->delivery_charge = round($addressdata->distance * 3, 2);
                $order->cod_delivery_charge = $request->cod_delivery_charge;
                $order->discount = $request->discount;
                $order->grand_total = $grand_total;
                $order->payment_method = $request->payment_method;
                $order->full_name = $addressdata->full_name;
                $order->phone_number = $addressdata->phone_number;
                $order->alternate_phone_number = $addressdata->alternate_phone_number;
                $order->pin_code = $addressdata->pin_code;
                $order->latitude = $addressdata->latitude;
                $order->longitude = $addressdata->longitude;
                $order->state = $addressdata->state;
                $order->city = $addressdata->city;
                $order->house_and_building = $addressdata->house_and_building;
                $order->area_address = $addressdata->area_address;
                $order->landmark = $addressdata->landmark;
                $order->status = 1; //Default staus 1= Pending
                $order->save();

                foreach ($cartdata as $data) {
                    $orderdetail = new OrderDetails();
                    $orderdetail->order_id = $order->id;
                    $orderdetail->product_id = $data->id;
                    $orderdetail->product_name = $data->product_name;
                    $orderdetail->product_detail = $data->product_detail;
                    // $orderdetail->product_image = $data->product_image;
                    $orderdetail->qty = $data->qty;
                    $orderdetail->product_price = $data->product_price;
                    $orderdetail->save();
                }
                Cart::where('user_id', $request->user_id)->delete();
                return response()->json([
                    'status' => 1,
                    'message' => 'Order Placed Successfully!',
                    'order' => $order
                ], 200);
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Your Cart is Empty!',
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
                'message' => 'Failed to Placed Order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //User All Order History
    public function getallorders(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer'
            ]);

            $order = Order::where('user_id', $request->user_id)->get();

            return response()->json([
                'status' => 1,
                'message' => 'Orders fetched successfully!',
                'order' => $order
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Order Details
    public function getorderdetails(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'order_number' => 'required'
            ]);

            $order = Order::with('orderDetails')->where('order_number', $request->order_number)->where('user_id', $request->user_id)->first();

            return response()->json([
                'status' => 1,
                'message' => 'Order Detail fetched successfully!',
                'order' => $order
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch order detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Order Status Management
    public function orderstatusupdate(Request $request)
    {
        try {
            $request->validate([
                'status' => 'required|integer',
                'order_number' => 'required'
            ]);

            $order = Order::with('orderDetails')->where('order_number', $request->order_number)->first();
            $order->status = $request->status;
            $order->save();

            return response()->json([
                'status' => 1,
                'message' => 'Order Status changed successfully!',
                'order' => $order
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to status changes',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
