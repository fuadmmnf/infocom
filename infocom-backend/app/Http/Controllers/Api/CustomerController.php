<?php

namespace App\Http\Controllers\Api;

use App\Handlers\SMSHandler;
use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateCustomer;
use App\Http\Requests\Customer\SendCustomerMessage;
use App\Http\Requests\Customer\UpdateCustomer;
use App\Jobs\SendSMSJob;
use App\Mail\CustomerCustomMessage;
use App\Models\Customer;
use App\Models\CustomerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::with('user')->orderByDesc('created_at');
        $query = $request->query('query');
        $queryService = $request->query('service');

        if ($queryService && $queryService != '') {
            $customers->whereJsonContains('services', intval($queryService));
        }

        if ($query) {
            $customers->where('code', $query)
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('phone', $query)->orWhere('email', $query);
                })
                ->orWhere('address', 'like', '%' . strtolower($query) . '%');
        }


        $customers = $customers->paginate(20);
        $customers->load('popaddress');
        return response()->json($customers);
    }

    public function find($customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        $customer->load('user', 'popaddress');

        return response()->json($customer);
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


    public function sendSMS(SendCustomerMessage $request)
    {
        $info = $request->validated();
        $customers = null;
        if ($info['type'] == 'popaddress') {
            $customers = Customer::where('popaddress_id', $info['type_id']);
        } elseif ($info['type'] == 'service') {
            $customers = Customer::whereJsonContains('services', $info['type_id']);
        } elseif ($info['type'] == 'individual') {
            $customers = Customer::find($info['type_id']);
        } else if ($info['type'] == 'total') {
            $customers = Customer::all();
        }

        $customers = $customers->with('user')->get('id', 'user.phone', 'user.email')->toArray();
        foreach ($customers as $customer) {
            $customermessage = CustomerMessage::create([
                'type' => $info['type'],
                'customers' => array_column($customers, 'id'),
                'message' => $info['message']
            ]);
            SendSMSJob::dispatch(['receiver' => $customer['phone'], 'message' => $info['message']]);
            Mail::to($customer['email'])->queue(new CustomerCustomMessage($customermessage));
        }

        return response()->noContent();
    }
}
