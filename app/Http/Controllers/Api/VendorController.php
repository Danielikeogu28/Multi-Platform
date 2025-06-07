<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function getcategories()
    {
        $categories = Categories::pluck('name', 'id');
        return response()->json([
            'categories' => $categories,
        ]);
    }
}
