<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStatisticRequest extends FormRequest
{

    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {
        return [
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
