<?php

namespace App\Http\Controllers\Api;

use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\CallcenterAgent\CreateCallcenterAgent;
use App\Models\CallcenterAgent;

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
}
