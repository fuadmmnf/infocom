<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomer extends FormRequest {
    public function rules() {
        return [
            'popaddress_id' => 'required|numeric',
            'name' => 'required',
            'phone' => 'required|unique:users,phone|size:11',
            'email' => 'required|unique:users,email',
            'code' => 'present',
//            'password' => 'required|confirmed',
            'technical_contact' => 'present',
            'management_contact' => 'present',
            'connection_package' => 'present',
            'other_services' => 'present',
            'connection_details' => 'present',
            'additional_technical_box' => 'present',
            'billing_information' => 'present',
            'kam_name' => 'present',
            'installation_date' => 'nullable|date',
        ];
    }

    public function authorize() {
        $user = $this->user('api');
        return $user!=null && $user->can('crud:complain');
    }
}
