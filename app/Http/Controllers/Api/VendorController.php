<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorValidationRequest;
use App\Models\Categories;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/vendor/categories",
     *     summary="Get list of vendor categories for signup",
     *     tags={"Vendor"},
     *     @OA\Response(
     *         response=200,
     *         description="Categories retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Categories retrieved successfully"),
     *             @OA\Property(
     *                 property="categories",
     *                 type="object",
     *                 additionalProperties={
     *                     "type": "string"
     *                 },
     *                 example={"1": "Food", "2": "E-commerce", "3": "Electronics"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */


    /**
     * Get list of vendor categories for signup.
     *
     * @return JsonResponse 
     */
    public function getcategories(): JsonResponse
    {

        $categories = Categories::pluck('name', 'id')->toArray();

        return response()->json([
            'status' => 'success',
            'message' => 'Categories retrieved successfully',
            'categories' => $categories,
        ], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/vendor/register",
     *     summary="Register a new vendor",
     *     tags={"Vendor"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={
     *                     "business_name", "business_email", "business_address", "home_address",
     *                     "email", "street", "city", "state", "country", "phone",
     *                     "password", "category_id"
     *                 },
     *                 @OA\Property(property="business_name", type="string", example="Danzy Kitchen"),
     *                 @OA\Property(property="business_email", type="string", format="email", example="business@example.com"),
     *                 @OA\Property(property="business_address", type="string", example="42 Market Rd, Onitsha"),
     *                 @OA\Property(property="home_address", type="string", example="12 Unity St, Awka"),
     *                 @OA\Property(property="email", type="string", format="email", example="vendor@example.com"),
     *                 @OA\Property(property="street", type="string", example="Allen Ave"),
     *                 @OA\Property(property="city", type="string", example="Lagos"),
     *                 @OA\Property(property="state", type="string", example="Lagos"),
     *                 @OA\Property(property="country", type="string", example="Nigeria"),
     *                 @OA\Property(property="phone", type="string", example="+2348012345678"),
     *                 @OA\Property(property="password", type="string", format="password", example="securepass123"),
     *                 @OA\Property(property="category_id", type="integer", example=2),
     *                 @OA\Property(property="profile_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vendor registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vendor registered successfully"),
     *             @OA\Property(property="vendor", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */


    public function register(VendorValidationRequest $request)
    {
        $validatedData = $request->validated();

        $profilePath = $request->hasFile('profile_image')
            ? $request->file('profile_image')->store('vendor', 'public')
            : 'default_image/vendor.png';


        $vendor = Vendor::create([
            'business_name' => $validatedData['business_name'],
            'business_address' => $validatedData['business_address'],
            'home_address' => $validatedData['home_address'],
            'email' => $validatedData['email'],
            'street' => $validatedData['street'],
            'city' => $validatedData['city'],
            'state' => $validatedData['state'],
            'country' => $validatedData['country'],
            'phone' => $validatedData['phone'],
            'password' => $validatedData['password'],
            'category_id' => $validatedData['category_id'],
            'profile_image' => $profilePath
        ]);

        return response()->json([
            'message' => 'Vendor registered successfully',
            'vendor' => $vendor
        ], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/vendor/login",
     *     summary="Login a vendor",
     *     tags={"Vendor"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="vendor@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="securepass123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="access_token", type="string", example="1|XjZ0aBxFfR8Vqksm..."),
     *             @OA\Property(property="token_type", type="string", example="Bearer"),
     *             @OA\Property(property="vendor", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid Credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid Credentials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error"
     *     )
     * )
     */

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::guard('vendor')->attempt($credentials)) {
            $vendor = Auth::guard('vendor')->user();

            $token = $vendor->createToken('Personal Access Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'vendor' => $vendor,
            ], 200);
        }
        return response()->json([
            'message' => 'Invalid Credentials'
        ], 401);
    }
}
