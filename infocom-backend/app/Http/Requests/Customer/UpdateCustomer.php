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
            'technical_contact' => 'present|nullable',
            'management_contact' => 'present|nullable',
            'connection_package' => 'present|nullable',
            'other_services' => 'present|nullable',
            'connection_details' => 'present|nullable',
            'additional_technical_box' => 'present|nullable',
            'billing_information' => 'present|nullable',
            'kam_name' => 'present|nullable',
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
