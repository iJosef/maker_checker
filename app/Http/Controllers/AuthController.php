<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated_fields = $request->validate([
            'name'          => 'string',
            'email'         => 'required|string|email|unique:users,email',
            'password'      => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name'       => $validated_fields['name'],
            'email'      => $validated_fields['email'],
            'password'   => bcrypt($validated_fields['password']),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($data, 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'  => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            $data = [
                'status' => 'error',
                'message' => 'Wrong Credentials'
            ];

            return response()->json($data, 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $data = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($data, 200);
    }


    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        $data = [
            'status' => 'success',
            'message' => 'logged out successfully'
        ];

        return response()->json($data, 200);
    }
}
