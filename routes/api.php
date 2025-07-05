<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductReviewController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/products',[ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

// Dashboard
Route::get('/vendor-dashboard', [DashboardController::class, 'dashboard']);
Route::get('/get-vendor-categories', [DashboardController::class, 'getVendorCategory']);

// User auth
Route::post('/user-register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login'])->name('login');

// Vendor auth
Route::post('/vendor/login', [VendorController::class, 'login']);
Route::post('/vendor-register', [VendorController::class, 'register']);


//vendor only
Route::middleware(['auth:vendor','vendor.category:E-commerces Vendor'])->group(function(){
    
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/update-product', [ProductController::class, 'update']);
    Route::delete('/delete-product', [ProductController::class, 'destroy']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('products/{id}/review', [ProductReviewController::class, 'store']);
});

//Admin 
Route::get('/get-users', [AdminController::class, 'getUser']);
Route::get('/get-vendors', [AdminController::class, 'getVendor']);
Route::get('/get-vendor-product-with-category', [AdminController::class, 'getVendorProductsWithCategory']);
