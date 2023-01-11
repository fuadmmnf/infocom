<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomer extends FormRequest {
    public function rules() {
        return [
            'popaddress_id' => 'present',
            'name' => 'required',
            'phone' => 'required|unique:users,phone|size:11',
            'email' => 'required|unique:users,email',
            'code' => 'present',
            'division' => 'required',
            'district' => 'required',
            'thana' => 'required',
            'address' => 'required',
//            'password' => 'required|confirmed',
            'services' => 'array',
            'technical_contact' => 'present',
            'management_contact' => 'present',
            'connection_package' => 'present',
            'other_services' => 'present',
            'connection_details' => 'present',
            'additional_technical_box' => 'present',
            'billing_information' => 'present',
            'kam_name' => 'present',
            'installation_date' => 'nullable|date',
            'client_type' => 'present',
            'connection_type' => 'present',
            'bandwidth_distribution_point' => 'present',
            'connectivity_type' => 'present',
            'bandwidth_allocation' => 'present',
            'allocated_ip' => 'present',
            'selling_price_bdt_excluding_vat' => 'present',
        ];
    }

    public function authorize() {
        $user = $this->user('api');
        return $user!=null && $user->can('crud:complain');
    }
}
