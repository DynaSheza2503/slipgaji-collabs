<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
    $validated = $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|string|email|unique:users',
    'password' => 'required|string|min:8',
    ]);
    $user = User::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'password' => bcrypt($validated['password']),
    ]);
    $token = $user->createToken('auth_token')->plainTextToken;
    return response()->json(['token' => $token], 201);
    }
    public function login(Request $request)
    {
    if (!Auth::attempt($request->only('email', 'password'))) {
   return response()->json(['message' => 'Unauthorized'],
    401);
    }
    $user = Auth::user();
    $token = $user->createToken('auth_token')->plainTextToken;
    return response()->json(['token' => $token, 'user' => $user]);

    }
}
    