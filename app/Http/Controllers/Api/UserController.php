<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserValidationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"first_name", "last_name", "email", "password", "phone", "username", "address"},
     *                 @OA\Property(property="first_name", type="string", example="John"),
     *                 @OA\Property(property="last_name", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="secret123"),
     *                 @OA\Property(property="phone", type="string", example="+123456789"),
     *                 @OA\Property(property="address", type="string", example="123 Street Name"),
     *                 @OA\Property(property="username", type="string", example="johndoe"),
     *                 @OA\Property(
     *                     property="profile_picture",
     *                     type="string",
     *                     format="binary",
     *                     description="Profile image file"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors"
     *     )
     * )
     */

    public function register(UserValidationRequest $request)
    {
        $validatedData = $request->validated();

        $profilePath = $request->hasFile('profile_picture')
            ? $request->file('profile_picture')->store('profile_pictures', 'public')
            : 'default_image/user.png';


        $user = User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'profile_image' => $profilePath,
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Log in an existing user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *        
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
     *         description="Validation error"
     *     )
     * )
     */

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('Personal Access Token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 200);
        }
        return response()->json([
            'message' => 'Invalid Credentials'

        ], 401);
    }
}
