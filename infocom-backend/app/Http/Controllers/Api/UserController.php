<?php

namespace App\Http\Controllers\Api;

use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    private function getUserType(User $user)
    {
        $roles = $user->getRoleNames();
        if($roles->count()){
            $user->load($roles[0]);
        }
        return $user;
    }

    public function login(LoginRequest $request)
    {
        $info = $request->validated();
        $user = User::where('email', $info['email'])->firstOrFail();
        if($user && Hash::check($info['password'], $user->password)){
            $userTokenHandler = new UserTokenHandler();
            $user = $this->getUserType($userTokenHandler->regenerateUserToken($user));
            return response()->json($user);
        }

        return response()->json('Invalid Credentials', 401);
    }

//    public function changePassword(array $request)
//    {
//        $user = User::where('phone', $request['phone'])->firstOrFail();
//        if(!$user || !Hash::check($request['old_password'], $user->password)){
//            return null;
//        }
//
//        $user->password = Hash::make($request['password']);
//        $user->save();
//
//        $userTokenHandler = new UserTokenHandler();
//        $userTokenHandler->revokeTokens($user);
//
//        return $user;
//    }


//    public function fetchUserById($user_id)
//    {
//        // TODO: Implement fetchUserById() method.
//        $user = User::where('id', $user_id)->with('employee')->firstOrFail();
//        return $user;
//    }
}
