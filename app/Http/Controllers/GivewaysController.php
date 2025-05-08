<?php

namespace App\Http\Controllers;

use App\Models\Giveways;
use App\Models\Order;
use App\Models\Prize;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GivewaysController extends ApiController
{
    //Daily Order All User
    public function givewayuserslist()
    {
        try {
            $dailyOrderUsers = Order::join('users', 'users.id', '=', 'orders.user_id')
                ->select(
                    'orders.order_number',
                    'users.id as user_id',
                    'users.name',
                    'users.phone_number',
                    'users.email',
                    'orders.grand_total',
                    'orders.created_at'
                )
                ->orderBy('orders.created_at', 'desc')
                ->get()
                ->groupBy(function ($order) {
                    return \Carbon\Carbon::parse($order->created_at)->format('d-M-Y');
                });


            return response()->json([
                'status' => 1,
                'message' => 'Users fetched successfully!',
                'users' => $dailyOrderUsers
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Get Giveway List
    public function getgiveway()
    {
        try {
            $giveway = Giveways::with('prizes')->get();

            return response()->json([
                'status' => 1,
                'message' => 'Giveway fetched successfully!',
                'giveway' => $giveway
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch giveway',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Giveway Add or Update
    public function addOrupdate(Request $request)
    {
        try {
            $validated = $request->validate([
                'giveway_id' => 'nullable|exists:giveways,id',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);
            date_default_timezone_set('Asia/Kolkata');
            $giveway_id = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 10)), 0, 20);

            if (!empty($validated['giveway_id'])) {
                $giveway = Giveways::find($validated['giveway_id']);
                if (!$giveway) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Giveway not found!'
                    ], 404);
                }
                $message = 'Giveway updated successfully!';
            } else {
                $giveway = new Giveways();
                $giveway->giveway_id = $giveway_id;
                $message = 'Giveway added successfully!';
            }
            if (Carbon::now()->format('m/d/Y H:i') >= Carbon::parse($validated['start_time'])->format('m/d/Y H:i')) {
                $giveway->status = 2; // 2= Processing Giveway
            } else {
                $giveway->status = 1; // 1= Upcoming Giveway
            }
            $giveway->start_time = $validated['start_time'];
            $giveway->end_time = $validated['end_time'];
            $giveway->save();

            return $this->sendSuccess($message, [
                'giveway' => $giveway,
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

    //Delete Giveway
    public function deletegiveaway(Request $request)
    {
        try {
            $request->validate([
                'giveway_id' => 'required',
            ]);

            $giveway = Giveways::find($request->giveway_id);

            if (!$giveway) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Giveway not found!'
                ], 404);
            }

            // Delete giveway record
            $giveway->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Giveway deleted successfully!'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to delete giveway',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //Add Giveway Prize
    public function addgivewayprize(Request $request)
    {
        try {
            $validated = $request->validate([
                'giveway_id' => 'required',
                'name' => 'required',
                'rank' => 'required',
                'image' => 'required',
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = 'prize-' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/giveaway_prizes'), $imageName);
            }

            $prize = new Prize();
            $prize->giveway_id = $request->giveway_id;
            $prize->name = $validated['name'];
            $prize->rank = $validated['rank'];
            $prize->image = $imageName;
            $prize->save();

            return response()->json([
                'status' => 1,
                'message' => 'Prize added successfully!',
                'prize' => $prize
            ], 200);
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

    //Delete Giveway Prize
    public function deleteprize(Request $request)
    {
        try {
            $request->validate([
                'prize_id' => 'required',
            ]);

            $prize = Prize::find($request->prize_id);

            if (!$prize) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Prize not found!'
                ], 404);
            }
            // Delete image from storage
            $imagePath = public_path('/uploads/giveaway_prizes/' . $prize->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            // Delete prize record
            $prize->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Prize deleted successfully!'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to delete prize',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
