<?php


namespace App\Handlers;


use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserTokenHandler {
    public function createUser($name, $phone, $email, $password): User {
        $newUser = new User();
        $newUser->email = $email;
        $newUser->name = $name;
        $newUser->phone = $phone;
        $newUser->password = Hash::make($password);
        $newUser->save();
        $newUser->token = $newUser->createToken($newUser->email . $newUser->phone)->accessToken;
        return $newUser;
    }

    public function createCustomer(array $info) {
        $user = $this->createUser($info['name'], $info['phone'], $info['email'], $info['phone']);
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->popaddress_id = $info['popaddress_id'] ?? null;
        $customer->code = $info['code'] ?? '';
        $customer->technical_contact = $info['technical_contact'] ?? '';
        $customer->management_contact = $info['management_contact'] ?? '';
        $customer->connection_package = $info['connection_package'] ?? '';
        $customer->other_services = $info['other_services'] ?? '';
        $customer->connection_details = $info['connection_details'] ?? '';
        $customer->additional_technical_box = $info['additional_technical_box'] ?? '';
        $customer->billing_information = $info['billing_information'] ?? '';
        $customer->kam_name = $info['kam_name'] ?? '';
        $customer->installation_date = Carbon::parse($info['installation_date']) ?? null;
        $customer->save();
        $user->assignRole('customer');

        return $customer;
    }

    public function updateCustomer($customer_id, array $info){
        $customer = Customer::findOrFail($customer_id);
        $info['installation_date'] = Carbon::parse($info['installation_date']) ?? null;
        $customer->user->update(['name' => $info['name']]);
        unset($info['name']);
        $customer->update($info);

        return $customer;
    }

    public function regenerateUserToken(User $user) {
//        $user->tokens()->delete();
        $user->token = $user->createToken($user->email . $user->phone)->accessToken;
        return $user;
    }

    public function revokeTokens(User $user) {
        $user->tokens()->delete();
    }
}
