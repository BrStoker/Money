<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use http\Client\Curl\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use \App\Models\Article;
use Illuminate\Support\Facades\DB;
use \App\Models\ArticleGroup;
use \App\Models\ArticleComment;
use Illuminate\Support\Facades\Auth;
use function Couchbase\defaultDecoder;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use \App\Traits\Fields;

    private $user = [ ];

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function __construct()
    {

    }

    public function currentUser($object = false)
    {

        if(!$this->user)
        {

            $this->user = Auth::user();
        
            if($this->user)
            {

                if($object)
                {

                    return $this->user;

                }
                else
                {

                    return $this->user->toArray();
                    
                }

            }
        }

    }

    public function getCity()
    {

        $result = [];

        $cities = \App\Models\City::get()->toArray();

        if(empty($cities) == false)
        {

            foreach($cities as $city)
            {

                $result[$city['id']] = $city['value'];
            
            }
            
        }

        return $result;

    }

    public function getCountry()
    {

        $result = [];

        $countries = \App\Models\Country::get()->toArray();

        if(empty($countries) == false)
        {

            foreach($countries as $country)
            {
                $countryVal = [
                    'value' => $country['id'],
                    'label' => $country['value']
                ];
                $result[] = $countryVal;
            
            }
            
        }

        return $result;

    }

    public function getArticles($user_id = null, $article_id = null) {

        $articlesData = [];
        $user = Auth::user();
        $query = Article::where('status', 2);
        $articles = [];
        if($user_id != null){

            $query->where('user_id', $user_id);
            
            if($query->exists()) {
                
                foreach($query->get() as $article) {
                    $articles[] = $article->toArray();
                }

            }

        } else {

            if($query->exists()) {

                $articles = $query->get();

                $groups = [];

                $articles->transform(function ($article) {


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
                    $comments = ArticleComment::where('article_id', $article->id)->where('article_comment_id', 0)->get();

                    $favorite = false;
                    if(Auth::user()){
                        $favorite = $article->favorites()->where('user_id', Auth::user()->id)->get()->count() > 0 ?? true;
                    }

                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'preview' => $article->preview,
                        'detail_text' => $article->detail_text,
                        'slug' => $article->slug,
                        'image' => $article->image,
                        'created_at' => $article->created_at,
                        'articleGroups' => $articleGroups,
                        'comments' => $comments,
                        'countComm' => $comments->count(),
                        'favorite' => $favorite,
                        'view' => $article->view,
                        'score' => $article->score,
                        'article_user' => $article_user
                    ];
                });

            }

        }

        return $articles;

    }

    public function getArticleGroup($id = false){

        $data = [];

        $query = ArticleGroup::where('active', 1)->where('user_id', Auth::user()->id);

        if($id) {
            $query->where('id', $id);
        }

        if($query->exists()) {

            $groups = $query->get();
        
            foreach ($groups as $group) {
                if($id == false){
                    $data[] = [
                        'name' => 'group_id_' . $group->id,
                        'text' => $group->title,
                        'checkboxBefore' => '/image/svg/sprite.svg#checkboxBefore',
                        'checkboxAfter' => '/image/svg/sprite.svg#checkboxAfter',
                        'required'=> true,
                        'checked' => false,
                        'classCss' => 'form-item',
                        'inputType'=> 'checkbox',
                        'type'=> 'checkbox',
                        'value' => false
                    ];

                }else{
                    $data[] = [
                        'name' => 'group_id_' . $group->id,
                        'text' => $group->title,
                        'checkboxBefore' => '/image/svg/sprite.svg#checkboxBefore',
                        'checkboxAfter' => '/image/svg/sprite.svg#checkboxAfter',
                        'required'=> true,
                        'checked' => false,
                        'classCss' => 'form-item',
                        'inputType'=> 'checkbox',
                        'type'=> 'checkbox',
                        'value' => false
                    ];

                }

            }

        }else{
            $title = 'Главная';
            $slug = $this->createSlug($title, '\App\Models\ArticleGroup');
//            dd($slug);
            $newCat = \App\Models\ArticleGroup::create([
                'title' => $title,
                'active' => 1,
                'slug' => $slug,
                'user_id' => Auth::user()->id
            ]);

            if($newCat){
                $data[] = [
                    'name' => 'group_id_' . $newCat->id,
                    'text' => $newCat->title,
                    'checkboxBefore' => '/image/svg/sprite.svg#checkboxBefore',
                    'checkboxAfter' => '/image/svg/sprite.svg#checkboxAfter',
                    'required'=> true,
                    'checked' => false,
                    'classCss' => 'form-item',
                    'inputType'=> 'checkbox',
                    'type'=> 'checkbox',
                    'value' => false
                ];
            }
        }

        return $data;

    }

    public function createCode() {

        return 1234;
    
    }

    public function addNotification($type, $entity_id, $entity_code, $user_id){

        if($this->canAddNotification($user_id)){

            $notification = \App\Models\NotificationEntity::create([
                'notification_id' => $type,
                'entity_id' => $entity_id,
                'entity_code' => $entity_code,
                'user_id' => $user_id,
                'value' => '',
                'created_at' => Carbon::now(),
                'updated_at' => null
            ]);

        }

    }

    private function canAddNotification($user_id){

        return \App\Models\User::find($user_id)->exists();

    }

    public function getUnreadNotification($user){

        $arrNotification = [];

        if($user){
            $unreadNotifications = \App\Models\NotificationEntity::where('user_id', $user->id)->where('updated_at', null)->get();
            $notifications = \App\Models\NotificationEntity::where('user_id', $user->id)->get();
            $counts = \App\Models\NotificationEntity::where('user_id', $user->id)->where('updated_at', null)->select('entity_code', \DB::raw('COUNT(*) as count'))->groupBy('entity_code')->get();
            $arrCounts = [
                'all' => $unreadNotifications->count(),
                'score' => 0,
                'comment' => 0,
                'subscribe' => 0
            ];
            if($unreadNotifications->count() > 0){
                foreach ($counts->toArray() as $count){
                    if($count['entity_code'] == 'subscribe'){
                        $arrCounts['subscribe'] += $count['count'];
                    }elseif ($count['entity_code'] == 'comment'){
                        $arrCounts['comment'] += $count['count'];
                    }elseif ($count['entity_code'] == 'score'){
                        $arrCounts['score'] += $count['count'];
                    }
                }
            }

            $counts = \App\Models\NotificationEntity::where('user_id', $user->id)
                ->select('entity_code', \DB::raw('COUNT(*) as count'))
                ->groupBy('entity_code')
                ->get();

            $entityCodes = $counts->pluck('entity_code')->toArray();

            $notifications = \App\Models\NotificationEntity::where('user_id', $user->id)
                ->whereIn('entity_code', $entityCodes)
                ->get();

            if($notifications->count() > 0){
                $articleUsers = [];
                $comment_users = [];
                foreach ($notifications as $notification){
                    switch ($notification){
                        case $notification->entity_code == 'comment':
                            $comment = \App\Models\ArticleComment::find($notification->entity_id);
                            $article = \App\Models\Article::find($comment->article_id);
                            if($article){
                                $countComments = $article->comments->where('article_comment_id', 0)->count();
                                $commentUser = \App\Models\User::find($comment->user_id);
                                $properties = $this->getEntityFields($commentUser, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
                                $fieldsInline = ['signature'];
                                $signature = null;
                                if(isset($properties) == true && empty($properties) == false && is_array($properties) == true){
                                    foreach($properties as $group){
                                        if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true){
                                            foreach($group['fields'] as $field){
                                                if(in_array($field['slug'], $fieldsInline) == true) {
                                                    if(isset($field['value'])){
                                                        $signature = $field['value'];
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $comment_users[$commentUser->id] = [
                                    'id' => $commentUser->id,
                                    'first_name' => $commentUser->first_name,
                                    'last_name' => $commentUser->last_name,
                                    'score_date' => $comment->created_at->format('d.m.Y'),
                                    'article_id' => $comment->article_id,
                                    'text' => $comment->value,
                                    'image' => $commentUser->image,
                                    'comment_id' => $comment->id,
                                    'signature' => $signature
                                ];


                                $arrNotification[$notification->entity_code] = [
                                    $article->id => [
                                        'id' => $article->id,
                                        'image' => $article->image,
                                        'created_at' => $article->created_at->format('d.m.Y'),
                                        'title' => $article->title,
                                        'view' => $article->view,
                                        'score' => $article->score,
                                        'slug' => $article->slug,
                                        'countComm' => $countComments,
                                        'favorite' => $article->favorite,
                                        'users' => []
                                    ]
                                ];
                            }
                            break;
                        case $notification->entity_code == 'subscribe':
                            $subscribe = \App\Models\UserSubscribe::where('id',$notification->entity_id)->first();
                            if($subscribe){
                                $userSubs = \App\Models\User::find($subscribe->user_subscribe_id);
                                $properties = $this->getEntityFields($userSubs, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
                                $fieldsInline = ['signature'];
                                $signature = null;
                                if(isset($properties) == true && empty($properties) == false && is_array($properties) == true){
                                    foreach($properties as $group){
                                        if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true){
                                            foreach($group['fields'] as $field){
                                                if(in_array($field['slug'], $fieldsInline) == true) {
                                                    if(isset($field['value'])){
                                                        $signature = $field['value'];
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                $arrNotification[$notification->entity_code][$userSubs->id] = [
                                    'id' => $userSubs->id,
                                    'image' => $userSubs->image,
                                    'first_name' => $userSubs->first_name,
                                    'last_name' => $userSubs->last_name,
                                    'signature' => $signature,
                                    'subscribe_date' => $subscribe->created_at->format('d.m.Y')
                                ];
                            }
                            break;
                        case $notification->entity_code == 'score':
                            $articleScore = \App\Models\ArticleScore::where('id', $notification->entity_id)->first();
                            if($articleScore){
                                $article = \App\Models\Article::find($articleScore->article_id);
                                $countComments = 0;
                                if($article){
                                    $countComments = $article->comments->where('article_comment_id', 0)->count();
                                    $scoreUser = \App\Models\user::find($articleScore->user_id);
                                    $fieldsInline = ['signature'];
                                    $signature = null;
                                    if(isset($properties) == true && empty($properties) == false && is_array($properties) == true){
                                        foreach($properties as $group){
                                            if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true){
                                                foreach($group['fields'] as $field){
                                                    if(in_array($field['slug'], $fieldsInline) == true) {
                                                        if(isset($field['value'])){
                                                            $signature = $field['value'];
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    $articleUsers[$scoreUser->id] = [
                                        'id' => $scoreUser->id,
                                        'first_name' => $scoreUser->first_name,
                                        'last_name' => $scoreUser->last_name,
                                        'score_date' => $articleScore->created_at->format('d.m.Y'),
                                        'article_id' => $articleScore->article_id,
                                        'image' => $scoreUser->image,
                                        'signature' => $signature
                                    ];
                                    $arrNotification[$notification->entity_code] = [
                                        $articleScore->article_id = [
                                            'id' => $article->id,
                                            'image' => $article->image,
                                            'created_at' => $article->created_at->format('d.m.Y'),
                                            'title' => $article->title,
                                            'view' => $article->view,
                                            'score' => $article->score,
                                            'slug' => $article->slug,
                                            'countComm' => $countComments,
                                            'favorite' => $article->favorite,
                                            'users' => []
                                        ]
                                    ];
                                }
                            }
                            break;
                        default:
                            return false;
                    }
                }

                foreach ($arrNotification as $item=>&$key) {
                    if($item == 'score'){
                        foreach ($key as &$article){
                            foreach ($articleUsers as $user){
                                if($article['id'] == $user['article_id']){
                                    $article['users'][$user['id']] = $user;
                                }
                            }
                        }
                    }elseif($item == 'comment'){
                        foreach ($key as &$article){
                            foreach ($comment_users as $user){
                                if($article['id'] == $user['article_id']){
                                    $article['users'][$user['id']] = $user;
                                }
                            }
                        }
                    }
                }
            }

            return array_merge($arrNotification, ['counts'=> $arrCounts]);
        }else{
            return $arrNotification;
        }



    }

    public function createSlug($title, String $groupModel = ''){

        $separator = '-';

        $originalSlug = Str::slug($title, $separator);

        $slug = $originalSlug;

        $rec = $groupModel::latest('id')->first();

        $id = 1;

        if($rec){

            $id = $groupModel::latest('id')->first()->id;

        }

        $slug = $originalSlug . $separator . $id;

        return $slug;

    }

    public function getAuthorInfo($user_id){

        $user = \App\Models\User::where('id', $user_id)->first();
        $userData = [];
        if($user){

            $userProperties = $this->getEntityFields($user, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');
            $fieldsInline = ['signature'];

            $signature = '';

            if(isset($userProperties) == true && empty($userProperties) == false && is_array($userProperties) == true) {
                foreach($userProperties as $group) {
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
            $fieldsInline = ['description'];
            $description = '';
            if(isset($userProperties) == true && empty($userProperties) == false && is_array($userProperties) == true) {
                foreach($userProperties as $group) {
                    if(isset($group['fields']) == true && empty($group['fields']) == false && is_array($group['fields']) == true) {
                        foreach($group['fields'] as $field) {
                            if(in_array($field['slug'], $fieldsInline) == true) {
                                if(isset($field['value'])){
                                    $description = $field['value'];
                                }

                            }
                        }
                    }
                }
            }


            $userData = [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'image' => $user->image,
                'signature' => $signature,
                'description' => $description,
                'email' => $user->email,
                'phone' => $user->phone,
                'view' => $user->view,
                'score' => $user->score,
                'subscribe' => $user->subscribe
            ];

        }

        return $userData;


    }


}
