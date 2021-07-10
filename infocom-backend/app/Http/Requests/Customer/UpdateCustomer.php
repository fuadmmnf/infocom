<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomer extends FormRequest {
    public function rules() {
        return [
            'popaddress_id' => 'present',
            'name' => 'required',
            'code' => 'present|nullable',
//            'password' => 'required|confirmed',
            'services' => 'array',
            'address' => 'present|nullable',
            'technical_contact' => 'present|nullable',
            'management_contact' => 'present|nullable',
            'connection_package' => 'present|nullable',
            'other_services' => 'present|nullable',
            'connection_details' => 'present|nullable',
            'additional_technical_box' => 'present|nullable',
            'billing_information' => 'present|nullable',
            'kam_name' => 'present|nullable',
            'installation_date' => 'nullable|date',
        ];
    }

    public function authorize() {
        $user = $this->user('api');
        return $user!=null && $user->can('crud:complain');
    }
}
