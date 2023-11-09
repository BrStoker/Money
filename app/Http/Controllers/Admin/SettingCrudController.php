<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminSettingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class SettingCrudController extends CrudController
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

        CRUD::setModel(\App\Models\Setting::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/settings');
        CRUD::setEntityNameStrings('', 'Настройки');

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
                'options' => \App\Models\Setting::$activities
            ], [
                'name' => 'setting_group_id',
                'label' => 'Группа',
                'type'  => 'select_from_array',
                'options' => \App\Models\SettingGroup::arrayForSelect(function($item){
                    return [$item->id => $item->title];
                })
            ],  [
                'name' => 'type',
                'label' => 'Тип',
                'type'  => 'select_from_array',
                'options' => \App\Models\Setting::$fieldTypes
            ], [
                'name' => 'multiply',
                'label' => 'Множественное',
                'type'  => 'boolean',
                'options' => \App\Models\Setting::$activities
            ],/* [
                'name' => 'value',
                'label' => 'Значение'
            ],*/ [
                'name' => 'sort',
                'label' => 'Сортировка'
            ], [
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

        CRUD::setValidation(AdminSettingRequest::class);

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
            'name' => "setting_group_id",
            'label' => 'Группа',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\SettingGroup::arrayForSelect(function($item){
                return [$item->id => $item->title];
            }),
            'allows_null' => false
        ]);

        CRUD::addField([
            'name' => "active",
            'label' => 'Активность',
            'type' => 'switch',
            'default' => true
        ]);

        CRUD::addField([
            'name' => "multiply",
            'label' => 'Множественное',
            'type' => 'switch'
        ]);

        CRUD::addField([
            'name' => "type",
            'label' => 'Тип',
            'type'        => 'select2_from_array',
            'options'     => \App\Models\Setting::$fieldTypes,
            'allows_null' => false
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

        /*CRUD::addField([
            'name' => "value",
            'label' => 'Значение',
            'type' => 'text'
        ]);*/
        
        CRUD::addField([
            'name' => "description",
            'label' => 'Описание',
            'type' => 'textarea'
        ]);


        if($entity) {

            self::fieldRender([
                'types' => \App\Models\Setting::$fieldTypesView,
                'field' => $entity->toArray(),
                'values' => $entity->values()
            ]);

            \App\Models\Setting::saved(function($entity) {

                $entity = CRUD::getCurrentEntry();

                static::setEntityField($entity, 'setting', '\App\Models\Setting', '\App\Models\SettingGroup');

            });

            /*$properties = static::getEntityFields($entity, 'setting', '\App\Models\Setting', '\App\Models\SettingGroup');

            if(sizeof($properties) > 0) {

                self::fieldsRender([
                    'types' => \App\Models\Setting::$fieldTypesView,
                    'data' => $properties,
                    'count' => 6,
                    'values' => ( $entity->values ? $entity->values->toArray() : [])
                ]);
    
                \App\Models\Setting::saved(function($entity) {
    
                    $entity = CRUD::getCurrentEntry();
    
                    static::setEntityFields($entity, 'setting', '\App\Models\Setting', '\App\Models\SettingGroup');
    
                });
    
            }*/

        }

        

        

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    
}
