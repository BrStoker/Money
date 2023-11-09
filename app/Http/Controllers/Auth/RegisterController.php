<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller,
		App\Providers\RouteServiceProvider,
		App\Models\User,
		http\Env\Response,
		Illuminate\Foundation\Auth\RegistersUsers,
		Illuminate\Support\Facades\Hash,
		Illuminate\Support\Facades\Validator,
		Illuminate\Support\Facades\Session,
		Illuminate\Http\Request,
        Illuminate\Support\Facades\Cookie;

class RegisterController extends Controller
{

    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {

        $this->middleware('guest');
    
    }

    public function register(Request $request)
    {

        if($request->method() == 'POST')
        {

            $validatorData = new \App\Http\Requests\UserRegisterRequest();

            $validator = \Illuminate\Support\Facades\Validator::make(
                $request->all(),
                $validatorData->rules(),
                $validatorData->messages(),
                $validatorData->attributes()
            );
            
            if (!$validator->fails())
            {

                $request_data = $validator->safe()->only(['first_name', 'last_name', 'email', 'phone', 'password']);

                $user_model = new \App\Models\User();

                $fields = [];

                foreach($request_data as $itemKey => $item)
                {
                    if($itemKey == 'password')
                    {
                        $fields[$itemKey] = \Illuminate\Support\Facades\Hash::make($item);
                    }else if ($itemKey == 'phone'){
                        $fields[$itemKey] = preg_replace('/^8/', '+7', $item, 1);
                    }else
                    {
                        $fields[$itemKey] = $item;
                    }
                }

                $user = \App\Models\User::create(array_merge($fields,[
                    'status' => 1,
                    'user_group_id' => 1
                ]));

                $referralLink = $request->cookie('referralLink');
                if(isset($referralLink) == true){
                    preg_match('/\d+$/', $referralLink, $matches);
                    $referal_id = intval($matches[0]);
                    $ref = \App\Models\User::find($referal_id);
                    if(isset($ref) == true){
                        $referal = \App\Models\UserReferal::create([
                            'user_id' => $ref->id,
                            'referal_id' => $user->id
                        ]);
                        Cookie::forget('referralLink');
                    }
                }


                $code = $this->createCode();
                
                if($code != null){

                    return response()->json([
                        'code' => 0,
                        'desc' => '',
                        'userId' => $user->id
                    ]);
                } else {

                    return response()->json([
                        'code' => 1,
                        'desc' => 'Ошибка получения кода'
                    ]);

                }
            }
            else
            {

                $messages = $validator->messages()->toArray();

                $errorMessages = array();

                foreach ($messages as $key=>$value){

                    $errorMessages[$key] = $value[0];

                }

                return response()->json( [ 'code' => 1, 'desc' => $errorMessages ] );

            }

        }

        else if($request->method() == 'GET')

        {
            
            return view('auth.register');

        }
    }

    public function confirm(\Illuminate\Http\Request $request){

        $request_data = $request->all();
        if($request_data['code'] == parent::createCode()){
            $user = User::find($request_data['id']);
            if(!$user){
                return response()->json(['code' => '1', 'desc'=>'Пользователь не найден']);
            }else{
                $user->status = 2;
                $user->save();

                if (\Auth::loginUsingId($user->id)){
                    return response()->json([
                        'code' => 0,
                        'location' => '/id' . $user->id
                    ]);
                }

            }
        }

    }



}
