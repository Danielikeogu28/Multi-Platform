<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorValidationRequest;
use App\Http\Resources\VendorResource;
use App\Models\Categories;
use App\Models\Vendor;
use App\Models\VendorCategories;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
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
     *                 @OA\Property(property="vendor_category_id", type="integer", example=2),
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
        try {
            $vendor = $request->validated();

              $vendor = strtolower($vendor['email']);

            $profilePath = $request->hasFile('profile_image')
                ? $request->file('profile_image')->store('vendor', 'public')
                : 'default_image/vendor.png';
          
            $vendor = Vendor::create([
                'business_name' => $vendor['business_name'],
                'business_address' => $vendor['business_address'],
                'home_address' => $vendor['home_address'],
                'email' => $vendor['email'],
                'street' => $vendor['street'],
                'city' => $vendor['city'],
                'state' => $vendor['state'],
                'country' => $vendor['country'],
                'phone' => $vendor['phone'],
                'password' => Hash::make($vendor['password']),
                'vendor_category_id' => $vendor['vendor_category_id'],
                'profile_image' => $profilePath
            ]);

            return response()->json([
                'message' => 'Vendor registered successfully',
                'vendor' => new VendorResource($vendor)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Vendor registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
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

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
      
        $email = strtolower($request->email);
        $vendor = Vendor::where('email', $email)->first();
        if (!$vendor || !Hash::check($request->password, $vendor->password)) {

            return response()->json([
                'status' => 'Fail',
                'message' => 'Invalid Credentials'
            ], 401);
        };
        $token = $vendor->createToken('Vendor Access Token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'vendor' => $vendor,
        ], 200);
    }
    public function logout() {}
}
