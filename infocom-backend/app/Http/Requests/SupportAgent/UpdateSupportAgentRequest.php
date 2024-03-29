<?php

namespace App\Http\Requests\SupportAgent;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupportAgentRequest extends FormRequest
{
    public function rules() {
        return [
            'department_id' => 'required|numeric',
            'name' => 'required',
            'phone' => 'required|size:11',
            'email' => 'required|email',
        ];
    }

    public function authorize() {
        $user = $this->user('api');
        return $user!=null && $user->can('crud:support_agent');
    }
}
