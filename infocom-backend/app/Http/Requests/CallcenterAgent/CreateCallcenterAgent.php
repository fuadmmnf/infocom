<?php

namespace App\Http\Requests\CallcenterAgent;

use Illuminate\Foundation\Http\FormRequest;

class CreateCallcenterAgent extends FormRequest
{
	public function rules()
	{
		return [
            'code' => 'required|unique:users,code',
            'name' => 'required',
            'phone' => 'required|unique:users,phone|size:11',
            'email' => 'required|email',
            'password' => 'required|confirmed',
		];
	}

	public function authorize()
	{
	    $user = $this->user('api');
		return $user!=null && $user->can('crud:callcenter_agent');
	}
}
