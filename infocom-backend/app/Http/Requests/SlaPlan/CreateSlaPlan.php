<?php

namespace App\Http\Requests\SlaPlan;

use Illuminate\Foundation\Http\FormRequest;

class CreateSlaPlan extends FormRequest
{
	public function rules()
	{
		return [
			'name' => 'required',
            'helptopic_id' => 'required|numeric',
			'timelimit' => 'required|nullable|numeric'
		];
	}

	public function authorize()
	{
        $user = $this->user('api');
        return $user!=null && $user->can('crud:department');
	}
}
