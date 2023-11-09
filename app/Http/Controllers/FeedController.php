<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\ArticleGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use \App\Models\ArticleComment;
use Illuminate\Support\Arr;

class FeedController extends Controller
{
    use CrudTrait;

    public function __construct()
    {

    }

    public function index(\Illuminate\Http\Request $request){

        $user = parent::currentUser(true);
        $notifications = parent::getUnreadNotification($user);
        $articles = $this->search(request(), 'filter');

        return view('feed', [
            'data' => json_encode([
                'user' => $user,
                'articles' => $articles['articles'],
                'notifications' => $notifications
            ])
        ]);


    }

    public function prepareUsers($users, $user_type, $filter = [])
    {


        $platforms = parent::getPlatforms(true);

        $result = [];

        $subscribers = 0;
        if(isset($filter['subscribers']) == true)
            $subscribers = $filter['subscribers'];

        $platform_ids = 0;
        if(isset($filter['platform_id']) == true)
            $platform_ids = $filter['platform_id'];

        foreach($users as $index => $user)
        {

            $user_platforms = [];

            $result2 = \App\Models\UserPlatform::where([
                'user_id' => $user->id,
                'user_type' => $user_type
            ]);

            if(empty($platform_ids) == false) {

                foreach($platform_ids as $index => $platform_id) {

                    if(!$index) {

                        $result2 = $result2->where('platform_id', '=', $platform_id)
                            ->where('link', '!=', '')
                            ->where('subscribers', '>=', $subscribers);

                    } else {

                        $result2 = $result2->orWhere('platform_id', '=', $platform_id)
                            ->where('link', '!=', '')
                            ->where('subscribers', '>=', $subscribers);

                    }

                }

            } else {
                $result2 = $result2->where('link', '!=', '')->where('subscribers', '>', $subscribers);
            }

            $result2 = $result2->get();

            if($result2->count())
            {
                foreach($result2 as $platform)
                {

                    $user_platforms[$platforms[$platform->platform_id]['code']] = $platform->toArray();

                }

            }

            if(empty($user_platforms) == false)
            {

                $score = \App\Models\Review::where([ 'user_id' => $user->id, 'active' => 1 ] )->avg('score');

                $result[] = array_merge($user->toArray(), [ 'user_platforms' => $user_platforms, 'score' => (empty($score) == false ? intval($score) : 1)] );

            }
            else
            {
                unset($users[$index]);
            }

        }

        return $result;

    }

