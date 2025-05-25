<?php

namespace App\Http\Controllers;

use App\Models\AdminLocation;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;

class ProductController extends ApiController
{
    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'nullable|exists:products,id',
                'shop_location_id' => 'required',
                'category_id' => 'required',
                'name' => 'required|string',
                'detail' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'image' => 'required|array', // Multiple images
                'image.*' => 'required|image|mimes:jpg,jpeg,png,svg,webp|max:2048', // Multiple images
            ]);

            $category = Category::find($validated['category_id']);
            if (!$category) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Category not found!'
                ], 404);
            }

            $location = AdminLocation::find($validated['shop_location_id']);
            if (!$location) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Location not found!'
                ], 404);
            }

            if (!empty($validated['product_id'])) {
                $product = Product::find($validated['product_id']);
                if (!$product) {
                    return response()->json([
                        'status' => 0,
                        'message' => 'Product not found!'
                    ], 404);
                }
                $message = 'Product updated successfully!';
            } else {
                $product = new Product();
                $message = 'Product added successfully!';
            }

            $product->shop_location_id = $validated['shop_location_id'];
            $product->category_id = $validated['category_id'];
            $product->name = $validated['name'];
            $product->detail = $validated['detail'];
            $product->description = $validated['description'];
            $product->price = $validated['price'];
            $product->save(); // Product ne save kariye pehla, pachi images add kari shakay

            // $imageUrls = [];

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/products'), $imageName);

                    // Image URL store karva mate
                    // $imageUrls[] = url('public/uploads/products/' . $imageName);

                    // Image data database ma store karva (assuming you have a separate `product_images` table)
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imageName
                    ]);
                }
            }

            return $this->sendSuccess($message, [
                'product' => $product,
                // 'image_urls' => $imageUrls
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
            // Fetch query parameters
            $category_id = $request->input('category_id');

            // Query builder with optional filtering
            $query = Product::with('images', 'shoplocation'); // Load images relationship

            if (!empty($category_id)) {
                $query->where('category_id', $category_id);
            }
            $products = $query->paginate(10);

            if ($products->isEmpty()) {
                return response()->json([
                    'status' => 1,
                    'message' => 'No products found.',
                    'products' => []
                ], 200);
            }

            return response()->json([
                'status' => 1,
                'message' => 'Products fetched successfully!',
                'products' => $products
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function searchProduct(Request $request)
    {
        try {
            $search = $request->input('search');

            // Initialize the query builder with eager loading for images
            $query = Product::with('images');

            // If search term is provided, filter products by name
            if (!empty($search)) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
            }

            // Fetch products based on search condition (either filtered or all products)
            $products = $query->get();

            // If no products found
            if ($products->isEmpty()) {
                return response()->json([
                    'status' => 1,
                    'message' => 'No products found.',
                    'products' => []
                ], 200);
            }

            // Transform the product data
            $products->transform(function ($product) {
                // Generate the full URL for each image
                $product->images = $product->images->map(function ($image) {
                    // Use the asset helper to generate the full URL of the image
                    $imageUrl = asset('uploads/products/' . $image->image);
                    return [
                        'id' => $image->id,
                        'product_id' => $image->product_id,
                        'image' => $imageUrl,  // Full URL of the image
                        'created_at' => $image->created_at,
                        'updated_at' => $image->updated_at
                    ];
                });

                return $product;
            });

            return response()->json([
                'status' => 1,
                'message' => 'Products fetched successfully!',
                'products' => $products
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => 'Failed to fetch products',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
