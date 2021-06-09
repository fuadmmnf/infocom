<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\CreateDepartment;
use App\Models\Department;

class DepartmentController extends Controller
{
	public function index()
	{
	    $departments = Department::all();
		return response()->json($departments);
	}

	public function create(CreateDepartment $request){
	    $department = Department::create($request->validated());
	    return response()->json($department, 201);
    }
}
