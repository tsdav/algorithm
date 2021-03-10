<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255|unique:users',
            'password'=>'required|string|',
        ]);
        $user = User::create([
            'name' => $validatedData['name'],
            'role_id' => $request['role_id'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);
        $token=$user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message'=>'You have registered'
        ]);
    }
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $password = $request['password'];

        $user = User::where('email',$request['email'])->first();

        if ($user === null) {
            return response()->json([
                'message' => 'Invalid login details'
            ],401);
        }else if (!Hash::check($password,$user->password)) {
            return response()->json([
                'message' => 'Invalid  password'
            ],401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $role = User::find($user->id)->role->role_name;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'role_is' => $role,
            'message'=>'You have logged in successfully'
        ]);

    }
    public function me(Request $request){
        return $request->user();
    }
}
