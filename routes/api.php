<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Dashboard
Route::get('/vendor-dashboard', [DashboardController::class, 'dashboard']);
Route::get('/get-vendor-categories', [DashboardController::class, 'getVendorCategory']);

// User auth
Route::post('/user-register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);

// Vendor auth
Route::post('/vendor/login', [VendorController::class, 'login']);
Route::post('/vendor-register', [VendorController::class, 'register']);


// Route::get('/categories', [VendorController::class, 'getcategories']);

Route::resource('/vendor/products', ProductController::class);


//Admin 
Route::get('/get-users', [AdminController::class, 'getUser']);
Route::get('/get-vendors', [AdminController::class, 'getVendor']);
Route::get('/get-vendor-product-with-category', [AdminController::class, 'getVendorProductsWithCategory']);
