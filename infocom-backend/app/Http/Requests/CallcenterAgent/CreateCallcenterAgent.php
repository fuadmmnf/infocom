<?php

namespace App\Http\Requests\CallcenterAgent;

use Illuminate\Foundation\Http\FormRequest;

class CreateCallcenterAgent extends FormRequest
{
	public function rules()
	{
		return [
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
		];
	}

	public function authorize()
	{
		return true;
	}
}
