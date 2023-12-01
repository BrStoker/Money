<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoursesRequest extends FormRequest
{
    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'detail_text' => 'required|string',
            'description' => 'nullable|string'
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
            'title.required' => 'Поле обязательно к заполнению.',
            'title.string' => 'Поле обязательно к заполнению.',
            'title.max' => 'Максимальная длина заголовка - 255 символов.',
            'preview.required' => 'Поле обязательно к заполнению.',
            'detail_text.required' => 'Поле обязательно к заполнению.',
        ];
    }
}
