<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CoursesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class CoursesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\AdminController;
    use \App\Traits\Fields;

    public function setup()
    {
        CRUD::setModel(\App\Models\Courses::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/courses');
        CRUD::setEntityNameStrings('', 'Курсы');
    }

    protected function setupListOperation()
    {

        CRUD::denyAccess('show');

        CRUD::setColumns([
            [
                'name' => 'id',
                'label' => '#'
            ], [
                'name' => 'title',
                'label' => 'Название',
                'limit' => 250
            ],[
                'name' => 'status',
                'label' => 'Статус',
                'type'  => 'boolean',
                'options' => \App\Models\Courses::$statuses
            ], [
                'name' => 'image',
                'label' => 'Фото',
                'type' => 'image',
                'prefix' => 'storage/',
                'width' => '75px',
                'height' => '75px'
            ], [
                'name' => 'preview',
                'label' => 'Превью',
                'type' => 'html'
            ]
        ]);

        CRUD::addFilter([
            'name'  => 'user',
            'type'  => 'text',
            'label' => 'Пользователь'
        ], false, function ($value) {

            $users = \App\Models\User::select('id')
                ->where('first_name', 'like', '%'.$value.'%')
                ->orWhere('last_name', 'like', '%'.$value.'%')
                ->orWhere('second_name', 'like', '%'.$value.'%')->get();
            
            if($users->count() > 0) {

                $users = $users->toArray();

                $query = CRUD::addClause(function(){ });

                $query->whereIn('user_id', array_column($users, 'id'));

            }

        });

    }

    protected function setupCreateOperation()
    {

        CRUD::setValidation(CoursesRequest::class);

        $entity = CRUD::getCurrentEntry();
        $values = [];

        if(isset($entity->id))
        {
            CRUD::addField([
                'name' => "html_id",
                'type'  => 'custom_html',
                'value' => '<b>#' . $entity->id . '</b>'
            ]);
        }

//        dd($entity);

        if($entity)
        {   

            if($entity->score)
            {

                CRUD::addField([
                    'name' => "html_scores",
                    'type'  => 'custom_html',
                    'value' => '<b>Оценок: '. $entity->scores->count() . '</b>'
                ]);

                CRUD::addField([
                    'name' => "html_scores_value",
                    'type'  => 'custom_html',
                    'value' => '<b>Рейтинг: '. $entity->score . '</b>'
                ]);
            }

//            if($entity->viewed)
//            {
//                CRUD::addField([
//                    'name' => "html_viewed",
//                    'type'  => 'custom_html',
//                    'value' => '<b>Просмотров: '. $entity->viewed->count() . '</b>'
//                ]);
//            }
//
//            if($entity->favorites)
//            {
//                CRUD::addField([
//                    'name' => "html_favorites",
//                    'type'  => 'custom_html',
//                    'value' => '<b>Подписчиков: '. $entity->favorites->count() . '</b>'
//                ]);
//            }

            if($entity->deleted_at)
            {
                CRUD::addField([
                    'name' => "html_online_at",
                    'type'  => 'custom_html',
                    'value' => '<b>Дата онлайн: '. $entity->online_at . '</b>'
                ]);
            }

            if($entity->created_at)
            {
                CRUD::addField([
                    'name' => "html_created_at",
                    'type'  => 'custom_html',
                    'label' => 'Дата создания',
                    'value' => '<b>Дата регистрации: '. $entity->created_at . '</b>'
                ]);
            }
    
            if($entity->updated_at)
            {
                CRUD::addField([
                    'name' => "html_updated_at",
                    'type'  => 'custom_html',
                    'value' => '<b>Дата обновления: '. $entity->updated_at . '</b>'
                ]);
            }

            if($entity->deleted_at)
            {
                CRUD::addField([
                    'name' => "html_deleted_at",
                    'type'  => 'custom_html',
                    'value' => '<b>Дата удаления: '. $entity->deleted_at . '</b>'
                ]);
            }

        }

        if(empty($entity->image) == false)
        {
            CRUD::addField([
                'name' => "html_image",
                'type'  => 'custom_html',
                'value' => "<img src='/storage/{$entity->image}' style='height:100px;' />"
            ]);
        }

        CRUD::addField([
            'name' => "image",
            'label' => 'Изображение',
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public'
        ]);

        CRUD::addField([
            'name' => "title",
            'label' => 'Название',
            'type' => 'text'
        ]);

        CRUD::addField([
            'name' => "courses_type_id",
            'label' => 'Тип курса',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\CoursesType::arrayForSelect(function($item){
                return [$item->id => $item->title];
            }),
            'allows_multiple' => true,
            'value' => (isset($entity->courses_type_id) == true && empty($entity->courses_type_id) == false ? $entity->courses_type_id : [])
        ]);

        CRUD::addField([
            'name' => "courses_subject_id",
            'label' => 'Тематика',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\CoursesSubject::arrayForSelect(function($item){
                return [$item->id => $item->title];
            }),
            'allows_multiple' => false,
            'value' => (isset($entity->courses_subject_id) == true && empty($entity->courses_subject_id) == false ? $entity->courses_subject_id : [])
        ]);

        CRUD::addField([
            'name' => "status",
            'label' => 'Статус',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\Courses::$statuses,
            'allows_null' => false,
            'allows_multiple' => false,
            'defaul' => 2
        ]);

        CRUD::addField([
            'name' => "slug",
            'label' => 'Символьный код',
            'target' => 'title',
            'type' => 'slug'
        ]);


        
        CRUD::addField([
            'name' => "preview",
            'label' => 'Превью',
            'type' => 'summernote'
        ]);

        CRUD::addField([
            'name' => "detail_text",
            'label' => 'Текст',
            'type' => 'summernote'
        ]);

        CRUD::addField([
            'name' => 'price',
            'label' => 'Цена',
            'type' => 'number'
        ]);

        CRUD::addField([
            'name' => 'free',
            'label' => 'Курс бесплатный',
            'type' => 'checkbox'
        ]);

        CRUD::addField([
            'name' => 'link',
            'label' => 'Ссылка',
            'type' => 'text'
        ]);

        CRUD::addField([
            'name' => "user_id",
            'label' => 'Пользователь',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\User::arrayForSelect(function($item){

                $fio = implode(' ',[
                    $item->first_name, $item->last_name, $item->second_name
                ]);

                return [$item->id => '#' . $item->id . ' ('.$fio.', '. $item->email .', '.$item->phone.')'];

            }),
            'allows_null' => false
        ]);
        

//        if($entity) {
//
//            $properties = static::getEntityFields($entity, 'courses', '\App\Models\ArticleField', '\App\Models\ArticleFieldGroup');
//
//            if(sizeof($properties) > 0) {
//
//                self::fieldsRender([ 'types' => \App\Models\Courses::$fieldTypesView, 'data' => $properties ]);
//
//                \App\Models\Courses::saved(function($entity) {
//
//                    $entity = CRUD::getCurrentEntry();
//
//                    static::setEntityFields($entity, 'article', '\App\Models\ArticleField', '\App\Models\ArticleFieldGroup');
//
//                });
//
//            }
//
//        }

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
