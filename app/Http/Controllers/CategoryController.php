<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CategoryController extends ApiController
{
    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'nullable|exists:categories,id',
                'name' => 'required|string|max:255',
            ]);

            if (!empty($validated['category_id'])) {
                $category = Category::find($validated['category_id']);
                if (!$category) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Category not found!'
                    ], 404);
                }
                $message = 'Category updated successfully!';
            } else {
                $category = new Category();
                $message = 'Category added successfully!';
            }

            $category->name = $validated['name'];
            $category->save();

            return $this->sendSuccess($message, [
                'category' => $category,
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

    public function get(Request $request)
    {
        try {


            $category = Category::get();

            if ($category->isEmpty()) {
                return response()->json([
                    'status' => 1,
                    'message' => 'No category found for this user.',
                    'addresses' => []
                ], 200);
            }

            return response()->json([
                'status' => 1,
                'message' => 'category fetch successfully!',
                'category' => $category
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
