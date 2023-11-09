<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettingGroupRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class SettingGroupCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\AdminController;
    use \App\Traits\AdminFields;

    public function setup()
    {
        CRUD::setModel(\App\Models\SettingGroup::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/setting-group');
        CRUD::setEntityNameStrings('', 'Группы настроек');
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
                'options' => \App\Models\SettingGroup::$activities
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
        CRUD::setValidation(SettingGroupRequest::class);

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
        

        if($entity) {

            $entityValues = $entity->values->toArray();

            self::fieldRender([
                'types' => \App\Models\Setting::$fieldTypesView,
                'field' => $entity->toArray(),
                'count' => 6,
                'values' => $entityValues
            ]);

            if(\App\Models\SettingField::$fieldTypesView[$entity->type] == 'select2_from_array') {

                \App\Models\SettingField::saved(function($entity) {

                    $entity = CRUD::getCurrentEntry();
        
                    static::setEntityFieldsTypeSelect($entity);
        
                });

            }

        }

        

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
