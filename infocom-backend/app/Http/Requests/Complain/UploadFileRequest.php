<?php

namespace App\Http\Requests\Complain;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customer_file' => 'sometimes|nullable|file',
            'feedback_file' => 'sometimes|nullable|file'
        ];
    }

    public function authorize()
    {
        $user = $this->user('api');
        return $user != null;
    }
}
