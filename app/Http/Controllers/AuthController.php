<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        // validation
        $request->validate([
            'name' => ['required'],
            'email' => ['required','email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed']
        ]);

        // creating user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // create token
        $token = $user->createToken('default')->plainTextToken;

        // return response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        // find user with email
        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect email or password'
            ]);
        }

        // delete any existing token for the user
        $user->tokens()->delete();

        // create a new token for the user
        $token = $user->createToken("login")->plainTextToken;

        // return token
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $request) {
        auth("sanctum")->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully'
        ]);
    }
}
