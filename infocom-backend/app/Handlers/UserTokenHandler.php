<?php


namespace App\Handlers;


use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class UserTokenHandler
{
    public function createUser($name, $phone, $code, $email, $password): User
    {
        $newUser = new User();
        $newUser->email = $email;
        $newUser->code = $code;
        $newUser->name = $name;
        $newUser->phone = $phone;
        $newUser->password = Hash::make($password);
        $newUser->save();
        $newUser->token = $newUser->createToken($newUser->code . $newUser->phone)->accessToken;
        return $newUser;
    }

    public function updateUser($user_id, array $info): User {
        $user = User::findOrFail($user_id);
        $user->email = $info['email'];
//        $user->code = $info['code'];
        $user->name = $info['name'];
        $user->phone = $info['phone'];
        if (isset($info['password'])) {
            $user->password = Hash::make($info['password']);
        }
        $user->save();
        return $user;
    }

    public function changePassword($user_id, array $info) {
        $user = User::findOrFail($user_id);
        if (!$user || !Hash::check($info['old_password'], $user->password)) {
            return null;
        }

        $user->password = Hash::make($info['password']);
        $user->save();


        return $user;
    }


    public function createCustomer(array $info)
    {
        $user = $this->createUser($info['name'], $info['phone'], $info['code'], $info['email'], $info['phone']);
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->popaddress_id = $info['popaddress_id'] ?? null;
        $customer->code = $info['code'] ?? '';
        $customer->type = $info['type'];
        $customer->services = $info['services'] ?? '';
        $customer->address = $info['address'] ?? '';
        $customer->technical_contact = $info['technical_contact'] ?? '';
        $customer->management_contact = $info['management_contact'] ?? '';
        $customer->connection_package = $info['connection_package'] ?? '';
        $customer->other_services = $info['other_services'] ?? '';
        $customer->connection_details = $info['connection_details'] ?? '';
        $customer->additional_technical_box = $info['additional_technical_box'] ?? '';
        $customer->billing_information = $info['billing_information'] ?? '';
        $customer->kam_name = $info['kam_name'] ?? '';
        $customer->installation_date = isset($info['installation_date']) ? Carbon::parse($info['installation_date']) : null;
        $customer->save();
        $user->assignRole('customer');

        return $customer;
    }

    public function updateCustomer($customer_id, array $info) {
        $customer = Customer::findOrFail($customer_id);
        $info['installation_date'] = isset($info['installation_date']) ? Carbon::parse($info['installation_date']) : null;
        $customer->user->update([
            'name' => $info['name']
        ]);
        unset($info['name']);
        $customer->update($info);

        return $customer;
    }

    public function regenerateUserToken(User $user) {
//        $user->tokens()->delete();
        $user->token = $user->createToken($user->code . $user->phone)->accessToken;
        return $user;
    }

    public function revokeTokens(User $user) {
        $user->tokens()->delete();
    }
}
