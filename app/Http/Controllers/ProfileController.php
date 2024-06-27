<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /* Get  user  has been Authenticated */
    public function getCurrentUser(Request $request)
    {
        $user = $request->user();
        return response()->json(['user' => new UserResource($user)]);
    }

    /* Update  user */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());
        return response()->json(['status' => true, 'message' => 'User Update Successfully', 'user' => new UserResource($user)]);
    }

    /* Log out Account   */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['status' => true, 'message' => 'user logged out',]);
    }

    /* delete Account */
    public function delete(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => true, 'message' => 'delete account successfully']);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json(['token' => $user->createToken("User New token")->plainTextToken,
            'User' => new UserResource($user)]);
    }
}
