<?php

namespace App\Http\Controllers\Api;

use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateCustomer;
use App\Http\Requests\Customer\UpdateCustomer;
use App\Models\Customer;

class CustomerController extends Controller {
    public function index() {
        $customers = Customer::orderByDesc('created_at')->with('user')->paginate(20);
        return response()->json($customers);
    }


    public function create(CreateCustomer $request) {
        $userTokenHandler = new UserTokenHandler();
        $customer = $userTokenHandler->createCustomer($request->validated());
        return response()->json($customer, 201);
    }

    public function update(UpdateCustomer $request) {
        $userTokenHandler = new UserTokenHandler();
        $userTokenHandler->updateCustomer($request->route('customer_id'), $request->validated());
        return response()->noContent();
    }
}
