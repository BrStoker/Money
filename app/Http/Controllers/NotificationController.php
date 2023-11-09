<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{

    public function __construct()
    {

    }

    public function index(\Illuminate\Http\Request $request)
    {

        $user = parent::currentUser(true);

        if(isset($user) == true && empty($user) == false)
        {
            $notifications = parent::getUnreadNotification($user);
            $this->readNotifications($user);
            return view('notification', [
                'data' => json_encode([
                    'user' => $user,
                    'notifications' => $notifications
                ])
            ]);

        }



    }

    public function readNotifications($user){

        $notifications = \App\Models\NotificationEntity::where('user_id', $user->id)->where('updated_at', null)->get();

        foreach($notifications as $notification){
            $notification->updated_at = Carbon::now();
            $notification->save();
        }

    }

    


}
