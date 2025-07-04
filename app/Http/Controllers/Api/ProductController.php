<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\IsVendorMiddleware;
use App\Http\Requests\ProductUpdateValidationRequest;
use App\Http\Requests\ProductValidationRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware(IsVendorMiddleware::class),
        ];
    }
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="List all products",
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function index(Request $request)
    {
        $vendor = auth('vendor')->user();
        if ($vendor->category->name !== 'E-commerces Vendor') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only E-commerce vendors can view products.',

            ], 403);
        }

        $products = Product::with('vendor')
            ->where('vendor_id', $vendor->id)
            ->where('product_category', $request->product_category_id)
            ->get();

        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No products found'
            ], 200);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'products' => $products,
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductValidationRequest $request)
    {
        $vendor = auth('vendor')->user();
        // Check vendor category
        if (!$vendor->category || $vendor->category->name !== 'E-commerces Vendor') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only E-commerce vendors can add products.',
            ], 403);
        }

        $product = new Product();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = $image->store('', 'public');
            $filePath = 'uploads/' . $fileName;
            $product->image = $filePath;
        }
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->condition = $request->condition;
        $product->status = $request->status ?? 'active';

        $product->product_category_id = $request->product_category_id;
        $product->vendor_id = $vendor->id;
        $product->vendor_category_id = $vendor->vendor_category_id;
        $product->save();


        if ($request->has('color') && $request->filled('color')) {
            foreach ($request->color as $color) {
                ProductColor::create([
                    'color' => $color,
                    'product_id' => $product->id
                ]);
            }
        }
        // Save additional images
        if ($request->hasFile('images')) {
            foreach ($request->images as $image) {
                $fileName = $image->store('', 'public');
                $filePath = 'uploads/products/' . $fileName;

                ProductImage::create([
                    'image_path' => $filePath,
                    'product_id' => $product->id
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Products"},
     *     summary="Get a product by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function show(string $id)
    {
        $vendor = auth('vendor')->user();
        if ($vendor->category->name !== 'E-commerces Vendor') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only E-commerce vendors can show products.',
            ]);
        }

        $product = Product::with('colors', 'images')->findOrFail($id);


        return response()->json([
            'status' => 'success',
            'message' => 'Product retrieved successfully',
            'product' => $product,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateValidationRequest $request, string $id)
    {
        $vendor = auth('vendor')->user();
        if ($vendor->category->name !== 'E-commerces Vendor') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only E-commerce vendors can update products.',
            ]);
        }

        $product = Product::findOrFail($id);


        if ($request->hasFile('image')) {
            File::delete(public_path('storage/' . $product->image));
            $image = $request->file('image');
            $fileName = $image->store('', 'public');
            $filePath = 'uploads/' . $fileName;

            $product->image = $filePath;
        };

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->product_category_id = $request->product_category_id;
        $product->condition = $request->condition;
        $product->status = $request->status ?? 'active';
        $product->vendor_id = $request->vendor_id;

        if ($request->has('color')  && $request->filled('color')) {
            foreach ($product->color as $color) {
                $color->delete();
            }
            foreach ($product->color as $color) {
                ProductColor::create([
                    'color' => $color,
                    'product_id' => $product->id
                ]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($product->images as $image) {
                File::delete(public_path($image->path));
            }
            $product->images()->delete();

            foreach ($request->images as $image) {
                $fileName = $image->store('', 'public');
                $filePath = "uploads/" . $fileName;
                ProductImage::create([
                    'product_id' => $product->id,
                    'Image_path' => $filePath,
                ]);
            }
        }

        return response()->json([
            'status' => 'Ok',
            'message' => 'Product Updated Successfully'
        ], 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vendor = auth('vendor')->user();
        if ($vendor->category->name !== 'E-commerces Vendor') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only E-commerce vendors can delete products.',
            ]);
        }
        $product = Product::findOrFail($id);

        if ($product->isEmpty()) {
            return response()->json(['message' => 'Product Not Found']);
        }
        $product->colors()->delete();

        if ($product->image && file_exists(public_path('storage/' . $product->image))) {
            File::delete(public_path('storage/' . $product->image));
        }
        foreach ($product->images as $image) {
            if ($image->image_path && file_exists(public_path('storage/' . $image->image_path))) {
                File::delete(public_path('storage/' . $image->image_path));
            }
        }
        $product->images()->delete();
        $product->delete();

        return response()->json([
            'status' => 'Ok',
            'message' => 'Product Deleted'
        ], 200);
    }
}
