<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {
	public function rules() {
		return [
			'code' => 'required',
            'password' => 'required'
		];
	}

	public function authorize() {
		return true;
	}
}
