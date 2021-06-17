<?php

namespace App\Http\Requests\Complain;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerFeedback extends FormRequest
{
    public function rules()
    {
        return [
            'customer_rating' => 'required|nullable|integer',
            'customer_review' => 'required|nullable',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
