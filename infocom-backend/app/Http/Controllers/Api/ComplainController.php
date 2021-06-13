<?php

namespace App\Http\Controllers\Api;

use App\Handlers\SMSHandler;
use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Complain\CreateComplain;
use App\Http\Requests\Complain\DestroyComplain;
use App\Http\Requests\Complain\UpdateComplain;
use App\Mail\ComplainStatusStaffAlert;
use App\Mail\CustomerComplainApproval;
use App\Models\CallcenterAgent;
use App\Models\Complain;
use App\Models\SupportAgent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ComplainController extends Controller {
    public function index(Request $request) {
        $complainsQuery = Complain::where('status', 'LIKE', '%' . $request->query('status') ?? '' . '%');
        if ($request->query('department_id') !== null) {
            $complainsQuery->where('department_id', $request->query('department_id'));
        }
        $complains = $complainsQuery->paginate(20);
        $complains->load('customer', 'customer.user', 'editor', 'editor.user');
        return response()->json($complains);
    }

    private function generateCode() {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $random_string = substr(str_shuffle(str_repeat($pool, 20)), 0, 20);
        return $random_string;
    }

    public function create(CreateComplain $request) {
        $info = $request->validated();

        \DB::beginTransaction();
        try {
            $info['code'] = $this->generateCode();
            if (!isset($info['customer_id'])) {
                $customer = User::where('email', $info['email'])->where('phone', $info['phone'])->first();
                if (!$customer) {
                    $info['password'] = $info['password'] ?? $info['phone'];
                    $userTokenHandler = new UserTokenHandler();
                    $customer = $userTokenHandler->createCustomer($info);
                }
                $info['customer_id'] = $customer->id;
                $complain = Complain::create(array_diff_key($info, array_flip(['name', 'phone', 'email', 'password'])));

                foreach (CallcenterAgent::with('user')->get() as $callcenteragent) {
                    Mail::to($callcenteragent->user->email)->send(new ComplainStatusStaffAlert($complain));
                }
            } else {
                $complain = Complain::create($info);
            }


        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        DB::commit();
        return response()->json($complain, 201);
    }


    private function handleComplainChange(Complain $before, Complain $after) {
        if ($before->status != $after->status) {
            if ($after->status == 'assigned') {
                $departmentTeam = SupportAgent::where('department_id', $after->department_id)->with('user')->get();
                foreach ($departmentTeam as $supportagent) {
                    Mail::to($supportagent->user->email)->send(new ComplainStatusStaffAlert($after));
                }
            } else if ($after->status == 'finished') {
                foreach (CallcenterAgent::with('user')->get() as $callcenteragent) {
                    Mail::to($callcenteragent->user->email)->send(new ComplainStatusStaffAlert($after));
                }
            } else if ($after->status == 'approved') {
                $message = '';
                SMSHandler::sendSMS($after->customer->user->phone, $message);
                Mail::to($after->customer->user->email)->send(new CustomerComplainApproval($after));
            }
        }

    }

    public function update(UpdateComplain $request) {
        $complain = Complain::findOrFail($request->route('complain_id'));
        $complain->load('customer', 'customer.user');

        \DB::beginTransaction();
        try {
            $info = array_filter($request->validated(), function ($val, $key) use ($complain) {
                return !is_null($val) && $val !== '' && $complain[$key] !== $val;
            }, ARRAY_FILTER_USE_BOTH); //safety check update of blank fields to tackle unexpected behavior
            $complainBefore = $complain->replicate();
            $complain->update($info);
            $this->handleComplainChange($complainBefore, $complain);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        DB::commit();
        return response()->noContent();
    }

    public function destroy(DestroyComplain $request) {
        $complain = Complain::findOrFail($request->route('complain_id'));
        if ($complain->status != 'pending') {
            throw new \Exception('Cannot delete processed complain');
        }

        $complain->delete();
        return response()->noContent();
    }
}
