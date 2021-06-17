<?php

namespace App\Http\Requests\Complain;

use Illuminate\Foundation\Http\FormRequest;

class CreateComplain extends FormRequest {
    public function rules() {
        $user = $this->user('api');
        return [
                'helptopic_id' => 'required|numeric',
                'complain_text' => 'required',
            ] + (($user == null) ? [
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
            ] :
                [
                    'customer_id' => 'required|numeric',
                    'agent_id' => 'required|numeric',
                    'department_id' => 'sometimes|numeric',
                    'slaplan_id' => 'sometimes|numeric',
                    'ticket_source' => 'required',
                    'complain_summary' => 'sometimes',
//                    'complain_time' => 'sometimes|date',
                    'priority' => 'sometimes'
                ]);
    }

    public function authorize() {
        return true;
    }
}
