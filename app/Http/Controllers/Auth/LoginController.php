<?php

namespace App\Http\Controllers\Auth;

use \App\Http\Controllers\Controller;
use \App\Providers\RouteServiceProvider;
use http\Env\Request;
use \Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {

        $this->middleware('guest')->except('logout');

    }

    public function login(\Illuminate\Http\Request $request)
    {

        if($request->method() == 'POST')
        {

            $validatorData = new \App\Http\Requests\UserAuthRequest();
            $remember = $request->input('rememberMe');

            $validator = \Illuminate\Support\Facades\Validator::make(
                $request->all(),
                $validatorData->rules(),
                $validatorData->messages(),
                $validatorData->attributes()
            );

            if (!$validator->fails())
            {

                $request_data = $validator->safe()->only(['email', 'password']);
                $remember = $remember ?? false;

                $user_model = new \App\Models\User();

                $login = $request->input('email');

                $hasId = substr(mb_strtolower($login), 0, 2) == 'id';
                $hasPhone = false;
                $email = null;

                $normalizedPhone = preg_replace('/[^\d]+/', '', $login);

                if (strlen($normalizedPhone) === 11 && in_array(substr($normalizedPhone, 0, 1), ['7', '8'])) {
                    $normalizedPhone = '+7' . substr($normalizedPhone, 1);
                    $hasPhone = true;
                }

                if ($hasId) {
                    $userId = substr($login, 2);
                    $user = \App\Models\User::find($userId);
                    if ($user) {
                        $email = $user->email;
                    }
                } elseif ($hasPhone) {
                    $user = \App\Models\User::where('phone', $normalizedPhone)->first();
                    if ($user) {
                        $email = $user->email;
                    }
                }
                if(!is_null($email)){

                    if (\Auth::attempt(['email' => $email, 'password' => $request_data['password']], $remember))
                    {

                        $user = \Auth::user();

                        if($user->status != 2)
                        {

                            $this->logout(false);

                            return response()->json( [ 'code' => 2, 'desc' => ['email'=> __('error.login_fail_active')]] );

                        }

                        return response()->json( ['code' => 0, 'location' =>  '/' ]  );
                        
                    }
                    else
                    {
                        $userLogin = \App\Models\User::where('email', $email)->first();
                        $errors = array();
                        if($userLogin){
                            $errors = [
                                'password' => __('error.login_fail_password')
                            ];
                        }else{
                            $errors = [
                                'email' => __('error.login_fail_email')
                            ];
                        }


                        return response()->json( [ 'code' => 2, 'desc' => $errors ]);
                    }
                }else{
                    return response()->json( [ 'code' => 2, 'desc' => ['email' =>__('error.login_fail_email')]] );
                }


            }
            else
            {

                return response()->json( [ 'code' => 1, 'desc' => $validator->messages() ] );
                
            }

        }
        else if($request->method() == 'GET')
        {

            return view('auth.login');

        }

    }

    public function logout($redirect = true)
    {

        \Auth::logout();

        if($redirect)
        {
            return redirect('/');
        }
        
    }

    public function getSupportLink(\Illuminate\Http\Request $request){

        $setting = \App\Models\Setting::where('slug', 'link-'.strtolower($request->name))->first();
        $link = '#';
        if($setting){
            $record = \App\Models\SettingValue::where('setting_id', $setting->id)->first();
            if($record){
                $link = $record->value;
            }
        }

        return response()->json([
            'status' => true,
            'link' => $link,
            'name' => $request->name
        ]);



    }

}
