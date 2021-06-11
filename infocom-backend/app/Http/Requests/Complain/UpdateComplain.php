<?php

namespace App\Http\Requests\Complain;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComplain extends FormRequest {
	public function rules() {
		return [
            'helptopic_id' => 'sometimes|numeric',
            'department_id' => 'sometimes|numeric',
            'editor_id' => 'sometimes|numeric',
            'slaplan_id' => 'sometimes|numeric',
            'status' => 'sometimes',
            'complain_text' => 'sometimes',
            'complain_summary' => 'sometimes',
            'complain_feedback' => 'sometimes',
            'priority' => 'sometimes',
            'complain_time' => 'sometimes|date',
		];
	}

	public function authorize() {
		return true;
	}
}