<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class CreateDepartment extends FormRequest
{
	public function rules()
	{
		return [
			'name' => 'required|unique:departments,name'
		];
	}

	public function authorize()
	{
        $user = $this->user('api');
        return $user!=null && $user->can('crud:department');
	}
}
