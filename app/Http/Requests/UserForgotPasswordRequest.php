<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserForgotPasswordRequest extends FormRequest
{

    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {
        return [
            'recovery_phone' => ''
        ];
    }

    public function attributes()
    {
        return [

        ];
    }

    public function messages()
    {
        return [
        ];
    }

}
