<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CoursesCommentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class CoursesCommentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\CoursesComment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/courses-comment');
        CRUD::setEntityNameStrings('', 'Комментарии к курсам');
    }

    protected function setupListOperation()
    {

        CRUD::denyAccess('show');

        CRUD::setColumns([
            [
                'name' => 'id',
                'label' => '#'
            ], [
                'name' => 'active',
                'label' => 'Активность',
                'type'  => 'boolean',
                'options' => \App\Models\CoursesComment::$activities
            ],[
                'name' => 'value',
                'label' => 'Текст'
            ]
        ]);
        
    }

    protected function setupCreateOperation()
    {

        CRUD::setValidation(CoursesCommentRequest::class);

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

        if($entity)
        {   

            if($entity->created_at)
            {
                CRUD::addField([
                    'name' => "html_created_at",
                    'type'  => 'custom_html',
                    'label' => 'Дата создания',
                    'value' => '<b>Дата создания: '. $entity->created_at . '</b>'
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

        CRUD::addField([
            'name' => "active",
            'label' => 'Активность',
            'type' => 'switch'
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

        CRUD::addField([
            'name' => "courses_id",
            'label' => 'Статья',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\Courses::arrayForSelect(function($item){
                return [$item->id => $item->title];
            }),
            'allows_null' => false
        ]);

        CRUD::addField([
            'name' => "comment_id",
            'label' => 'Комментарий',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\Courses::arrayForSelect(function($item){
                return [ $item->id => '#' . $item->id ];
            }),
            'allows_null' => false
        ]);


        CRUD::addField([
            'name' => "value",
            'label' => 'Текст',
            'type' => 'summernote'
        ]);


    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
