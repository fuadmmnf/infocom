<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'code' => 'required'
        ];
    }

    public function authorize()
    {
        return true;
    }
}
