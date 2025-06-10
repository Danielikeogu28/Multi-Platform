<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="List all products",
     *     @OA\Response(response=200, description="Success")
     * )
     */
    public function index()
    {
        $product = Product::with('vendor')->get();
        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
