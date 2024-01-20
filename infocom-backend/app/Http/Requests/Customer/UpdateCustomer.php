<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomer extends FormRequest {
    public function rules() {
        return [
            'popaddress_id' => 'sometimes',
            'name' => 'sometimes',
            'services' => 'sometimes|array',
            'client_type' => 'sometimes',
            'connectivity_type' => 'sometimes',
            'customer_id' => 'sometimes',
            'address' => 'sometimes',
            'technical_contact' => 'sometimes',
            'management_contact' => 'sometimes',
            'billing_contact_person' => 'sometimes',
            'kam_name' => 'sometimes',
            'selling_price' => 'sometimes',
            'price_details' => 'sometimes',
//            'password' => 'required|confirmed',
            'nttn' => 'sometimes',
            'bw_allocation' => 'sometimes',
            'mrtg_details' => 'sometimes',
            'allocated_ip' => 'sometimes',
            'comment_box' => 'sometimes',
            'router_details' => 'sometimes|nullable',
            'installation_date' => 'sometimes',
            'first_billing_date' => 'sometimes',
            'additional_file' => 'sometimes',
        ];
    }

    public function authorize() {
        $user = $this->user('api');
        return $user!=null && $user->can('crud:complain');
    }
}
