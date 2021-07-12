<?php

namespace App\Http\Requests\Complain;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplain extends FormRequest
{
    public function rules()
    {
        $user = $this->user('api');
        return [
                'helptopic_id' => 'required|numeric',
                'complain_text' => 'required',
                'customer_id' => 'required|numeric',
            ] + (($user->can('crud:callcenter')) ? [
                'agent_id' => 'required|numeric',
                'department_id' => 'required|numeric',
                'slaplan_id' => 'required|numeric',
                'ticket_source' => 'required',
                'complain_summary' => 'sometimes',
//                    'complain_time' => 'sometimes|date',
                'priority' => 'sometimes'
            ] : []);
    }

    public function authorize()
    {
        $user = $this->user('api');
        return $user != null;
    }
}
