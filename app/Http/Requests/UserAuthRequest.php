<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAuthRequest extends FormRequest
{

    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {
        return [
            'email' => 'required',
            'password' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'email' => __('main.email'),
            'password' => __('main.password')
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('main.required_field'),
            'password.required' => __('main.required_field')
        ];
    }

}
