<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest {
	public function rules() {
		return [
			'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
		];
	}

	public function authorize() {
		return true;
	}
}
