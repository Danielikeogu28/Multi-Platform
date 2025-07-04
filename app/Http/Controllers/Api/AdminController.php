<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VendorResource;
use App\Models\VendorCategories;
use Illuminate\Http\JsonResponse;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        //return [new Middleware(IsAdminMiddleware::class)];
    }

    public function getuser(Request $request)
    {
        $user = User::where('role', 'users')->get();

        if($user->isEmpty()){
            return response()->json([
                'status' => 'error',
                'message' => 'No user Found'
            ],404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'User retrieved successfully',
            'user' => UserResource::collection($user)
        ],200);
    }

    public function getVendor(Request $request)
    {
        $vendor = Vendor::where('role', 'vendor')->get();

        if($vendor->isEmpty()){
            return response()->json([
                'status' => 'error',
                'message' => 'No vendors Found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Vendor retrieved successfully',
            'vedors' => VendorResource::collection($vendor)
        ],200);
    }

   
}
