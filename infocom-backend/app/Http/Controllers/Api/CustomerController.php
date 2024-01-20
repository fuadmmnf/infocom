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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $customers->where('customer_id', 'like', '%'. $query . '%')
                ->orWhereHas('user', function ($q) use ($query) {
                    $q->where('name', 'like', '%'. $query . '%')->orWhere('phone', 'like', '%'. $query . '%');
                });
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

    public function getNoticeHistory()
    {

    }

    public function getAllCustomerIds()
    {
        $customerCodes = Customer::where('customer_id', '!=', '')->get(['id', 'customer_id'])->toArray();
        return response()->json($customerCodes);
    }

    public function searchByCustomerId($customer_id)
    {
        $customer = Customer::where('customer_id', $customer_id)->firstOrFail();
        $customer->load('user', 'popaddress');

        return response()->json($customer);
    }

    public function create(CreateCustomer $request)
    {
        $userTokenHandler = new UserTokenHandler();
        $customer = $userTokenHandler->createCustomer($request->validated());
        return response()->json($customer, 201);
    }

    public function saveFile(UpdateCustomer $request)
    {
        $userTokenHandler = new UserTokenHandler();
        $userTokenHandler->updateCustomerFile($request->route('customer_id'), ['additional_file' => $request->file('additional_file')]);
        return response()->noContent();
    }

    public function update(UpdateCustomer $request)
    {
        $userTokenHandler = new UserTokenHandler();
        $userTokenHandler->updateCustomer($request->route('customer_id'), $request->validated());
        return response()->noContent();
    }


    public function sendNotice(SendCustomerMessage $request)
    {
        $info = $request->validated();
        $customers = null;
        if ($info['type'] == 'popaddress') {
            $customers = Customer::where('popaddress_id', $info['type_id'])->get();
        } elseif ($info['type'] == 'service') {
            $customers = Customer::whereJsonContains('services', $info['type_id'])->get();
        } elseif ($info['type'] == 'individual') {
            $customers = Customer::where('id', $info['type_id'])->get();
        } else if ($info['type'] == 'all') {
            $customers = Customer::all();
        }
        $customers->load('user');

        DB::beginTransaction();
        try {
            foreach ($customers as $customer) {

                SendSMSJob::dispatch(['receiver' => $customer->user->phone, 'message' => $info['message']]);
                Mail::to($customer->user->email)->queue(new CustomerCustomMessage($info['message']));
            }
            CustomerMessage::create([
                'type' => $info['type'],
                'customers' => $customers->pluck('id'),
                'message' => $info['message']
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        DB::commit();
        return response()->noContent();
    }
}
