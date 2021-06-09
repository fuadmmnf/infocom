<?php


namespace App\Handlers;


use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTokenHandler
{
    public function createUser($name, $phone, $email, $password): User
    {
        $newUser = new User();
        $newUser->email = $email;
        $newUser->name = $name;
        $newUser->phone = $phone;
        $newUser->password = Hash::make($password);
        $newUser->save();
        $newUser->token = $newUser->createToken($newUser->name . $newUser->phone)->accessToken;
        return $newUser;
    }


    public function regenerateUserToken(User $user)
    {
//        $user->tokens()->delete();
        $user->token = $user->createToken($user->name . $user->phone)->accessToken;
        return $user;
    }

    public function revokeTokens(User $user)
    {
        $user->tokens()->delete();
    }
}
