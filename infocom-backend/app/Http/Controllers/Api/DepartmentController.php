<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\CreateDepartment;
use App\Http\Requests\Department\CreateLeader;
use App\Models\Department;
use App\Models\DepartmentLeaders;

class DepartmentController extends Controller {
    public function index() {
        $departments = Department::all();
        $departments->load('leader', 'leader.user');
        return response()->json($departments);
    }

    public function create(CreateDepartment $request) {
        $department = Department::create($request->validated());
        return response()->json($department, 201);
    }

    public function createLeader(CreateLeader $request) {
        $info = $request->validated();
        $departmentLeader = DepartmentLeaders::updateOrCreate(
            ['department_id' => $info['department_id']],
            ['leader_id' => $info['leader_id']]
        );
        return response()->json($departmentLeader, 201);
    }
}
