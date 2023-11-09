<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

class PartnersController extends Controller
{

    public function __construct()
    {

    }

    public function index(\Illuminate\Http\Request $request){

        $user = parent::currentUser(true);
        $userArray = $user->toArray();

        if(isset($user) == true && empty($user) == false)
        {
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
            $refUrl = $protocol . $_SERVER['HTTP_HOST'] . '/ref' . $user->id;

            $referals = $user->referals()->get();
            $refs = [];
            foreach ($referals as $referal){

                $refUser = \App\Models\user::find($referal->referal_id);

                $this->getReferals($refs, $refUser);

            }
            return view('partners', [
                'data' => json_encode([
                    'user' => array_merge($userArray, ['referals'=>$refs], ['counts'=> $this->countNestedArrayElements($refs)]),
                    'refurl' => $refUrl,
                    'notifications' => parent::getUnreadNotification($user)
                ])
            ]);

        }


    }

    public function getReferals(&$arReferals, $entity) {

        if(isset($entity->referals) == true && !is_null($entity->referals)){
            $childrens = $entity->referals;
            $arReferals[$entity->id] = [
                'id' => $entity->id,
                'first_name' => $entity->first_name,
                'last_name' => $entity->last_name,
                'image' => $entity->image,
                'counts' => $this->countNestedArrayElements($childrens),
                'referals' => []
            ];

            if(isset($childrens)) {
                foreach ($childrens as $children){
                    $user = \App\Models\User::find($children->referal_id);
                    $this->getReferals($arReferals[$entity->id]['referals'], $user);
                }

            }
        }


        return $arReferals;

    }

    public function countNestedArrayElements($array)
    {

        $countTop = count($array);
        $countNested = 0;

        foreach ($array as $element) {
            if (is_array($element)) {
                $countNested += count($element['referals']);
            }
        }
        return [
            'top' => $countTop,
            'nested' => $countNested
        ];
    }

}
