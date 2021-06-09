<?php

namespace App\Http\Requests\HelpTopic;

use Illuminate\Foundation\Http\FormRequest;

class CreateHelpTopic extends FormRequest
{
	public function rules()
	{
		return [
			'name' => 'required'
		];
	}

	public function authorize()
	{
		return true;
	}
}
