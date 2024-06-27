<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /*  Show All User  */
    public function index()
    {
        $users = User::select('id', 'name', 'email')->get();
        return response()->json(['User' => UserResource::collection($users)]);
    }

    /*  Store User  */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return response()->json(['message' => 'User Created Successfully', 'User' => new UserResource($user)]);
    }

    /*  Update User By Id  */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return response()->json(['message' => 'User Update Successfully', 'user' => new UserResource($user)]);
    }

    /*  delete User  By Id */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
