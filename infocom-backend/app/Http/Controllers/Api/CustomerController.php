<?php

namespace App\Http\Controllers\Api;

use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateCustomer;
use App\Models\Customer;

class CustomerController extends Controller {
    public function index() {

    }

    public function create(CreateCustomer $request) {
        $info = $request->validated();
        $userTokenHandler = new UserTokenHandler();
        $user = $userTokenHandler->createUser($info['name'], $info['phone'], $info['email'], $info['password']);
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->save();
        $user->assignRole('customer');

        return response()->json($customer, 201);
    }
}
