<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\Request;
use \App\Models\Courses;
use \App\Models\CoursesComment;
use Carbon\Carbon;


class CoursesController extends Controller
{

    public function __construct()
    {

    }

    public function index(\Illuminate\Http\Request $request){

        $user = parent::currentUser(true);
        $notifications = parent::getUnreadNotification($user);
        $courses = $this->search(request(), 'filter');
        $courseTypes = \App\Models\CoursesType::all();
        if($courseTypes){
            $courseTypes->transform(function ($type) {
                return [
                    'value' => $type->id,
                    'label' => $type->title
                ];
            });
        }

        $courseSubjects = \App\Models\CoursesSubject::all();
        if($courseSubjects){

            $courseSubjects->transform(function ($subject) {
                return [
                    'value' => $subject->id,
                    'label' => $subject->title
                ];
            });
        }
        return view('courses', [
            'data' => json_encode([
                'user' => $user,
                'notifications' => $notifications,
                'courses' => $courses['courses'],
                'courseTypes' => $courseTypes,
                'courseSubject' => $courseSubjects
            ])
        ]);

    }

    public function search(\Illuminate\Http\Request $request, $action = false){

        $request_data = $request->all();

        $count = 0;
        $limit = 10;
        $page = 1;

        $result = false;
        $actionSwitch = false;
        $courses = [];
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

            if($actionSwitch){

                switch($actionSwitch){

                    case 'filter':
                        $query = Courses::where('status', 2)->orderBy('created_at', 'DESC');





//                        if(isset($request_data['autorName']) == true && empty($request_data['autorName']) == false) {

//                            $query->where('first_name', 'like', '%'.$request_data['fio'].'%')
//                                ->orWhere('last_name', 'like', '%'.$request_data['fio'].'%')
//                                ->orWhere('second_name', 'like', '%'.$request_data['fio'].'%');

//                        }

                        if(isset($request_data['courses_type_id']) == true && empty($request_data['courses_type_id']) == false) {

                            $query->whereJsonContains('courses_type_id', $request_data['courses_type_id']);

                        }

                        if(isset($request_data['free']) == true && empty($request_data['free']) == false){
                            $query->where('free', $request_data['free']? 1 : 0);
                        }

                        if(isset($request_data['withComments']) == true && empty($request_data['withComments']) == false){
                            if($request_data['withComments'] == true){
                                $uniqueValuesArray = \App\Models\CoursesComment::distinct()->pluck('courses_id');
                                if(!empty($uniqueValuesArray)){
                                    $query->whereIn('id', $uniqueValuesArray);
                                }
                            }

                        }

                        if(isset($request_data['fio']) == true && empty($request_data['fio']) == false){
                            $query->where('title', 'like', '%'.$request_data['fio'].'%');
                        }


                        if(isset($request_data['grade_min']) == true && empty($request_data['grade_min']) == false){
                            $query->where('score', '>=', $request_data['grade_min']);
                        }

                        if(isset($request_data['grade_max']) == true && empty($request_data['grade_max']) == false){
                            $query->where('score', '<=', $request_data['grade_max']);
                        }

                        if(isset($request_data['price_min']) == true && empty($request_data['price_min']) == false){
                            $query->where('price', '>=', $request_data['price_min']);
                        }

                        if(isset($request_data['price_max']) == true && empty($request_data['price_max']) == false){
                            $query->where('price', '<=', $request_data['price_max']);
                        }

                        if(isset($request_data['new']) == true && empty($request_data['new']) == false){
                            $currentDate = Carbon::now();
                            $weekAgo = $currentDate->subWeek();
                            $query->whereDate('created_at', '>=', $weekAgo);
                        }

                        $count = $query->count();

                        $result = $query
                            ->skip($page > 1 ? $limit * ($page - 1) : 0)
                            ->take($limit)
                            ->get();


                        if($result->count() > 0){
                            foreach ($result as $item){

                                $course_user = \App\Models\User::where('id', $item->user_id)->first();

                                $fieldsInline = [ 'signature' ];

                                $properties = $this->getEntityFields($course_user, 'user', '\App\Models\UserField', '\App\Models\UserFieldGroup');

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

                                $course_user = [
                                    'id' => $course_user->id,
                                    'first_name' => $course_user->first_name,
                                    'last_name' => $course_user->last_name,
                                    'image' => $course_user->image ? '/storage/' . $course_user->image : '/image/avatar.png',
                                    'last_online_at' => $course_user->last_online_at ? $course_user->last_online_at->format('Y-m-d H:i:s'): null,
                                    'signature' => $signature
                                ];

                                if(isset($item->courses_type_id) == true && !is_null($item->courses_type_id)){
                                    $courseType = \App\Models\CoursesType::whereIn('id', $item->courses_type_id)->get();
                                    if(isset($courseType)){
                                        $courseType->transform(function($type){
                                            return [
                                                'title' => $type->title
                                            ];
                                        });
                                    }
                                }else{
                                    $courseType = '';
                                }

                                if(isset($item->courses_subject_id) == true && !is_null($item->courses_subject_id)){
                                    $courseSubject = \App\Models\CoursesSubject::where('id', $item->courses_subject_id)->first();
                                    if($courseSubject){
                                        $courseSubject = $courseSubject->title;
                                    }
                                }else{
                                    $courseSubject = '';
                                }

                                $coursesDownload = \App\Models\CoursesBuying::where('courses_id', $item->id)->get()->count();

                                $comments = CoursesComment::where('courses_id', $item->id)->where('courses_comment_id', 0)->get();

                                $courses[] = array_merge($item->toArray(), [
                                    'countComm' => $comments->count(),
                                    'courseType' => $courseType,
                                    'courseSubject' => $courseSubject,
                                    'courses_user' => $course_user,
                                    'downloaded' => $coursesDownload
                                ]);

                            }

                        }
                }

                if($result){

                    $result = [
                        'limit' => $limit,
                        'page' => $page,
                        'courses' => $courses,
                        'count' => count($courses)
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

    public function show($slug){

        $user = parent::currentUser(true);
        $notifications = parent::getUnreadNotification($user);
        $course = \App\Models\Courses::where('slug', $slug)->first();

        if($course){
            $userData = parent::getAuthorInfo($course->user_id);

            $courseType = \App\Models\CoursesType::whereIn('id', $course->courses_type_id)->get();
            if($courseType){
                $courseType->transform(function ($type) {

                    return [
                        'title' => $type->title
                    ];
                });
            }
            $downloaded = \App\Models\CoursesBuying::where('courses_id', $course->id)->get()->count();

            $recordComment = [];

            $comments = $course->comments->where('article_comment_id', 0);
            if($course->status == 2){
                $arComments = [];

                $comments = $course->comments->where('article_comment_id', 0);

                if($comments) {
                    $recordComment = $this->getComments($arComments, $comments);
                }

            }

            $countComm = \App\Models\CoursesComment::where('courses_id', $course->id)->get()->count();

            $courseData = [
                'id' => $course->id,
                'title' => $course->title,
                'detail_text' => $course->detail_text,
                'image' => $course->image,
                'price' => $course->price,
                'author' => $userData,
                'courseType' => $courseType,
                'score' => $course->score,
                'comments' => $recordComment,
                'downloaded' => $downloaded,
                'countComm' => $countComm,
                'free' => !($course->free == 0)


            ];

            return view('course', [
                'data' => json_encode([
                    'user' => Auth::user(),
                    'course' => $courseData,
                    'notifications' => $notifications
                ])
            ]);

        }else{
            abort(404);
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

    public function add_course(\Illuminate\Http\Request $request){

        $user = parent::currentUser(true);
        $notifications = parent::getUnreadNotification($user);

        $courseTypes = \App\Models\CoursesType::all();
        if($courseTypes){
            $courseTypes->transform(function ($type) {
                return [
                    'value' => $type->id,
                    'label' => $type->title
                ];
            });
        }

        $courseSubjects = \App\Models\CoursesSubject::all();
        if($courseSubjects){

            $courseSubjects->transform(function ($subject) {
                return [
                    'value' => $subject->id,
                    'label' => $subject->title
                ];
            });
        }

        if($request->method() == 'POST'){

            $title = $request->input('title');
            if (\App\Models\Courses::where('title', trim($title))->exists()) {
                return response()->json(['code' => 1, 'desc' => 'Такой заголовок уже существует. Укажите другой.']);
            }
            $validatorData = new \App\Http\Requests\CoursesRequest();
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
                $user = Auth::user();
                $request_data = $validator->safe()->only(['slug', 'status', 'courses_type_id', 'courses_subject_id', 'image', 'title', 'preview', 'detail_text', 'user_id']);

                if($request->image){
                    $request_data['image'] = $request->image;
                }
                if($request->courses_type_id){
                    $request_data['courses_type_id'] = $request->courses_type_id;
                }
                if($request->courses_subject_id){
                    $request_data['courses_subject_id'] = $request->courses_subject_id;
                }
                if($request->price){
                    $request_data['price'] = $request->price;
                }else{
                    $request_data['free'] = true;
                    $request_data['price'] = 0;
                }

                if($user){
                    $request_data['user_id'] = $user->id;
                    $course = new \App\Models\Courses();

                    foreach ($request_data as $index=>$value){
                        if($index == 'title'){
                            $course->slug = parent::createSlug($value, '\App\Models\Courses'); //$this->createSlug($value);
                        }
                        $course->{$index} = $value;
                    }

                    $course->status = 2;
                    $newCourse = $course->save();

                    if($newCourse){

                        return response()->json([

                            'code' => 0,
                            'location' => '/course/'.$course->slug,
                            'redirectId' => $course->id,

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

        }else{

            return view('user.course.add', [
                'data' => json_encode([
                    'user' => $user,
                    'notifications' => $notifications,
                    'courseTypes' => $courseTypes,
                    'courseSubject' => $courseSubjects
                ])
            ]);


        }


    }

    public function vote(\Illuminate\Http\Request $request){


//        dd($request->all());
        $request_data = $request->all();
        if(isset($request_data['course_id'])){

            $course = \App\Models\Courses::where('id', $request_data['course_id'])->first();
//            dd($course);
            if($course){
                $record = $course->scores()->create([
                    'value' => $request_data['rate'],
                    'courses_id' => $request_data['course_id'],
                    'user_id' => Auth::user()->id
                    ]);
                if($record){
                    return response()->json([
                        'code' => 1,
                        'rate' => $course->scores()->get(),
                    ]);
                }else{
                    return response()->json([
                        'code' => 0,
                        'error' => 'Оценка не поставлена'
                    ]);
                }
            }else{
                return response()->json([
                    'code' => 0,
                    'error' => 'Статья не найдена'
                ]);

            }


        }



    }

}
