<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\VendorCategories;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $vendor = auth('vendor')->user();

        if (!$vendor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access. Please log in as a vendor.'
            ], 401);
        }

        $vendor->load('category');
        $data = [];

        switch ($vendor->category->name){
            case 'E-commerces Vendor':
                $data = Product::where('vendor_id', $vendor->id)->get();
                break;

        default:
                $data = [];
                break;

        }

        return response()->json([
            'vendor_category' => $vendor->category->name,
            'data' => $data 

        ]);
    }

     public function getVendorCategory(Request $request)
    {
        $vendor = VendorCategories::all(['id', 'name']);
        return response()->json([
            'status' => 'success',
            'message' => 'Vendor categories retrieved successfully',
            'vendor_categories' => $vendor
        ], 200);
    }
}
