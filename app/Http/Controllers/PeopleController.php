<?php

    namespace App\Http\Controllers;

    use DB;
    use \Illuminate\Http\Request;
    use App\Models\User;
    use App\Models\UserField;
    use function Couchbase\defaultDecoder;
    use Illuminate\Support\Facades\Auth;
    
    class PeopleController extends Controller {

        use \App\Traits\Fields;

        public function __construct() { }

        public function index() {

            $user = [];

            if(!auth()->guest()) {
                $user = auth()->user()->toArray();
            }
            $interests = [];

            $queryInterest = \App\Models\UserField::where('slug', 'interest');

            if($queryInterest->exists()) {
                $interests = $queryInterest->first()->values->toArray();
            }

            $search = $this->search(request(), 'filter');

            if(isset($search['users']) == true && empty($search['users']) == false) {

                $fieldsInline = [ 'signature', 'description' ];

                foreach($search['users'] as &$item) {

                    if(isset($item['properties']) == true && empty($item['properties']) == false&& is_array($item['properties']) == true) {

                        foreach($item['properties'] as $group) {

                            if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {

                                foreach($group['fields'] as $field) {

                                    if(in_array($field['slug'], $fieldsInline) == true) {
                                        if(isset($field['value'])){
                                            $item[$field['slug']] = $field['value'];
                                        }
                                    }

                                }

                            }

                        }

                        $item['socials'] = $this->getUserSocials($item['properties']);

                    }

                }

            }

            $users = [];
            
            foreach ($search['users'] as $searchUser){
                $signature = (isset($searchUser['signature']) == true ? $searchUser['signature'] : null);
                $description = (isset($searchUser['description']) == true ? $searchUser['description'] : null);
                $userInterests = [];
                if(isset($searchUser['properties']) == true){
                    foreach ($searchUser['properties'] as $property){
                        if(isset($property['slug']) == true && $property['slug'] == 'main'){
                            if(isset($property['fields']) == true){

                                foreach ($property['fields'] as $field){

                                    if(isset($field['slug']) == true && $field['slug'] == 'interest'){
                                        if(isset($field['value']) == true){
                                            array_push($userInterests, $field['value']);
                                        }

                                    }

                                }
                            }
                        }

                    }
                }
                $users[] = [
                    'id' => $searchUser['id'],
                    'user_group_id' => $searchUser['user_group_id'],
                    'status' => $searchUser['status'],
                    'email' => $searchUser['email'],
                    'phone' => $searchUser['phone'],
                    'gender' => $searchUser['gender'],
                    'birthday' => $searchUser['birthday'],
                    'image' => $searchUser['image'],
                    'first_name' => $searchUser['first_name'],
                    'last_name' => $searchUser['last_name'],
                    'second_name' => $searchUser['second_name'],
                    'country_id' => $searchUser['country_id'],
                    'city_id' => $searchUser['city_id'],
                    'last_online_at' => $searchUser['last_online_at'],
                    'view' => $searchUser['view'],
                    'score' => $searchUser['score'],
                    'subscribe' => $searchUser['subscribe'],
                    'isSubscribe' => $searchUser['isSubscribe'],
                    'interests' => $userInterests,
                    'properties' => $searchUser['properties'],
                    'signature' => $signature,
                    'description' => $description,
                    'socials' => $searchUser['socials']
                ];

            }
            $data = [
                'interests' => $interests,
                'countries' => parent::getCountry(),
                'users' => $users
            ];

            if(isset($user) == true && empty($user) == false) {
                $data['user'] = $user;
                $data['notifications'] = parent::getUnreadNotification(parent::currentUser(true));
            }

            return view('people', [
                'data' => json_encode($data)
            ]);

        }

        public function search(\Illuminate\Http\Request $request, $action = false) {

            global $request_data, $fieldInterest, $user;

            $request_data = $request->all();

            $count = 0;
            $limit = 10;
            $page = 1;

            $result = false;
            $actionSwitch = false;
            $users = [];
            
            if(!auth()->guest()) {
                $user = auth()->user();
            }

            if(isset($request_data['action']) == true && empty($request_data['action']) == false || $action <> false)
            {

                if(isset($request_data['page']) == true && intval($request_data['page'])) {
                    $page = intval($request_data['page']);
                }

                if(isset($request_data['limit']) == true && intval($request_data['limit'])) {
                    $limit = intval($request_data['limit']);
                }

                if(isset($request_data['action']) == true) {
                    $actionSwitch = $request_data['action'];
                } else {
                    $actionSwitch = $action;
                }

                if($actionSwitch) {

                    switch($actionSwitch) {

                        case 'filter':

                            $query = \App\Models\User::where('status', 2);

                            if($user) {
                                $query->where('id', '!=', $user->id);
                            }

                            if(isset($request_data['fio']) == true && empty($request_data['fio']) == false) {

                                $query->where('first_name', 'like', '%'.$request_data['fio'].'%')
                                    ->orWhere('last_name', 'like', '%'.$request_data['fio'].'%')
                                    ->orWhere('second_name', 'like', '%'.$request_data['fio'].'%');

                            }

                            if(isset($request_data['image']) == true && empty($request_data['image']) == false) {
                                $query->where('image', '!=', '');
                            }

                            if(isset($request_data['gender']) == true && empty($request_data['gender']) == false) {
                                $query->where('gender', '=', $request_data['gender']);
                            }

                            if(isset($request_data['country_id']) == true && empty($request_data['country_id']) == false) {
                                $query->where('country_id', '=', $request_data['country_id']);
                            }

                            if(isset($request_data['city_id']) == true && empty($request_data['city_id']) == false) {
                                $query->where('city_id', '=', $request_data['city_id']);
                            }

                            if(isset($request_data['online']) == true && empty($request_data['online']) == false) {
                                if(isset($request_data['activity']) == true && empty($request_data['activity'] == false)){
                                    if($request_data['activity'] == '24h') {

                                        $query->where('last_online_at', '>=', now()->subDay());

                                    } else if($request_data['activity'] == '7d') {

                                        $query->where('last_online_at', '>=', now()->subDays(7));

                                    }else if($request_data['activity'] == 'all'){
                                        $query->whereNotNull('last_online_at');
                                    }
                                }else{
                                    $query->where('last_online_at', '>=', now()->subMinutes(5));
                                }

                            }

                            if(isset($request_data['age_min']) == true && empty($request_data['age_min']) == false) {
                                $query->where('birthday', '<=', now()->subYears($request_data['age_min']) );
                            }

                            if(isset($request_data['age_max']) == true && empty($request_data['age_max']) == false) {
                                $query->where('birthday', '>=', now()->subYears($request_data['age_max']) );
                            }

//                            if(isset($request_data['activity']) == true && empty($request_data['activity']) == false) {
//
//                                if($request_data['activity'] == '24h') {
//
//                                    $query->where('last_online_at', '>=', now()->subDay());
//
//                                } else if($request_data['activity'] == '7d') {
//
//                                    $query->where('last_online_at', '>=', now()->subDays(7));
//
//                                }else if($request_data['activity'] == 'all'){
//                                    $query->whereNotNull('last_online_at');
//                                }
//
//                            }

                            if(isset($request_data['interest']) == true && empty($request_data['interest']) == false && is_array($request_data['interest']) == true) {

                                $queryInterest = \App\Models\UserField::where('slug', 'interest');

                                if($queryInterest->exists()) {

                                    $fieldInterest = $queryInterest->first();

                                    $query->whereHas('values', function($query){

                                        global $request_data, $fieldInterest;

                                        foreach($request_data['interest'] as $index => $item) {


                                            if($index) {
                                                
                                                $query->whereOr('user_field_id', $fieldInterest->id)->where('value', 'like', '%"' . $item. '"%');
                                                
                                            } else {

                                                $query->where('user_field_id', $fieldInterest->id)->where('value', 'like', '%"' . $item. '"%');

                                            }

                                        }

                                    });

                                }

                            }

                            if(isset($request_data['unsubscibe']) == true && empty($request_data['unsubscibe']) == false) {

                                if($user) {

                                    $query->whereNotExists(function($query){

                                        global $user;

                                        $query->select('user_subscribe.id')
                                            ->from('user_subscribe')
                                            ->where('user_subscribe.user_id', '=', $user->id)
                                            ->whereRaw('user_subscribe.user_subscribe_id = users.id');
    
                                    });

                                }

                            }

                            $count = $query->count();

                            $result = $query
                                ->skip($page > 1 ? $limit * ($page - 1) : 0)
                                ->take($limit)
                                ->get();

                            if($result->count() > 0) {

                                foreach($result as $item) {
                                    
                                    $isSubscribe = false;

                                    if($user) {
                                        
                                        $isSubscribe = \App\Models\UserSubscribe::where('user_subscribe_id', $user->id)->where('user_id', $item->id)->exists();
                                        
                                    }

                                    $users[] = array_merge(
                                        $item->toArray(),
                                        [
                                            'isSubscribe' => $isSubscribe,
                                            'properties' => $this->getEntityFields($item, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup')
                                        ]
                                    );

                                }

                            }

                        break;

                    }
                
                }

                if($result) {

                    $result = [
                        'limit' => $limit,
                        'page' => $page,
                        'users' =>  $users,
                        'count' => $count
                    ];

                    if($action) {

                        return $result;

                    } else {

                        return response()->json( array_merge([ 'code' => 0], $result));

                    }

                }

            }

        }

        private function getUserSocials($array){
            $socials = [];

            foreach ($array as $group){
                if($group['slug'] == 'socials'){
                    if(isset($group['fields']) && !empty($group['fields'])){
                        foreach ($group['fields'] as $field){
                            if(isset($field['value']) && $field['value'] != null){
                                $socials[$field['slug']] = $field['value'];
                            }
                        }
                    }
                }
            }

            return$socials;

        }

        public function writeMessage(Request $request){


            $currentUser = Auth::user();

            $userSubscribe = \App\Models\User::find($request->user_id);

            if($currentUser && $userSubscribe && $currentUser->id <> $userSubscribe->id){
                $isSubcribe = $userSubscribe->subscribers()->where('user_subscribe_id', $currentUser->id)->get();

                if($isSubcribe->count() <> 0){
                    $redirectTo = route('user', [$request->user_id]);

                }else{
                    $subscribe = $userSubscribe->subscribers()->create([
                        'user_subscribe_id' => $currentUser->id
                    ]);

                    if($userSubscribe->subscribers()->where('user_subscribe_id', $currentUser->id)->count()){
                        parent::addNotification(1, $subscribe->id, 'subscribe', $userSubscribe->id);

                        $redirectTo = route('user', [$request->user_id]);

                    }

                }

                return response()->json([
                    'success' => true,
                    'redirectTo' => $redirectTo
                ]);

            }else{
                return response()->json([
                    'success' => false
                ]);
            }

        }


    }
