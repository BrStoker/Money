<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Http\RedirectResponse;
    use App\Models\User;
    use App\Models\UserGroup;
    use App\Models\UserField;
    use App\Models\City;
    use App\Models\UserFieldValue;
    use App\Models\UserValue;
    use App\Models\ArticleFavorite;
    use App\Models\Article;
    use Illuminate\Support\Facades\Cookie;
    use function Couchbase\defaultDecoder;
    use function React\Promise\all;

    class UserController extends Controller
    {

        use \App\Traits\Fields;

        public function __construct() { }

        public function index() {

        }

        public function image(\Illuminate\Http\Request $request){

            $validatorData = new \App\Http\Requests\UserImageRequest();
            $validator = \Illuminate\Support\Facades\Validator::make(
                $request->all(),
                $validatorData->rules(),
                $validatorData->messages(),
                $validatorData->attributes()
            );
            $request_data = $validator->safe()->only([ 'image' ]);

            $image = $request->file('image');

            $user = parent::currentUser(true);

            if(isset($image) == true && empty($image) == false)
            {

                $user->image = $image->path();

                if($user->save())
                {
                    return response()->json( ['code' => 0, 'image' =>  $user->image ]  );
                }

            }

        }

        public function edit() {

            $user = parent::currentUser(true);

            if(isset($user) && !empty($user)){

                $country = parent::getCountry();
                $properties = $this->getEntityFields($user, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
                $arrayUser = $user->toArray();
                $interests = [];
                $userInterests = [];
                $newUser = true;
                foreach ($properties as $prop=>$value){
                    if(isset($value['fields'])){
                        foreach ($value['fields'] as $field){
                            if(isset($field['value'])){
                                if($field['slug'] == 'interest'){
                                    $newUser = false;
                                    foreach($field['value'] as $option){
                                        $interes = UserFieldValue::find($option)->toArray();
                                        $value = [
                                            'title' => $interes['value'],
                                            'name' => 'interes_id_'.$interes['id'],
                                            'imageBefore' => '/image/svg/sprite.svg#checkboxBefore',
                                            'imageAfter' => '/image/svg/sprite.svg#checkboxAfter',
                                            'type'=> 'checkbox',
                                            'value' => '',
                                            'checked' => true
                                        ];
                                        $userInterests[] = $value;
                                    }
                                    foreach ($field['options'] as $option){
                                        $interes = [
                                            'title' => $option['value'],
                                            'name' => 'interes_id_'.$option['id'],
                                            'imageBefore' => '/image/svg/sprite.svg#checkboxBefore',
                                            'imageAfter' => '/image/svg/sprite.svg#checkboxAfter',
                                            'type'=> 'checkbox',
                                            'value' => '',
                                            'checked' => false
                                        ];
                                        $interests[] = $interes;
                                    }
                                }

                                $arrayUser['fields[' . $field['slug'] . ']'] = $field['value'];
                                if($field['slug'] == 'signature'){
                                    $arrayUser['signature'] = $field['value'];
                                }
                                if($field['slug'] == 'description'){
                                    $arrayUser['description'] = $field['value'];
                                }
                            }else{
                                if($field['slug'] == 'interest'){
                                    foreach ($field['options'] as $option){
                                        $interes = [
                                            'title' => $option['value'],
                                            'name' => 'interes_id_'.$option['id'],
                                            'imageBefore' => '/image/svg/sprite.svg#checkboxBefore',
                                            'imageAfter' => '/image/svg/sprite.svg#checkboxAfter',
                                            'type'=> 'checkbox',
                                            'value' => '',
                                            'checked' => false
                                        ];
                                        $userInterests[] = $interes;
                                    }
                                }
                            }
                        }
                    }
                }
                $interests = [];
                $fieldsInline = [ 'signature', 'description', 'interest' ];

                $queryInterest = \App\Models\UserField::where('slug', 'interest');

                if($queryInterest->exists()) {
                    $interests = $queryInterest->first()->values->toArray();
                }

                $notifications = parent::getUnreadNotification($user);

                $arrayUser['userInterests'] = $userInterests;
                $arrayUser['new'] = $newUser;


                return view('user.edit', [
                    'data' => json_encode([
                        'interests' => $interests,
                        'user' => $arrayUser,
                        'countries' => $country,
                        'notifications' => $notifications
                    ])
                ]);
            }
        }

        public function profile(\Illuminate\Http\Request $request) {

            if($request->method() == 'POST') {

                $validatorData = new \App\Http\Requests\UserProfileRequest();

                $validationFields = [];
                foreach($request->all() as $item=>$value){
                    if($item != 'newpassword' && $item != 'repeat_newpassword'){

                        $validationFields[$item] = $value;
                    }else{

                        if(!is_null($value)){
                            $validationFields[$item] = $value;
                        }
                    }
                }

                $validator = \Illuminate\Support\Facades\Validator::make(
                    $validationFields,
                    $validatorData->rules(),
                    $validatorData->messages(),
                    $validatorData->attributes()
                );


                if (!$validator->fails())
                {

                    $request_data = $validator->safe(); //->only([ 'first_name', 'last_name', 'fields', 'gender', 'birthday', 'phone', 'email', 'country_id' ])

                    $user = parent::currentUser(true);

                    if(isset($request->newpassword)){
                        $request_data['password'] = \Illuminate\Support\Facades\Hash::make($request->newpassword);
                    }
                    if(isset($request->birthday) && !is_null($request->birthday) && $request->birthday != 'null'){
                        $request_data['birthday'] = $request->birthday;
                    }
                    if(isset($request->gender) && !is_null($request->gender) !== null){
                        $request_data['gender'] = $request->gender;
                    }
                    if(isset($request->country_id) && !is_null($request->country_id) !== null){
                        $request_data['country_id'] = $request->country_id;
                    }
                    if(isset($request->city) && !is_null($request->city) !== null){
                        $request_data['city_id'] = $request->city;
                    }


                    $fields = [];
                    foreach($request_data as $index => $value) {

                        if($index <> 'fields' && isset($user->{$index}) == true) {
                            $user->{$index} = $value;
                        }else{
                            if($index == 'newpassword' || $index == 'repeat_newpassword'){
                                $user->password = $value;
                            }else{
                                $fields[$index] = $value;
                            }

                        }

                    }

                    if($fields){
                        foreach ($fields as $field=>$value){
                            if(is_array($value) == true) {
                                foreach($value as $subkey => $item){
                                    $field = \App\Models\UserField::where('slug', $subkey)->first();
                                    if($field){
                                        $record = \App\Models\UserValue::where('user_field_id', $field->id)->where('user_id', Auth::user()->id)->first();
                                        if($record){
                                            $record->value = $item;
                                            $record->save();
                                        }else{
                                            $record = \App\Models\UserValue::create([
                                                'user_id' => Auth::user()->id,
                                                'user_field_id' => $field->id,
                                                'value' => $item
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    foreach ($fields as $field => $value){
                        if(!is_array($value)){
                            $user->{$field} = $value;
                        }

                    }

                    $user->save();
                    $user->refresh();

                    return response()->json( [ 'code' => 0, 'desc' => '', 'location' => '/id' . Auth::user()->id ] );

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


            } else {

                $user = parent::currentUser(true);
                $notifications = parent::getUnreadNotification($user);
                $user = [
                    'model' => $user,
                    'data' => $user->toArray(),
                    'properties' => $this->getEntityFields($user, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup')
                ];


                $interests = [];
                $fieldsInline = [ 'signature', 'description', 'interest' ];

                $queryInterest = \App\Models\UserField::where('slug', 'interest');

                if($queryInterest->exists()) {
                    $interests = $queryInterest->first()->values->toArray();
                }
                $favoriteArticles = ArticleFavorite::where('user_id', $user['data']['id'])->get();
                $favoriteArticles->transform(function($record){
                    $article = \App\Models\Article::where('id', $record->article_id)->first();

                    if($article){
                        $articleAuthor = \App\Models\User::where('id', $article->user_id)->first();
                        $signature = '';
                        $properties = $this->getEntityFields($articleAuthor, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
                        $fieldsInline = ['signature'];
                        $articleGroups = [];
                        if(isset($article->article_group_ids) == true && empty($article->article_group_ids) == false) {

                            $article_group_ids = str_replace('[', '', $article->article_group_ids);
                            $article_group_ids = str_replace(']', '', $article->article_group_ids);

                            $articleGroups = \App\Models\ArticleGroup::whereIn('id', $article_group_ids)->where('active', 1)->get();
                            $articleGroups->transform(function ($group) {

                                return [
                                    'name' => $group->title
                                ];

                            });
                        }

                        if($properties){
                            foreach($properties as $group) {
                                if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {
                                    foreach($group['fields'] as $field) {

                                        if(in_array($field['slug'], $fieldsInline) == true) {
                                            if(isset($field['value'])){
                                                $signature = $field['value'];
                                            }else{
                                                $signature = null;
                                            }

                                        }

                                    }

                                }
                            }
                        }


                        return [
                            'id' => $article->id,
                            'image' => $article->image ? '/storage/'. $article->image : '/image/no-image.png',
                            'title' => $article->title,
                            'preview' => $article->preview ? $article->prevew : str_limit($article->detail_text, 150),
                            'detail_text' => $article->detail_text,
                            'articleGroups' => $articleGroups,
                            'article_user' => [
                                'id' => $articleAuthor->id,
                                'first_name' => $articleAuthor->first_name,
                                'last_name' => $articleAuthor->last_name,
                                'image' => $articleAuthor->image ? '/storage/' . $articleAuthor->image : '/image/avatar.png',
                                'last_online_at' => $articleAuthor->last_online_at ? $articleAuthor->last_online_at->format('Y-m-d H:i:s') : null,
                                'isSubscribe' => (bool)\App\Models\UserSubscribe::where('user_subscribe_id', Auth::user()->id)->where('user_id', $article->user_id)->first(),
                                'signature' => $signature
                            ],
                            'score' => $article->score,
                            'view' => $article->view,
                            'favorite' => $article->favorite,
                            'created_at' => $article->created_at
                        ];
                    }else{
                        return [];
                    }




                });

//                dd($favoriteArticles);

                if(isset($user['properties']) == true && empty($user['properties']) == false && is_array($user['properties']) == true) {

                    foreach($user['properties'] as $group) {

                        if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {
                            foreach($group['fields'] as $field) {

                                if(in_array($field['slug'], $fieldsInline) == true) {
                                    if(isset($field['value'])){
                                        $user['data'][$field['slug']] = $field['value'];
                                    }else{
                                        $user['data'][$field['slug']] = null;
                                    }

                                }

                            }

                        }

                    }

                }

                $articles = parent::getArticles($user['model']->id);
                $socials = $this->getUserSocials($user['properties']);
                return view('user.profile', [
                    'data' => json_encode([
                        'interests' => $interests,
                        'countries' => parent::getCountry(),
                        'user' => array_merge(
                            $user['data'], [
                                'new' => isset($user['data']['interest']) == false && empty($user['data']['interest']) == true,
                                'socials' => $socials,
                                'favorite' => $favoriteArticles
                            ]
                        ),
                        'notifications' => $notifications,
                        'articles' => $articles
                    ])
                ]);

            }

        }

        public function store_interests(\Illuminate\Http\Request $request){

            $user = parent::currentUser(true);

            $request_data = $request->all();
            $interests = [];
            $fieldsInline = [ 'interest' ];
            $properties = $this->getEntityFields($user, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
            if($properties == true && is_array($properties) == true) {
                foreach($properties as $group) {
                    if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {
                        foreach($group['fields'] as $field) {
                            if(in_array($field['slug'], $fieldsInline) == true) {
                                if(isset($field['value'])){
                                    $interests = $field['value'];
                                }else{
                                    $interests = null;
                                }
                            }
                        }
                    }
                }
            }
            $fields = explode(',', $request_data['fields']['interest']);
            $fields = array_unique(array_merge($fields, $interests));
            $UserValue = new \App\Models\UserValue();
            $UserValue->user_id = $user->id;
            $UserValue->user_field_id = 13;
            $UserValue->value = json_encode($fields);
            $values = $UserValue->save();

            $interests = json_decode($UserValue->value);

            if($values){
                return response()->json( [ 'code' => 0, 'desc' => '', 'interests' => $interests, 'location' => '/user/profile' ] );
            }else{
                return response()->json( [ 'code' => 1, 'desc' => 'Ошибка сохранения' ] );
            }
        }

        public function setReferralCookie($referralLink)
        {
            Cookie::queue('referralLink', $referralLink, 1440);
        }

        public function detail(\Illuminate\Http\Request $request, $user_id)
        {

            
            $pageUserModel = User::find($user_id);

            if($pageUserModel) {

                $currentUser = null;
                $currentUserModel = parent::currentUser(true);
//                dd($currentUserModel);
                if($currentUserModel) {
                    $currentUser = $currentUserModel->toArray();
                }

                $properties = $this->getEntityFields($pageUserModel, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
                $currentUserInterests = [];
                $newUser = true;
                foreach ($properties as $prop=>$value){
                    if(isset($value['fields'])){
                        foreach ($value['fields'] as $field){
                            if(isset($field['value'])){
                                if($field['slug'] == 'interest'){
                                    $newUser = false;
                                    foreach($field['value'] as $option){
                                        $interes = UserFieldValue::find($option)->toArray();
                                        $value = [
                                            'title' => $interes['value'],
                                            'name' => 'interes_id_'.$interes['id'],
                                            'imageBefore' => '/image/svg/sprite.svg#checkboxBefore',
                                            'imageAfter' => '/image/svg/sprite.svg#checkboxAfter',
                                            'type'=> 'checkbox',
                                            'value' => '',
                                            'checked' => true
                                        ];
                                        $currentUserInterests[] = $value;
                                    }
                                    foreach ($field['options'] as $option){
                                        $interes = [
                                            'title' => $option['value'],
                                            'name' => 'interes_id_'.$option['id'],
                                            'imageBefore' => '/image/svg/sprite.svg#checkboxBefore',
                                            'imageAfter' => '/image/svg/sprite.svg#checkboxAfter',
                                            'type'=> 'checkbox',
                                            'value' => '',
                                            'checked' => false
                                        ];
                                        $currentUserInterests[] = $interes;
                                    }
                                }

                                $arrayUser['fields[' . $field['slug'] . ']'] = $field['value'];
                                if($field['slug'] == 'signature'){
                                    $arrayUser['signature'] = $field['value'];
                                }
                                if($field['slug'] == 'description'){
                                    $arrayUser['description'] = $field['value'];
                                }
                            }else{
                                if($field['slug'] == 'interest'){
                                    foreach ($field['options'] as $option){
                                        $interes = [
                                            'title' => $option['value'],
                                            'name' => 'interes_id_'.$option['id'],
                                            'imageBefore' => '/image/svg/sprite.svg#checkboxBefore',
                                            'imageAfter' => '/image/svg/sprite.svg#checkboxAfter',
                                            'type'=> 'checkbox',
                                            'value' => '',
                                            'checked' => false
                                        ];
                                        $currentUserInterests[] = $interes;
                                    }
                                }
                            }
                        }
                    }
                }


                #$queryUser = \App\Models\User::where('id', $user_id);

                #if($queryUser->exists()) {

                    $user = [
                        'model' => $pageUserModel,
                        'data' => $pageUserModel->toArray(),
                        'properties' => $this->getEntityFields($pageUserModel, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup')
                    ];

                    if(!empty($currentUser)) {
                        // TODO поправить потом чтобы просмотры засчитывались даже если пользовтель неавторизован
                        if(!$user['model']->viewed()->where('user_view_id', $currentUser['id'])->exists() && $currentUser['id'] != $user['model']->id) {

                            $user['model']->viewed()->create([
                                'user_view_id' => $user['model']->id
                            ]);

                        }
                    }

                    $userFavorites = ArticleFavorite::where('user_id', $user['data']['id'])->get();

                    $userFavorites->transform(function ($record) {

                        $article = \App\Models\Article::where('id', $record->article_id)->first();

                        if($article){
                            $articleAuthor = \App\Models\User::where('id', $article->user_id)->first();
                            $articleGroups = '';
                            if(isset($article->article_group_ids) == true && empty($article->article_group_ids) == false) {

                                $article_group_ids = str_replace('[', '', $article->article_group_ids);
                                $article_group_ids = str_replace(']', '', $article->article_group_ids);

                                $articleGroups = \App\Models\ArticleGroup::whereIn('id', $article_group_ids)->where('active', 1)->get();
                                $articleGroups->transform(function ($group) {

                                    return [
                                        'name' => $group->title
                                    ];

                                });
                            }

                            return [
                                'id' => $article->id,
                                'title' => $article->title,
                                'image' => $article->image ? '/storage/'. $article->image : '/image/no-image.png',
                                'preview' => $article->preview,
                                'detail_text' => $article->detail_text,
                                'slug' => $article->slug,
                                'articleGroups' => $articleGroups,
                                'article_user' => [
                                    'id' => $articleAuthor->id,
                                    'image' => $articleAuthor->image ? '/storage/' . $articleAuthor->image : '/image/avatar.png',
                                    'first_name' => $articleAuthor->first_name,
                                    'last_name' => $articleAuthor->last_name,
                                    'last_online_at' => $articleAuthor->last_online_at ? $articleAuthor->last_online_at->format('Y-m-d H:i:s'): null
                                ],
                                'score' => $article->score,
                                'favorite' => $article->favorite,
                                'view' => $article->view,
                                'created_at' => $article->created_at->format('Y-m-d H:i:s')
                            ];


                        }else{
                            return [];
                        }


                    });

                    $userArticles = parent::getArticles($user['model']->id);

                    $fieldsInline = [ 'signature', 'description' ];

                    if(isset($user['properties']) == true && empty($user['properties']) == false&& is_array($user['properties']) == true) {

                        foreach($user['properties'] as $group) {

                            if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {

                                foreach($group['fields'] as $field) {

                                    if(isset($field['value'])){
                                        if(in_array($field['slug'], $fieldsInline) == true) {

                                            $user['data'][$field['slug']] = $field['value'];

                                        }

                                    }

                                }


                            }

                        }
                        $user['data']['socials'] = $this->getUserSocials($user['properties']);

                    }

                    $isSubscribe = false;

                    if(!empty($currentUser)) {
                        $isSubscribe = \App\Models\UserSubscribe::where('user_subscribe_id', $currentUser['id'])->where('user_id', $user['model']->id)->exists();
                    }

                    $interests = [];
                    $queryInterest = \App\Models\UserField::where('slug', 'interest');

                    if($queryInterest->exists()) {
                        $interests = $queryInterest->first()->values->toArray();
                    }

                    $referralLink = route('user-reflink', $user_id);

                    if ($referralLink) {
                        $this->setReferralCookie($referralLink);
                    }
                    if(!empty($currentUser)){
                        $currentUser = array_merge($currentUser, [
                            'new' => $newUser,
                            'userInterests' => $currentUserInterests
                        ]);
                    }

                    $user_detail = array_merge($user['data'],['favorite' => $userFavorites]);

                    return view('user.detail', [
                        'data' => json_encode([
                            'user' => $currentUser,
                            'user_detail' => $user_detail,
                            'interests' => $interests,
                            'isSubscribe' => $isSubscribe,
                            'articles' => $userArticles,
                        ])
                    ]);

                #} else {
                #    abort(404);
                #}

            } else {
                abort(404);
            }

        }

        public function add_article(\Illuminate\Http\Request $request) {

            if($request->method() == 'POST'){
                $title = $request->input('title');
                if (\App\Models\ArticleGroup::where('title', trim($title))->exists()) {
                    return response()->json(['code' => 1, 'desc' => 'Такой заголовок уже существует. Укажите другой.']);
                }
                $validatorData = new \App\Http\Requests\ArticleRequest();
                if(isset($request->detail_text)){
                    $request->merge(['preview' => mb_substr($request->detail_text, 0, 100, "UTF-8")]);
                }

                $validator = \Illuminate\Support\Facades\Validator::make(
                    $request->all(),
                    $validatorData->rules(),
                    $validatorData->messages(),
                    $validatorData->attributes()
                );
                if (!$validator->fails()){
                    $user = parent::currentUser(true);

                    $request_data = $validator->safe()->only(['slug', 'status', 'article_group_ids', 'user_id', 'image', 'title', 'preview', 'detail_text', 'user_id']);

                    if($request->image){
                        $request_data['image'] = $request->image;
                    }
                    if($request->article_group_ids){
                        $request_data['article_group_ids'] = $request->article_group_ids;
                    }
                    if($user){
                        $request_data['user_id'] = $user->id;

                        $article = new \App\Models\Article();
                        foreach ($request_data as $index=>$value){
                            if($index == 'title'){
                                $article->slug = parent::createSlug($value, '\App\Models\Article'); //$this->createSlug($value);
                            }
                            $article->{$index} = $value;
                        }
//                        dd($article);
                        $article->status = 2;
                        $newArt = $article->save();
//                        dd($newArt);
                        if($newArt){
                            if($request->fields){
                                foreach ($request->fields as $key => $value){
                                    $record = \App\Models\ArticleValue::create([
                                        'article_field_id' => $key,
                                        'value' => $value == "true" ? 1 : 0,
                                        'article_id' => $article->id,
                                    ]);
                                }
                            }
                            return response()->json([

                                'code' => 0,
                                'location' => '/'.$article->slug,
                                'redirectId' => $article->id,

                            ]);

                        }else{
                            return response()->json([
                                'code' => 1,
                                'desc' => ''
                            ]);
                        }
                    }
                }else{
                    return response()->json(['code' => 1, 'errors' => $validator->errors()]);
                }



            } else {

                $user = parent::currentUser();
                $articleGroups = parent::getArticleGroup();

                return view('user.article.add', [
                    'data' => json_encode([
                        'user' => $user,
                        'articlegroups' => $articleGroups
                    ])
                ]);


            }

        }

        public function add_subscribe(\Illuminate\Http\Request $request) {

            if($request->method() == 'POST') {

                $error = true;
                $desc = '';
                $subscribe = false;
                $request_data = $request->all();
                $user = parent::currentUser(true);
                $userSubscribe = User::find($request_data['userId']);

                if($user && $userSubscribe && $user->id <> $userSubscribe->id) {
                    
                    $userSubscribeCheck = $userSubscribe->subscribers()->where('user_subscribe_id', $user->id)->get();

                    if($userSubscribeCheck->count() == 0) {

                        $subscribe = $userSubscribe->subscribers()->create([
                            'user_subscribe_id' => $user->id
                        ]);

                        if($userSubscribe->subscribers()->where('user_subscribe_id', $user->id)->count()) {
                            parent::addNotification(1, $subscribe->id, 'subscribe', $userSubscribe->id);
                            $error = false;
                            $subscribe = true;
                                
                        }

                    }else{
                        $userSubscribe->subscribers()->delete([
                            'user_subscribe_id' => $user->id
                        ]);
                        if(!$userSubscribe->subscribers()->where('user_subscribe_id', $user->id)->count()) {
                            $error = false;
                            $subscribe = false;

                        }

                    }

                }

                if($error == true) {
                    return response()->json( ['code' => 1, 'desc' =>  '' ]  );
                } else {
                    return response()->json( ['code' => 0, 'desc' =>  '',  'subscribe' => $subscribe]  );
                }
                
            }
        
        }

        public function forgot_password(\Illuminate\Http\Request $request) {

            $validatorData = new \App\Http\Requests\UserForgotPasswordRequest();

            $validator = \Illuminate\Support\Facades\Validator::make(
                $request->all(),
                $validatorData->rules(),
                $validatorData->messages(),
                $validatorData->attributes()
            );

            $request_data = $request->all();

            $user = User::where('phone', $request_data['recovery_phone'])->first();


            if($user){
                $code = parent::createCode();
                return response()->json([
                    'code' => 0,
                    'desc' => ''
                ]);
            } else {
                return response()->json([
                    'code' => 1,
                    'desc' => ['recovery_phone' => __('error.recovery_phone_fail')]
                ]);
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

        public function setCookie(\Illuminate\Http\Request $request){
            $this->setReferralCookie($request->url());
            return redirect()->route('index');
        }

        public function getCode()
        {
            return response()->json([
                'code' => 0,
                'desc' => 'Новый код сгенерирован'
            ]);
        }

        public function complainUser(Request $request){

            $user = Auth::user();
            $isset = \App\Models\Complain::where('user_id', $user->id)->where('request', $request->reason)->where('object_type', 1)->where('object_id', $request->article_id)->first();
            if($isset){
                return response()->json([
                    'code' => 1,
                    'message' => 'Вы уже отправляли жалобу на эту статью'
                ]);
            }else{
                $complaint = \App\Models\Complain::create([
                    'user_id' => $user->id,
                    'object_type' => 1,
                    'object_id' => $request->article_id,
                    'request' => $request->reason
                ]);



                if($complaint){
                    return response()->json([
                        'code' => 0,
                        'message' => 'Жалоба отправлена администратору.'
                    ]);
                }else{
                    return response()->json([
                        'code' => 1,
                        'message' => 'Ошибка отправки жалобы'
                    ]);
                }

            }




        }

        public function userDelete(Request $request){

            $user = \App\Models\User::where('id',Auth::user()->id)->first();
            if($user){
                if($user->delete()){
                    return response()->json([
                        'code' => 0,
                        'redirect' => '/logout'
                    ]);
                }else{
                    return response()->json([
                        'code' => 1,
                        'message' => 'Ошибка удаления аккаунта'
                    ]);
                }


            }

            return response()->json([
                'code' => 1,
                'message' => 'Пользователь не найден'
            ]);


        }



    }
