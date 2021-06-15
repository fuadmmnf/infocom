<?php

namespace App\Http\Requests\SupportAgent;

use Illuminate\Foundation\Http\FormRequest;

class CreateSupportAgent extends FormRequest {
    public function rules() {
        return [
            'department_id' => 'required|numeric',
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed',
        ];
    }

    public function authorize() {
        $user = $this->user('api');
        return $user!=null && $user->can('crud:support_agent');
    }
}
