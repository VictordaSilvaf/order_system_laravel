<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            // Generate a new token for the authenticated user
            $token = Auth::user()->createToken('auth_token')->plainTextToken;

            // Return the token in the response
            return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
        }

        // If authentication fails, return an error response
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function register(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Create a new user
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Generate a new token for the registered user
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the token in the response
        return response()
            ->json(['access_token' => $token, 'token_type' => 'Bearer'])
            ->setStatusCode(201);
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        // Return a success response
        return response()->json(['message' => 'Logged out successfully']);
    }
}
