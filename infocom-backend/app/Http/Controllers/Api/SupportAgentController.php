<?php

namespace App\Http\Controllers\Api;

use App\Handlers\UserTokenHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\SupportAgent\CreateSupportAgent;
use App\Http\Requests\SupportAgent\UpdateSupportAgentRequest;
use App\Models\Department;
use App\Models\SupportAgent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SupportAgentController extends Controller
{
    public function index()
    {
        $supportAgents = SupportAgent::orderByDesc('created_at')->get();
        $supportAgents->load('user', 'department');
        return response()->json($supportAgents);
    }

    public function create(CreateSupportAgent $request)
    {
        $info = $request->validated();
        $department = Department::findOrFail($info['department_id']);

        $userTokenHandler = new UserTokenHandler();
        $user = $userTokenHandler->createUser($info['name'], $info['phone'], $info['code'], $info['email'], $info['password']);
        $supportagent = new SupportAgent();
        $supportagent->user_id = $user->id;
        $supportagent->department_id = $department->id;
        $supportagent->save();
        $user->assignRole('support_agent');

        return response()->json($supportagent, 201);
    }

    public function update(UpdateSupportAgentRequest $request)
    {
        $supportagent = SupportAgent::findOrFail($request->route('supportagent_id'));

        $info = $request->validated();
        $userTokenHandler = new UserTokenHandler();
        $userTokenHandler->updateUser($supportagent->user_id, $info);

        $supportagent->update([
            'department_id' => $info['department_id']
        ]);

        return response()->noContent();
    }

    public function destroy(Request $request){
        $supportagent = SupportAgent::findOrFail($request->route('supportagent_id'));
        $user = $supportagent->user;
        $user->delete();

        return response()->noContent();
    }
}
