<?php


namespace App\Handlers;


use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
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
        $newUser->token = $newUser->createToken($newUser->email . $newUser->phone)->accessToken;
        return $newUser;
    }

    public function createCustomer(array $info){
        $user = $this->createUser($info['name'], $info['phone'], $info['email'], $info['password']);
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->code = $info['code']?? '';
        $customer->save();
        $user->assignRole('customer');

        return $customer;
    }

    public function regenerateUserToken(User $user)
    {
//        $user->tokens()->delete();
        $user->token = $user->createToken($user->email . $user->phone)->accessToken;
        return $user;
    }

    public function revokeTokens(User $user)
    {
        $user->tokens()->delete();
    }
}
