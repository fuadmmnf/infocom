<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomer extends FormRequest {
    public function rules() {
        return [
            'popaddress_id' => 'required|numeric',
            'name' => 'required',
            'code' => 'required',
            'password' => 'required|confirmed',
            'technical_contact' => 'present',
            'management_contact' => 'present',
            'connection_package' => 'present',
            'other_services' => 'present',
            'connection_details' => 'present',
            'additional_technical_box' => 'present',
            'billing_information' => 'present',
            'kam_name' => 'present',
        ];
    }

    public function authorize() {
        return true;
    }
}
