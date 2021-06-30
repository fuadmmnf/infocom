<?php

namespace App\Http\Controllers\Api;

use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\CallcenterAgent\CreateCallcenterAgent;
use App\Http\Requests\CallcenterAgent\UpdateAgentRequest;
use App\Models\CallcenterAgent;
use Illuminate\Http\Request;

class CallcenterAgentController extends Controller {
    public function index() {
        $callcenterAgents = CallcenterAgent::orderByDesc('created_at')->with('user')->get();
        return response()->json($callcenterAgents);
    }

    public function create(CreateCallcenterAgent $request) {
        $info = $request->validated();

        $userTokenHandler = new UserTokenHandler();
        $user = $userTokenHandler->createUser($info['name'], $info['phone'], $info['email'], $info['password']);
        $callcenterAgent = new CallcenterAgent();
        $callcenterAgent->user_id = $user->id;
        $callcenterAgent->save();
        $user->assignRole('callcenter_agent');

        return response()->json($callcenterAgent, 201);

    }

    public function update(UpdateAgentRequest $request){
        $callcenteragent = CallcenterAgent::findOrFail($request->route('callcenteragent_id'));
        $userTokenHandler = new UserTokenHandler();
        $userTokenHandler->updateUser($callcenteragent->user_id, $request->validated());
        return response()->noContent();
    }

    public function destroy(Request $request){
        $callcenteragent = CallcenterAgent::findOrFail($request->route('callcenteragent_id'));
        $user = $callcenteragent->user;
        $user->delete();

        return response()->noContent();
    }
}
