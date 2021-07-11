<?php

namespace App\Http\Controllers\Api;

use App\Handlers\SMSHandler;
use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Complain\CreateComplain;
use App\Http\Requests\Complain\DestroyComplain;
use App\Http\Requests\Complain\UpdateComplain;
use App\Http\Requests\Complain\UpdateCustomerFeedback;
use App\Mail\ComplainStatusStaffAlert;
use App\Mail\CustomerComplainAcknowledge;
use App\Mail\CustomerComplainApproval;
use App\Models\CallcenterAgent;
use App\Models\Complain;
use App\Models\Customer;
use App\Models\SupportAgent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ComplainController extends Controller
{
    public function index(Request $request)
    {
        $complainsQuery = Complain::where('status', 'LIKE', '%' . $request->query('status') ?? '' . '%');
        if ($request->query('department_id') !== null) {
            $complainsQuery->where('department_id', $request->query('department_id'));
        }

        if($request->query('status') == 'approved'){
            $complainsQuery->orWhere('status', 'overdue')->whereNotNull('approve_time');
            if ($request->query('customer_code')) {
                $customer = Customer::where('code', $request->query('customer_code'))->firstOrFail();
                $complainsQuery->where('customer_id', $customer->id);
            }

            if ($request->query('start_date') && $request->query('end_date')) {
                $start = Carbon::parse($request->query('start_date'));
                $end = Carbon::parse($request->query('end_date'));
                $complainsQuery->whereBetween('complain_time', [$start, $end]);
            }
        } else if($request->query('status') == 'overdue'){
            $complainsQuery->whereNull('approve_time');
        }

        $complains = $complainsQuery->paginate(20);
        $complains->load('customer', 'customer.user', 'editor', 'editor.user');
        return response()->json($complains);
    }

    public function getComplainByCode($complain_code)
    {
        $complain = Complain::where('code', $complain_code)->firstOrFail();
        return response()->json($complain);
    }

    private function generateCode()
    {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $random_string = substr(str_shuffle(str_repeat($pool, 20)), 0, 20);
        return $random_string;
    }

    public function create(CreateComplain $request)
    {
        $info = $request->validated();
        \DB::beginTransaction();
        try {
            $info['complain_time'] = Carbon::now();
            if (!isset($info['customer_id'])) {
                $user = User::where('email', $info['email'])->where('phone', $info['phone'])->first();

                if (!$user) {
                    $info['password'] = $info['password'] ?? $info['phone'];
                    $userTokenHandler = new UserTokenHandler();

                    $customer = $userTokenHandler->createCustomer($info);
                } else {
                    $customer = $user->customer;
                }
                $info['customer_id'] = $customer->id;
                $info['code'] = $this->generateCode();
                $complain = Complain::create(array_diff_key($info, array_flip(['name', 'phone', 'email', 'password'])));

                if ($complain->status == 'pending') {
                    foreach (CallcenterAgent::with('user')->get() as $callcenteragent) {
                        Mail::to($callcenteragent->user->email)->queue(new ComplainStatusStaffAlert($complain));
                    }
                }


            } else {
                $info['code'] = $this->generateCode();
                $info['status'] = 'assigned'; // as customer_id only set when agent makes the request
                // as customer_id only set when agent makes the request
                $complain = Complain::create($info);
                $complain->load('customer', 'customer.user');
                if ($complain->status == 'assigned') {
                    $complain->assigned_time = Carbon::now();

                    Mail::to($complain->customer->user->email)->queue(new CustomerComplainAcknowledge($complain));
                    $departmentTeam = SupportAgent::where('department_id', $complain->department_id)->with('user')->get();
                    foreach ($departmentTeam as $supportagent) {
                        Mail::to($supportagent->user->email)->queue(new ComplainStatusStaffAlert($complain));
                    }
                }

                $complain->save();
                $message = "Dear " . $complain->customer->user->name . ", we have acknowledged and forwarded your complain/requirement (TT#" . $complain->id . ") to our concern team for investigation. We aim to get back to you with an update at the shortest possible time. Best Regards, CMS Team, INFOCOM Limited";
                SMSHandler::sendSMS($complain->customer->user->phone, $message);
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        DB::commit();
        return response()->json($complain, 201);
    }


    private function handleComplainChange(Complain $before, Complain $after)
    {
        if ($before->status != $after->status) {
            if ($after->status == 'assigned') {
                $after->assigned_time = Carbon::now();
                Mail::to($after->customer->user->email)->queue(new CustomerComplainAcknowledge($after));

                $departmentTeam = SupportAgent::where('department_id', $after->department_id)->with('user')->get();
                foreach ($departmentTeam as $supportagent) {
                    Mail::to($supportagent->user->email)->queue(new ComplainStatusStaffAlert($after));
                }

                $message = "Dear " . $after->customer->user->name . ", we have acknowledged and forwarded your complain/requirement (TT#" . $after->id . ") to our concern team for investigation. We aim to get back to you with an update at the shortest possible time. Best Regards, CMS Team, INFOCOM Limited";
                SMSHandler::sendSMS($after->customer->user->phone, $message);

            } else if ($after->status == 'finished') {
                $after->finished_time = Carbon::now();
                foreach (CallcenterAgent::with('user')->get() as $callcenteragent) {
                    Mail::to($callcenteragent->user->email)->queue(new ComplainStatusStaffAlert($after));
                }
            } else if ($after->status == 'approved') {
                $after->approved_time = Carbon::now();
                $message = "Dear " . $after->customer->user->name . ", this SMS is to notify you that we believe this ticket (TT#" . $after->id . ")  has been resolved. Best Regards, CMS Team, INFOCOM Limited";
                SMSHandler::sendSMS($after->customer->user->phone, $message);
                Mail::to($after->customer->user->email)->queue(new CustomerComplainApproval($after));
            }
            $after->save();
        } else if ($before->status == $after->status && $before->editor_id != $after->editor_id) {
            Mail::to($after->editor->user->email)->queue(new ComplainStatusStaffAlert($after));
        }


    }

    public function update(UpdateComplain $request)
    {
        $complain = Complain::where('id', $request->route('complain_id'))->where('status', '!=', 'overdue')->firstOrFail();
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

    public function storeFeedback(UpdateCustomerFeedback $request)
    {
        $complain = Complain::where('code', $request->route('complain_code'))->firstOrFail();
        $complain->update($request->validated());
        return response()->noContent();
    }

    public function destroy(DestroyComplain $request)
    {
        $complain = Complain::findOrFail($request->route('complain_id'));
        if ($complain->status != 'pending') {
            throw new \Exception('Cannot delete processed complain');
        }

        $complain->delete();
        return response()->noContent();
    }
}
