<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Utils\AppConst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // signUp new user
    public function signUp(UserStoreRequest $request)
    {
        $request->validated();

        if (User::where('username', $request->username)->exists()) {
            return response()->json([
                'message' => 'Username already exists',
                'status_code' => AppConst::USERNAME_ALREADY_EXISTS,
            ]);
        } else {
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'message' => 'Email already exists',
                    'status_code' => AppConst::EMAIL_ALREADY_EXISTS,
                ]);
            } else {
                $user = User::create([
                    'username' => $request->username,
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);

                return response()->json([
                    'message' => 'Registered Successfully',
                    'status_code' => 201,
                    'data' => $user,
                ], 201);
            }
        }
    }

    // login user
    public function login(UserLoginRequest $request)
    {
        $request->validated();

        if (Auth::attempt($request->only('username', 'password'))) {

            $user = User::where('username', $request['username'])->firstOrFail();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'You are loggedin successfully',
                'access_token' => 'Bearer '.$token,
                'status_code' => 200
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid username or password',
                'status_code' => AppConst::INVALID_CREDENTIALS
            ]);
        }
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'You have logged out successfully'
        ], 200);
    }

    //fetch all users
    public function users(){
        return response()->json([
            'data' => UserResource::collection(User::all()),
        ]);
    }
}
