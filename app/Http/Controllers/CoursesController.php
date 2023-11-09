<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

class CoursesController extends Controller
{

    public function __construct()
    {

    }

    public function index(\Illuminate\Http\Request $request){

        $user = parent::currentUser();

        if(isset($user) == true && empty($user) == false)
        {

            return view('courses', [
                'data' => json_encode([
                    'user' => $user
                ])
            ]);

        }
        else
        {

            return view('courses', [
                'data' => json_encode([
                    'user' => $user
                ])
            ]);

        }
    }

//    public function prepareUsers($users, $user_type, $filter = [])
//    {
//
//
//        $platforms = parent::getPlatforms(true);
//
//        $result = [];
//
//        $subscribers = 0;
//        if(isset($filter['subscribers']) == true)
//            $subscribers = $filter['subscribers'];
//
//        $platform_ids = 0;
//        if(isset($filter['platform_id']) == true)
//            $platform_ids = $filter['platform_id'];
//
//        foreach($users as $index => $user)
//        {
//
//            $user_platforms = [];
//
//            $result2 = \App\Models\UserPlatform::where([
//                'user_id' => $user->id,
//                'user_type' => $user_type
//            ]);
//
//            if(empty($platform_ids) == false) {
//
//                foreach($platform_ids as $index => $platform_id) {
//
//                    if(!$index) {
//
//                        $result2 = $result2->where('platform_id', '=', $platform_id)
//                            ->where('link', '!=', '')
//                             ->where('subscribers', '>=', $subscribers);
//
//                    } else {
//
//                        $result2 = $result2->orWhere('platform_id', '=', $platform_id)
//                            ->where('link', '!=', '')
//                            ->where('subscribers', '>=', $subscribers);
//
//                    }
//
//                }
//
//            } else {
//                $result2 = $result2->where('link', '!=', '')->where('subscribers', '>', $subscribers);
//            }
//
//            $result2 = $result2->get();
//
//            if($result2->count())
//            {
//                foreach($result2 as $platform)
//                {
//
//                    $user_platforms[$platforms[$platform->platform_id]['code']] = $platform->toArray();
//
//                }
//
//            }
//
//            if(empty($user_platforms) == false)
//            {
//
//                $score = \App\Models\Review::where([ 'user_id' => $user->id, 'active' => 1 ] )->avg('score');
//
//                $result[] = array_merge($user->toArray(), [ 'user_platforms' => $user_platforms, 'score' => (empty($score) == false ? intval($score) : 1)] );
//
//            }
//            else
//            {
//                unset($users[$index]);
//            }
//
//        }
//
//        return $result;
//
//    }

//    public function index(\Illuminate\Http\Request $request)
//    {
//
//        $user = parent::currentUser();
//
//        if(isset($user) == true && empty($user) == false)
//        {
//
//            return $this->search($request, $user);
//
//        }
//        else
//        {
//
//            return view('index', [
//                'data' => json_encode([
//                    'user' => $user
//                ])
//            ]);
//
//        }
//
//    }

    
//    public function search(\Illuminate\Http\Request $request, $user)
//    {
//
//        $user_type = 0;
//        $users = [];
//
//        $limit = 50;
//        $offset = 0;
//        $count = 0;
//
//        //$paltform_id = 0;
//        //$user_platforms = parent::getUserPlatforms($user['id'], true);
//
//        if($request->method() == 'POST')
//        {
//
//            $request_data = $request->all();
//
//            if(isset($request_data['type_action']) == true && empty($request_data['type_action']) == false)
//            {
//
//                switch($request_data['type_action'])
//                {
//
//                    case 'filter':
//
//                        $result = \App\Models\User::where('active', 1)
//                        ->where('type', 'like', '%' . $user_type . '%');
//
//                        if(isset($request_data['nishes_ids']) == true && empty($request_data['nishes_ids']) == false)
//                        {
//
//                            foreach($request_data['nishes_ids'] as $index => $nishes_ids) {
//
//                                if(!$index) {
//                                    $result->where('nishes_ids', 'like', '%"' . $nishes_ids . '"%' );
//                                } else {
//                                    $result->orWhere('nishes_ids', 'like', '%"' . $nishes_ids . '"%' );
//                                }
//
//                            }
//
//                        }
//                        if(isset($request_data['country_id']) == true && empty($request_data['country_id']) == false)
//                        {
//
//                            foreach($request_data['country_id'] as $index => $country_id) {
//
//                                if(!$index) {
//                                    $result->where('country_id', 'like', '%"' . $country_id . '"%' );
//                                } else {
//                                    $result->orWhere('country_id', 'like', '%"' . $country_id . '"%' );
//                                }
//
//                            }
//
//                        }
//
//                        if(isset($request_data['city_id']) == true && empty($request_data['city_id']) == false)
//                        {
//
//                            $result->where('city_id', 'like', '%' . $request_data['city_id'] . '%' );
//
//                        }
//
//                        if(isset($request_data['language_id']) == true && empty($request_data['language_id']) == false)
//                        {
//
//                            foreach($request_data['language_id'] as $index => $language_id) {
//
//                                if(!$index) {
//                                    $result->where('language_id', 'like', '%"' . $language_id . '"%' );
//                                } else {
//                                    $result->orWhere('language_id', 'like', '%"' . $language_id . '"%' );
//                                }
//
//                            }
//
//                        }
//
//                        $result = $result->offset($offset)
//                        ->limit($limit)
//                        ->get();
//
//                        if($result->count())
//                        {
//
//                            $users = $this->prepareUsers($result, $user_type, [
//                                'subscribers' => $request_data['subscribers'],
//                                'platform_id' => $request_data['platform_id']
//                            ]);
//
//                        }
//
//                        return response()->json( [
//                            'code' => 0, 'users' =>  $users
//                        ]  );
//
//                    break;
//
//                    case 'more':
//
//                    break;
//
//                }
//
//            }
//
//        }
//        else
//        {
//
//            $result = \App\Models\User::where('active', 1)
//            ->where('type', 'like', '%' . $user_type . '%')
//            ->offset($offset)
//            ->limit($limit)
//            ->get();
//
//            if($result->count())
//            {
//
//                $users = $this->prepareUsers($result, $user_type);
//
//            }
//
//            return view('search', [
//                'data' => json_encode([
//                    'nishes' => \App\Models\Nishe::get()->toArray(),
//                    'country' => \App\Models\Country::get()->toArray(),
//                    'city' => \App\Models\City::get()->toArray(),
//                    'language' => \App\Models\Language::get()->toArray(),
//                    'platform' => \App\Models\Platform::get()->toArray(),
//                    'user' => $user,
//                    'users' => $users,
//                    'limit' => $limit,
//                    'offset' => $offset,
//                    'user_type' => $user_type
//                ])
//            ]);
//
//        }
//
//
//
//    }

}
