<?php

namespace App\Http\Controllers\Api;

use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Complain\CreateComplain;
use App\Http\Requests\Complain\DestroyComplain;
use App\Http\Requests\Complain\UpdateComplain;
use App\Models\Complain;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComplainController extends Controller {
    public function index(Request $request) {
        $complainsQuery = DB::table('complains')->where('status', 'LIKE', '%' . $request->query('status') ?? '' . '%');
        if ($request->query('department_id') !== null) {
            $complainsQuery->where('department_id', $request->query('department_id'));
        }

        $complains = $complainsQuery->paginate(20);
        return response()->json($complains);
    }

    public function create(CreateComplain $request) {
        $info = $request->validated();

        \DB::beginTransaction();
        try {
            $customer = User::where('email', $info['email'])->where('phone', $info['phone'])->firstOrFail();
            if (!$customer) {
                $info['password'] = $info['password'] ?? $info['phone'];
                $userTokenHandler = new UserTokenHandler();
                $customer = $userTokenHandler->createCustomer($request->validated());
            }
            $info['customer_id'] = $customer->id;
            $complain = Complain::create($info);
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        DB::commit();
        return response()->json($complain, 201);
    }

    public function update(UpdateComplain $request) {
        $complain = Complain::findOrFail($request->route('complain_id'));

        $info = array_filter($request->validated(), function ($val, $key) use ($complain) {
            return !is_null($val) && $val !== '' && $complain[$key] !== $val;
        }, ARRAY_FILTER_USE_BOTH);

        $complain->update($info);

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
