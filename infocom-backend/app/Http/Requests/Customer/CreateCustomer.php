<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomer extends FormRequest {
    public function rules() {
        return [
            'popaddress_id' => 'present',
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'present|unique:users,email',
            'services' => 'present|array',
            'client_type' => 'present',
            'connectivity_type' => 'present',
            'customer_id' => 'present',
            'address' => 'present',
            'technical_contact' => 'present',
            'management_contact' => 'present',
            'billing_contact_person' => 'present',
            'kam_name' => 'present',
            'selling_price' => 'present',
            'price_details' => 'present',
            'password' => 'sometimes|confirmed',
            'nttn' => 'present',
            'bw_allocation' => 'present',
            'mrtg_details' => 'present',
            'allocated_ip' => 'present',
            'comment_box' => 'present',
            'router_details' => 'present|nullable',
            'installation_date' => 'present',
            'first_billing_date' => 'present',
        ];
    }

    public function authorize() {
        $user = $this->user('api');
        return $user!=null && $user->can('crud:complain');
    }
}
