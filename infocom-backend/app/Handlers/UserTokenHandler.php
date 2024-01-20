<?php


namespace App\Handlers;


use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


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

    public function updateUser($user_id, array $info): User
    {
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

    public function changePassword($user_id, array $info)
    {
        $user = User::findOrFail($user_id);
        if (!$user || !Hash::check($info['old_password'], $user->password)) {
            return null;
        }

        $user->password = Hash::make($info['password']);
        $user->save();


        return $user;
    }


    public function createCustomer(array $info, array $files)
    {
        DB::beginTransaction();
        try {
            $user = $this->createUser($info['name'], $info['phone'], $info['code'], $info['email'], $info['phone']);
            $customer = new Customer();
            $customer->user_id = $user->id;
            $customer->popaddress_id = $info['popaddress_id'] ?? null;
            $customer->services = $info['services'] ?? '';
            $customer->client_type = $info['client_type'];
            $customer->connectivity_type = $info['connectivity_type'];
            $customer->customer_id = $info['customer_id'] ?? '';
            $customer->address = $info['address'] ?? '';
            $customer->technical_contact = $info['technical_contact'] ?? '';
            $customer->management_contact = $info['management_contact'] ?? '';
            $customer->billing_contact_person = $info['billing_contact_person'] ?? '';
            $customer->kam_name = $info['kam_name'] ?? '';
            $customer->selling_price = $info['selling_price'] ?? '';
            $customer->price_details = $info['price_details'] ?? '';
            $customer->nttn = $info['nttn'] ?? '';
            $customer->bw_allocation = $info['bw_allocation'] ?? '';
            $customer->mrtg_details = $info['mrtg_details'] ?? '';
            $customer->allocated_ip = $info['allocated_ip'] ?? '';
            $customer->comment_box = $info['comment_box'] ?? '';
            $customer->router_details = $info['router_details'] ?? '';
            $customer->installation_date = isset($info['installation_date']) ? Carbon::parse($info['installation_date']) : null;
            $customer->first_billing_date = isset($info['first_billing_date']) ? Carbon::parse($info['first_billing_date']) : null;
            foreach ($files as $attr => $file) {
                $name = '/' . $attr . '_' . uniqid() . '.' . $file->extension();
//                $file->store('local', $name);
                Storage::disk('public_uploads')->putFileAs('customers', $file, $name);
                $customer->setAttribute($attr, $name);
            }
            $customer->save();
            $user->assignRole('customer');
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        DB::commit();
        return $customer;
    }

    public function updateCustomer($customer_id, array $info)
    {
        $customer = Customer::findOrFail($customer_id);
        $info['installation_date'] = isset($info['installation_date']) ? Carbon::parse($info['installation_date']) : null;
        $customer->user->update([
            'name' => $info['name']
        ]);
        unset($info['name']);
        $customer->update($info);

        return $customer;
    }

    public function regenerateUserToken(User $user)
    {
//        $user->tokens()->delete();
        $user->token = $user->createToken($user->code . $user->phone)->accessToken;
        return $user;
    }

    public function revokeTokens(User $user)
    {
        $user->tokens()->delete();
    }
}
