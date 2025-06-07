<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('guest')->group(function (){
    Route::post('/register', [UserController::class, 'register']);
});

Route::get('/categories', [VendorController::class, 'getcategories']);