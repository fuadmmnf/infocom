<?php

namespace App\Http\Requests\Complain;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplain extends FormRequest {
    public function rules() {
        return [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'helptopic_id' => 'required|numeric',
            'department_id' => 'sometimes|numeric',
            'slaplan_id' => 'sometimes|numeric',
            'complain_text' => 'required',
            'complain_summary' => 'sometimes',
            'complain_time' => 'sometimes|date',
            'priority' => 'sometimes'
        ];
    }

    public function authorize() {
        return true;
    }
}
