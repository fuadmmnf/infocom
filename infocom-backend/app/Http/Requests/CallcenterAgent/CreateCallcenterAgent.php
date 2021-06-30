<?php

namespace App\Http\Requests\CallcenterAgent;

use Illuminate\Foundation\Http\FormRequest;

class CreateCallcenterAgent extends FormRequest
{
	public function rules()
	{
		return [
            'name' => 'required',
            'phone' => 'required|unique:users,phone|size:11',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|confirmed',
		];
	}

	public function authorize()
	{
	    $user = $this->user('api');
		return $user!=null && $user->can('crud:callcenter_agent');
	}
}
