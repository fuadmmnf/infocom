<?php

namespace App\Http\Controllers\Api;

use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateCustomer;
use App\Http\Requests\Customer\UpdateCustomer;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::with('user')->orderByDesc('created_at');

        $query = $request->query('query');
        if ($query) {
            $customers->where('code', $query)
                ->whereHas('user', function ($q) use ($query) {
                    $q->orWhere('phone', $query)->orWhere('email', $query);
                })
                ->orWhere('address', 'like', '%' . strtolower($query) . '%');
        }

        $customers = $customers->paginate(20);
        $customers->load('popaddress');
        return response()->json($customers);
    }

    public function getAllCustomerCode()
    {
        $customerCodes = Customer::where('code', '!=', '')->pluck('code');
        return response()->json($customerCodes);
    }


    public function create(CreateCustomer $request)
    {
        $userTokenHandler = new UserTokenHandler();
        $customer = $userTokenHandler->createCustomer($request->validated());
        return response()->json($customer, 201);
    }

    public function update(UpdateCustomer $request)
    {
        $userTokenHandler = new UserTokenHandler();
        $userTokenHandler->updateCustomer($request->route('customer_id'), $request->validated());
        return response()->noContent();
    }
}
