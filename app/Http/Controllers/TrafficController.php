<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

class TrafficController extends Controller
{
    
    public function index(Request $request)
    {

        $user = parent::currentUser(true);
        $notifications = parent::getUnreadNotification($user);
        return view('traffic', [
            'data' => json_encode([
                'user' => $user,
                'notifications' => $notifications
            ])
        ]);




    }

}
