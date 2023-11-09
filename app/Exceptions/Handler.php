<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Translation\Translator;
use Illuminate\Translation\ArrayLoader;
use Symfony\Component\Translation\MessageSelector;
use Illuminate\Support\Facades\Validator as FacadeValidator;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }



    // $rules = array(
    //     'name' => ['regex:/^[A-Za-z]+\s[A-Za-z]+$/u'],
    //     'name_wrong' => ['regex:/^[A-Za-z]+\s[A-Za-z]+$/u'],
    //     'login' => ['required', 'alpha_num'],
    //     'login_wrong' => ['required', 'alpha_num'],
    //     'email' => ['email'],
    //     'email_wrong' => ['email'],
    //     'password' => ['required', 'min:8', 'max:64'],
    //     'password_wrong' => ['required', 'min:8', 'max:64'],
    //     'date' => ['date'],
    //     'date_wrong' => ['date'],
    //     'ipv4' => ['ipv4'],
    //     'ipv4_wrong' => ['ipv4'],
    //     'uuid' => ['uuid'],
    //     'uuid_wrong' => ['uuid'],
    //     'agreed' => ['required', 'boolean']
    // );
    
    // $messages = array(
    //     'name_wrong.regex' => 'Username is required.',
    //     'password_wrong.required' => 'Password is required.',
    //     'password_wrong.max' => 'Password must be no more than :max characters.',
    //     'email_wrong.email' => 'Email is required.',
    //     'login_wrong.required' => 'Login is required.',
    //     'login_wrong.alpha_num' => 'Login must consist of alfa numeric chars.',
    //     'agreed.required' => 'Confirm radio box required.',
    // );
    
    // $loader = new ArrayLoader();
    // $translator = new Translator($loader, 'en');
    // $validatorFactory = new ValidatorFactory($translator);
    
    // $validator = $validatorFactory->make($form, $rules, $messages);
    
    // return $validator->messages();


}
