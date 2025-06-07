<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserValidationRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(UserValidationRequest $request)
    {
      $validatedData = $request->validated();

      $profilePath = $request->hasFile('profile_picture')
        ? $request->file('profile_picture')->store('profile_pictures', 'public')
        : 'default_image/user.png';


        $user= User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'address' => $validatedData['address'],
            'profile_image' => $profilePath,
            'username' => $validatedData['username'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }
}
