<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class CreateLeader extends FormRequest {
	public function rules() {
		return [
			'department_id' => 'required|numeric',
            'leader_id' => 'required|numeric'
		];
	}

	public function authorize() {
		return true;
	}
}
