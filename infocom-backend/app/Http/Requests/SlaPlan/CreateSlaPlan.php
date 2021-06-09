<?php

namespace App\Http\Requests\SlaPlan;

use Illuminate\Foundation\Http\FormRequest;

class CreateSlaPlan extends FormRequest
{
	public function rules()
	{
		return [
			'name' => 'required|unique:sla_plans,name'
		];
	}

	public function authorize()
	{
		return true;
	}
}
