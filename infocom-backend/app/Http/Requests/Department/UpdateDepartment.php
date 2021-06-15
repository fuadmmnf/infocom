<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartment extends FormRequest {
	public function rules() {
		return [
            'leader_id' => 'required|numeric'
		];
	}

	public function authorize() {
        $user = $this->user('api');
        return $user!=null && $user->can('crud:department');
	}
}
