<?php

namespace App\Http\Requests\HelpTopic;

use Illuminate\Foundation\Http\FormRequest;

class CreateHelpTopic extends FormRequest
{
	public function rules()
	{
		return [
			'name' => 'required|unique:help_topics,name'
		];
	}

	public function authorize()
	{
        $user = $this->user('api');
        return $user!=null && $user->can('crud:department');
	}
}