    public function search(\Illuminate\Http\Request $request, $action = false){

        $request_data = $request->all();

        $count = 0;
        $limit = 10;
        $page = 1;

        $result = false;
        $actionSwitch = false;
        $articles = [];

        if(isset($request_data['action']) == true && empty($request_data['action']) == false || $action <> false){

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

                        $query = Article::where('status', 2)->orderBy('created_at', 'DESC');

                        $count = $query->count();

                        $result = $query
                            ->skip($page > 1 ? $limit * ($page - 1) : 0)
                            ->take($limit)
                            ->get();


                        if($result->count() > 0){
                            foreach ($result as $item){
                                $values = \App\Models\ArticleValue::where('article_id', $item['id'])->get();
                                foreach($values as $value){
                                    if($value->article_field_id == 2){
                                        if($value->value == 1){
                                            $article_user = \App\Models\User::where('id', $item->user_id)->first();
                                            $fieldsInline = [ 'signature' ];
                                            $properties = $this->getEntityFields($article_user, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
                                            $signature = '';

                                            if(isset($properties) == true && empty($properties) == false && is_array($properties) == true) {
                                                foreach($properties as $group) {
                                                    if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {
                                                        foreach($group['fields'] as $field) {
                                                            if(in_array($field['slug'], $fieldsInline) == true) {
                                                                if(isset($field['value'])){
                                                                    $signature = $field['value'];
                                                                }

                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            $article_user = [
                                                'id' => $article_user->id,
                                                'first_name' => $article_user->first_name,
                                                'last_name' => $article_user->last_name,
                                                'image' => $article_user->image ? '/storage/' . $article_user->image : '/image/avatar.png',
                                                'last_online_at' => $article_user->last_online_at ? $article_user->last_online_at->format('Y-m-d H:i:s'): null,
                                                'signature' => $signature
                                            ];

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

                                            $comments = ArticleComment::where('article_id', $item->id)->where('article_comment_id', 0)->get();
                                            $favorite = false;

                                            if(Auth::user()){
                                                $favorite = $item->favorites()->where('user_id', Auth::user()->id)->get()->count() > 0 ?? true;
                                            }

                                            $articles[] = array_merge($item->toArray(), [
                                                'favorite' => $favorite,
                                                'countComm' => $comments->count(),
                                                'articleGroups' => $articleGroups,
                                                'article_user' => $article_user
                                            ]);
                                        }
                                    }
                                }

                            }



                        }

                }

                if($result){

                    $result = [
                        'limit' => $limit,
                        'page' => $page,
                        'articles' => $articles,
                        'count' => count($articles)
                    ];

                    if($action){

                        return $result;

                    }else{
                        return response()->json( array_merge(['code' => 0], $result) );
                    }
                }



            }

        }




    }


    public function getComments(&$arComments, $entites) {

        foreach($entites as $entity) {
            if(!is_null($entity->user)){
                $arComments[$entity->id] = [
                    'comment_id' => $entity->id,
                    'text' => $entity->value,
                    'created_at' => $entity->created_at,
                    'comment_user' => $entity->user->toArray(),
                    'childrens' => []
                ];

                $childrens = $entity->childrens;

                if($childrens) {
                    $this->getComments($arComments[$entity->id]['childrens'], $childrens);
                }
            }

        }

        return $arComments;

    }

    public function show_article($slug){

        $article = Article::where('slug', $slug)->first();

        if($article){

            if(Auth::user()){
                if(Auth::user()->id != $article->user_id){
                    $userSee = \App\Models\ArticleView::where('article_id', $article->id)->where('user_id', Auth::user()->id)->get()->count();
                    if($userSee == 0){
                        $articleView = \App\Models\ArticleView::create([
                            'article_id' => $article->id,
                            'user_id' => Auth::user()->id

                        ]);
                        if($articleView){
                            $article->refresh();
                        }
                    }
                }
            }

            $arrArticle = [];
            $ArticleAuthor = \App\Models\User::where('id', $article->user_id)->first();

            if($ArticleAuthor){
                $isSubscribe = false;
                if(Auth::user()){
                    $isSubscribe = \App\Models\UserSubscribe::where('user_subscribe_id', Auth::user()->id)->where('user_id', $ArticleAuthor->id)->exists();
                }
                $properties = $this->getEntityFields($ArticleAuthor, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
                $fieldsInline = [ 'signature' ];
                $signature = '';
                if(isset($properties) == true && empty($properties) == false && is_array($properties) == true) {
                    foreach($properties as $group) {
                        if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {
                            foreach($group['fields'] as $field) {
                                if(in_array($field['slug'], $fieldsInline) == true) {
                                    if(isset($field['value'])){
                                        $signature = $field['value'];
                                    }

                                }
                            }
                        }
                    }
                }
                $article_user = [
                    'id' => $ArticleAuthor->id,
                    'first_name' => $ArticleAuthor->first_name,
                    'last_name' => $ArticleAuthor->last_name,
                    'image' => $ArticleAuthor->image ? '/storage/' . $ArticleAuthor->image : '/image/avatar.png',
                    'signature' => $signature,
                    'isSubscribe' => $isSubscribe,

                ];
                $recordComment = [];

                $comments = $article->comments->where('article_comment_id', 0);

                if($article->status == 2){
                    $arComments = [];

                    $comments = $article->comments->where('article_comment_id', 0);

                    if($comments) {
                        $recordComment = $this->getComments($arComments, $comments);
                    }

                }

                $arrArticle[] = [
                    'id' => $article->id,
                    'slug' => $article->slug,
                    'image' => $article->image,
                    'title' => $article->title,
                    'detail_text' => $article->detail_text,
                    'created_at' => $article->created_at,
                    'view' => $article->view,
                    'score' => $article->score,
                    'favorite' => $article->favorite,
                    'comments' => $recordComment,
                    'countComm' => $comments->count(),
                    'article_user' => $article_user,

                ];

            }

            return view('article', [
                'data' => json_encode([
                    'user' => Auth::user(),
                    'articles' => $arrArticle,
                    'notifications' => parent::getUnreadNotification(Auth::user())
                ])
            ]);
        }

    }

    public function add_category(\Illuminate\Http\Request $request){

        $request_data = $request->all();

        $categories = implode(',' , $request_data['category_name']);
        $categories = explode(",", $categories);
        $articleGroups = [];
        foreach($categories as $item){
            $slugName = Str::of($item)->slug('-');

            $category = \App\Models\ArticleGroup::where('slug', $slugName)->get()->count();
            if ($category > 0) {
                return response()->json(['code' => 1, 'desc' => 'Такая категория уже существует. Укажите другое наименование.']);
            }else {
                $newCat = \App\Models\ArticleGroup::create([
                    'title' => $item,
                    'active' => 1,
                    'slug' => $slugName,
                    'user_id' => Auth::user()->id
                ]);
                if($newCat) {
                    $articleGroups = parent::getArticleGroup($newCat->id);

                }
            }

        }

        return response()->json( ['code' => 0, 'desc' =>  'Категория добавлена', 'articlegroups'=>$articleGroups]);

    }

    public function score_add(\Illuminate\Http\Request $request){

        if($request->method() == 'POST'){

            $error = true;

            $request_data = $request->all();
            $user = parent::currentUser(true);
            $article = Article::find($request_data['article_id']);

            if($article){
                if($user && $article && $article->scores()->where('user_id', $user->id)->where('value', 1)->count() == 0){

                    $scores = $article->scores()->create([
                        'user_id' => $user->id,
                        'article_id' => $article->id,
                        'value' => 1
                    ]);


                    if($scores){

                        parent::addNotification(1, $scores->id, 'score', $article->user_id);
                        $error = false;

                    }

                }
                $scores = $this->getArticleScores($article, true);

                if($error == true){

                    return response()->json( ['code' => 1, 'desc' =>  'Вы уже голосовали за эту статью', 'like' =>true, 'score' => $scores ]  );

                }else{

                    return response()->json( ['code' => 0, 'desc' =>  'Оценка добавлена', 'like' =>true, 'score' => $scores] );

                }

            }

        }
        return false;

    }

    public function score_remove(\Illuminate\Http\Request $request){

        if($request->method() == 'POST'){

            $error = true;

            $request_data = $request->all();
            $user = parent::currentUser(true);
            $article = Article::find($request_data['article_id']);

            if($article){
                if($user && $article && $article->scores()->where('user_id', $user->id)->where('value', 0)->count() == 0){

                    $scores = $article->scores()->create([
                        'user_id' => $user->id,
                        'article_id' => $article->id,
                        'value' => 0
                    ]);


                    if($scores){

                        parent::addNotification(1, $scores->id, 'score', $article->user_id);
                        $error = false;

                    }

                }

                $scores = $this->getArticleScores($article, false);

                if($error == true){

                    return response()->json( ['code' => 1, 'desc' =>  'Вы уже голосовали за эту статью', 'like' =>false, 'score' => $scores ]  );

                }else{

                    return response()->json( ['code' => 0, 'desc' =>  'Оценка добавлена', 'like' =>false, 'score' => $scores] );

                }

            }

        }

        return false;

    }

    public function getScore(Request $request){

        $article = \App\Models\Article::where('id', $request->article_id)->first();

        if($article){

            $like = $this->getArticleScores($article, true);
            $disLike = $this->getArticleScores($article, false);

            return response()->json([
                'code' => 0,
                'scores' => [
                    'like' => $like,
                    'disLike' => $disLike
                ]
            ]);

        }

    }

    private function getArticleScores($article, $like = true){

        $scores = 0;
        if($like){
            $scores = $article->scores()->where('value', 1)->get()->count();
        }else{
            $scores = $article->scores()->where('value', 0)->get()->count();
        }

        return $scores;

    }

    public function article_edit(\Illuminate\Http\Request $request, $article_id){

        if($request->method() == 'POST'){


            $validatorData = new \App\Http\Requests\ArticleRequest();

            $validator = \Illuminate\Support\Facades\Validator::make(
                $request->all(),
                $validatorData->rules(),
                $validatorData->messages(),
                $validatorData->attributes()
            );
            if (!$validator->fails()){
                $user = parent::currentUser(true);

                $request_data = $validator->safe()->only(['slug', 'article_group_ids', 'user_id', 'image', 'title', 'preview', 'detail_text', 'user_id']);
                if($user){

                    $request_data['user_id'] = $user->id;
                    $request_data['image'] = $request->image;
                    $request_data['article_group_ids'] = $request->article_group_ids;
                    $request_data['description'] = $request->description;
                    $article = Article::where('id',$article_id)->first();
                    if($article){
                        $saveData = [];
                        foreach ($request_data as $item=>$data){
                            if($item == 'title'){
                                $article->slug = parent::createSlug($data, '\App\Models\Article');
                            }
                            $article->{$item} = $data;
                        }

                        $saved = $article->save();

                        if($saved){
                            if($request->fields){
                                foreach ($request->fields as $key => $value){

                                    $record = \App\Models\ArticleValue::where('article_field_id', $key)->first();
                                    if($record){
                                        $record->value = $value;
                                    }else{
                                        $record = \App\Models\ArticleValue::create([
                                            'article_field_id' => $key,
                                            'value' => $value,
                                            'article_id' => $article->id,
                                        ]);
                                    }
                                }
                            }
                        }

                        if($saved){
                            return response()->json( ['code' => 0, 'desc' =>  '', 'location' => '/'. $article->slug]  );
                        }else{
                            return response()->json( ['code' => 1, 'desc' =>  '' ]  );
                        }

                    }

                }

            }

        }else{

            $article = Article::where('id', $article_id)->first();

            $values = $article->values()->get();

            $values->transform(function ($value) {

                return [
                    'fields['.$value->article_field_id.']' => $value->value
                ];

            });

            $values = array_reduce($values->toArray(), function ($carry, $item) {
                return $carry + $item;
            }, []);

            $arrArticle = array_merge($article->toArray(), $values);

            if($article != false){

                $user = [];
                if(!auth()->guest()) {
                    $user = auth()->user()->toArray();
                }

                $groupArticles = parent::getArticleGroup();
                $notifications = parent::getUnreadNotification(parent::currentUser(true));

                return view('user.article.edit', [
                    'data' => json_encode([
                        'user' => $user,
                        'articlegroups' => $groupArticles,
                        'article' => $arrArticle,
                        'notifications' => $notifications
                    ])
                ]);

            }



        }


    }

    public function favorite(\Illuminate\Http\Request $request){

        $user = Auth::user();

        if($request->method() == 'POST'){

            $article = Article::find($request->article_id);

            if($article){


                if ($article->favorites()->where('user_id', $user->id)->get()->count() == 0) {
                    $article->favorites()->create([
                        'user_id' => $user->id,
                        'article_id' => $article->id
                    ]);

                    $userFavorites = \App\Models\ArticleFavorite::where('user_id', $user->id)->get();
                    $articleFavorite = [];
                    if($userFavorites){
                        foreach($userFavorites as $favorite){
                            if($favorite){
                                $favArticle = \App\Models\Article::where('id', $favorite->article_id)->first();
                                $articleGroups = '';
                                if($favArticle){
                                    if(isset($favArticle->article_group_ids) == true && empty($favArticle->article_group_ids) == false) {

                                        $article_group_ids = str_replace('[', '', $favArticle->article_group_ids);
                                        $article_group_ids = str_replace(']', '', $favArticle->article_group_ids);

                                        $articleGroups = \App\Models\ArticleGroup::whereIn('id', $article_group_ids)->where('active', 1)->get();
                                        $articleGroups->transform(function ($group) {

                                            return [
                                                'name' => $group->title
                                            ];

                                        });
                                    }
                                    $favorite = false;
                                    if(Auth::user()){
                                        $favorite = $favArticle->favorites()->where('user_id', Auth::user()->id)->get()->count() > 0 ?? true;
                                    }
                                    $comments = \App\Models\ArticleComment::where('article_id', $article->id)->where('article_comment_id', 0)->get();
                                    $article_user = \App\Models\User::where('id', $favArticle->user_id)->first();
                                    $fieldsInline = [ 'signature' ];
                                    $properties = $this->getEntityFields($article_user, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
                                    $signature = '';

                                    if(isset($properties) == true && empty($properties) == false && is_array($properties) == true) {
                                        foreach($properties as $group) {
                                            if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {
                                                foreach($group['fields'] as $field) {
                                                    if(in_array($field['slug'], $fieldsInline) == true) {
                                                        if(isset($field['value'])){
                                                            $signature = $field['value'];
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    }


                                    $article_user = [
                                        'id' => $article_user->id,
                                        'first_name' => $article_user->first_name,
                                        'last_name' => $article_user->last_name,
                                        'image' => $article_user->image ? '/storage/' . $article_user->image : '/image/avatar.png',
                                        'last_online_at' => $article_user->last_online_at ? $article_user->last_online_at->format('Y-m-d H:i:s'): null,
                                        'signature' => $signature
                                    ];
                                    $favArticle = [
                                        'id' => $favArticle->id,
                                        'slug' => $favArticle->slug,
                                        'articleGroup' => $articleGroups,
                                        'created_at' => $favArticle->created_at->format('d.m.Y'),
                                        'detail_text' => $favArticle->detail_text,
                                        'favorite' => $favorite,
                                        'comments' => $comments->toArray(),
                                        'image' => $favArticle->image ? '/storage/' . $favArticle->image : '/storage/image/no-image.png',
                                        'score' => $favArticle->score,
                                        'title' => $favArticle->title,
                                        'view' => $favArticle->view,
                                        'articleUser' => $article_user
                                    ];
                                    $articleFavorite[] = $favArticle;
                                }
                            }
                        }
                    }

                    if ($article->favorites()->where('user_id', $user->id)->get()->count() == 0){
                        return response()->json( ['code' => 1, 'desc' =>  'Статья не добавлена в избранное', 'favorite' => $articleFavorite, 'isFavorite' => false] );
                    }else{
                        return response()->json( ['code' => 0, 'desc' =>  'Статья добавлена в избранное', 'favorite' => $articleFavorite, 'isFavorite' => true] );
                    }
                }else{
                    $article->favorites()->delete([
                        'article_id' => $request->article_id,
                        'user_id' => $user->id
                    ]);
                    $userFavorites = \App\Models\ArticleFavorite::where('user_id', $user->id)->get();
                    $articleFavorite = [];
                    if($userFavorites){
                        foreach($userFavorites as $favorite){
                            if($favorite){
                                $article = \App\Models\Article::where('id', $favorite->article_id)->first();
                                $articleGroups = '';
                                if($article){
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
                                    $favorite = false;
                                    if(Auth::user()){
                                        $favorite = $article->favorites()->where('user_id', Auth::user()->id)->get()->count() > 0 ?? true;
                                    }
                                    $comments = \App\Models\ArticleComment::where('article_id', $article->id)->where('article_comment_id', 0)->get();
                                    $article_user = \App\Models\User::where('id', $article->user_id)->first();
                                    $fieldsInline = [ 'signature' ];
                                    $properties = $this->getEntityFields($article_user, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
                                    $signature = '';

                                    if(isset($properties) == true && empty($properties) == false && is_array($properties) == true) {
                                        foreach($properties as $group) {
                                            if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {
                                                foreach($group['fields'] as $field) {
                                                    if(in_array($field['slug'], $fieldsInline) == true) {
                                                        if(isset($field['value'])){
                                                            $signature = $field['value'];
                                                        }

                                                    }
                                                }
                                            }
                                        }
                                    }


                                    $article_user = [
                                        'id' => $article_user->id,
                                        'first_name' => $article_user->first_name,
                                        'last_name' => $article_user->last_name,
                                        'image' => $article_user->image ? '/storage/' . $article_user->image : '/image/avatar.png',
                                        'last_online_at' => $article_user->last_online_at ? $article_user->last_online_at->format('Y-m-d H:i:s'): null,
                                        'signature' => $signature
                                    ];
                                    $article = [
                                        'id' => $article->id,
                                        'slug' => $article->slug,
                                        'articleGroup' => $articleGroups,
                                        'created_at' => $article->created_at->format('d.m.Y'),
                                        'detail_text' => $article->detail_text,
                                        'favorite' => $favorite,
                                        'comments' => $comments->toArray(),
                                        'image' => $article->image ? '/storage/' . $article->image : '/image/no-image.png',
                                        'score' => $article->score,
                                        'title' => $article->title,
                                        'view' => $article->view,
                                        'articleUser' => $article_user
                                    ];
                                    $articleFavorite[] = $article;
                                }
                            }
                        }
                    }
                    return response()->json( ['code' => 0, 'desc' =>  'Статья удалена из избранного', 'favorite' => $articleFavorite, 'isFavorite' => false] );
                }

            }

        }else{
            $article = Article::find($request->article_id);
            if($article){

                if($article->favorites->where('user_id', $user->id)->get()->count() == 0){
                    return response()->json([
                        'code' => 0,
                        'isFavorite' => false,
                    ]);
                }else{
                    return response()->json([
                        'code' => 0,
                        'isFavorite' => true,
                    ]);
                }
            }
        }


    }

    public function getFavorite(\Illuminate\Http\Request $request){
        $user = Auth::user();
        $article = Article::find($request->article_id);
        if($article){
            if(\App\Models\ArticleFavorite::where('user_id', $user->id)->where('article_id', $article->id)->get()->count() == 0){
                return response()->json([
                    'code' => 0,
                    'isFavorite' => false,
                ]);
            }else{
                return response()->json([
                    'code' => 0,
                    'isFavorite' => true,
                ]);
            }
        }else{
            return response()->json([
                'code' => 0,
                'isFavorite' => false,
            ]);
        }

    }

    public function add_comment(\Illuminate\Http\Request $request){

        if($request->method() == 'POST'){
            $user = parent::currentUser(true);
            if($user){
                $request_data = $request->all();
                $comment_id = 0;
                if(isset($request_data['comment_id'])){
                    $comment_id = $request_data['comment_id'];
                }
                $comment = \App\Models\ArticleComment::create([
                    'active' => 1,
                    'user_id' => $user->id,
                    'article_id' => $request_data['article_id'],
                    'article_comment_id' => $comment_id,
                    'value' => $request_data['text']
                ]);
                if($comment){

                    $arrComments = [];

                    $articleModel = Article::find($request_data['article_id']);

                    if($user->id != $articleModel->iser_id){

                        parent::addNotification(1, $comment->id, 'comment', $articleModel->user_id);

                    }

                    $comments = $articleModel->comments->where('article_comment_id', 0);

                    $commentsData = $this->getComments($arrComments, $comments);

                    return response()->json( ['code' => 0, 'desc' =>  'Комментарий добавлен', 'comments' => $commentsData, 'comment_id' => $comment->id] );

                }else{

                    return response()->json( ['code' => 1, 'desc' =>  'Комментарий не добавлен']);

                }

            }

        }
    }

    public function setLike(Request $request){

        $user = Auth::user();
        $comment = \App\Models\ArticleComment::find($request->comment_id);

        if ($comment){
            //TODO проверить момент чтобы пользователь не смог несколько раз поставить + комментарию пока просто добавляем значение в БД
            $isset = \App\Models\ArticleCommentAction::where('user_id', $user->id)->where('article_comment_id', $request->comment_id)->where('type', 0)->get();
            if($isset->count() == 0){

                $newRecord = \App\Models\ArticleCommentAction::create([
                    'article_id' => $comment->article_id,
                    'article_comment_id' => $comment->id,
                    'user_id' => $user->id,
                    'type' => 0,
                ]);
                if($newRecord){
                    $likes = $comment->actions()->where('type', 0)->where('article_id', $comment->article_id)->get()->count();
                    $dislike = $comment->actions()->where('type', 1)->where('article_id', $comment->article_id)->get()->count();

                    return response()->json([
                        'code' => 0,
                        'counts' => [
                            'likes' => $likes,
                            'disLikes' => $dislike
                        ]
                    ]);
                }
            }else{
                return response()->json([
                    'code' => 1,
                    'error' => 'Вы уже повышали оценку этому комментарию'

                ]);
            }

        }

    }

    public function setDisLike(Request $request){

        $user = Auth::user();
        $comment = \App\Models\ArticleComment::find($request->comment_id);

        if ($comment){
            $isset = (bool)\App\Models\ArticleCommentAction::where('user_id', $user->id)->where('article_comment_id', $request->comment_id)->where('type', 1)->get()->count();
            if($isset == 0){
                $newRecord = \App\Models\ArticleCommentAction::create([
                    'article_id' => $comment->article_id,
                    'article_comment_id' => $comment->id,
                    'user_id' => $user->id,
                    'type' => 1,
                ]);

                if($newRecord){
                    $likes = $comment->actions()->where('type', 0)->where('article_id', $comment->article_id)->get()->count();
                    $dislike = $comment->actions()->where('type', 1)->where('article_id', $comment->article_id)->get()->count();




                    return response()->json([
                        'code' => 0,
                        'counts' => [
                            'likes' => $likes,
                            'disLikes' => $dislike
                        ]
                    ]);
                }

            }else{
                return response()->json([
                    'code' => 1,
                    'error' => 'Вы уже понижали оценку этому комментарию'
                ]);
            }

        }



    }

    public function getLike(Request $request){

        $comment = \App\Models\ArticleComment::find($request->comment_id);

        if($comment){

            $likes = $comment->actions()->where('type', 0)->where('article_id', $comment->article_id)->get()->count();
            $dislike = $comment->actions()->where('type', 1)->where('article_id', $comment->article_id)->get()->count();

            $countLikes = $likes - $dislike;

            return response()->json([
                'code' => 0,
                'counts' => [
                    'likes' => $likes,
                    'disLikes' => $dislike
                ]
            ]);

        }


    }

    public function article_image(\Illuminate\Http\Request $request){

        $validatorData = new \App\Http\Requests\UserImageRequest();
        $validator = \Illuminate\Support\Facades\Validator::make(
            $request->all(),
            $validatorData->rules(),
            $validatorData->messages(),
            $validatorData->attributes()
        );
        $request_data = $validator->safe()->only([ 'image' ]);

        $image = $request->file('image');

        $article = \App\Models\Article::find($request->article_id);

        if($article){
            if(isset($image) == true && empty($image) == false)
            $article->image = $image->path();

            if($article->save()){
                return response()->json(['code' => 0, 'image' => $article->image]);
            }
        }

    }

    public function article_delete(Request $request, $article_id){

        if(Auth::user()){

            $article = \App\Models\Article::find($article_id);

            if($article){

                $favorite = \App\Models\ArticleFavorite::where('article_id', $article->id)->where('user_id', Auth::user()->id)->first();

                if($favorite){
                    $favorite->delete();
                }

                if($article->delete()){

                    $articles = parent::getArticles(Auth::user()->id);
                    return response()->json([
                        'code' => 0,
                        'articles' => $articles
                    ]);

                }else{
                    return response()->json([
                        'code' => 1,
                        'message' => 'Ошибка удаления статьи'
                    ]);
                }


            }


        }else{
            return response()->json([
                'code' => 1,
                'message' => 'Ошибка удаления статьи'
            ]);
        }


    }

    public function uploadImage(Request $request){

        $uploadedFile = $request->file('file');

        $fileName = time() . '_' . $uploadedFile->getClientOriginalName();

        $file = $uploadedFile->storeAs('/public/images', $fileName);

        if($file){
            $fileUrl = str_replace('public/', '', $file);

            return response()->json([
                'code' => 0,
                'image' => $fileUrl
            ]);

        }else{
            return response()->json([
                'code' => 1,
                'errorMessage' => 'Ошибка загрузки файла'
            ]);
        }

    }

    public function get_info(Request $request){

        $request_data = $request->all();

        $article = \App\Models\Article::where('id', $request_data['article_id'])->first();
        if($article){

            $views = $article->view;
            $score = $article->score;
            $countComm = \App\Models\ArticleComment::where('article_id', $request_data['article_id'])->where('article_comment_id', 0)->get()->count();

            $data = [
                'views' => $views,
                'score' => $score,
                'countComm' => $countComm
            ];

            return response()->json([
                'code' => 0,
                'statistics' => $data
            ]);
        }else{
            return response()->json([
                'code' => 1,
                'message' => 'Статья в базе не найдена'
            ]);
        }


    }

}
