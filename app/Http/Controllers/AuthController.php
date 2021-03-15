<?php

namespace App\Http\Controllers;
use App\Http\Requests\RegisterValidateRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterValidateRequest $request): UserResource
    {

        $validatedData = $request->validated();

        $user = new User();

        $user->name = $validatedData['name'];
        $user->role_id = $request['role_id'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);

        $user->save();

        $token=$user->createToken('auth_token')->plainTextToken;

        $data = [
          'token' => $token,
          'token-type' => 'bearer'
        ];

        return UserResource::make($user)->additional($data);
    }

    public function login(Request $request)
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

        $data = [
            'message'=>'You have logged in successfully',
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];

        return UserResource::make($user)->additional($data);

    }
    public function me(Request $request){
        return $request->user();
    }
}
