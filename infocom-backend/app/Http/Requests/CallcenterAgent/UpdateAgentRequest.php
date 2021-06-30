<?php

namespace App\Http\Requests\CallcenterAgent;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAgentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required|size:11',
            'email' => 'required|email',
        ];
    }

    public function authorize()
    {
        $user = $this->user('api');
        return $user!=null && $user->can('crud:callcenter_agent');
    }
}
