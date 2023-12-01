<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ArticleGroupRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class CoursesSubjectCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\CoursesSubject::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/courses-subject');
        CRUD::setEntityNameStrings('', 'Тематика курсов');
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
                'options' => \App\Models\CoursesSubject::$activities
            ],[
                'name' => 'sort',
                'label' => 'Сортировка'
            ],[
                'name' => 'slug',
                'label' => 'Символьный код'
            ], [
                'name' => 'title',
                'label' => 'Название'
            ], [
                'name' => 'description',
                'label' => 'Описание'
            ]
        ]);

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ArticleGroupRequest::class);

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
            'type' => 'switch',
            'default' => true
        ]);

        CRUD::addField([
            'name' => "sort",
            'label' => 'Сортировка',
            'type' => 'text',
            'default' => 500
        ]);

        CRUD::addField([
            'name' => "slug",
            'label' => 'Символьный код',
            'target' => 'title',
            'type' => 'slug'
        ]);

        CRUD::addField([
            'name' => "title",
            'label' => 'Название',
            'type' => 'text'
        ]);
        
        CRUD::addField([
            'name' => "description",
            'label' => 'Описание',
            'type' => 'textarea'
        ]);


    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
