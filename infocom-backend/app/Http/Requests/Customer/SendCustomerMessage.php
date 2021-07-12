<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class SendCustomerMessage extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'type_id' => 'required|numeric',
            'message' => 'required'
        ];
    }

    public function authorize()
    {
        $user = $this->user('api');
        return $user != null && $user->can('crud:customer');
    }
}
