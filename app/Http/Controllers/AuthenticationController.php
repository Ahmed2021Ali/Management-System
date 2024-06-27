<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    /* Register a new user */
    public function register(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return response()->json(['message' => __('User Created Successfully'),
            'token' => $user->createToken("User Token")->plainTextToken, 'User' => new UserResource($user)]);
    }


    /* Login a  user */
    public function login(LoginUserRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json(['message' => 'Email & Password does not match with our record.']);
        } else {
            $user = User::where('email', $request->email)->first();
            return shoeMessage('User Login  Successfully', $user, 'UserResource', true);
        }
    }


}
