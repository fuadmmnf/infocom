<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomer extends FormRequest
{
	public function rules()
	{
		return [
		    'name' => 'required',
			'phone' => 'required|unique:users,phone',
			'email' => 'required|unique:users,email',
			'password' => 'required|min:8|confirmed',
		];
	}

	public function authorize()
	{
		return true;
	}
}
