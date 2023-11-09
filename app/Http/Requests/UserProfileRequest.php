<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserProfileRequest extends FormRequest
{

    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {

        $rules = [
            'first_name' => ['required', 'string', 'max:20', 'regex:/^[\p{L}]+$/u'],
            'last_name' => ['required', 'string', 'max:20', 'regex:/^[\p{L}]+$/u'],
            'fields.signature' => 'nullable|string',
            'fields.description' => 'nullable|string',
            'fields.nickname' => 'nullable|string',
            'fields.telegram' => 'nullable|string',
            'fields.vkontakte' => 'nullable|url',
            'fields.instagram' => 'nullable|url',
            'fields.youtube' => 'nullable|url',
            'fields.yandex_dzen' => 'nullable|url',
            'phone' => 'nullable|string',
            'email' => 'required|email',
            'newpassword' => 'sometimes|nullable|regex:/^.*(?=.{3,})(?=.*[a-zа-яё])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'repeat_newpassword' => 'sometimes|required_with:newpassword|same:newpassword'
        ];

        return $rules;


    }

    public function attributes()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Введите имя',
            'first_name.regex' => 'Имя может состоять только из букв',
            'first_name.max' => 'Максимальное количество символов в имени 20',
            'last_name.required' => 'Введите фамилию',
            'last_name.regex' => 'Фамилия может состоять только из букв',
            'last_name.max' => 'Максимальное количество символов в фамилии 20',
            'email.required' => 'Введите адрес электронной почты',
            'newpassword.regex' => 'Пароль должен состоять из букв и цифр и содержать как минимум 1 заглавную букву',
            'repeat_newpassword.same' => 'Повторный пароль должен совпадать с новым паролем',

        ];
    }

}
