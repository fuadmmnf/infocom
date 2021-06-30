<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
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
