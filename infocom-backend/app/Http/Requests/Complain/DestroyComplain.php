<?php

namespace App\Http\Requests\Complain;

use Illuminate\Foundation\Http\FormRequest;

class DestroyComplain extends FormRequest {
	public function rules() {
		return [

		];
	}

	public function authorize() {
		return true;
	}
}
