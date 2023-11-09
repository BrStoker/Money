<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{

    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:20', 'regex:/^[\p{L}]+$/u'],
            'last_name' => ['required', 'string', 'max:20', 'regex:/^[\p{L}]+$/u'],
            'email' => "required|unique:users",
            'phone' => ['required', 'unique:users', 'regex:/^(?:\+7|7|8)?\s?\(?\d{3}\)?(?:\s?|-)?\d{3}(?:-)?\d{2}(?:-)?\d{2}$/'],
            'password' => 'required|regex:/^.*(?=.{3,})(?=.*[a-zа-яё])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'password_confirm' => 'required|same:password'
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => mb_strtoupper(__('main.first_name')),
            'last_name' => mb_strtoupper(__('main.last_name')),
            'email' => mb_strtoupper(__('main.email')),
            'phone' => mb_strtoupper(__('main.phone')),
            'password' => mb_strtoupper(__('main.password')),
            'password_confirm' => mb_strtoupper(__('main.password')),
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => __('main.required_field'),
            'email.required' => __('main.required_field'),
            'phone.required' => __('main.required_field'),
            'email.unique' => __('error.unique_email'),
            'phone.unique' => __('error.unique_phone'),
            'password.required' => __('main.required_field'),
            'password_confirm.required' => __('main.required_field'),

            'first_name.max' => 'Поле не должно содержать более 20 символов',
            'last_name.max' => 'Поле не должно содержать более 20 символов',
            'first_name.regex' => 'Допускаются только буквы',
            'last_name.regex' => 'Допускаются только буквы',
            'email.regex' => 'Указан не корректный Email',
            'phone.numeric' => 'Укажите корректный номер телефона',
            'phone.regex' => 'Формат ввода номера телефона неверен',
            'password.regex' => 'Пароль должен состоять из букв и цифр и содержать как минимум 1 заглавную букву',
            'password_confirm.same' => 'Пароли не совпадают'
        ];
    }

}
