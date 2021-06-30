<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'phone' => 'required|unique:users,phone|size:11',
            'email' => 'required|unique:users,email|email',
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ];
    }

    public function authorize()
    {
        $user = $this->user('api');
        return $user != null;
    }
}
