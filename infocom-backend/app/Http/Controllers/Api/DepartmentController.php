<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\CreateDepartment;
use App\Http\Requests\Department\UpdateDepartment;
use App\Models\Department;

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

    public function update(UpdateDepartment $request) {
        $info = $request->validated();
        $department = Department::findOrFail($request->route('department_id'));
        $department->update($info);
        return response()->noContent();
    }
}
